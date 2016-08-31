<?php

namespace App\Services;

use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class TaskService
 * @package App\Services
 */
class TaskService
{

    /**
     * @var Task
     */
    private $task;

    /**
     * @var User
     */
    private $user;


    /**
     * TaskService constructor.
     */
    public function __construct()
    {
        $this->user = Auth::user();
        $this->task = new Task();
    }


    /**
     * @return mixed
     */
    public function getOpenTasks()
    {
        return $this->task->open()->get();
    }


    /**
     * @return mixed
     */
    public function getTasksDueToday()
    {
        return $this->task
            ->where('complete', false)
            ->where('due_date', '<=', Carbon::today()->format('Y-m-d'))
            ->get();
    }


    /**
     * @return mixed
     */
    public function getTasksDueThisWeek()
    {
        return $this->task
            ->where('complete', false)
            ->where('due_date', '>=', Carbon::today()->startOfWeek()->format('Y-m-d'))
            ->where('due_date', '<=', Carbon::today()->endOfWeek()->format('Y-m-d'))
            ->get();
    }


    /**
     * @return mixed
     */
    public function getTasksDueNextWeek()
    {
        return $this->task
            ->where('complete', false)
            ->where('due_date', '>=',Carbon::today()->startOfWeek()->addDays(7)->format('Y-m-d'))
            ->where('due_date', '<=', Carbon::today()->endOfWeek()->addDays(7)->format('Y-m-d'))
            ->get();
    }


    /**
     * @return mixed
     */
    public function getTasksDueAfterNextWeek()
    {
        return $this->task
            ->where('complete', false)
            ->where(function ($query) {
                $query->whereNull('due_date')
                      ->orWhere('due_date', '>',Carbon::today()->endOfWeek()->addDays(7)->format('Y-m-d'));
            })->select([ '*', DB::raw('due_date IS NULL AS due_date_null') ])->get();
    }
}
