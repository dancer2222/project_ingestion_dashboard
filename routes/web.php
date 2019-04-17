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
    Route::group(['prefix' => 'ingestion', 'namespace' => 'Ingestion'], function () {
        Route::get('/', 'Process\\IngestionController@indexIngestMovies')->name('indexIngestMovies');
        Route::post('/movie/awsCheck', 'Aws\\AwsController@checkMovieForAwsBucket')->name('awsCheck');
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

    Route::namespace('Media')->group(function () {
        // Search
        Route::get('/search', 'SearchController@index')->name('search');

        // Books
        Route::name('books.')->prefix('/books')->group(function () {
            Route::get('/', 'BooksController@index')->name('index');
        });

        // Movies
        Route::name('movies.')->prefix('/movies')->group(function () {
            Route::get('/', 'MoviesController@index')->name('index');
            Route::get('/{id}', 'MoviesController@show')->name('show');
            Route::post('/{id}', 'MoviesController@update')->name('update');
        });

        // Albums
        Route::name('albums.')->prefix('/albums')->group(function () {
            Route::get('/', 'AlbumsController@index')->name('index');
        });

        // Audiobooks
        Route::name('audiobooks.')->prefix('/audiobooks')->group(function () {
            Route::get('/', 'AudiobooksController@index')->name('index');
        });

        // Games
        Route::name('games.')->prefix('/games')->group(function () {
            Route::get('/', 'GamesController@index')->name('index');
        });
    });
    // Auth::logout();
});

Auth::routes(['verify' => true]);
