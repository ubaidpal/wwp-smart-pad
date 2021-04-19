<?php

namespace App\Providers;

use App\Models\Accounts;
use App\Models\Employees;
use App\Observers\AccountsObserver;
use App\Observers\EmployeesObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AccontsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Accounts::observe(AccountsObserver::class); // add accounts observer
        Employees::observe(EmployeesObserver::class); // add employee observer

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
