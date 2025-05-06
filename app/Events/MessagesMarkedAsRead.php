<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessagesMarkedAsRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatId;
    public $messageIds;
    public $readerId;
    public $senderId;

    public function __construct($chatId, $messageIds, $readerId, $senderId)
    {
        $this->chatId = $chatId;
        $this->messageIds = $messageIds;
        $this->readerId = $readerId;
        $this->senderId = $senderId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chatId);
    }

    public function broadcastAs()
    {
        return 'MessagesMarkedAsRead';
    }

    public function broadcastWith()
    {
        return [
            'chatId' => $this->chatId,
            'messageIds' => is_array($this->messageIds) ? $this->messageIds : $this->messageIds->toArray(),
            'readerId' => $this->readerId,
            'senderId' => $this->senderId,
        ];
    }
}
