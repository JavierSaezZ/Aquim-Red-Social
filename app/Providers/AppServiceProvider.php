<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // <--- No olvides importar esto

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Esto limita la longitud de los strings para que MySQL no se queje
        Schema::defaultStringLength(191);
    }
}