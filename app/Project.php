<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        return $this->tasks()->open()
            ->with('project')
            ->with([
                'tags' => function ($query) {
                    $query->orderBy('is_context', 'desc');
                    $query->orderBy('name', 'asc');
                }
            ])
            ->select(['*', DB::raw('due_date IS NULL AS due_date_null')])
            ->orderBy('next', 'desc')
            ->orderBy('due_date_null', 'asc')
            ->orderBy('due_date')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
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
