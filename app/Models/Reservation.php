<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'reservation_type',
        'start_date',
        'end_date',
        'start_datetime',
        'end_datetime',
        'total_price',
        'status',
        'comment',
    ];

    /**
     * تأكد من أن الحقول المناسبة فقط تم تعيينها بناءً على نوع الحجز.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->reservation_type == 'daily') {
                // إذا كان الحجز باليوم، قم بتعبئة التاريخ فقط
                $model->start_datetime = null;
                $model->end_datetime = null;
            } elseif ($model->reservation_type == 'hourly') {
                // إذا كان الحجز بالساعة، قم بتعبئة التاريخ والوقت
                $model->start_date = null;
                $model->end_date = null;
            }
        });
    }

    /**
     * العلاقة مع جدول المستخدمين
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع جدول المنتجات
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
