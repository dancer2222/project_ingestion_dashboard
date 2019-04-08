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

    // Licensors Search
    Route::name('licensors.')->prefix('licensors')->namespace('Licensors')->group(function ()
    {
        Route::get('/', 'LicensorsController@index')->name('index');
        Route::get('/get-json', 'LicensorsController@showAll')->name('get-json');
        Route::post('/search', 'LicensorsController@getBySearch')->name('search');
    });

    
    Route::group(['middleware' => ['permission:manage users']], function () {
        // Users
        Route::get('/users', 'Users\\UsersController@index')->name('users.index');
        Route::get('/users/{id}/edit', 'Users\\UsersController@edit')->name('users.edit');
        Route::post('/users/{id}/destroy', 'Users\\UsersController@destroy')->name('users.destroy');
        Route::post('/users/{id}/update', 'Users\\UsersController@update')->name('users.update');
        Route::post('/users/{id}/password', 'Users\\UsersController@password')->name('users.password');
        Route::get('/users/create', 'Users\\UsersController@create')->name('users.create'); 
        Route::post('/users/create', 'Users\\UsersController@store')->name('users.store'); 

        // Roles 
        Route::get('/roles', 'Roles\\RolesController@index')->name('roles.index');
        Route::get('/roles/{id}/edit', 'Roles\\RolesController@edit')->name('roles.edit');
        Route::post('/roles/{id}/destroy', 'Roles\\RolesController@destroy')->name('roles.destroy');
        Route::post('/roles/{id}/edit', 'Roles\\RolesController@update')->name('roles.update');
        Route::get('/roles/create', 'Roles\\RolesController@create')->name('roles.create'); 
        Route::post('/roles/create', 'Roles\\RolesController@store')->name('roles.store');

        //Permissions
        Route::get('/permissions/{id}/edit', 'Roles\\PermissionsController@edit')->name('permissions.edit');
        Route::post('/permissions/{id}/destroy', 'Roles\\PermissionsController@destroy')->name('permissions.destroy');
        Route::post('/permissions/{id}/update', 'Roles\\PermissionsController@update')->name('permissions.update');
        Route::get('/permissions/create', 'Roles\\PermissionsController@create')->name('permissions.create'); 
        Route::post('/permissions/create', 'Roles\\PermissionsController@store')->name('permissions.store');
    });
    // Auth::logout();
});

Auth::routes(['verify' => true]);
