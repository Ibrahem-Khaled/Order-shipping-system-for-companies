<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;


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
        \Carbon\Carbon::setLocale('ar');
        Paginator::useBootstrap();

        Gate::define('is-super-admin', function (User $user) {
            // افترض أن السوبر أدمن له role معين أو ID محدد
            return $user->role === 'superAdmin';
        });
    }
}
