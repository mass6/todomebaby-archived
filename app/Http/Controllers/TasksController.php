<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;

/**
 * Class TasksController
 * @package App\Http\Controllers
 */
class TasksController extends Controller
{

    /**
     * @param             $id
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function show($id, TaskService $taskService)
    {
        return response()->json($taskService->findById($id));
    }


    /**
     * @param Request     $request
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function store(Request $request, TaskService $taskService)
    {
        $task = $taskService->addTask($request->all());

        return response()->json($task);
    }


    /**
     * @param Task        $task
     * @param Request     $request
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function update(Task $task, Request $request, TaskService $taskService)
    {
        return response()->json($taskService->updateTask($task, $request->all()));
    }


    /**
     * Delete a task
     *
     * @param Task        $task
     * @param TaskService $taskService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task, TaskService $taskService)
    {
        return response()->json($taskService->deleteTask($task));
    }


    /**
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function getScheduledTaskCounts(TaskService $taskService)
    {
        $today = $taskService->tasksDueTodayCount();
        $tomorrow = $taskService->tasksDueTomorrowCount();
        $thisWeek = $taskService->tasksDueThisWeekCount();
        $nextWeek = $taskService->tasksDueNextWeekCount();
        $later = $taskService->tasksDueLaterCount();

        return response()->json([
            'today' => $today,
            'tomorrow' => $tomorrow,
            'thisWeek' => $thisWeek,
            'nextWeek' => $nextWeek,
            'later' => $later,
        ]);
    }


    /**
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function getTasksDueToday(TaskService $taskService, Request $request)
    {
        return $this->getTaskListResponse('Today', $taskService->getTasksDueToday($request->get('with-completed')));
    }


    /**
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function getTasksDueTomorrow(TaskService $taskService, Request $request)
    {
        return $this->getTaskListResponse('Tomorrow', $taskService->getTasksDueTomorrow($request->get('with-completed')));
    }


    /**
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function getTasksDueThisWeek(TaskService $taskService, Request $request)
    {
        return $this->getTaskListResponse('This Week', $taskService->getTasksDueThisWeek($request->get('with-completed')));
    }


    /**
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function getTasksDueNextWeek(TaskService $taskService, Request $request)
    {
        return $this->getTaskListResponse('Next Week', $taskService->getTasksDueNextWeek($request->get('with-completed')));
    }


    /**
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function getTasksDueLater(TaskService $taskService, Request $request)
    {
        return $this->getTaskListResponse('Later', $taskService->getTasksDueLater($request->get('with-completed')));
    }


    /**
     * @param $listName
     * @param $tasks
     *
     * @return mixed
     */
    protected function getTaskListResponse($listName, $tasks)
    {
        return response()->json(['listName' => $listName, 'tasks' => $tasks]);
    }

}
