<?php

use Illuminate\Http\Request;
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

Route::post('/', 'Spp\ProductBuilder\Http\ProductBuilderController@store');
Route::get('/getProductInfo/{product_id}', 'Spp\ProductBuilder\Http\ProductBuilderController@getProductInfo');
Route::post('/updateProduct/{product_id}', 'Spp\ProductBuilder\Http\ProductBuilderController@updateProductInfo');
Route::post('/updateProduct', 'Spp\ProductBuilder\Http\ProductBuilderController@update');
Route::post('/productOptions', 'Spp\ProductBuilder\Http\ProductBuilderController@productOptions');
Route::post('/excelReader', 'Spp\ProductBuilder\Http\ProductBuilderController@excelReader');
Route::post('/importSubOptions', 'Spp\ProductBuilder\Http\ProductBuilderController@importSubOptions');
Route::get('/products/{brand_id?}/{category_id?}', 'Spp\ProductBuilder\Http\ProductBuilderController@getProducts');
Route::get('/inCollectionProducts/{in_collection?}/{brand_id?}/{category_id?}', 'Spp\ProductBuilder\Http\ProductBuilderController@inCollectionProducts');
Route::get('/countries', 'Spp\ProductBuilder\Http\ProductBuilderController@getCountries');
Route::get('/getactivecountries', 'Spp\ProductBuilder\Http\ProductBuilderController@getActiveCountries');
Route::get('/setSession/{belongsToId}', 'Spp\ProductBuilder\Http\ProductBuilderController@setSession');
Route::post('/resetPriceGrid', 'Spp\ProductBuilder\Http\ProductBuilderController@resetPriceGrid');
Route::get('/priceGrid/{product_id}', 'Spp\ProductBuilder\Http\ProductBuilderController@getPriceGrid');
Route::get('/productOptions/{product_id}', 'Spp\ProductBuilder\Http\ProductBuilderController@getOptions');
Route::get('/getCountryMeasureUnit', 'Spp\ProductBuilder\Http\ProductBuilderController@getCountryMeasureUnit');
Route::get('/getBrandComment/{brand_id}', 'Spp\ProductBuilder\Http\ProductBuilderController@getBrandComment');

Route::get('/getCategories', 'Spp\ProductBuilder\Http\CategoryController@index');
Route::post('/destroyCategory', 'Spp\ProductBuilder\Http\CategoryController@destroy');
Route::get('/getFabricCategories', 'Spp\ProductBuilder\Http\CategoryController@indexFabric');
Route::get('/getBrands', 'Spp\ProductBuilder\Http\CategoryController@indexBrand');
Route::get('/getExclusiveBrands/{type?}', 'Spp\ProductBuilder\Http\CategoryController@getExclusiveBrands');
Route::post('/destroyBrand', 'Spp\ProductBuilder\Http\CategoryController@destroyBrand');
Route::post('/updateBrandStatus', 'Spp\ProductBuilder\Http\CategoryController@updateBrandStatus');
Route::post('/updateExclusiveStatus', 'Spp\ProductBuilder\Http\CategoryController@updateExclusiveStatus');
Route::post('/destroyProduct', 'Spp\ProductBuilder\Http\CategoryController@destroyProduct');
Route::get('/getCountries', 'Spp\ProductBuilder\Http\CategoryController@indexCountries');
Route::post('/destroyCountry', 'Spp\ProductBuilder\Http\CategoryController@destroyCountry');
Route::post('/updateCategoryStatus', 'Spp\ProductBuilder\Http\CategoryController@updateCategoryStatus');
Route::post('/updateProductStatus', 'Spp\ProductBuilder\Http\ProductBuilderController@updateProductStatus');
Route::post('/updateCountryStatus', 'Spp\ProductBuilder\Http\CategoryController@updateCountryStatus');
Route::post('/updateCountryUser', 'Spp\ProductBuilder\Http\ProductBuilderController@updateCountryUser');
/*********** Product Collection Routes *************/
Route::get('/brands', 'Spp\ProductBuilder\Http\ProductCollectionController@getBrands');
Route::get('/brand/collections/{id}', 'Spp\ProductBuilder\Http\\ProductCollectionController@getCollectionsById');
Route::get('/collections', 'Spp\ProductBuilder\Http\\ProductCollectionController@getCollections');
Route::get('/productsToAdd', 'Spp\ProductBuilder\Http\\ProductCollectionController@getProducts');
Route::post('/storeCollection', 'Spp\ProductBuilder\Http\\ProductCollectionController@storeCollection');
Route::post('/addProductToCollection','Spp\ProductBuilder\Http\\ProductCollectionController@addProductToCollection');
Route::post('/deleteProductFromCollection','Spp\ProductBuilder\Http\\ProductCollectionController@deleteProductFromCollection');
Route::post('/deleteCollection','Spp\ProductBuilder\Http\\ProductCollectionController@deleteCollection');
Route::post('/addProductCollection','Spp\ProductBuilder\Http\\ProductCollectionController@addProductCollection');
Route::get('/getProductCollections/{product_id}', 'Spp\ProductBuilder\Http\\ProductCollectionController@getProductCollections');
/****Companies Routes****/
Route::get('/companies', 'Spp\ProductBuilder\Http\CompanyController@index');
Route::post('/destroyCompany', 'Spp\ProductBuilder\Http\CompanyController@destroyCompany');

/*********** Dealer Routes *************/
include('dealer-route.php');

/*********** HelpRoutes *************/
include('helps-route.php');


/*********** get Only Brand *************/
Route::get('/getBrandList', 'Spp\ProductBuilder\Http\ProductBuilderController@getBrandList');

/*********** get Only Categories *************/
Route::get('/brandCategory', 'Spp\ProductBuilder\Http\ProductBuilderController@getCategories');
Route::get('/activeCategories', 'Spp\ProductBuilder\Http\ProductBuilderController@activeCategories');

// clone product
Route::get('/getCloneProduct/{product_id?}', 'Spp\ProductBuilder\Http\ProductBuilderController@getCloneProduct');
Route::post('/cloneProduct', 'Spp\ProductBuilder\Http\ProductBuilderController@cloneProduct');

Route::get('/brandCategories/{brand_id}', 'Spp\ProductBuilder\Http\ProductBuilderController@getCategories');

Route::post('/bulkStatus', 'Spp\ProductBuilder\Http\ProductBuilderController@changeBulkStatus');
Route::post('/parsePriceGrid', 'Spp\ProductBuilder\Http\ProductBuilderController@getParsedPriceGrid');
Route::post('/appendProductOption', 'Spp\ProductBuilder\Http\ProductBuilderController@appendProductOption');

Route::post('/deleteProductOption', 'Spp\ProductBuilder\Http\ProductBuilderController@deleteOption');

Route::post('/deleteProductSubOption', 'Spp\ProductBuilder\Http\ProductBuilderController@deleteSubOption');

Route::post('/deletePriceGrid', 'Spp\ProductBuilder\Http\ProductBuilderController@deletePriceGrid');

/*********** BrandRoutes *************/
include('brands-route.php');

/*********** NotificationRoutes *************/
include('notifications-route.php');

/*********** UsersRoutes *************/
include('users-route.php');

/*********** ConstraintsRoutes *************/
include('constraints-route.php');

/*********** Account Type Routes *************/
include('account-type-routes.php');

Route::get('getOptionJson/{product_id}', 'Spp\ProductBuilder\Http\ProductBuilderController@getOptionJson');
