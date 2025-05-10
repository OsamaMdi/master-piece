<?php

namespace App\Models;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mail\ReservationCancelledWithSuggestions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
public function allReservationsIncludingTrashed()
{
    return $this->hasMany(Reservation::class, 'product_id')->withTrashed();
}

protected static function booted()
{
    static::deleting(function ($product) {
        $reservations = $product->reservations()->with('user')->get();

        foreach ($reservations as $reservation) {
            if (
                $reservation->status === 'not_started' &&
                $reservation->user &&
                $reservation->user->email
            ) {
                $suggested = Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->where('status', 'available')
                    ->limit(3)
                    ->get();

                Mail::to($reservation->user->email)->send(
                    new ReservationCancelledWithSuggestions($reservation, $product, $suggested)
                );
            }

            $reservation->delete();
        }
    });
}

}
