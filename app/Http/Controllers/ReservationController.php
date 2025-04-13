<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function cancel(string $reservationId)
    {
        try {
            $reservation = Reservation::findOrFail($reservationId);


            $remainingHours = \Carbon\Carbon::parse($reservation->start_date)->diffInHours(now(), false);

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

        return view('merchants.reservation.productReservationDetails', compact('reservation', 'reviews'));
    }
}
