<?php
/**
 * Created by PhpStorm.
 * User: Nabeel Qadri
 * Date: 4-11-2019
 * File: Dealer Routes
 * Time: 5:19 PM
 */
/*===============================================================================
 *
 *                  ***********      Constraints Routes Integrations       **********
 *
 * ==============================================================================*/

// Api route
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/addConstraint', 'Spp\ProductBuilder\Http\ConstraintsController@addConstraint');
Route::post('/deleteConstraint', 'Spp\ProductBuilder\Http\ConstraintsController@deleteConstraint');
Route::post('/deleteCondition', 'Spp\ProductBuilder\Http\ConstraintsController@deleteCondition');


Route::get('/getCalculation/{option_id}', 'Spp\ProductBuilder\Http\ConstraintsController@getCalculation');
Route::post('/saveCalculation/{option_id}', 'Spp\ProductBuilder\Http\ConstraintsController@saveCalculation');
