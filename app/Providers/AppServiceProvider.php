<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        // bypass permission check for Super Admin role
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });


        // Keep necessary reusable data gloablly accessible for views which extend admin layout
        View::composer('layouts.admin', function ($view) {
            if (Auth::check()) {
                $globalData['system_configurations'] = getOption('system_configurations');
                $view->with('globalData', $globalData);
            }
        });

    }
}
