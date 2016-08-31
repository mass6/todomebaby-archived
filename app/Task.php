<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Task
 * @package App
 */
class Task extends Model
{

    /**
     * Add global scope to restrict to current authenticated user
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', Auth::user()->id);
        });
    }


    /**
     * Marks the task complete
     */
    public function markComplete()
    {
        $this->complete = true;
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
     * Scope the query to only include open tasks.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('complete', false);
    }
}
