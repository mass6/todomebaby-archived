<?php

use App\Project;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskModelTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @var User
     */
    public $user;


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([]);
        $this->actingAs($this->user);
    }

    /**
     * Service scopes queries to specified user
     *
     * @test
     */
    public function it_scopes_queries_to_the_specified_user()
    {
        $this->generateUserTasks(5);
        // create tasks that belong to another user
        $unscopedUser = factory(User::class)->create([]);
        $this->generateUserTasks(3, $unscopedUser);

        $scopedUsersTasks = Task::all();
        $this->assertCount(5, $scopedUsersTasks);
    }

    /**
     * Service scopes queries to open tasks
     *
     * @test
     */
    public function it_applies_the_open_scope()
    {
        // given I have an open and a closed task
        $tasks = $this->generateUserTasks(2);
        $tasks[0]->complete = true;
        $tasks[0]->save();

        // when I run a query with the openScope applied
        $openTasks = Task::open()->get();

        // then it should only return tasks which are open
        $this->assertCount(1, $openTasks);
        $this->assertEquals($tasks[1]->title, $openTasks->first()->title);
    }

    /**
     * Scope return task list with relations and sorting applied, with closed
     * tasks if includeCompleted parameter is true
     *
     * @test
     */
    public function it_applies_the_task_list_scope()
    {
        // Sorting order: Next/true, due_date/asc, priority/desc, created/asc
        // given I have 6 tasks:
        // 0: next/true, due_date/null, priority/low - (Expected position: 2)
        // 1: next/true, due_date/null, priority/high - (Expected position: 1)
        // 2: next/true, due_date/today, priority/low - (Expected position: 0)
        // 3: next/false, due_date/today, priority/low - (Expected position: 4)
        // 4: next/false, due_date/today, priority/low (created_after #4) - (Expected position: 5)
        // 5: next/false, due_date/today, priority/high - (Expected position: 3)
        // 6: next/false, due_date/tomorrow, priority/low - (Expected position: 6)

        $today = Carbon::today()->toDayDateTimeString();
        $tasks = $this->generateUserTasks(7, null,[
            'priority' => 'low',
            'next' => false,
            'due_date' => null,
            'created_at' => Carbon::now()->subDays(5)
        ]);
        $tasks[0]->update(['next'=>true]);
        $tasks[1]->update(['next'=>true, 'priority'=>'high']);
        $tasks[2]->update(['next'=>true, 'due_date'=>$today]);
        $tasks[3]->update(['due_date'=>$today]);
        $tasks[4]->created_at = Carbon::now()->subDays(2);
        $tasks[4]->update(['due_date'=>$today]);
        $tasks[5]->update(['due_date'=>$today, 'priority'=>'high']);
        $tasks[6]->update(['due_date'=>Carbon::tomorrow()->toDayDateTimeString()]);

        $taskList = Task::taskList()->get();

        // verify sort order
        $this->assertEquals($tasks[2]->id, $taskList[0]->id);
        $this->assertEquals($tasks[1]->id, $taskList[1]->id);
        $this->assertEquals($tasks[0]->id, $taskList[2]->id);
        $this->assertEquals($tasks[5]->id, $taskList[3]->id);
        $this->assertEquals($tasks[3]->id, $taskList[4]->id);
        $this->assertEquals($tasks[4]->id, $taskList[5]->id);
        $this->assertEquals($tasks[6]->id, $taskList[6]->id);

        // verify relations are present
        $this->assertArrayHasKey('project', $taskList->first()->toArray());
        $this->assertArrayHasKey('tags', $taskList->first()->toArray());

    }


    /**
     * It toggles the next field flag
     *
     * @test
     */
    public function it_toggles_the_next_field()
    {
        $task = $this->generateUserTasks(1, null, ['next' => false]);
        $task->toggleNextFlag();
        $this->assertTrue($task->next);
        $task->toggleNextFlag();
        $this->assertFalse($task->next);
    }

    /**
     * It toggles the completion status
     *
     * @test
     */
    public function it_toggles_the_completion_status()
    {
        $task = $this->generateUserTasks(1);

        $task->toggleComplete();
        $this->assertTrue($task->complete);

        $task->toggleComplete();
        $this->assertFalse($task->complete);
    }


    /**
     * It associates a task to a project
     *
     * @test
     */
    public function it_associates_task_to_project()
    {
        $project = Project::create(['name'=>'Project One', 'user_id'=>$this->user->id]);
        $task = $this->generateUserTasks(1);

        $task->associateToProject($project->id);
        $this->assertEquals($project->id, $task->project->id);
    }

    /**
     * Created_at field is set by ApplicationServiceProvider event method
     *
     * @test
     *
     */
    public function it_automatically_sets_the_completed_at_field()
    {
        $task = $this->generateUserTasks(1)->first();
        $this->assertNull($task->completed_at);

        $task->update(['complete' => true]);
        $this->assertNotEmpty($task->completed_at);

        $task->update(['complete' => false]);
        $this->assertNull($task->completed_at);
    }


    /**
     * Service scopes queries to specified user
     *
     * @test
     */
    public function it_sets_the_task_priority()
    {
        $task = $this->generateUserTasks(1, null, [
            'title' => 'Task',
            'priority' => 'LOW',
            'user_id' => $this->user->id,
        ]);
        $task->setPriority('med');
        $this->assertEquals('medium', $task->priority);
    }

    /**
     * Tests the the priority provided must be LOW, MED, or HGH
     *
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_if_priority_value_is_not_allowed()
    {
        $task = $this->generateUserTasks(1);
        $task->priority = 'INVALID';
    }

    /**
     * Transforms priority field into human readable text
     *
     * @test
     */
    public function it_transforms_the_priority_field_into_human_readable_text()
    {
        $task = $this->generateUserTasks(1, null, ['priority' => 'low']);
        $this->assertEquals('low', $task->priority);

        $task->priority = 'MED';
        $this->assertEquals('medium', $task->priority);

        $task->priority = 'HGH';
        $this->assertEquals('high', $task->priority);
    }

    // Helper Methods


    protected function generateUserTasks($amount = 5, User $user = null, $overrides = [])
    {
        $overrides['user_id'] = $user ? $user->id : $this->user->id;

        return factory(Task::class, $amount)->create($overrides);
    }

}
