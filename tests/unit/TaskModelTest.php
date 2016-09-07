<?php

use App\Project;
use App\Services\ProjectService;
use App\Services\TaskService;
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
     * @var Task
     */
    private $task;


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([]);
        $this->actingAs($this->user);
        $this->task = Task::create([
            'title' => 'Task',
            'priority' => 'LOW',
            'user_id' => $this->user->id,
        ]);
    }


    /**
     * Service scopes queries to specified user
     *
     * @test
     */
    public function it_sets_the_task_priority()
    {
        $task = $this->task;
        $task->setPriority('med');
        $this->assertEquals('med', $task->getOriginal('priority'));
    }

    /**
     * Tests the the priority provided must be LOW, MED, or HGH
     *
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_if_priority_value_is_not_allowed()
    {
        $task = $this->task;
        $task->priority = 'INVALID';
    }

    /**
     * Transforms priority field into human readable text
     *
     * @test
     */
    public function it_transforms_the_priority_field_into_human_readable_text()
    {
        $task = $this->task;
        $this->assertEquals('low', $task->priority);

        $task->priority = 'MED';
        $this->assertEquals('medium', $task->priority);

        $task->priority = 'HGH';
        $this->assertEquals('high', $task->priority);
    }

}
