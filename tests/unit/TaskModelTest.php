<?php

use App\Task;
use App\User;
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
     * @group failing
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
