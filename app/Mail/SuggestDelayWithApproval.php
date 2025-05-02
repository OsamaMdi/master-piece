<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ReservationActionLog; // ✅ جديد

class SuggestDelayWithApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $product;
    public $nextAvailable;
    public $reason;
    public $token;
    public $approveUrl;
    public $rejectUrl;

    public function __construct($reservation, $product, $nextAvailable, $reason)
    {
        $this->reservation = $reservation;
        $this->product = $product;
        $this->nextAvailable = $nextAvailable;
        $this->reason = $reason;

        // ✅ توليد توكن فريد للحجز
        $this->token = sha1($reservation->id . $reservation->created_at);

        // ✅ حفظ التوكن في قاعدة البيانات
        ReservationActionLog::updateOrCreate(
            ['token' => $this->token],
            ['reservation_id' => $reservation->id]
        );

        // ✅ الحصول على IP المحلي تلقائياً
        $ip = $this->getLocalIp();

        // ✅ توليد روابط الموافقة والرفض
        $this->approveUrl = "http://{$ip}:8000/reservation/approve-delay/{$reservation->id}?token={$this->token}&start_date={$nextAvailable['start_date']}&end_date={$nextAvailable['end_date']}";
        $this->rejectUrl  = "http://{$ip}:8000/reservation/reject-delay/{$reservation->id}?token={$this->token}";
    }

    public function build()
    {
        return $this->subject('Suggested New Dates for Your Reservation')
            ->markdown('emails.reservations.suggest-delay')
            ->with([
                'reservation' => $this->reservation,
                'product' => $this->product,
                'nextAvailable' => $this->nextAvailable,
                'reason' => $this->reason,
                'approveUrl' => $this->approveUrl,
                'rejectUrl' => $this->rejectUrl,
            ]);
    }

    private function getLocalIp()
    {
        $host = getHostName();
        $ip = getHostByName($host);

        if (filter_var($ip, FILTER_VALIDATE_IP) && (str_starts_with($ip, '192.') || str_starts_with($ip, '10.'))) {
            return $ip;
        }

        return '127.0.0.1';
    }
}
