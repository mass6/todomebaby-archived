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
    use UserScopingTrait;

    /**
     * @var array
     */
    protected $fillable = ['name', 'user_id', 'active'];


    /**
     * Returns all tasks which are open
     *
     * @return mixed
     */
    public function openTasks()
    {
        return $this->tasks()->with('project')->get()->filter(function($task){
            return $task->complete == false;
        })->values();
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
