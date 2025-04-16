<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'identity_number',
        'identity_image',
        'profile_picture',
        'identity_country',
        'status',
        'user_type',
        'phone',
        'address',
        'city',
    ];

    /**
     * The attributes that should be mutated to dates (for soft deletes).
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Hide sensitive attributes when returning user data.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ========== Relationships ==========

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
}
