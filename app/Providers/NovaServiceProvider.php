<?php

namespace App\Providers;
use App\User;
use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;
use Spp\Help\Help;
use Spp\ItemBuilder\ItemBuilder;
use Spp\ProductBuilder\ProductBuilder;
use Spp\ProductCollection\ProductCollection;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        $users = User::select('email')->get();
        $userArr = [];
        foreach ($users as $user){
            $userArr[] = $user->email;
        }
        Gate::define('viewNova', function ($user) use ($userArr) {
            return in_array($user->email, $userArr);
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help(),
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new ItemBuilder(),
            new ProductBuilder()
        ];
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
