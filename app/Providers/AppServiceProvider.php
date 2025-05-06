<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
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
        Paginator::useBootstrap();

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


        view()->composer('*', function ($view) {
    if (auth()->check()) {
        $user = auth()->user();
        $userId = $user->id;
        $userType = get_class($user);

        // جلب كل الشاتات اللي المستخدم طرف فيها
        $chatIds = \App\Models\Chat::where(function ($q) use ($userId, $userType) {
                $q->where('receiver_id', $userId)->where('receiver_type', $userType);
            })
            ->orWhere(function ($q) use ($userId, $userType) {
                $q->where('sender_id', $userId)->where('sender_type', $userType);
            })
            ->pluck('id');

        // تحديث كل الرسائل داخل هذه الشاتات التي لم تُسلم بعد
        \App\Models\Message::whereIn('chat_id', $chatIds)
            ->where('sender_id', '!=', $userId) // يعني مش أنا اللي باعتها
            ->where('delivered', false)
            ->update([
                'delivered' => true,
            ]);
    }
});



    }

    }

