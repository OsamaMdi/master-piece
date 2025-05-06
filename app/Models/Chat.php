<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id', 'sender_type',
        'receiver_id', 'receiver_type',
        'status',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    
    public function messages()
{
    return $this->hasMany(Message::class)->orderBy('id'); // ✅ ترتيب دائم
}


    // الطرف المُرسل (يمكن أن يكون User أو Merchant)
    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    public function receiver(): MorphTo
    {
        return $this->morphTo();
    }
}
