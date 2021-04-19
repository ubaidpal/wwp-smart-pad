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
Route::get('/getUsers', 'Spp\ProductBuilder\Http\UserController@getUsers');
Route::get('/getUserAccess', 'Spp\ProductBuilder\Http\UserController@getUserAccess');
Route::post('/destroyUser', 'Spp\ProductBuilder\Http\UserController@destroyUser');

Route::get('/getRoles', 'Spp\ProductBuilder\Http\RoleController@getRoles');
Route::post('/destroyRole', 'Spp\ProductBuilder\Http\RoleController@destroyRole');

Route::get('/getLogs', 'Spp\ProductBuilder\Http\RoleController@getLogs');
