<?php
/**
 * Created by   :  Ubaid UllAh
 * Project Name : SmartPad
 * Product Name : PhpStorm
 * Date         : 05/28/2019 2:32 PM
 * File Name    : ProductOptionJsonClassServiceProvider.php
 */
namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ProductOptionJsonClassServiceProvider extends ServiceProvider
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
        App::bind('productoptionjson', function()
        {
            return new \App\Classes\ProductOptionJson;
        });
    }
}
