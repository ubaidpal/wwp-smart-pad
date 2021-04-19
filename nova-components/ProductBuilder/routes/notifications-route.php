<?php
/**
 * Created by PhpStorm.
 * User: Ubaid
 * Date: 3-26-2019
 * File: Notification Routes
 * Time: 2:02 PM
 */
/*===============================================================================
 *
 *                  ***********      Help Routes Integrations       **********
 *
 * ==============================================================================*/

// Api route
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/getNotifications', 'Spp\ProductBuilder\Http\NotificationsController@index'); // get Notification
Route::get('/getNotificationLogs', 'Spp\ProductBuilder\Http\NotificationsController@getLogs'); // get Notification
Route::post('/destroyNotification', 'Spp\ProductBuilder\Http\NotificationsController@destroyNotification'); //destroy Notification
Route::post('/addNotification', 'Spp\ProductBuilder\Http\NotificationsController@saveNotification'); //add Notification
Route::get('/getNotifyData/{id}', 'Spp\ProductBuilder\Http\NotificationsController@getNotifyData'); // get Notification
