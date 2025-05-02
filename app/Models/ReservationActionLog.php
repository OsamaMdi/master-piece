<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationActionLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation_id',
        'token',
        'action',
        'used_at',
    ];
}
