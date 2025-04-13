<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'read'
    ];

    // تحديد الحقل الذي سيتم تخزين تاريخ الحذف فيه
    protected $dates = ['deleted_at'];

    // علاقة الرسالة مع المحادثة
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    // علاقة الرسالة مع المرسل
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
