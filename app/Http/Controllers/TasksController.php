<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Support\Facades\Response;

class TasksController extends Controller
{

    public function store(Request $request, TaskService $taskService)
    {
        $task = $taskService->addTask($request->all());

        return response()->json($task);
    }

    public function show($id, TaskService $taskService)
    {
        return response()->json($taskService->findById($id));
    }

    public function getTasksDueToday(TaskService $taskService)
    {
        return $this->getTaskListResponse('today', $taskService->getTasksDueToday());
    }

    public function getTasksDueTomorrow(TaskService $taskService)
    {
        return $this->getTaskListResponse('tomorrow', $taskService->getTasksDueTomorrow());
    }

    public function getTasksDueThisWeek(TaskService $taskService)
    {
        return $this->getTaskListResponse('this week', $taskService->getTasksDueThisWeek());
    }

    public function getTasksDueNextWeek(TaskService $taskService)
    {
        return $this->getTaskListResponse('next week', $taskService->getTasksDueNextWeek());
    }

    public function getTasksDueInFuture(TaskService $taskService)
    {
        return $this->getTaskListResponse('in future', $taskService->getTasksDueInFuture());
    }

    protected function getTaskListResponse($listName, $tasks)
    {
        return response()->json(['listName' => 'Due ' . ucwords($listName), 'tasks' => $tasks]);
    }

}
