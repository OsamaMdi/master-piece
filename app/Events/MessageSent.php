<?php
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiverId;

    public function __construct(Message $message, $receiverId)
    {
        $this->message = [
            'id' => $message->id,
            'chat_id' => $message->chat_id,
            'message' => $message->message,
            'image_url' => $message->image_url ? asset('storage/' . $message->image_url) : null,
            'time' => $message->created_at->format('h:i A'),
        ];

        $this->receiverId = $receiverId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message['chat_id']);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'receiver_id' => $this->receiverId
        ];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
}
