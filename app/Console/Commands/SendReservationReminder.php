<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationReminder;
use Carbon\Carbon;

class SendReservationReminder extends Command
{
    protected $signature = 'reservation:send-reminders';
    protected $description = 'Send reminder emails for reservations starting tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $reservations = Reservation::where('status', 'not_started')
            ->whereDate('start_date', $tomorrow)
            ->whereDate('created_at', '<', Carbon::today()) // ✅ ليس حجز من اليوم لبكرا
            ->with('user', 'product')
            ->get();

        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)->send(new ReservationReminder($reservation));
            $this->info("Reminder sent to: " . $reservation->user->email);
        }
    }
}
