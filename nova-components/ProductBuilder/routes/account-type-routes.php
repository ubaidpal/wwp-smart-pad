<?php
/**
 * Created by PhpStorm.
 * User: Nabeel Qadri
 * Date: 1-29-2019
 * File: Users Routes
 * Time: 8:02 PM
 */
/*===============================================================================
 *
 *                  ***********      Users Routes Integrations       **********
 *
 * ==============================================================================*/

// Api route
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Get Users
Route::get('/getAccountTypes', 'Spp\ProductBuilder\Http\AccountTypeController@index');
Route::post('/destroyAccountType/{id}', 'Spp\ProductBuilder\Http\AccountTypeController@destroy');
Route::post('/editAccountType/{id}', 'Spp\ProductBuilder\Http\AccountTypeController@update');
