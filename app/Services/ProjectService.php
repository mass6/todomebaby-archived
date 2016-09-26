<?php

namespace App\Services;

use App\Project;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProjectService
 * @package App\Services
 */
class ProjectService
{


    /**
     * @var Project
     */
    private $project;

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
        $this->project = new Project;
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
        return Project::find($id);
    }

    /**
     * @param $slug
     *
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return Project::where('slug', $slug)->first();
    }

    /**
     * @return mixed
     */
    public function getActiveProjects()
    {
        return Project::where('active', true)->orderBy('name', 'asc')->get()
            ->map(function($project){
                $project->taskCount = $project->taskList()->count();
                return $project;
            });
    }


    /**
     * @param      $id
     * @param bool $withCompleted
     *
     * @return mixed
     */
    public function getTasksByProjectId($id, $withCompleted = false)
    {
        $project = Project::find($id);

        return $project->taskList($withCompleted);
    }

    /**
     * Adds a new project
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addProject(Array $data)
    {
        $data = collect($data);
        $due_date = $data->get('due_date') ?: null;

        return $this->user->projects()->create([
            'name' => $data->get('name'),
            'description' => $data->get('description'),
            'due_date' => $due_date,
        ]);
    }

    /**
     * @param Project  $project
     * @param array $data
     *
     * @return Project
     */
    public function updateProject(Project $project, Array $data)
    {
        $data = collect($data);
        if ($data->get('due_date') === '' ) {
            $data->put('due_date', null);
        }
        $project->update($data->toArray());

        return $project;
    }


    /**
     * Delete project and any related tasks
     *
     * @param Project $project
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteProject(Project $project)
    {
        $project->tasks->each(function($task) {
            $task->delete();
        });

        return $project->delete();
    }


}
