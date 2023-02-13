<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
//Clear Cache facade value:

use App\Helpers\CustomDateFilter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Reoptimized class loader:
Route::get('/optimize', function () {
    return Artisan::call('optimize');
});
//Clear Config cache:
Route::get('/config-cache', function () {
    return Artisan::call('config:cache');
});

//Clear Config cache:
Route::get('/sync-permission', function () {
    return Artisan::call('db:seed --class=PermissionTableSeeder');
});

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'AppController@app')->name('app');
});
Auth::routes();

Route::group(['middleware' => ['auth'], 'prefix' => 'api', 'namespace' => 'Api'], function () {

    Route::GET('/checkUserValid', 'UserController@checkUserValid');
    Route::resource('/users', 'UserController');

    Route::resource('roles', 'RoleController');
    Route::GET('get-permission/{id}', 'RoleController@getPermission');

    Route::resource('/categories', 'CategoriesController');
    Route::resource('/attributes', 'AttributeController');

    Route::resource('/items', 'ItemController');
    Route::get('/items-delete-sub/{stock_id}', 'ItemController@deleteSub');
    Route::post('/items-import', 'ItemController@ItemImport');
    Route::post('/items-show', 'ItemController@ItemShow');
    Route::get('/items-filter', 'ItemController@itemFilter');

    // ====================== Company Infor =========================//
    // =====================================================//
    Route::resource('/com-infor', 'CompanyInforController');

    Route::GET('/get-keyword-date', function () {
        return CustomDateFilter::getKeywordDate();
    });
});
