<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @package App
 */
class Task extends Model
{

    use UserScopingTrait, UuidTrait;


    /**
     * Task Priority level name mapping
     */
    const PRIORITY_LEVELS = [
        'low'    => 'low',
        'med'    => 'med',
        'medium' => 'med',
        'hgh'    => 'hgh',
        'high'   => 'hgh'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'user_id',
        'complete',
        'completed_at',
        'next',
        'due_date',
        'project_id',
        'priority',
        'details',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'complete' => 'boolean',
        'next'     => 'boolean',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Scope the query to only include open tasks.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('complete', false);
    }


    /**
     * Toggles the Next flag on/off
     */
    public function toggleNextFlag()
    {
        if ( ! $this->attributes['next']) {
            $this->attributes['next'] = true;
        } else {
            $this->attributes['next'] = false;
        }
        $this->save();
    }


    /**
     * Toggles the complete flag on/off, and sets/un-sets the completed date
     */
    public function toggleComplete()
    {
        if ( ! $this->complete) {
            $this->complete     = true;
            $this->completed_at = Carbon::now();
        } else {
            $this->complete     = false;
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
     * @param  string $value
     *
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
     * @param  string $value
     *
     * @return void
     */
    public function setPriorityAttribute($value)
    {
        $this->validatePriority($value);
        $this->attributes['priority'] = STATIC::PRIORITY_LEVELS[strtolower($value)];
    }


    /**
     * @param $value
     */
    protected function validatePriority($value)
    {
        if ( ! key_exists(strtolower($value), STATIC::PRIORITY_LEVELS)) {
            throw new \InvalidArgumentException($value . ' is not one of the allowed priority values (low, med, medium, hgh, high)');
        }
    }


    /**
     * @param $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        $this->save();
    }


    /**
     * @param $projectId
     */
    public function associateToProject($projectId)
    {
        $this->project()->associate(Project::find($projectId))->save();
    }


    // Model Relations

    /**
     * Task belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Task can belong to a project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    /**
     * Task can have many tags
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
