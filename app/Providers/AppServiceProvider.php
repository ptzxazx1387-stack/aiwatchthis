<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        Paginator::defaultView('vendor.pagination.minimal');
        Paginator::defaultSimpleView('vendor.pagination.minimal-simple');

        $this->registerFormattingDirectives();
    }

    /**
     * دستورهای Blade برای قالب‌بندی فارسی — ارقام، مبلغ و تاریخ شمسی.
     */
    private function registerFormattingDirectives(): void
    {
        Blade::directive('fa', fn ($expr) => "<?php echo e(\App\Support\Fmt::fa($expr)); ?>");
        Blade::directive('num', fn ($expr) => "<?php echo e(\App\Support\Fmt::num($expr)); ?>");
        Blade::directive('money', fn ($expr) => "<?php echo e(\App\Support\Fmt::money($expr)); ?>");
        Blade::directive('brief', fn ($expr) => "<?php echo e(\App\Support\Fmt::compact($expr)); ?>");
        Blade::directive('jdate', fn ($expr) => "<?php echo e(\App\Support\Fmt::date($expr)); ?>");
        Blade::directive('jshort', fn ($expr) => "<?php echo e(\App\Support\Fmt::shortDate($expr)); ?>");
        Blade::directive('jdatetime', fn ($expr) => "<?php echo e(\App\Support\Fmt::dateTime($expr)); ?>");
        Blade::directive('ago', fn ($expr) => "<?php echo e(\App\Support\Fmt::ago($expr)); ?>");
    }
}
