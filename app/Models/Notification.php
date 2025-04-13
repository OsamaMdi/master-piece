<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'message', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

