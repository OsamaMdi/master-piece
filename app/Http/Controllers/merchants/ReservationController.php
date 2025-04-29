<?php

namespace App\Http\Controllers\merchants;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Image;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\ReservationStatusTrait;

class ReservationController extends Controller
{
    use ReservationStatusTrait;
    
    public function cancel(string $reservationId)
    {
        try {
            $reservation = Reservation::findOrFail($reservationId);

            $startDate = \Carbon\Carbon::parse($reservation->start_date);
            $now = now();

            if ($startDate->isFuture()) {
                $remainingHours = $now->diffInHours($startDate, false);

                if ($remainingHours > 48) {
                    $reservation->status = 'cancelled';
                    $reservation->save();

                    return redirect()->route('merchant.reservation.details', $reservationId)
                        ->with('success', 'Reservation cancelled successfully!');
                } else {
                    return redirect()->route('merchant.reservation.details', $reservationId)
                        ->with('error', 'You cannot cancel this reservation within 48 hours of the start date.');
                }
            } else {
                return redirect()->route('merchant.reservation.details', $reservationId)
                    ->with('error', 'You cannot cancel a reservation that has already started.');
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

        // Check and update reservation status before displaying
        $this->checkAndUpdateStatus($reservation);

        $reviews = collect();
        if ($reservation->user && $reservation->product) {
            $reviews = $reservation->product->reviews()
                ->where('user_id', $reservation->user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('merchants.reservation.productReservationDetails', compact('reservation', 'reviews'));
    }

    public function showProductReservations(string $productId)
    {
        $product = Product::findOrFail($productId);

        $reservations = Reservation::with(['user', 'product.reviews'])
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        // Check and update status for each reservation
        foreach ($reservations as $reservation) {
            $this->checkAndUpdateStatus($reservation);
        }

        return view('merchants.reservation.productReservations', compact('product', 'reservations'));
    }

    public function showReservations()
    {
        $user = Auth::user();

        $reservations = Reservation::with(['user', 'product.reviews'])
            ->whereHas('product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        // Check and update status for each reservation
        foreach ($reservations as $reservation) {
            $this->checkAndUpdateStatus($reservation);
        }

        return view('merchants.reservation.reservations', compact('reservations'));
    }

    /**
     * Check and update the reservation status based on dates (only for daily reservations)
     */
    /* private function checkAndUpdateStatus(Reservation $reservation)
    {
        if (in_array($reservation->status, ['cancelled', 'reported', 'completed'])) {
            // If cancelled, reported, or already completed ➔ do not change anything
            return;
        }

        $now = now();

        $start = Carbon::parse($reservation->start_date)->startOfDay();
        $end = Carbon::parse($reservation->end_date)->endOfDay(); // Important: set end to the end of the day

        if ($now->greaterThan($end)) {
            // Reservation has fully ended ➔ mark as completed and calculate platform fee
            $reservation->status = 'completed';
            if (is_null($reservation->platform_fee) && $reservation->total_price) {
                $reservation->platform_fee = $reservation->total_price * 0.05;
            }
        } elseif ($now->between($start, $end)) {
            // Reservation is currently ongoing ➔ in_progress
            $reservation->status = 'in_progress';
        } elseif ($now->lessThan($start)) {
            // Reservation has not started yet ➔ not_started
            $reservation->status = 'not_started';
        }

        $reservation->save();
    } */

}
