<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Blade;
use Route;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Paginator::defaultView('components.table-pagination');

        if (config('app.debug')) {
            DB::listen(function ($query) {

                Log::channel('query_log')->debug(
                    $query->sql,
                    $query->bindings,
                    $query->time,
                );

            });

            Log::channel('query_log')->info(
                PHP_EOL
            );

        }

        Blade::if('routeis', function ($expression) {
            return fnmatch($expression, Route::currentRouteName());
        });


    }
}
