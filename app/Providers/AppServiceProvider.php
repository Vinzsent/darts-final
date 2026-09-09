<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        // General form / CRUD submission limiter.
        // Keyed by user id when authenticated, otherwise by IP.
        RateLimiter::for('forms', function (Request $request) {
            $key = $request->user()?->getAuthIdentifier()
                ?: $request->ip()
                ?: 'guest';

            return Limit::perMinute(20)->by('forms:' . $key);
        });

        // Login brute-force protection.
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by('login:' . ($request->input('username') ?: $request->ip()));
        });
    }
}

