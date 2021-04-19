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
 *                  ***********      Dealer Routes Integrations       **********
 *
 * ==============================================================================*/

// Api route
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/getDealerStatus', 'Spp\ProductBuilder\Http\DealerController@getDealerStatus'); // get Dealer Status
Route::get('/getDealerList/{folder_id?}', 'Spp\ProductBuilder\Http\DealerController@index'); // get Dealer Accounts List
Route::get('/getActiveCountry', 'Spp\ProductBuilder\Http\DealerController@getActiveCountry'); // get Active Country List
Route::post('/saveDealerStatus', 'Spp\ProductBuilder\Http\DealerController@saveDealerStatus'); // get Active Country List
Route::post('/addAccounts', 'Spp\ProductBuilder\Http\DealerController@addAccounts'); // Add Dealer Accounts List
Route::get('/signUpDealer/{activation_token?}', 'Spp\ProductBuilder\Http\DealerController@signUpDealer'); // Add Dealer add Employee Accounts and Send Email To Employee
Route::get('/getDealers', 'Spp\ProductBuilder\Http\DealerController@dealers'); // get Dealer Accounts List
Route::get('/getAllDealers/{brand_id}', 'Spp\ProductBuilder\Http\DealerController@getDealers'); // get Dealer Accounts List

Route::post('/addEmployeeAccounts', 'Spp\ProductBuilder\Http\DealerController@addEmployeeAccounts'); // Add Dealer Accounts List
Route::post('/destroyAccounts', 'Spp\ProductBuilder\Http\DealerController@destroyAccounts'); // Destroy Accounts
Route::post('/destroyEmployee', 'Spp\ProductBuilder\Http\DealerController@destroyEmployee'); // Destroy Employee
Route::post('/getEmployeeRecord', 'Spp\ProductBuilder\Http\DealerController@getEmployeeRecord'); // Destroy Employee

Route::get('/getCompanies/{account_id}', 'Spp\ProductBuilder\Http\DealerController@getCompanies');

Route::post('/updateAccountStatus', 'Spp\ProductBuilder\Http\DealerController@updateAccountStatus');
Route::post('/updateEmployeeStatus', 'Spp\ProductBuilder\Http\DealerController@updateEmployeeStatus');
Route::post('/updateAccountBrand', 'Spp\ProductBuilder\Http\DealerController@updateAccountBrand');
Route::post('/updateAccountExclusiveBrand', 'Spp\ProductBuilder\Http\DealerController@updateAccountExclusiveBrand');
Route::post('/updateAccountBrandInBulk', 'Spp\ProductBuilder\Http\DealerController@updateAccountBrandInBulk');
Route::post('/updateBrandAccountInBulk', 'Spp\ProductBuilder\Http\DealerController@updateBrandAccountInBulk');

Route::get('/getDealerCompanies', 'Spp\ProductBuilder\Http\DealerController@dealerWithCompanies'); // get Dealer Accounts

Route::get('/getBrandAccounts/{brand_id}', 'Spp\ProductBuilder\Http\DealerController@getBrandAccounts');

Route::get('/getDealerInfo/{account_id}', 'Spp\ProductBuilder\Http\DealerController@getDealerInfo'); // get Active Country List

Route::post('/addDealerFolder', 'Spp\ProductBuilder\Http\DealerController@addDealerFolder');
Route::get('/getFolders', 'Spp\ProductBuilder\Http\DealerController@getFolders');
Route::get('/getAllFolders', 'Spp\ProductBuilder\Http\DealerController@getAllFolders');
Route::get('/getFolders/{parent_id}', 'Spp\ProductBuilder\Http\DealerController@getFolders');
Route::post('/destroyFolder', 'Spp\ProductBuilder\Http\DealerController@destroyFolder'); // Destroy Folder

Route::get('/getAccountTypes', 'Spp\ProductBuilder\Http\DealerController@getAccountTypes');
Route::get('/getNotSlave', 'Spp\ProductBuilder\Http\DealerController@getNotSlave');

//Set Refferal Token
Route::post('/setRefferalToken/{id}', 'Spp\ProductBuilder\Http\DealerController@setRefferalToken');


Route::get('/getSMSLogs/{id}', 'Spp\ProductBuilder\Http\DealerController@getSMSLogs'); // get SMS Logs
