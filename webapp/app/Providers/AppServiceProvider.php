<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        Blade::directive('bareforeach', function (string $expression) {
            return "<?php foreach($expression): ?>";
        });
        Blade::directive('endbareforeach', function (string $expression) {
            return "<?php endforeach ?>";
        });
    }
}
