<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Illuminate\Http\Request;

use App\Http\Requests;

class ProjectsController extends Controller
{

    public function getActive(ProjectService $projectService)
    {
        return response()->json($projectService->getActiveProjects());
    }
}
