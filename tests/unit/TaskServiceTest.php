<?php

use App\Services\TaskService;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
        $this->tasks = $this->generateUserTasks();
        // create tasks that belong to another user
        $otherUser = factory(User::class)->create([]);
        $this->generateUserTasks($otherUser, 3);

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
        $this->tasks = $this->generateUserTasks();

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
        $this->tasks = $this->generateUserTasks();
        $this->setDueDates($this->tasks, Carbon::today()->addDays(1)->toDateString());

        $this->tasks->first()->setDueDate(Carbon::today()->toDateString());
        // tasks past due should also be retrieved
        $this->tasks->last()->setDueDate(Carbon::today()->subDays(5)->toDateString());

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
        $this->tasks = $this->generateUserTasks();
        $this->setDueDates($this->tasks, Carbon::today()->addDays(15)->toDateString());

        $this->tasks->first()->setDueDate(Carbon::today()->startOfWeek()->toDateString());
        $this->tasks->last()->setDueDate(Carbon::today()->endOfWeek()->toDateString());

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
        $this->tasks = $this->generateUserTasks();
        $this->setDueDates($this->tasks, Carbon::today()->toDateString());

        $this->tasks->first()->setDueDate(Carbon::today()->startOfWeek()->addDays(7)->toDateString());
        $this->tasks->last()->setDueDate(Carbon::today()->endOfWeek()->addDays(7)->toDateString());
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
        $this->tasks = $this->generateUserTasks();
        $this->setDueDates($this->tasks, Carbon::today()->toDateString());

        // task due past end of next week should be retrieved
        $this->tasks->first()->setDueDate(Carbon::today()->addDays(35)->toDateString());
        $this->tasks->first()->save();
        // task with no due date should be retrieved
        $this->tasks->last()->due_date = null;
        $this->tasks->last()->save();

        $tasksDueInFuture = $this->taskService->getTasksDueAfterNextWeek();

        $this->assertCount(2, $tasksDueInFuture);
    }


    /**
     * @param User  $user
     * @param int   $amount
     * @param bool  $complete
     * @param array $overrides
     *
     * @return mixed
     */
    protected function generateUserTasks(User $user = null, $amount = 5, $complete = false, $overrides = [])
    {
        $user = $user ?: $this->user;
        $overrides['complete'] = $complete;
        $tasks = factory(Task::class, $amount)->make($overrides);
        $user->tasks()->saveMany($tasks);

        return $tasks;
    }


    /**
     * @param $tasks
     * @param $date
     */
    protected function setDueDates($tasks, $date)
    {
        foreach ($tasks as $task) {
            $task->setDueDate($date);
            $task->save();
        }
    }

}
