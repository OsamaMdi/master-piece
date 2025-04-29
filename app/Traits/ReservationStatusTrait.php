<?php

namespace App\Traits;

use App\Models\Reservation;
use Carbon\Carbon;

trait ReservationStatusTrait
{
    protected function checkAndUpdateStatus(Reservation $reservation)
    {
        if (in_array($reservation->status, ['cancelled', 'reported', 'completed'])) {
            return;
        }

        $now = now();
        $start = Carbon::parse($reservation->start_date)->startOfDay();
        $end = Carbon::parse($reservation->end_date)->endOfDay();

        if ($now->greaterThan($end)) {
            $reservation->status = 'completed';

            
            if ($reservation->total_price) {
                if (is_null($reservation->paid_amount) || $reservation->paid_amount < $reservation->total_price) {
                    $reservation->paid_amount = $reservation->total_price;
                }

                if (is_null($reservation->platform_fee)) {
                    $reservation->platform_fee = $reservation->total_price * 0.05;
                }
            }
        } elseif ($now->between($start, $end)) {
            $reservation->status = 'in_progress';
        } elseif ($now->lessThan($start)) {
            $reservation->status = 'not_started';
        }

        $reservation->save();
    }

}
