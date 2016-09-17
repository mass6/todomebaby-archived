<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Project
 * @package App
 */
class Project extends Model
{

    use UserScopingTrait, UuidTrait;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [ 'user_id', 'name', 'description', 'due_date', 'active' ];


    /**
     * Returns all tasks which are open
     *
     * @return mixed
     */
    public function openTasks()
    {
        return $this->tasks()->with('project')->get()->filter(function ($task) {
            return $task->complete == false;
        })->load(['tags' => function($query)
        {
            $query->orderBy('is_context', 'desc');
            $query->orderBy('name', 'asc');
        }])->values();
    }


    /**
     * Project can have many related tasks
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
