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
Route::get('/home', function () {
    return redirect('/web');
});
Route::get('/', function () {
    return redirect('/web');
});

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout');

// Disabled until ready to launch
// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm');
//$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/web/{vue?}', ['middleware' => 'auth', function () {
    return view('taskmanager');
}])->where('vue', '[\/\w\.-]*');

Route::group(['middleware' => ['auth','web']], function () {
    // Keep session alive, called by client every 15 seconds
    Route::get('/health', function() {
        return response(null, 200);
    });

    Route::post('tasks', 'TasksController@store');
    Route::get('tasks/scheduled', 'TasksController@getScheduledTaskCounts');
    Route::get('tasks/{task}', 'TasksController@show');
    Route::patch('tasks/{task}', 'TasksController@update');
    Route::delete('tasks/{task}', 'TasksController@destroy');

    Route::get('tasklists/inbox', 'TasksController@getInbox');
    Route::get('tasklists/all', 'TasksController@getAllTasks');
    Route::get('tasklists/next', 'TasksController@getNext');
    Route::get('tasklists/today', 'TasksController@getTasksDueToday');
    Route::get('tasklists/tomorrow', 'TasksController@getTasksDueTomorrow');
    Route::get('tasklists/this-week', 'TasksController@getTasksDueThisWeek');
    Route::get('tasklists/next-week', 'TasksController@getTasksDueNextWeek');
    Route::get('tasklists/later', 'TasksController@getTasksDueLater');

    Route::post('projects', 'ProjectsController@store');
    Route::get('projects', 'ProjectsController@getActive');
    Route::get('projects/{project}/tasks', 'ProjectsController@getTasksByProject');
    Route::patch('projects/{project}', 'ProjectsController@update');
    Route::delete('projects/{project}', 'ProjectsController@destroy');
    Route::get('projects/{project}', 'ProjectsController@show');

    Route::get('tags/contexts', 'TagsController@getContexts');
    Route::get('/tags/typeahead/{query}', 'TagsController@getTagSuggestions');
    Route::get('tags/{tag}/tasks', 'TagsController@getTasksByTag');
});

