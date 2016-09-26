<?php

namespace App;

use App\Libraries\Slugger;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package App
 */
class Tag extends Model
{
    use UserScopingTrait, UuidTrait;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'is_context',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_context' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically saved slugged version of the name attribute.
        static::saving(function ($model) {
            $model->slug = Slugger::slug($model->name);
        });
    }

    /**
     * Tag can belong to many tasks
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }


    /**
     * Retrieves all open tasks associated with the model
     *
     * @return mixed
     */
    public function openTasks()
    {
        return $this->tasks()->where('tasks.complete', false);
    }

}
