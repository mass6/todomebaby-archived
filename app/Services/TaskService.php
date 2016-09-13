<?php

namespace App\Services;

use App\Project;
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
        $this->task = new Task;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    // Query Services

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findById($id)
    {
        return Task::find($id);
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
            ->open()
            ->with('project')
            ->where('due_date', '<=', Carbon::today()->toDateString())
            ->get();
    }

    /**
     * @return mixed
     */
    public function tasksDueTodayCount()
    {
        return $this->getTasksDueToday()->count();
    }

    /**
     * @return mixed
     */
    public function getTasksDueTomorrow()
    {
        return $this->task
            ->open()
            ->with('project')
            ->where('due_date',  Carbon::tomorrow()->toDateString())
            ->get();
    }

    /**
     * @return mixed
     */
    public function tasksDueTomorrowCount()
    {
        return $this->getTasksDueTomorrow()->count();
    }

    /**
     * @return mixed
     */
    public function getTasksDueThisWeek()
    {
        return $this->task
            ->open()
            ->with('project')
            ->where('due_date', '>=', Carbon::today()->startOfWeek()->toDateString())
            ->where('due_date', '<=', Carbon::today()->endOfWeek()->toDateString())
            ->get();
    }

    /**
     * @return mixed
     */
    public function tasksDueThisWeekCount()
    {
        return $this->getTasksDueThisWeek()->count();
    }


    /**
     * @return mixed
     */
    public function getTasksDueNextWeek()
    {
        return $this->task
            ->open()
            ->with('project')
            ->where('due_date', '>=',Carbon::today()->startOfWeek()->addDays(7)->toDateString())
            ->where('due_date', '<=', Carbon::today()->endOfWeek()->addDays(7)->toDateString())
            ->get();
    }

    /**
     * @return mixed
     */
    public function tasksDueNextWeekCount()
    {
        return $this->getTasksDueNextWeek()->count();
    }

    /**
     * @return mixed
     */
    public function getTasksDueInFuture()
    {
        return $this->task
            ->open()
            ->with('project')
            ->where(function ($query) {
                $query->whereNull('due_date')
                      ->orWhere('due_date', '>',Carbon::today()->endOfWeek()->addDays(7)->toDateString());
            })->select([ '*', DB::raw('due_date IS NULL AS due_date_null') ])->get();
    }

    /**
     * @return mixed
     */
    public function tasksDueInFutureCount()
    {
        return $this->getTasksDueInFuture()->count();
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function getTasksByProjectId($id)
    {
        $project = Project::find($id);

        return $project->openTasks();
    }

    // Command Services

    /**
     * Adds a new task
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addTask(Array $data)
    {
        $data = collect($data);
        $due_date = $data->get('due_date') ?: null;

        $task =  $this->user->tasks()->create([
            'title' => $data->get('title'),
            'complete' => $data->get('complete', false),
            'completed_at' => null,
            'next' => $data->get('next', false),
            'due_date' => $due_date,
            'priority' => $data->get('priority', 'LOW'),
            'details' => $data->get('details'),
        ]);
        $this->associateToProject($task, $data->get('project_id'));

        return $task;
    }


    /**
     * @param Task  $task
     * @param array $data
     *
     * @return Task
     */
    public function updateTask(Task $task, Array $data)
    {
        if (! isset($data['project_id']) || $data['project_id'] === '' ) {
            $data['project_id'] = null;
        }
        $task->update($data);

        return $task;
    }


    /**
     * @param Task $task
     * @param      $project_id
     */
    public function associateToProject(Task $task, $project_id)
    {
        $task->associateToProject($project_id);
    }


    /**
     * @param Task $task
     */
    public function toggleComplete(Task $task)
    {
        $task->toggleComplete();
    }


    /**
     * @param Task $task
     */
    public function toggleNextFlag(Task $task)
    {
        $task->toggleNextFlag();
    }

}
