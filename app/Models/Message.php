<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_id',
        'sender_id', 'sender_type',
        'message', 'image_url',
        'read', 'read_at',
    ];

    protected $casts = [
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // العلاقة مع الشات
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    // العلاقة مع المرسل (يوزر أو تاجر)
    public function sender()
    {
        return $this->morphTo();

    }
}
