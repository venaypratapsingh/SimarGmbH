<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS for all URLs when APP_URL uses HTTPS or in production
        $appUrl = config('app.url');
        if (str_starts_with($appUrl, 'https://') || config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // Also force HTTPS if request is already HTTPS (for Render)
        if (request()->secure() || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
    }
}
