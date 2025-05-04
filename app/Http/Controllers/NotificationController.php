<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', 0)
            ->update(['is_read' => true]);

            return response()->noContent();
    }

    public function adminIndex(Request $request)
    {
        abort_unless(auth()->user()->user_type === 'admin', 403);

        $priority = $request->query('priority');

        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $query = Notification::where('user_id', auth()->id());

        if ($priority) {
            $query->where('priority', $priority);
        }

        $notifications = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.notifications', compact('notifications'));
    }



public function adminClearAll()
{
  
    Notification::where('user_id', auth()->id())->delete();

    return redirect()->back()->with('success', 'All notifications deleted.');

}

public function merchantIndex(Request $request)
{
    abort_unless(auth()->user()->user_type === 'merchant', 403);

    $priority = $request->query('priority');

    Notification::where('user_id', auth()->id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

    $query = Notification::where('user_id', auth()->id());

    if ($priority) {
        $query->where('priority', $priority);
    }

    $notifications = $query->orderByDesc('created_at')->paginate(10);

    return view('merchants.notifications', compact('notifications'));
}

    public function destroy(Notification $notification)
{
    abort_unless($notification->user_id === auth()->id(), 403);

    $notification->delete();

    return back()->with('success', 'Notification deleted successfully.');
}

}
