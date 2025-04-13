<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatNotification extends Model
{
    use HasFactory, SoftDeletes;  // إضافة SoftDeletes هنا

    protected $fillable = [
        'user_id',
        'chat_id',
        'message',
        'read'
    ];

    // علاقة الإشعار مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة الإشعار مع المحادثة
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
