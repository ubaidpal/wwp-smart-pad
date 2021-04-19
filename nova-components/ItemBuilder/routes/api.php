<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::post('/', 'Spp\ItemBuilder\Http\ItemBuilderController@store');
Route::post('/browse', 'Spp\ItemBuilder\Http\ItemBuilderController@browse');
Route::post('/update', 'Spp\ItemBuilder\Http\ItemBuilderController@update');
Route::post('/excelToJson', 'Spp\ItemBuilder\Http\ItemBuilderController@excelToJson');
Route::get('/getBrands', 'Spp\ItemBuilder\Http\ItemBuilderController@indexBrand');
Route::get('/getVersion/{brand_id?}', 'Spp\ItemBuilder\Http\ItemBuilderController@getVersion');
Route::get('/activeCategories', 'Spp\ItemBuilder\Http\ItemBuilderController@activeCategories');
Route::get('/getBrandComment/{brand_id}', 'Spp\ItemBuilder\Http\ItemBuilderController@getBrandComment');
Route::post('/updateVersion', 'Spp\ItemBuilder\Http\ItemBuilderController@updateVersion');
Route::post('/deleteVersion', 'Spp\ItemBuilder\Http\ItemBuilderController@deleteVersion');
