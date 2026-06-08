<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set locale Carbon ke Indonesia untuk translatedFormat()
        Carbon::setLocale('id');

        // Blade directive shortcut rupiah: @rupiah($angka)
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo App\Helpers\InvenHelper::rupiah($expression); ?>";
        });
    }
}
