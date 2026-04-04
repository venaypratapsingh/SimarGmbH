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
        
        // Force IPv4 for PostgreSQL connections (fixes IPv6 "Network is unreachable" error)
        $this->forceIpv4ForDatabase();
    }
    
    /**
     * Force IPv4 resolution for database host to avoid IPv6 connection issues
     *
     * @return void
     */
    protected function forceIpv4ForDatabase()
    {
        $dbHost = env('DB_HOST');
        
        // Only apply if DB_HOST is set and contains a domain name (not already an IP)
        if ($dbHost && !filter_var($dbHost, FILTER_VALIDATE_IP)) {
            // Resolve hostname to IPv4 address
            $ipv4 = gethostbyname($dbHost);
            
            // Only update if we got a valid IPv4 (not the hostname itself)
            if ($ipv4 && $ipv4 !== $dbHost && filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                // Override the config to use IPv4 address
                config(['database.connections.pgsql.host' => $ipv4]);
            }
        }
    }
}
