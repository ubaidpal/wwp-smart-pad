<?php
/**
 * Created by   :  Ubaid UllAh
 * Project Name : SmartPad
 * Product Name : PhpStorm
 * Date         : 04/8/2019 2:32 PM
 * File Name    : ProductOptionsClassServiceProvider.php
 */
namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ProductOptionsClassServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('productoption', function()
        {
            return new \App\Classes\ProductOption;
        });
    }
}
