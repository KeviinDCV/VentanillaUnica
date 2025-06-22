<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

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
        // En desarrollo, forzar HTTP para evitar problemas con HTTPS
        if (app()->environment('local')) {
            URL::forceScheme('http');
        }

        // Configurar Carbon para Colombia
        Carbon::setLocale('es');
        setlocale(LC_TIME, 'es_CO.UTF-8', 'es_CO', 'Spanish_Colombia');

        // Configurar zona horaria por defecto para PHP
        date_default_timezone_set('America/Bogota');
    }
}
