<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
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
        // Forzar que todas las rutas usen el host actual de la petición,
        // evitando errores 419 Page Expired por cruce entre localhost y 127.0.0.1
        if (request()->root()) {
            \Illuminate\Support\Facades\URL::forceRootUrl(request()->root());
        }

        // En desarrollo: lanzar excepción si se detecta un lazy load (N+1 query)
        // Esto ayuda a identificar y corregir queries ineficientes durante el desarrollo.
        if (app()->isLocal()) {
            Model::preventLazyLoading();
        }
    }
}
