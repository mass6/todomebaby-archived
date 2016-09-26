<?php

use App\Project;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectModelTest extends TestCase
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
     * @test
     */

    /**
     * Queries are scoped to authenticated user
     *
     * @test
     */
    public function it_scopes_queries_to_the_specified_user()
    {
        $this->generateProjects(2);
        // create projects that belong to another user
        $unscopedUser = factory(User::class)->create([]);
        $this->generateProjects(3, $unscopedUser);

        $scopedUserProjects = Project::all();
        $this->assertCount(2, $scopedUserProjects);
    }


    /**
     * It fetches related task list with relations and sorting applied, with closed
     * tasks if includeCompleted parameter is true
     *
     * @test
     */
    public function it_applies_the_task_list_scope()
    {
        // Sorting order: Next/true, due_date/asc, priority/desc, created/asc
        // Given I have a project with 7 tasks:
        // 0: next/true, due_date/null, priority/low - (Expected position: 2)
        // 1: next/true, due_date/null, priority/high - (Expected position: 1)
        // 2: next/true, due_date/today, priority/low - (Expected position: 0)
        // 3: next/false, due_date/today, priority/low - (Expected position: 4)
        // 4: next/false, due_date/today, priority/low (created_after #4) - (Expected position: 5)
        // 5: next/false, due_date/today, priority/high - (Expected position: 3)
        // 6: next/false, due_date/tomorrow, priority/low - (Expected position: 6)

        $today = Carbon::today()->toDayDateTimeString();
        $project = $this->generateProjects(1);
        $tasks = $this->generateUserTasks(7, null,[
            'project_id' => $project->id,
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

        $taskList = $project->taskList();

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


    // Helper Methods


    protected function generateProjects($amount = 5, User $user = null, $overrides = [])
    {
        $overrides['user_id'] = $user ? $user->id : $this->user->id;

        return factory(Project::class, $amount)->create($overrides);
    }


    protected function generateUserTasks($amount = 5, User $user = null, $overrides = [])
    {
        $overrides['user_id'] = $user ? $user->id : $this->user->id;

        return factory(Task::class, $amount)->create($overrides);
    }


}
