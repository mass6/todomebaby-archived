<?php

namespace App\Services;

use App\Project;

/**
 * Class ProjectService
 * @package App\Services
 */
class ProjectService
{

    /**
     * @return mixed
     */
    public function getActiveProjects()
    {
        return Project::where('active', true)->get()
            ->map(function($project){
                $project->taskCount = $project->openTasks()->count();
                return $project;
            });
    }
}
