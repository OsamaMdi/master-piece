<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'name', 'slug', 'email', 'password',
        'identity_number', 'profile_picture', 'identity_image', 'identity_country',
        'status', 'user_type', 'phone', 'address', 'city',
        'block_reason', 'blocked_until'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

/*     public function getRouteKeyName()
    {
        return 'slug';
    } */

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function chatNotifications()
    {
        return $this->hasMany(ChatNotification::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function websiteReviews()
    {
        return $this->hasMany(WebsiteReview::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites')
                    ->withTimestamps()
                    ->withPivot('slug')
                    ->whereNull('favorites.deleted_at');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
