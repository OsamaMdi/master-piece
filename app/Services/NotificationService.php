<?php

namespace App\Services;

use App\Models\Notification;
use App\Events\NotificationPushed;

class NotificationService
{
    public static function send(
        int $toUserId,
        string $message,
        string $type,
        string $url = null,
        string $priority = 'normal',
        ?int $fromUserId = null
    ) {
        $notification = Notification::create([
            'user_id' => $toUserId,
            'message' => $message,
            'type' => $type,
            'url' => $url,
            'priority' => $priority,
            'from_user_id' => $fromUserId,
            'is_read' => false,
        ]);

        broadcast(new NotificationPushed($notification, $toUserId))->toOthers();

        return $notification;
    }
}
