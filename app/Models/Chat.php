<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user1_id',
        'user2_id'
    ];

    // علاقة المحادثة مع الرسائل
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // علاقة المحادثة مع المستخدم الأول
    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    // علاقة المحادثة مع المستخدم الثاني
    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    // علاقة المحادثة مع إشعارات الشات
    public function chatNotifications()
    {
        return $this->hasMany(ChatNotification::class);
    }
}
