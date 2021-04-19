<?php
/**
 * Created by PhpStorm.
 * User: Ubaid
 * Date: 1-29-2019
 * File: Dealer Routes
 * Time: 4:02 PM
 */
/*===============================================================================
 *
 *                  ***********      Brand Routes Integrations       **********
 *
 * ==============================================================================*/

// Api route
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/addNewBrand', 'Spp\ProductBuilder\Http\ProductBuilderController@addNewBrand'); //add  New Brand
Route::get('/getBrand/{id?}', 'Spp\ProductBuilder\Http\ProductBuilderController@getBrand');
// add New Category
Route::post('/addNewCategory', 'Spp\ProductBuilder\Http\ProductBuilderController@addNewCategory');

// Get Brands Dealers
Route::get('/getBrandDealers/{brand_id}/{fabric?}', 'Spp\ProductBuilder\Http\ProductBuilderController@getBrandDealers');
