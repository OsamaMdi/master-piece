<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request)
    {


        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'start_date'   => 'required|date|after:today',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'note'         => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantityRequested = 1;

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $comment = $request->input('note');

        $reservations = Reservation::where('product_id', $product->id)
            ->where('status', '!=', 'cancelled')
            ->whereDate('end_date', '>=', Carbon::today())
            ->get();

        $dateCounts = [];
        foreach ($reservations as $res) {
            $resStart = Carbon::parse($res->start_date);
            $resEnd = Carbon::parse($res->end_date);
            for ($date = $resStart->copy(); $date->lte($resEnd); $date->addDay()) {
                $key = $date->toDateString();
                $dateCounts[$key] = ($dateCounts[$key] ?? 0) + $res->quantity;
            }
            $nextDay = $resEnd->copy()->addDay()->toDateString();
            $dateCounts[$nextDay] = ($dateCounts[$nextDay] ?? 0) + $res->quantity;
        }

        $conflictDates = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $key = $date->toDateString();
            $reserved = $dateCounts[$key] ?? 0;

            if ($reserved + $quantityRequested > $product->quantity) {
                $conflictDates[] = $key;
            }
        }


        if (!empty($conflictDates)) {
            return back()->withErrors([
                'start_date' => 'This product is not available on: ' . implode(', ', $conflictDates),
            ])->withInput();
        }

        $conflictingInsideReservation = $reservations->first(function ($res) use ($start, $end) {
            $existingStart = Carbon::parse($res->start_date);
            $existingEnd = Carbon::parse($res->end_date);

            return $existingStart->gte($start) && $existingEnd->lte($end);
        });

        if ($conflictingInsideReservation) {
            return back()->withErrors([
                'start_date' => 'There is an existing reservation fully inside your selected range.',
            ])->withInput();
        }

        $diffDays = $end->diffInDays($start);
        $totalPrice = $diffDays > 0 ? $product->price * $diffDays : 0;

        Reservation::create([
            'user_id'         => auth()->id(),
            'product_id'      => $product->id,
            'reservation_type'=> 'daily',
            'start_date'      => $start->toDateString(),
            'end_date'        => $end->toDateString(),
            'quantity'        => $quantityRequested,
            'total_price'     => $totalPrice,
            'status'          => 'pending',
            'comment'         => $comment,
        ]);

        return redirect()->back()->with('success', 'Reservation submitted successfully!');
    }



    public function getUnavailableDates(Product $product)
    {
        $today = Carbon::today();

        $reservations = Reservation::where('product_id', $product->id)
            ->where('status', '!=', 'cancelled')
            ->where('end_date', '>=', $today) // فقط الحجوزات الفعّالة بعد اليوم
            ->get();

        $dateCounts = [];

        foreach ($reservations as $res) {
            $start = Carbon::parse($res->start_date);
            $end = Carbon::parse($res->end_date);

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $key = $date->toDateString();
                $dateCounts[$key] = ($dateCounts[$key] ?? 0) + 1; // كل حجز = قطعة واحدة
            }
        }

        $disabledDates = [];
        foreach ($dateCounts as $date => $reservedQty) {
            if ($reservedQty >= $product->quantity) {
                $disabledDates[] = $date;
            }
        }

        return response()->json([
            'product_quantity'     => $product->quantity,
            'reserved_quantities'  => $dateCounts,
            'disabled_dates'       => $disabledDates,
        ]);
    }


}
