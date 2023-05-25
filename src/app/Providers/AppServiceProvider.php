<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        if (config('database.connections.mysql.log_queries')) {
            DB::listen(function ($query) {
                Log::info(
                    $query->sql . ' | bindings: ' . json_encode($query->bindings) . ' | query time: ' . $query->time
                );
            });
        }
    }
}
