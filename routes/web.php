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


Auth::routes();
Route::get('/home', 'HomeController@index');

Route::get('/', function () {
    return redirect('/web');
});
Route::get('/web/{vue?}', ['middleware' => 'auth', function () {
    return view('webapp');
}])->where('vue', '[\/\w\.-]*');

Route::get('tasklists/today', 'TasksController@getTasksDueToday');
Route::get('tasklists/tomorrow', 'TasksController@getTasksDueTomorrow');
Route::get('tasklists/this-week', 'TasksController@getTasksDueThisWeek');
Route::get('tasklists/next-week', 'TasksController@getTasksDueNextWeek');
Route::get('tasklists/future', 'TasksController@getTasksDueInFuture');


Route::group(['middleware' => ['auth','web']], function () {
    Route::post('tasks', 'TasksController@store');
    Route::get('tasks/{task}', 'TasksController@show');
    Route::patch('tasks/{task}', 'TasksController@update');
    Route::get('projects', 'ProjectsController@getActive');
});

