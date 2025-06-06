<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebsiteReview extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'rating', 'review_text'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
