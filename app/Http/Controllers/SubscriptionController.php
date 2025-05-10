<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{

    public function adminIndex(Request $request)
    {
        $query = User::where('user_type', 'merchant')
            ->whereHas('subscription')
            ->with('subscription');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('subscription', function ($subQ) use ($search) {
                      $subQ->where('subscription_type', 'like', "%{$search}%");
                  });
            });
        }

        $subscribers = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.subscriptions', compact('subscribers'));
    }



    public function subscriptionStore(Request $request)
{
    $request->validate([
        'subscription_type' => 'required|string',
        'price' => 'required|numeric',
    ]);

    $user = Auth::user();

    $validPlans = [
        15 => 3,
        25 => 6,
        40 => 12,
    ];

    $price = (int) $request->price;
    $duration = (int) $request->duration;

    if (!isset($validPlans[$price]) || $validPlans[$price] !== $duration) {
        return back()->with('error', 'Invalid subscription plan.');
    }

    $startDate = now();
    $endDate = now()->addMonths($duration);

    $existingSubscription = $user->subscriptions()
        ->whereNull('deleted_at')
        ->latest('end_date')
        ->first();

    if ($existingSubscription) {
        if ($existingSubscription->end_date >= now()) {
            return back()->with('error', 'You already have an active subscription.');
        }

        $existingSubscription->delete();
    }

    Subscription::create([
        'user_id' => $user->id,
        'subscription_type' => $request->subscription_type,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'price' => $price,
    ]);

    return redirect()->route('merchant.subscription')->with('success', 'Subscription activated successfully!');
}

public function showSubscriptionPage()
{
    $user = Auth::user();

    $hasActiveSubscription = $user->subscriptions()
        ->whereNull('deleted_at')
        ->where('end_date', '>=', now())
        ->exists();

    if ($hasActiveSubscription) {
        return redirect()->route('merchant.dashboard')->with('error', 'You already have an active subscription.');
    }

    return view('merchants.subscription.index');
}

public function showMySubscription()
{
    $user = Auth::user();

    $subscription = $user->subscriptions()
        ->whereNull('deleted_at')
        ->where('end_date', '>=', now())
        ->latest('end_date')
        ->first();

    if (!$subscription) {
        return redirect()->route('merchant.subscription')->with('error', 'No active subscription found.');
    }

    return view('merchants.subscription.show', [
        'activeSubscription' => $subscription,
    ]);
}
}
