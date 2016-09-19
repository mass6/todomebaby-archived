<?php

use App\Project;
use App\Task;
use App\User;
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


    public function it_fetches_all_related_tasks_which_are_open()
    {
        $user = factory(User::class)->create([]);
        $this->actingAs($user);
        $project = $user->projects()->create(factory(Project::class)->make([])->toArray());
        $tasks = factory(Task::class, 3)->create([
            'user_id' => $user->id,
            'project_id' => $project->id
        ]);
        $tasks->last()->toggleComplete();
        //var_dump($tasks->toArray());

        $this->assertEquals(2, $project->openTasks()->count());
    }


    // Helper Methods


    protected function generateProjects($amount = 5, User $user = null, $overrides = [])
    {
        $overrides['user_id'] = $user ? $user->id : $this->user->id;

        return factory(Project::class, $amount)->create($overrides);
    }


}
