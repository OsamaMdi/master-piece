<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // استخدام Bootstrap مع الـ Pagination
        Paginator::useBootstrap();

        // إشعارات الـ admin والتاجر في الـ navbar
        View::composer(['admin.partials.navbar', 'merchants.partials.navbar'], function ($view) {
            $notifications = [];
            $unreadCount = 0;

            if (Auth::check()) {
                $user = Auth::user();

                $notifications = Notification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->orderByDesc('created_at')
                    ->limit(10)
                    ->get();

                $unreadCount = $notifications->count();
            }

            $view->with(compact('notifications', 'unreadCount'));
        });

        // تفعيل تسليم الرسائل غير المستلمة عند فتح أي صفحة
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $userId = $user->id;
                $userType = get_class($user);

                $chatIds = \App\Models\Chat::where(function ($q) use ($userId, $userType) {
                        $q->where('receiver_id', $userId)->where('receiver_type', $userType);
                    })
                    ->orWhere(function ($q) use ($userId, $userType) {
                        $q->where('sender_id', $userId)->where('sender_type', $userType);
                    })
                    ->pluck('id');

                \App\Models\Message::whereIn('chat_id', $chatIds)
                    ->where('sender_id', '!=', $userId)
                    ->where('delivered', false)
                    ->update([
                        'delivered' => true,
                    ]);
            }
        });

        // الاشتراك الحالي للتاجر
        View::composer('merchants.partials.navbar', function ($view) {
            $activeSubscription = null;

            if (Auth::check() && Auth::user()->user_type === 'merchant') {
                $user = Auth::user();

                $activeSubscription = $user->subscription()
                    ->whereNull('deleted_at')
                    ->where('end_date', '>=', now())
                    ->latest('end_date')
                    ->first();
            }

            $view->with('activeSubscription', $activeSubscription);
        });

        // توليد روابط Vite و APP_URL حسب الشبكة المحلية
        if ($this->app->environment('local')) {
            $ip = getHostByName(getHostName());
            URL::forceRootUrl("http://{$ip}:8000");
        }

        $viteHost = request()->getHost();
        View::share('viteHost', $viteHost);
    }
}
