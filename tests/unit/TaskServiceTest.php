<?php

use App\Services\TaskService;
use App\Task;
use App\User;
use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;

class TaskServiceTest extends TestCase
{

    use DatabaseMigrations;

    public $user;
    public $tasks;
    protected $taskService;


    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([]);
        // Set authenticated user
        $this->actingAs($this->user);
        $this->taskService = new TaskService();
    }

    /**
     * Service scopes queries to specified user
     *
     * @test
     */
    public function it_scopes_queries_to_the_specified_user()
    {

        $this->tasks = factory(Task::class,5)->make(['complete' => false]);
        $this->user->tasks()->saveMany($this->tasks);
        // create tasks that belong to another user
        $otherUser = factory(User::class)->create([]);
        $tasks = factory(Task::class,3)->make(['complete' => false]);
        $otherUser->tasks()->saveMany($tasks);

        $scopedUsersTasks = $this->taskService->getOpenTasks();

        $this->assertCount(5, $scopedUsersTasks);
    }

    /**
     * Service retrieves all pending tasks
     *
     * @test
     */
    public function it_retrieves_all_open_tasks()
    {
        $this->tasks = factory(Task::class,5)->make(['complete' => false]);
        $this->user->tasks()->saveMany($this->tasks);

        $this->tasks->last()->markComplete();
        $currentTasks = $this->taskService->getOpenTasks();

        $this->assertCount(4, $currentTasks);
    }

    /**
     * Service retrieves all tasks due today
     *
     * @test
     */
    public function it_retrieves_all_tasks_due_today()
    {
        $this->tasks = factory(Task::class,5)
            ->make(['complete' => false, 'due_date' => Carbon::today()->addDays(1)->format('Y-m-d')]);
        $this->user->tasks()->saveMany($this->tasks);
        $this->tasks->first()->setDueDate(Carbon::today()->format('Y-m-d'));
        // tasks past due should also be retrieved
        $this->tasks->last()->setDueDate(Carbon::today()->subDays(5)->format('Y-m-d'));

        $tasksDueToday = $this->taskService->getTasksDueToday();

        $this->assertCount(2, $tasksDueToday);
    }

    /**
     * Service retrieves all tasks due this week
     *
     * @test
     */
    public function it_retrieves_all_tasks_due_this_week()
    {
        $this->tasks = factory(Task::class,5)->make(['complete' => false]);
        $this->user->tasks()->saveMany($this->tasks);
        $this->tasks->first()->setDueDate(Carbon::today()->startOfWeek()->format('Y-m-d'));
        $this->tasks->last()->setDueDate(Carbon::today()->endOfWeek()->format('Y-m-d'));

        $tasksDueThisWeek = $this->taskService->getTasksDueThisWeek();

        $this->assertCount(2, $tasksDueThisWeek);
    }

    /**
     * Service retrieves all tasks due next week
     *
     * @test
     */
    public function it_retrieves_all_tasks_due_next_week()
    {
        $this->tasks = factory(Task::class,5)->make(['complete' => false]);
        $this->user->tasks()->saveMany($this->tasks);
        $this->tasks->first()->setDueDate(Carbon::today()->startOfWeek()->addDays(7)->format('Y-m-d'));
        $this->tasks->last()->setDueDate(Carbon::today()->endOfWeek()->addDays(7)->format('Y-m-d'));
        $tasksDueNextWeek = $this->taskService->getTasksDueNextWeek();

        $this->assertCount(2, $tasksDueNextWeek);
    }

    /**
     * Service retrieves all tasks due later than next week
     *
     * @test
     */
    public function it_retrieves_all_tasks_due_later_than_next_week()
    {
        $this->tasks = factory(Task::class,5)->make(['complete' => false]);
        $this->user->tasks()->saveMany($this->tasks);
        // first 3 tasks should not be retrieved
        $this->tasks[0]->setDueDate(Carbon::today()->format('Y-m-d'));
        $this->tasks[0]->save();
        $this->tasks[1]->setDueDate(Carbon::today()->format('Y-m-d'));
        $this->tasks[1]->save();
        $this->tasks[2]->setDueDate(Carbon::today()->format('Y-m-d'));
        $this->tasks[2]->save();
        // task due past end of next week should be retrieved
        $this->tasks[3]->setDueDate(Carbon::today()->addDays(35)->format('Y-m-d'));
        $this->tasks[3]->save();
        // task with no due date should be retrieved
        $this->tasks->last()->due_date = null;
        $this->tasks->last()->save();

        $tasksDueInFuture = $this->taskService->getTasksDueAfterNextWeek();

        $this->assertCount(2, $tasksDueInFuture);
    }

}
