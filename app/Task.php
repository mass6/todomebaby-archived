<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Task
 * @package App
 */
class Task extends Model
{
    use UserScopingTrait;

    const PRIORITY_LEVELS = array(
        'low' => 'low',
        'med' => 'med',
        'medium' => 'med',
        'hgh' => 'hgh',
        'high' => 'hgh'
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'user_id', 'complete', 'completed_at', 'next', 'due_date', 'project_id', 'priority', 'details',];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'complete' => 'boolean',
        'next' => 'boolean',
    ];

    /**
     * Scope the query to only include open tasks.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('complete', false);
    }

    public function toggleNextFlag()
    {
        if ( ! $this->attributes['next']) {
            $this->attributes['next'] = true;
        } else {
            $this->attributes['next'] = false;
        }
        $this->save();
    }

    public function toggleComplete()
    {
        if (! $this->complete) {
            $this->complete = true;
            $this->completed_at = Carbon::now();
        } else {
            $this->complete = false;
            $this->completed_at = null;
        }
        $this->save();
    }

    /**
     * Sets the due date on the task
     *
     * @param $date
     */
    public function setDueDate($date)
    {
        $this->due_date = $date;
        $this->save();
    }

    /**
     * Get the priority name.
     *
     * @param  string  $value
     * @return string
     */
    public function getPriorityAttribute($value)
    {
        $humanReadable = [
            'low' => 'low',
            'med' => 'medium',
            'hgh' => 'high',
        ];

        return $humanReadable[$value];
    }

    /**
     * Proxy to setPriority method.
     *
     * @param  string  $value
     * @return void
     */
    public function setPriorityAttribute($value)
    {
        $this->validatePriority($value);
        $this->attributes['priority'] = STATIC::PRIORITY_LEVELS[strtolower($value)];
    }

    protected function validatePriority($value)
    {
        if (! key_exists(strtolower($value), STATIC::PRIORITY_LEVELS)) {
            throw new \InvalidArgumentException($value . ' is not one of the allowed priority values (low, med, medium, hgh, high)');
        }
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        $this->save();
    }

    public function associateToProject($projectId)
    {
        $this->project()->associate(Project::find($projectId))->save();
    }


    // Model Relations

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
