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
 *                  ***********      Help Routes Integrations       **********
 *
 * ==============================================================================*/

// Api route
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/getGroupHelp', 'Spp\ProductBuilder\Http\HelpController@index'); // get Groups
Route::post('/destroyGroup', 'Spp\ProductBuilder\Http\HelpController@destroyGroup'); //destroy Group
//get Help
Route::get('/getHelp/{help_group_id?}', 'Spp\ProductBuilder\Http\HelpController@getHelp'); //destroy Group/
Route::post('/destroyHelp', 'Spp\ProductBuilder\Http\HelpController@destroyHelp'); //destroy Group

Route::get('/getGroupsList', 'Spp\ProductBuilder\Http\HelpController@getGroupsList'); //destroy Group
