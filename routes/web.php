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
        Route::get('/', 'Process\\MovieIngestionController@indexIngestMovies')->name('indexIngestMovies');
        Route::get('/image', 'Process\\MovieIngestionController@processImages')->name('processImages');

        Route::post('/movie/convertMetadata', 'Process\\MovieIngestionController@convertMetadataFile')->name('convertMetadataFile');
        Route::post('/movie/ombdApi', 'Process\\MovieIngestionController@getDataFromOMBD')->name('ombdApi');
        Route::post('/movie/awsCheck', 'Process\\MovieIngestionController@checkMovieForAwsBucket')->name('awsCheck');
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

    // Stats routes
    Route::namespace('Stats')->name('stats.')->prefix('stats')->group(function () {
        // Movies
        Route::name('movies')->prefix('/movies')->group(function () {
            Route::get('/total_count', 'MoviesStatsController@countAll')->name('total_count');
        });

        // Books
        Route::name('books')->prefix('/books')->group(function () {
            Route::get('/total_count', 'BooksStatsController@countAll')->name('total_count');
        });

        // Music
        Route::name('music')->prefix('/music-Album')->group(function () {
            Route::get('/total_count', 'AlbumsStatsController@countAll')->name('total_count');
        });
    });

    Route::namespace('Media')->group(function () {
        // Search
        Route::get('/search', 'SearchController@index')->name('search');

        // Movies
        Route::name('movies.')->prefix('/movies')->group(function () {
            Route::get('/', 'MoviesController@index')->name('index');
            Route::get('/{id}', 'MoviesController@show')->name('show');
            Route::post('/{id}', 'MoviesController@update')->name('update');
        });

        // Books
        Route::name('books.')->prefix('/books')->group(function () {
            Route::get('/', 'BooksController@index')->name('index');
            Route::get('/{id}', 'BooksController@show')->name('show');
            Route::post('/{id}', 'BooksController@update')->name('update');
        });

        // Albums
        Route::name('albums.')->prefix('/albums')->group(function () {
            Route::get('/', 'AlbumsController@index')->name('index');
            Route::get('/{id}', 'AlbumsController@show')->name('show');
            Route::post('/{id}', 'AlbumsController@update')->name('update');
            Route::get('/track/{id}', 'AlbumsController@showTrack')->name('showTrack');
        });

        // Audiobooks
        Route::name('audiobooks.')->prefix('/audiobooks')->group(function () {
            Route::get('/', 'AudiobooksController@index')->name('index');
            Route::get('/{id}', 'AudiobooksController@show')->name('show');
            Route::post('/{id}', 'AudiobooksController@update')->name('update');
        });

        // Games
        Route::name('games.')->prefix('/games')->group(function () {
            Route::get('/', 'GamesController@index')->name('index');
            Route::get('/{id}', 'GamesController@show')->name('show');
            Route::post('/{id}', 'GamesController@update')->name('update');
        });
    });
    // Auth::logout();
});

Auth::routes(['verify' => true]);
