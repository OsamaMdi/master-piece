<?php

namespace App\Http\Controllers;


use App\Models\Chat;
use App\Models\Message;
use App\Models\Product;
use MessagesMarkedAsRead;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userType = get_class($user);

        $chats = Chat::with(['sender', 'receiver', 'messages'])
            ->where(function ($q) use ($user, $userType) {
                $q->where('sender_id', $user->id)
                  ->where('sender_type', $userType);
            })
            ->orWhere(function ($q) use ($user, $userType) {
                $q->where('receiver_id', $user->id)
                  ->where('receiver_type', $userType);
            })
            ->latest('updated_at')
            ->get()
            ->map(function ($chat) use ($user, $userType) {
                $unreadCount = $chat->messages
                    ->where('sender_id', '!=', $user->id)
                    ->where('sender_type', '!=', $userType)
                    ->where('read', false)
                    ->count();

                $chat['unread_count'] = $unreadCount;

                return $chat;
            });

        return view('chat.index', compact('chats'));
    }

    public function show($id)
    {
        $chat = Chat::with('messages.sender')->findOrFail($id);

        $user = Auth::user();
        $userType = get_class($user);

        // تأكيد أن المستخدم داخل الشات
        if (
            ($chat->sender_id !== $user->id || $chat->sender_type !== $userType) &&
            ($chat->receiver_id !== $user->id || $chat->receiver_type !== $userType)
        ) {
            abort(403);
        }

        // جلب كل الرسائل اللي مش مرسلة من المستخدم الحالي
        $messagesToUpdate = $chat->messages()
            ->where('sender_id', '!=', $user->id)
            ->where(function ($query) {
                $query->where('read', false)
                      ->orWhere('delivered', false);
            })
            ->pluck('id');

        if ($messagesToUpdate->isNotEmpty()) {
            Message::whereIn('id', $messagesToUpdate)->update([
                'read' => true,
                'read_at' => now(),
                'delivered' => true,
            ]);
        }

        Log::info('✅ Message Read + Delivered Updated', [
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'user_type' => $userType,
            'updated_count' => $messagesToUpdate->count(),
            'message_ids' => $messagesToUpdate,
        ]);

        return view('chat.show', compact('chat'));
    }

    public function markDelivered(Request $request)
    {
        $user = Auth::user();
        $userType = get_class($user);

       
        $chatIds = \App\Models\Chat::where(function ($q) use ($user, $userType) {
                $q->where('receiver_id', $user->id)->where('receiver_type', $userType);
            })
            ->orWhere(function ($q) use ($user, $userType) {
                $q->where('sender_id', $user->id)->where('sender_type', $userType);
            })
            ->pluck('id');


        $messages = \App\Models\Message::whereIn('chat_id', $chatIds)
            ->where('sender_id', '!=', $user->id)
            ->where('delivered', false)
            ->get();

        $updatedCount = 0;
        $updatedIds = [];

        foreach ($messages as $msg) {
            $msg->delivered = true;
            $msg->save();

            $updatedCount++;
            $updatedIds[] = $msg->id;

            broadcast(new \App\Events\MessageDeliveredStatusUpdated(
                $msg->id,
                $msg->chat_id,
                $msg->sender_id
            ))->toOthers();
        }

        return response()->json([
            'updated' => $updatedCount,
            'updated_ids' => $updatedIds,
        ]);
    }


    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($request->chat_id);

        if (
            ($chat->sender_id !== $user->id || $chat->sender_type !== get_class($user)) &&
            ($chat->receiver_id !== $user->id || $chat->receiver_type !== get_class($user))
        ) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $user->id)
            ->where('read', false)
            ->get();

        $updatedCount = 0;
        $updatedIds = [];

        foreach ($messages as $msg) {
            $msg->read = true;
            $msg->delivered = true;
            $msg->read_at = now();
            $msg->save();

            $updatedCount++;
            $updatedIds[] = $msg->id;
        }

        if ($updatedCount > 0) {
            $senderId = $messages->first()->sender_id;
            event(new \App\Events\MessagesMarkedAsRead(
                $chat->id,
                $updatedIds,
                $user->id,
                $senderId
            ));
        }

        return response()->json([
            'updated' => $updatedCount,
            'updated_ids' => $updatedIds
        ]);
    }



    public function send(Request $request)
    {
        Log::info('🚀 [Chat] Send request received', [
            'user_id' => Auth::id(),
            'input' => $request->all()
        ]);

        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $userType = get_class($user);
        $chat = Chat::findOrFail($request->chat_id);

        if (
            ($chat->sender_id !== $user->id || $chat->sender_type !== $userType) &&
            ($chat->receiver_id !== $user->id || $chat->receiver_type !== $userType)
        ) {
            abort(403);
        }

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('chat_images', 'public')
            : null;

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'sender_type' => $userType,
            'message' => $request->message,
            'image_url' => $imagePath,
            'read_by_sender' => true,
            'read_by_receiver' => false,
        ]);

        $chat->touch();

        $receiverId = $chat->sender_id === $user->id ? $chat->receiver_id : $chat->sender_id;

        broadcast(new MessageSent($message, $receiverId))->toOthers();

        return response()->json([
            'id' => $message->id, // ⬅️ هذا هو المطلوب
            'message' => $message->message,
            'image_url' => $message->image_url ? asset('storage/' . $message->image_url) : null,
            'time' => $message->created_at->format('h:i A'),
            'seen' => $message->read_by_receiver,
        ]);
    }



    // بدء محادثة جديدة من صفحة منتج
    public function startFromProduct(Product $product)
    {
        $user = Auth::user();
        $userType = get_class($user);

        $chat = Chat::where(function ($q) use ($user, $userType, $product) {
            $q->where('sender_id', $user->id)
                ->where('sender_type', $userType)
                ->where('receiver_id', $product->user_id)
                ->where('receiver_type', 'App\Models\Merchant');
        })->orWhere(function ($q) use ($user, $userType, $product) {
            $q->where('receiver_id', $user->id)
                ->where('receiver_type', $userType)
                ->where('sender_id', $product->user_id)
                ->where('sender_type', 'App\Models\Merchant');
        })->first();

        if (!$chat) {
            $chat = Chat::create([
                'sender_id' => $user->id,
                'sender_type' => $userType,
                'receiver_id' => $product->user_id,
                'receiver_type' => 'App\Models\Merchant',
            ]);
        }

        $previewMessage = "🛒 Product Inquiry:\n" .
            "Name: {$product->name}\n" .
            "Category: {$product->category->name}\n" .
            "Link: " . route('products.show', $product->id);

        Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'sender_type' => $userType,
            'message' => $previewMessage,
        ]);

        return redirect()->route('chat.show', $chat->id);
    }

    public function unreadCount()
{
    $user = Auth::user();

    $chatIds = \App\Models\Chat::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('sender_type', get_class($user));
        })
        ->orWhere(function ($q) use ($user) {
            $q->where('receiver_id', $user->id)->where('receiver_type', get_class($user));
        })
        ->pluck('id');

    $count = \App\Models\Message::whereIn('chat_id', $chatIds)
        ->where('sender_id', '!=', $user->id)
        ->where('read', false)
        ->count();

    return response()->json(['count' => $count]);
}









   /*  public function fetchNewMessages(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);
        $user = Auth::user();

        // تحديد الطرف الآخر
        $otherParty = ($chat->sender_id == $user->id)
            ? ['id' => $chat->receiver_id, 'type' => $chat->receiver_type]
            : ['id' => $chat->sender_id, 'type' => $chat->sender_type];

        $afterId = (int) $request->query('after');

        $messages = $chat->messages()
            ->with('sender')
            ->where('id', '>', $afterId)
            ->where('sender_id', $otherParty['id'])
            ->where('sender_type', $otherParty['type'])
            ->orderBy('id')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'image_url' => $msg->image_url ? asset('storage/' . $msg->image_url) : null,
                    'time' => $msg->created_at->format('h:i A')
                ];
            });

        return response()->json($messages);
    } */


}
