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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {
        return redirect('home');
    });
    
    Route::get('/home', 'HomeController@index')->name('home');

    Route::name('providers.')->prefix('providers')->namespace('Providers')->group(function () {
        Route::get('/', 'ProvidersController@index')->name('index');
        Route::get('/{media_type}/{id}', 'ProvidersController@show')->name('show');
//        Route::get('/{media_type}/{id}/status_changes', 'ProvidersController@showTrackingStatusChanges')->name('showStatusChanges');
    });

    // Licensors Search
    Route::name('licensors.')->prefix('licensors')->namespace('Licensors')->group(function () {
        Route::get('/', 'LicensorsController@index')->name('index');
        Route::get('/get-json', 'LicensorsController@showAll')->name('get-json');
        Route::post('/search', 'LicensorsController@getBySearch')->name('search');

//        Route::get('/{id}', 'LicensorsController@getBySearch')->name('show');
//        Route::get('/{id}/export/content', 'LicensorsController@exportContent')->name('export.content');
    });
    // Auth::logout();
});

Auth::routes(['verify' => true]);
