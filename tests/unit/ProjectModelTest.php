<?php

use App\Project;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectModelTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     */
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


}
