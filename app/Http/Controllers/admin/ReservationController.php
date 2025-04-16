<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Image;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ReservationStatus;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function cancel(string $reservationId)
    {
        try {
            $reservation = Reservation::findOrFail($reservationId);

     /*
            dd([
                'start_date' => $reservation->start_date,
                'now' => now(),
                'diffInHours' => now()->diffInHours($reservation->start_date, false),
            ]); */

            $remainingHours = now()->diffInHours($reservation->start_date, false);

            if ($remainingHours > 48) {
                $reservation->status = 'cancelled';
                $reservation->save();

                return redirect()->route('merchant.reservation.details', $reservationId)
                    ->with('success', 'Reservation cancelled successfully!');
            } else {
                return redirect()->route('merchant.reservation.details', $reservationId)
                    ->with('error', 'You cannot cancel this reservation within 48 hours of the start date.');
            }
        } catch (\Exception $e) {
            Log::error('Error while cancelling reservation: ' . $e->getMessage());

            return redirect()->route('merchant.reservation.details', $reservationId)
                ->with('error', 'An error occurred while cancelling the reservation.');
        }
    }



    public function showReservationDetails(string $reservationId)
    {
        $reservation = Reservation::with(['user', 'product'])->findOrFail($reservationId);

        $reviews = collect();
        if ($reservation->user && $reservation->product) {
            $reviews = $reservation->product->reviews()
                ->where('user_id', $reservation->user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.reservation.productReservationDetails', compact('reservation', 'reviews'));
    }
    //   Show rof on product reservations
    public function showProductReservations(string $productId)
    {
        $product = Product::findOrFail($productId);

        $reservations = Reservation::with(['user', 'product.reviews'])
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.reservation.productReservations', compact('product', 'reservations'));
    }

      // Show all reservations for the current user
    public function showReservations()
{
    $user = Auth::user();

    // Get all reservations where the product belongs to the current user
    $reservations = Reservation::with(['user', 'product.reviews'])
        ->whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(30);

    return view('admin.reservation.reservations', compact('reservations'));
}
}
