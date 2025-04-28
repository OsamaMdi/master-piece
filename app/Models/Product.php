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
        'slug',
        'description',
        'price',
        'quantity',
        'status',
        'is_deliverable',
        'usage_notes',
        'user_id',
        'category_id',
        'block_reason', 'blocked_until'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoredBy()
    {
        return $this->belongsToMany(User::class, 'favorites')
                    ->withTimestamps()
                    ->withPivot('slug')
                    ->whereNull('favorites.deleted_at');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

  /*   public function getRouteKeyName()
    {
        return 'slug';
    } */
}
