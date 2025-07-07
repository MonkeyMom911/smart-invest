<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
      if (config('app.env') === 'production') { // <-- Tambahkan kondisi ini
            URL::forceScheme('https'); // <-- Tambahkan baris ini
        }
    }
}
