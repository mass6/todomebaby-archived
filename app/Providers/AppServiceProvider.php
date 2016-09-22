<?php

namespace App\Providers;

use App\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Task::updating(function($task) {
            $task->completed_at = $task->complete ? Carbon::now() : null;
            return;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
