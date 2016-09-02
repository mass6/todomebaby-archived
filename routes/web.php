<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('test', function () {
    return view('welcome');
});

use App\Services\TaskService;

Auth::routes();


Route::get('/', function () {
    return redirect('/web');
});

Route::get('/web', ['middleware' => 'auth', function () {
    return view('webapp');
}]);


Route::get('/home', 'HomeController@index');
