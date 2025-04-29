<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Reservation;
use Illuminate\Support\Str;
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
            'amount_paid'  => 'required|numeric|min:0', // الدفعة المدخلة
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantityRequested = 1; // دائماً نحجز قطعة واحدة

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $comment = $request->input('note');
        $amountPaid = (float) $request->input('amount_paid');

        // جلب الحجوزات الحالية لهذا المنتج
        $reservations = Reservation::where('product_id', $product->id)
            ->where('status', '!=', 'cancelled')
            ->whereDate('end_date', '>=', Carbon::today())
            ->get();

        // ✅ أولاً: تحقق من وجود أي تداخل بالتواريخ
        $conflictingReservation = $reservations->first(function ($res) use ($start, $end) {
            $existingStart = Carbon::parse($res->start_date);
            $existingEnd = Carbon::parse($res->end_date);

            return $start <= $existingEnd && $end >= $existingStart;
        });

        if ($conflictingReservation) {
            // إذا وجد تداخل ننتقل للخطوة الثانية (فحص الكميات)
            // لا نوقف مباشرة هنا
        }

        // ✅ ثانياً: تحقق من الكمية المحجوزة مقابل الكمية المتوفرة
        $dateCounts = [];

        foreach ($reservations as $res) {
            $resStart = Carbon::parse($res->start_date);
            $resEnd = Carbon::parse($res->end_date);

            for ($date = $resStart->copy(); $date->lte($resEnd); $date->addDay()) {
                $key = $date->toDateString();
                $dateCounts[$key] = ($dateCounts[$key] ?? 0) + $res->quantity;
            }
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
                'start_date' => 'This product is fully booked on these dates: ' . implode(', ', $conflictDates),
            ])->withInput();
        }

        // ✅ إذا مررنا من التحققين ➔ الحجز آمن ونكمل

        // حساب السعر الكلي
        $diffDays = $end->diffInDays($start) + 1;
        $totalPrice = $diffDays > 0 ? $product->price * $diffDays : 0;

        // إنشاء الحجز
        Reservation::create([
            'user_id'          => auth()->id(),
            'product_id'       => $product->id,
            'slug'             => Str::uuid(),
            'reservation_type' => 'daily',
            'start_date'       => $start->toDateString(),
            'end_date'         => $end->toDateString(),
            'total_price'      => $totalPrice,
            'paid_amount'      => $amountPaid,
            'platform_fee'     => ($totalPrice * 0.05),
            'quantity'         => 1,
            'status'           => 'not_started',
            'comment'          => $comment,
        ]);

        return redirect()->back()->with('success', 'Reservation submitted successfully!');
    }



    public function getUnavailableDates(Product $product)
    {
        $today = Carbon::today();

        $reservations = Reservation::where('product_id', $product->id)
            ->where('status', '!=', 'cancelled')
            ->where('end_date', '>=', $today)
            ->get();

        $dateCounts = [];

        foreach ($reservations as $res) {
            $start = Carbon::parse($res->start_date);
            $end = Carbon::parse($res->end_date);

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $key = $date->toDateString();
                $dateCounts[$key] = ($dateCounts[$key] ?? 0) + 1; 
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
