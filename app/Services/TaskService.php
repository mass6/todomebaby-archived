<?php

namespace App\Services;

use App\Tag;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
        return Task::with('project', 'tags')->find($id);
    }


    /**
     * Retrieve tasks by Tag
     *
     * @param Tag  $tag
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function findByTag(Tag $tag, $withCompleted = false)
    {
        return $tag->tasks()->taskList($withCompleted)->get();
    }


    /**
     * Retrieve all open tasks by Tag Name
     *
     * @param $name
     *
     * @return mixed
     */
    public function findByTagName($name)
    {
        return Tag::where('name', $name)->first()->tasks()->taskList()->get();
    }

    /**
     * Retrieve all open tasks
     *
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getAllTasks($withCompleted = false)
    {
        return $this->task
            ->open()
            ->taskList($withCompleted)
            ->get();
    }

    /**
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getInbox($withCompleted = false)
    {
        return $this->task
            ->whereNull('project_id')
            ->taskList($withCompleted)
            ->get();
    }

    /**
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getNext($withCompleted = false)
    {
        return $this->task
            ->where('next', true)
            ->taskList($withCompleted)
            ->get();
    }

    /**
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getTasksDueToday($withCompleted = false)
    {
        return $this->task
            ->where('due_date', '<=', Carbon::today()->toDateString())
            ->taskList($withCompleted)
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
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getTasksDueTomorrow($withCompleted = false)
    {
        return $this->task
            ->where('due_date', Carbon::tomorrow()->toDateString())
            ->taskList($withCompleted)
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
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getTasksDueThisWeek($withCompleted = false)
    {
        return $this->task
            ->where('due_date', '>=', Carbon::today()->startOfWeek()->toDateString())
            ->where('due_date', '<=', Carbon::today()->endOfWeek()->toDateString())
            ->taskList($withCompleted)
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
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getTasksDueNextWeek($withCompleted = false)
    {
        return $this->task
            ->where('due_date', '>=', Carbon::today()->startOfWeek()->addDays(7)->toDateString())
            ->where('due_date', '<=', Carbon::today()->endOfWeek()->addDays(7)->toDateString())
            ->taskList($withCompleted)
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
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getTasksDueLater($withCompleted = false)
    {
        return $this->task
            ->where(function ($query) {
                $query->whereNull('due_date')->orWhere('due_date', '>', Carbon::today()->endOfWeek()->addDays(7)->toDateString());
            })
            ->taskList($withCompleted)
            ->get();
    }


    /**
     * @return mixed
     */
    public function tasksDueLaterCount()
    {
        return $this->getTasksDueLater()->count();
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

        $task = $this->user->tasks()->create([
            'title'        => $data->get('title'),
            'complete'     => $data->get('complete', false),
            'completed_at' => null,
            'next'         => $data->get('next', false),
            'due_date'     => $due_date,
            'priority'     => $data->get('priority', 'LOW'),
            'details'      => $data->get('details'),
        ]);
        $this->associateToProject($task, $data->get('project_id'));
        $this->associateTags($task, $data->get('tagsinput'));

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
     * @param      $tags
     */
    private function associateTags(Task $task, $tags)
    {
        // Don't udpate if tags are
        if (is_null($tags)) {
            return;
        }

        $tags      = $this->filterOutWhiteSpace($tags);
        $tagModels = $this->getTagModels($tags, $task);
        $task->tags()->sync($tagModels->pluck('id')->all());
    }


    /**
     * @param $tags
     *
     * @return static
     */
    protected function filterOutWhiteSpace($tags)
    {
        return collect(explode(',', $tags))->filter(function ($tag) {
            return trim($tag);
        })->map(function ($tag) {
            return trim($tag);
        });
    }


    /**
     * @param Collection $tags
     * @param Task       $task
     *
     * @return static
     */
    protected function getTagModels(Collection $tags, Task $task)
    {
        return $tags->map(function ($tag) use ($task) {
            return $this->getTag($task, $tag);
        });
    }


    /**
     * @param Task $task
     * @param      $name
     *
     * @return static
     */
    protected function getTag(Task $task, $name)
    {
        if ( ! $tag = Tag::where('user_id', $task->user_id)->where('name', $name)->first()) {
            $tag = $this->createTag($task, $name);
        }

        return $tag;
    }


    /**
     * @param Task $task
     * @param      $name
     *
     * @return static
     */
    protected function createTag(Task $task, $name)
    {
        return Tag::create([
            'user_id'    => $task->user_id,
            'name'       => $name,
            'is_context' => substr(trim($name), 0, 1) == '@' ?: false,
        ]);
    }


    /**
     * @param Task  $task
     * @param array $data
     *
     * @return Task
     */
    public function updateTask(Task $task, Array $data)
    {
        $data = collect($data);
        if ($data->get('due_date') === '') {
            $data->put('due_date', null);
        }
        if ($data->get('project_id') === '') {
            $data->put('project_id', null);
        }
        $task->update($data->toArray());
        $this->associateTags($task, $data->get('tagsinput'));

        return $task;
    }


    /**
     * Delete task
     *
     * @param Task $task
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteTask(Task $task)
    {
        return $task->delete();
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
