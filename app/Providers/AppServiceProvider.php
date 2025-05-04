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

    }

    }

