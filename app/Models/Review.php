<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'product_id', 'rating', 'review_text'];


    public function reports()
{
    return $this->morphMany(Report::class, 'reportable');
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
         return $this->belongsTo(Product::class)->withTrashed();
    }
}
