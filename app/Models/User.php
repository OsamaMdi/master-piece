<?php

namespace App\Models;


use Illuminate\Support\Facades\Mail;
use App\Mail\DeleteProductNotification;
use App\Mail\AccountDeletedNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mail\ReservationCancelledWithSuggestions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


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

    // علاقات عامة
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)
            ->whereDate('end_date', '>=', now())
            ->latestOfMany();
    }



    public function notifications()
    {
        return $this->hasMany(Notification::class);
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


    public function sentChats()
    {
        return $this->morphMany(Chat::class, 'sender');
    }

    public function receivedChats()
    {
        return $this->morphMany(Chat::class, 'receiver');
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }
    public function getMorphClass()
{
    return self::class;
}

public function getAllChatsAttribute()
{
    return \App\Models\Chat::where(function ($q) {
        $q->where('sender_id', $this->id)
          ->where('sender_type', get_class($this));
    })->orWhere(function ($q) {
        $q->where('receiver_id', $this->id)
          ->where('receiver_type', get_class($this));
    })->get();
}



protected static function booted()
{
    static::deleting(function ($user) {
        $user->reservations()->each(function ($reservation) {
            $reservation->delete();
        });

        $user->products()->each(function ($product) {
            $product->reservations()->delete();
            $product->delete();
        });

        Mail::to($user->email)->send(new AccountDeletedNotification($user));
    });
}




}
