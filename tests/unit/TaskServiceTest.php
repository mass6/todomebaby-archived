<?php

use App\Project;
use App\Services\ProjectService;
use App\Services\TaskService;
use App\Tag;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskServiceTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @var User
     */
    public $user;

    /**
     * @var TaskService
     */
    protected $taskService;

    public $tasks;


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([]);
        // Set authenticated user
        $this->actingAs($this->user);
        $this->taskService = new TaskService();
    }

    // Query Service Tests


    /**
     * Service retrieves a task by ID
     *
     * @test
     */
    public function it_retrieves_a_task_by_id()
    {
        $createdTask = $this->generateUserTasks($this->user, 1);
        $foundTask = $this->taskService->findById($createdTask->id);

        $this->assertInstanceOf(Task::class, $foundTask);
        $this->assertEquals($createdTask->id, $foundTask->id);
        $this->assertEquals($createdTask->title, $foundTask->title);
        $this->assertArrayHasKey('project', $foundTask->toArray());
        $this->assertArrayHasKey('tags', $foundTask->toArray());
    }

    /**
     * Service retrieves all pending tasks
     *
     * @test
     */
    public function it_retrieves_all_open_tasks()
    {
        $this->tasks = $this->generateUserTasks();

        $this->tasks->last()->toggleComplete();
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

        // User, amount, project, overrides
        $project = factory(Project::class)->create(['name' => 'My New Project', 'user_id' => $this->user->id]);
        $this->tasks->first()->associateToProject($project->id);

        $this->setDueDates($this->tasks, Carbon::today()->addDays(1)->toDateString());
        $this->tasks->first()->setDueDate(Carbon::today()->toDateString());
        // tasks past due should also be retrieved
        $this->tasks->last()->setDueDate(Carbon::today()->subDays(5)->toDateString());

        $tasksDueToday = $this->taskService->getTasksDueToday();
        $this->assertCount(2, $tasksDueToday);
        $this->assertArrayHasKey('project', $tasksDueToday->first()->toArray());
        $this->assertArrayHasKey('tags', $tasksDueToday->first()->toArray());
    }

    /**
     * Service retrieves count of all tasks due today
     *
     * @test
     */
    public function it_retrieves_count_of_all_tasks_due_today()
    {
        $this->tasks = $this->generateUserTasks();

        $this->setDueDates($this->tasks, Carbon::today()->toDateString());
        $this->tasks->first()->toggleComplete();

        $this->assertEquals(4, $this->taskService->tasksDueTodayCount());
    }


    /**
     * Service retrieves all tasks due tomorrow
     *
     * @test
     */
    public function it_retrieves_all_tasks_due_tomorrow()
    {
        $this->tasks = $this->generateUserTasks();
        $project = factory(Project::class)->create(['name' => 'My New Project', 'user_id' => $this->user->id]);
        $this->tasks->first()->associateToProject($project->id);

        $this->tasks->first()->setDueDate(Carbon::tomorrow()->toDateString());
        $this->tasks->last()->setDueDate(Carbon::tomorrow()->toDateString());

        $tasksDueTomorrow = $this->taskService->getTasksDueTomorrow();

        $this->assertCount(2, $tasksDueTomorrow);
        $this->assertArrayHasKey('project', $tasksDueTomorrow->first()->toArray());
        $this->assertArrayHasKey('tags', $tasksDueTomorrow->first()->toArray());
    }

    /**
     * Service retrieves count of all tasks due tomorrow
     *
     * @test
     */
    public function it_retrieves_count_of_all_tasks_due_tomorrow()
    {
        $this->tasks = $this->generateUserTasks();

        $this->setDueDates($this->tasks, Carbon::tomorrow()->toDateString());
        $this->tasks->first()->toggleComplete();

        $this->assertEquals(4, $this->taskService->tasksDueTomorrowCount());
    }

    /**
     * Service retrieves all tasks due this week
     *
     * @test
     */
    public function it_retrieves_all_tasks_due_this_week()
    {
        $this->tasks = $this->generateUserTasks();
        $project = factory(Project::class)->create(['name' => 'My New Project', 'user_id' => $this->user->id]);
        $this->tasks->first()->associateToProject($project->id);

        $this->setDueDates($this->tasks, Carbon::today()->addDays(15)->toDateString());

        $this->tasks->first()->setDueDate(Carbon::today()->startOfWeek()->toDateString());
        $this->tasks->last()->setDueDate(Carbon::today()->endOfWeek()->toDateString());

        $tasksDueThisWeek = $this->taskService->getTasksDueThisWeek();

        $this->assertCount(2, $tasksDueThisWeek);
        $this->assertArrayHasKey('project', $tasksDueThisWeek->first()->toArray());
        $this->assertArrayHasKey('tags', $tasksDueThisWeek->first()->toArray());
    }

    /**
     * Service retrieves count of all tasks due this week
     *
     * @test
     */
    public function it_retrieves_count_of_all_tasks_due_this_week()
    {
        $this->tasks = $this->generateUserTasks();

        $this->setDueDates($this->tasks, Carbon::today()->startOfWeek()->toDateString());
        $this->tasks->first()->toggleComplete();

        $this->assertEquals(4, $this->taskService->tasksDueThisWeekCount());
    }

    /**
     * Service retrieves all tasks due next week
     *
     * @test
     */
    public function it_retrieves_all_tasks_due_next_week()
    {
        $this->tasks = $this->generateUserTasks();
        $project = factory(Project::class)->create(['name' => 'My New Project', 'user_id' => $this->user->id]);
        $this->tasks->first()->associateToProject($project->id);

        $this->setDueDates($this->tasks, Carbon::today()->toDateString());

        $this->tasks->first()->setDueDate(Carbon::today()->startOfWeek()->addDays(7)->toDateString());
        $this->tasks->last()->setDueDate(Carbon::today()->endOfWeek()->addDays(7)->toDateString());
        $tasksDueNextWeek = $this->taskService->getTasksDueNextWeek();

        $this->assertCount(2, $tasksDueNextWeek);
        $this->assertArrayHasKey('project', $tasksDueNextWeek->first()->toArray());
        $this->assertArrayHasKey('tags', $tasksDueNextWeek->first()->toArray());

    }

    /**
     * Service retrieves count of all tasks due next week
     *
     * @test
     */
    public function it_retrieves_count_of_all_tasks_due_next_week()
    {
        $this->tasks = $this->generateUserTasks();

        $this->setDueDates($this->tasks, Carbon::today()->endOfWeek()->addDays(2)->toDateString());
        $this->tasks->first()->toggleComplete();

        $this->assertEquals(4, $this->taskService->tasksDueNextWeekCount());
    }

    /**
     * Service retrieves all tasks due later than next week
     *
     * @test
     */
    public function it_retrieves_all_tasks_due_in_future()
    {
        $this->tasks = $this->generateUserTasks();
        $project = factory(Project::class)->create(['name' => 'My New Project', 'user_id' => $this->user->id]);
        $this->tasks->first()->associateToProject($project->id);

        $this->setDueDates($this->tasks, Carbon::today()->toDateString());

        // task due past end of next week should be retrieved
        $this->tasks->first()->setDueDate(Carbon::today()->addDays(35)->toDateString());
        $this->tasks->first()->save();
        // task with no due date should be retrieved
        $this->tasks->last()->due_date = null;
        $this->tasks->last()->save();

        $tasksDueInFuture = $this->taskService->getTasksDueInFuture();

        $this->assertCount(2, $tasksDueInFuture);
        $this->assertArrayHasKey('project', $tasksDueInFuture->first()->toArray());
        $this->assertArrayHasKey('tags', $tasksDueInFuture->first()->toArray());
    }

    /**
     * Service retrieves count of all tasks due in future
     *
     * @test
     */
    public function it_retrieves_count_of_all_tasks_due_in_future()
    {
        $this->tasks = $this->generateUserTasks();

        $this->setDueDates($this->tasks, Carbon::today()->addDays(20)->toDateString());
        $this->tasks->first()->toggleComplete();
        $this->tasks->last()->due_date = null;
        $this->tasks->last()->save();

        $this->assertEquals(4, $this->taskService->tasksDueInFutureCount());
    }


    /**
     * @test
     */
    public function it_retrieves_tasks_by_tag()
    {
        $this->generateUserTasks($this->user, 2);
        $tasks = $this->generateUserTasks($this->user, 3, false, [], ['bazz']);

        $tasks = $this->taskService->findByTag($tasks->first()->tags->first());

        $this->assertCount(3, $tasks);
        $this->assertArrayHasKey('project', $tasks->first()->toArray());
        $this->assertArrayHasKey('tags', $tasks->first()->toArray());
        $this->assertEquals('bazz', $tasks->first()->tags->first()->name);
    }
    /**
     * @test
     */
    public function it_retrieves_tasks_by_tag_name()
    {
        $this->generateUserTasks($this->user, 2);
        $this->generateUserTasks($this->user, 3, false, [], ['bazz']);

        $tasks = $this->taskService->findByTagName('bazz');


        $this->assertCount(3, $tasks);
        $this->assertArrayHasKey('project', $tasks->first()->toArray());
        $this->assertArrayHasKey('tags', $tasks->first()->toArray());
        $this->assertEquals('bazz', $tasks->first()->tags->first()->name);
    }

    // Command Service Tests

    /**
     * Service adds a new task
     *
     * @test
     */
    public function it_adds_a_new_task()
    {
        $project = Project::create(factory(Project::class)->make(['user_id' => $this->user->id])->toArray());
        $taskFormData = [
            'title' => 'Task 1',
            'complete' => false,
            'next' => true,
            'due_date' => Carbon::tomorrow()->toDateString(),
            'project_id' => $project->id,
            'tagsinput' => '@foo, bar',
            'priority' => 'medium',
            'details' => 'Details about this task',
        ];
        //$this->user->projects()->create(factory(Project::class)->make([])->toArray());
        //$task = factory(Task::class)->make($taskFormData);
        //$task->tags = '@foo, bar';
        $this->taskService->addTask($taskFormData);

        $savedTask = $this->user->tasks->first();
        $this->assertCount(1, $this->user->tasks);
        $this->assertEquals(
            [
                'title' => 'Task 1',
                'complete' => false,
                'next' => true,
                'due_date' => Carbon::tomorrow()->toDateString(),
                'project_id' => $project->id,
                'priority' => 'medium',
                'details' => 'Details about this task',
            ],
            collect($savedTask)->only(['title', 'complete', 'next', 'due_date', 'project_id', 'priority', 'details'])->toArray()
        );

        $tags = $savedTask->tags;
        $this->assertCount(2, $tags);
        $this->assertEquals('@foo', $tags[0]->name);
        $this->assertEquals(true, $tags[0]->is_context);
        $this->assertEquals('bar', $tags[1]->name);
        $this->assertEquals(false, $tags[1]->is_context);
    }

    /**
     * Tags should not be created if tags input field is blank
     *
     * @test
     */
    public function it_does_not_save_blank_tags()
    {
        $data1 = ['tagsinput' => '', 'title' => 'Task 1'];
        $data2 = ['tagsinput' => ' ', 'title' => 'Task 2'];
        $data3 = ['tagsinput' => '  ', 'title' => 'Task 3'];
        $data4 = ['tagsinput' => ' , ', 'title' => 'Task 3'];
        $data5 = ['tagsinput' => 'foo, ', 'title' => 'Task 4'];
        $this->taskService->addTask($data1);
        $this->taskService->addTask($data2);
        $this->taskService->addTask($data3);
        $this->taskService->addTask($data4);
        $this->taskService->addTask($data5);

        $tasks = $this->user->tasks;
        $this->assertCount(0, $tasks[0]->tags);
        $this->assertCount(0, $tasks[1]->tags);
        $this->assertCount(0, $tasks[2]->tags);
        $this->assertCount(0, $tasks[3]->tags);
        $this->assertCount(1, $tasks[4]->tags);
    }


    /**
     * Service updates a task
     *
     * @test
     */
    public function it_updates_a_task()
    {
        $date = Carbon::tomorrow()->toDateString();
        $taskData = [
            'title' => 'Task 1',
            'complete' => false,
            'next' => true,
            'due_date' => $date,
            'priority' => 'medium',
            'details' => 'Details about this task',
            'user_id' => $this->user->id,
        ];

        $task = $this->generateUserTasks($this->user,1,false,$taskData);

        $newData = [
            'title' => 'Task Changed',
            'next' => false,
            'tagsinput' => '@foo, baz, bop',
        ];

        $this->taskService->updateTask($task, $newData);

        $this->assertEquals('Task Changed', $task->title);
        $this->assertEquals(false, $task->next);
        $this->assertEquals($date, $task->due_date);
    }

    /**
     * Service updates a task
     *
     * @test
     */
    public function it_updates_tags()
    {
        $task = $this->generateUserTasks($this->user,1);

        $newData = [
            'tagsinput' => '@foo, baz, bop',
        ];

        $this->taskService->updateTask($task, $newData);
        $this->assertCount(3, $task->tags);
        $this->assertEquals('@foo', $task->tags[0]->name);
        $this->assertEquals('baz', $task->tags[1]->name);
        $this->assertEquals('bop', $task->tags[2]->name);
    }

    /**
     * Service does not update tags if tagsinput is missing
     *
     * @test
     */
    public function it_does_not_updates_tags_if_tagsinput_field_is_not_present_in_request()
    {
        $task = $this->generateUserTasks($this->user,1);

        // No tags data is provided in the uddate data
        $newData = [
            'title' => 'New title',
        ];

        $this->taskService->updateTask($task, $newData);
        $this->assertCount(2, $task->tags);
        $this->assertEquals('@foo', $task->tags[0]->name);
        $this->assertEquals('bar', $task->tags[1]->name);
    }

    /**
     * Service updates a task
     *
     * @test
     * @group current
     */
    public function it_unsets_a_project()
    {
        $date = Carbon::tomorrow()->toDateString();
        $taskData = [
            'title' => 'Task 1',
            'complete' => false,
            'next' => true,
            'due_date' => $date,
            'priority' => 'medium',
            'details' => 'Details about this task',
            'user_id' => $this->user->id,
        ];
        $task = factory(Task::class)->create($taskData);

        $newData = [
            'project' => '',
        ];

        $this->taskService->updateTask($task, $newData);

        $this->assertEquals(null, $task->project_id);
    }
    /**
     * Service sets default fields values
     *
     * @test
     */
    public function it_sets_default_field_values_for_new_tasks_when_no_input_is_given()
    {
        $taskData = ['title' => 'Task 1'];
        $task = $this->taskService->addTask($taskData);

        $this->assertFalse($task->complete);
        $this->assertNull($task->complete_at);
        $this->assertFalse($task->next);
        $this->assertNull($task->due_date);
        $this->assertNull($task->details);
    }

    /**
     * Service creates a new task
     *
     * @test
     */
    public function it_toggles_the_next_flag_on_a_task()
    {
        $taskData = ['title' => 'Task 1'];
        $task = $this->taskService->addTask($taskData);

        $this->taskService->toggleNextFlag($task);
        $this->assertTrue($task->next);

        $this->taskService->toggleNextFlag($task);
        $this->assertFalse($task->next);
    }
    /**
     * Service toggles a task complete and sets or nulls the completed_at date.
     *
     * @test
     */
    public function it_toggles_the_completed_flag_on_a_task_and_sets_or_nulls_the_completed_at_date()
    {
        $taskData = ['title' => 'Task 1'];
        $task = $this->taskService->addTask($taskData);

        $this->taskService->toggleComplete($task);
        $this->assertTrue($task->complete);
        $this->assertNotNull($task->completed_at);

        $this->taskService->toggleComplete($task);
        $this->assertFalse($task->complete);
        $this->assertNull($task->completed_at);
    }






    // Helper Methods

    /**
     * @param User  $user
     * @param int   $amount
     * @param bool  $complete
     * @param array $overrides
     * @param array $tags
     *
     * @return mixed
     */
    protected function generateUserTasks(User $user = null, $amount = 5, $complete = false, $overrides = [], $tags = ['@foo', 'bar'])
    {
        $user = $user ?: $this->user;
        $overrides['complete'] = $complete;
        $tasks = factory(Task::class, $amount)->make($overrides);
        $amount > 1 ? $user->tasks()->saveMany($tasks) : $user->tasks()->save($tasks);

        $tagModels = collect($tags)->map(function($tag) use ($user) {
            return factory(Tag::class)->create(['name'=> $tag, 'user_id'=>$user->id]);
        });

        $tasks->each(function($task) use ($tagModels) {
            $task->tags()->attach($tagModels->pluck('id')->all());
        });

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
