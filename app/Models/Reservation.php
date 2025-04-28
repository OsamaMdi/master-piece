<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'product_id', 'slug', 'reservation_type',
        'start_date', 'end_date', 'start_datetime', 'end_datetime',
        'total_price', 'paid_amount', 'platform_fee',
        'status', 'comment'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->reservation_type == 'daily') {
                $model->start_datetime = null;
                $model->end_datetime = null;
            } elseif ($model->reservation_type == 'hourly') {
                $model->start_date = null;
                $model->end_date = null;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

   /*  public function getRouteKeyName()
    {
        return 'slug';
    } */

    public function reports()
{
    return $this->morphMany(\App\Models\Report::class, 'reportable');
}

}
