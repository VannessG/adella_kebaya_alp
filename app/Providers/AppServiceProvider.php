<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Branch;
use Illuminate\Support\Facades\Route;

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
        Paginator::useBootstrapFive();
        
        if ($this->app->environment('local')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $view->with('branches', Branch::where('is_active', true)->get());
            $view->with('canResetPassword', Route::has('password.request'));
        });
    }
}