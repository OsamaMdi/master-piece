<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'status',
        'user_id',
        'category_id',
    ];

    // استخدام السوفت ديليت
    protected $dates = ['deleted_at'];

    // العلاقة مع جدول المستخدمين (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع جدول التصنيفات (Category)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع جدول الصور (ProductImages)
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    // العلاقة مع جدول الريفيوهات (Reviews)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // العلاقة مع جدول الحجوزات (Reservations)
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
