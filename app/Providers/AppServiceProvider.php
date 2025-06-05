<?php

namespace App\Providers;

use App\Helpers\CurrencyFormatter;
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
        if(config('app.env') !== 'local') {
            \URL::forceScheme('https');
        }

        // Add currency formatter directive
        Blade::directive('currency', function ($expression) {
            return "<?php echo \App\Helpers\CurrencyFormatter::format($expression); ?>";
        });
    }
}
