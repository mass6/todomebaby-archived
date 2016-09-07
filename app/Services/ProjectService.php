<?php

namespace App\Services;

use App\Project;

class ProjectService
{

    public function getActiveProjects()
    {
        return Project::where('active', true)->get();
    }
}
