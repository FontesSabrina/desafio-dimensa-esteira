<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // 1. Importe esta classe

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 2. Adicione esta linha para o Laravel usar o estilo do Tailwind
        Paginator::useTailwind();
    }
}
