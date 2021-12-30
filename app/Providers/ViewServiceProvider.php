<?php

namespace App\Providers;

use App\View\Composers\TopNavBarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // Using class based composers...
        View::composer('components/top-nav-bar', TopNavBarComposer::class);
        // Using closure based composers...
        // View::composer('dashboard', function ($view) {
        //     //
        // });
    }
}
