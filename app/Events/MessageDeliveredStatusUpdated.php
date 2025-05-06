<?php

namespace App\Events;


use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageDeliveredStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message_id;
    public $chat_id;
    public $sender_id;

    public function __construct($message_id, $chat_id, $sender_id)
    {
        $this->message_id = $message_id;
        $this->chat_id = $chat_id;
        $this->sender_id = $sender_id;

        // 🔍 Log to confirm event is firing
        Log::info('[Broadcast] MessageDeliveredStatusUpdated Fired', [
            'message_id' => $this->message_id,
            'chat_id' => $this->chat_id,
            'sender_id' => $this->sender_id,
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chat_id);
    }

    public function broadcastAs()
    {
        return 'MessageDeliveredStatusUpdated';
    }
}
