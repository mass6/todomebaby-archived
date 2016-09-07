<?php

use App\Project;
use App\Services\ProjectService;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectServiceTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @var User
     */
    public $user;

    /**
     * @var ProjectService
     */
    protected $projectService;

    public $projects;


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([]);
        // Set authenticated user
        $this->actingAs($this->user);
        $this->projectService = new ProjectService();
    }

    // Query Service Tests

    /**
     * Service scopes queries to specified user
     *
     * @test
     */
    public function it_scopes_queries_to_the_specified_user()
    {
        $this->projects = $this->generateUserProjects();
        // create projects that belong to another user
        $otherUser = factory(User::class)->create([]);
        $this->generateUserProjects($otherUser, 3);

        $scopedUsersProjects = $this->projectService->getActiveProjects();

        $this->assertCount(5, $scopedUsersProjects);
    }

    /**
     * Service retrieves all active projects
     *
     * @test
     */
    public function it_retrieves_all_active_projects()
    {
        $this->projects = $this->generateUserProjects();

        $this->projects->last()->active = false;
        $this->projects->last()->save();

        $activeProjects = $this->projectService->getActiveProjects();

        $this->assertCount(4, $activeProjects);
    }

    ///**
    // * Service retrieves all tasks due today
    // *
    // * @test
    // */
    //public function it_retrieves_all_tasks_due_today()
    //{
    //    $this->tasks = $this->generateUserTasks();
    //    $this->setDueDates($this->tasks, Carbon::today()->addDays(1)->toDateString());
    //
    //    $this->tasks->first()->setDueDate(Carbon::today()->toDateString());
    //    // tasks past due should also be retrieved
    //    $this->tasks->last()->setDueDate(Carbon::today()->subDays(5)->toDateString());
    //
    //    $tasksDueToday = $this->taskService->getTasksDueToday();
    //
    //    $this->assertCount(2, $tasksDueToday);
    //}
    //
    //
    ///**
    // * Service retrieves all tasks due tomorrow
    // *
    // * @test
    // */
    //public function it_retrieves_all_tasks_due_tomorrow()
    //{
    //    $this->tasks = $this->generateUserTasks();
    //
    //    $this->tasks->first()->setDueDate(Carbon::tomorrow()->toDateString());
    //    $this->tasks->last()->setDueDate(Carbon::tomorrow()->toDateString());
    //
    //    $tasksDueTomorrow = $this->taskService->getTasksDueTomorrow();
    //
    //    $this->assertCount(2, $tasksDueTomorrow);
    //}
    //
    ///**
    // * Service retrieves all tasks due this week
    // *
    // * @test
    // */
    //public function it_retrieves_all_tasks_due_this_week()
    //{
    //    $this->tasks = $this->generateUserTasks();
    //    $this->setDueDates($this->tasks, Carbon::today()->addDays(15)->toDateString());
    //
    //    $this->tasks->first()->setDueDate(Carbon::today()->startOfWeek()->toDateString());
    //    $this->tasks->last()->setDueDate(Carbon::today()->endOfWeek()->toDateString());
    //
    //    $tasksDueThisWeek = $this->taskService->getTasksDueThisWeek();
    //
    //    $this->assertCount(2, $tasksDueThisWeek);
    //}
    //
    ///**
    // * Service retrieves all tasks due next week
    // *
    // * @test
    // */
    //public function it_retrieves_all_tasks_due_next_week()
    //{
    //    $this->tasks = $this->generateUserTasks();
    //    $this->setDueDates($this->tasks, Carbon::today()->toDateString());
    //
    //    $this->tasks->first()->setDueDate(Carbon::today()->startOfWeek()->addDays(7)->toDateString());
    //    $this->tasks->last()->setDueDate(Carbon::today()->endOfWeek()->addDays(7)->toDateString());
    //    $tasksDueNextWeek = $this->taskService->getTasksDueNextWeek();
    //
    //    $this->assertCount(2, $tasksDueNextWeek);
    //}
    //
    ///**
    // * Service retrieves all tasks due later than next week
    // *
    // * @test
    // */
    //public function it_retrieves_all_tasks_due_in_future()
    //{
    //    $this->tasks = $this->generateUserTasks();
    //    $this->setDueDates($this->tasks, Carbon::today()->toDateString());
    //
    //    // task due past end of next week should be retrieved
    //    $this->tasks->first()->setDueDate(Carbon::today()->addDays(35)->toDateString());
    //    $this->tasks->first()->save();
    //    // task with no due date should be retrieved
    //    $this->tasks->last()->due_date = null;
    //    $this->tasks->last()->save();
    //
    //    $tasksDueInFuture = $this->taskService->getTasksDueInFuture();
    //
    //    $this->assertCount(2, $tasksDueInFuture);
    //}
    //
    //// Command Service Tests
    //
    ///**
    // * Service adds a new task
    // *
    // * @test
    // */
    //public function it_adds_a_new_task()
    //{
    //    $taskFormData = [
    //        'title' => 'Task 1',
    //        'complete' => false,
    //        'next' => true,
    //        'due_date' => Carbon::tomorrow()->toDateString(),
    //        'project_id' => 1,
    //        'priority' => 'MED',
    //        'details' => 'Details about this task',
    //    ];
    //    $this->user->projects()->create(factory(Project::class)->make([])->toArray());
    //    $task = factory(Task::class)->make($taskFormData);
    //    $this->taskService->addTask($task->toArray());
    //    $this->assertCount(1, $this->user->tasks);
    //
    //    $savedTask = collect($this->user->tasks->first());
    //    $this->assertEquals(
    //        $task->toArray(),
    //        $savedTask->only(['title', 'complete', 'next', 'due_date', 'project_id', 'priority', 'details'])->toArray()
    //    );
    //}
    //
    //
    ///**
    // * Service updates a task
    // *
    // * test
    // * @group current
    // */
    //public function it_updates_a_task()
    //{
    //    //TODO: FINISH THIS TEST DEFINITION
    //
    //    $taskData = [
    //        'title' => 'Task 1',
    //        'complete' => false,
    //        'next' => true,
    //        'due_date' => Carbon::tomorrow()->toDateString(),
    //        'details' => 'Details about this task',
    //    ];
    //    $task = factory(Task::class)->make($taskData);
    //}
    ///**
    // * Service sets default fields values
    // *
    // * @test
    // */
    //public function it_sets_default_field_values_for_new_tasks_when_no_input_is_given()
    //{
    //    $taskData = ['title' => 'Task 1'];
    //    $task = $this->taskService->addTask($taskData);
    //
    //    $this->assertFalse($task->complete);
    //    $this->assertNull($task->complete_at);
    //    $this->assertFalse($task->next);
    //    $this->assertNull($task->due_date);
    //    $this->assertNull($task->details);
    //}
    //
    ///**
    // * Service creates a new task
    // *
    // * @test
    // */
    //public function it_toggles_the_next_flag_on_a_task()
    //{
    //    $taskData = ['title' => 'Task 1'];
    //    $task = $this->taskService->addTask($taskData);
    //
    //    $this->taskService->toggleNextFlag($task);
    //    $this->assertTrue($task->next);
    //
    //    $this->taskService->toggleNextFlag($task);
    //    $this->assertFalse($task->next);
    //}
    ///**
    // * Service toggles a task complete and sets or nulls the completed_at date.
    // *
    // * @test
    // */
    //public function it_toggles_the_completed_flag_on_a_task_and_sets_or_nulls_the_completed_at_date()
    //{
    //    $taskData = ['title' => 'Task 1'];
    //    $task = $this->taskService->addTask($taskData);
    //
    //    $this->taskService->toggleComplete($task);
    //    $this->assertTrue($task->complete);
    //    $this->assertNotNull($task->completed_at);
    //
    //    $this->taskService->toggleComplete($task);
    //    $this->assertFalse($task->complete);
    //    $this->assertNull($task->completed_at);
    //}






    // Helper Methods

    /**
     * @param User  $user
     * @param int   $amount
     * @param bool  $active
     * @param array $overrides
     *
     * @return mixed
     */
    protected function generateUserProjects(User $user = null, $amount = 5, $active = true, $overrides = [])
    {
        $user = $user ?: $this->user;
        $overrides['active'] = $active;
        $projects = factory(Project::class, $amount)->make($overrides);
        $amount > 1 ? $user->projects()->saveMany($projects) : $user->projects()->save($projects);

        return $projects;
    }


    /**
     * @param $tasks
     * @param $date
     */
    //protected function setDueDates($tasks, $date)
    //{
    //    foreach ($tasks as $task) {
    //        $task->setDueDate($date);
    //        $task->save();
    //    }
    //}

}
