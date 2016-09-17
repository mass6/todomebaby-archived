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
//Route::get('/home', 'HomeController@index');
Route::get('/', function () {
    return redirect('/web');
});

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout');

// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm');
//$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/web/{vue?}', ['middleware' => 'auth', function () {
    return view('webapp');
}])->where('vue', '[\/\w\.-]*');

Route::group(['middleware' => ['auth','web']], function () {

    Route::post('tasks', 'TasksController@store');
    Route::get('tasks/scheduled', 'TasksController@getScheduledTaskCounts');
    Route::get('tasks/{task}', 'TasksController@show');
    Route::patch('tasks/{task}', 'TasksController@update');

    Route::get('tasklists/today', 'TasksController@getTasksDueToday');
    Route::get('tasklists/tomorrow', 'TasksController@getTasksDueTomorrow');
    Route::get('tasklists/this-week', 'TasksController@getTasksDueThisWeek');
    Route::get('tasklists/next-week', 'TasksController@getTasksDueNextWeek');
    Route::get('tasklists/future', 'TasksController@getTasksDueInFuture');

    Route::post('projects', 'ProjectsController@store');
    Route::get('projects', 'ProjectsController@getActive');
    Route::get('projects/{project}/tasks', 'ProjectsController@getTasksByProject');
    Route::patch('projects/{project}', 'ProjectsController@update');
    Route::get('projects/{project}', 'ProjectsController@show');

    Route::get('tags/{tag}/tasks', 'TasksController@getTasksByTag');
});

