<?php

namespace App\Http\Controllers;

use App\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class ProjectsController
 * @package App\Http\Controllers
 */
class ProjectsController extends Controller
{

    /**
     * @param             $id
     * @param ProjectService $projectService
     *
     * @return mixed
     */
    public function show($id, ProjectService $projectService)
    {
        return response()->json($projectService->findById($id));
    }


    /**
     * @param ProjectService $projectService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActive(ProjectService $projectService)
    {
        return response()->json($projectService->getActiveProjects());
    }


    /**
     * @param Project     $project
     * @param ProjectService $projectService
     *
     * @return mixed
     */
    public function getTasksByProject(Project $project, ProjectService $projectService, Request $request)
    {
        return response()->json(['listName' => $project->name, 'tasks' => $projectService->getTasksByProjectId($project->id, $request->get('with-completed'))]);
    }

    /**
     * @param Request        $request
     * @param ProjectService $projectService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, ProjectService $projectService)
    {
        $project = $projectService->addProject($request->all());

        return response()->json($project);
    }


    /**
     * @param Project        $project
     * @param Request     $request
     * @param ProjectService $projectService
     *
     * @return mixed
     */
    public function update(Project $project, Request $request, ProjectService $projectService)
    {
        $updatedProject = $projectService->updateProject($project, $request->all());

        return response()->json($updatedProject);
    }


    /**
     * @param Project        $project
     * @param ProjectService $projectService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project, ProjectService $projectService)
    {
        return response()->json($projectService->deleteProject($project));
    }
}
