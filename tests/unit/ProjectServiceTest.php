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
     * Service retrieves a project by ID
     *
     * @test
     */
    public function it_retrieves_a_project_by_id()
    {
        $project = $this->generateUserProjects($this->user, 1);
        $foundProject = $this->projectService->findById($project->id);

        $this->assertInstanceOf(Project::class, $foundProject);
        $this->assertEquals($project->id, $foundProject->id);
        $this->assertEquals($project->name, $foundProject->name);
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
        $this->assertArrayHasKey('taskCount', $activeProjects->first()->toArray());
    }


    /**
     * @test
     */
    public function it_retrieves_all_tasks_by_project()
    {
        // Given I have a project named Project One with two tasks
        $project = factory(Project::class)->create(['name' => 'Project One', 'user_id' => $this->user->id]);
        $tasks = $this->generateUserTasks($this->user, 3, $project);
        $tasks->first()->project_id = null;
        $tasks->first()->save();

        // When I call getTasksByProjectId()
        $tasks = $this->projectService->getTasksByProjectId($project->id);

        // Then it should return 2 projects
        $this->assertCount(2, $tasks);
        $this->assertArrayHasKey('tags', $tasks->first()->toArray());
    }

    // Command Service Tests

    /**
     * Service adds a new project
     *
     * @test
     */
    public function it_adds_a_new_project()
    {
        $projectFormData = [
            'name' => 'Project One',
            'description' => 'project description',
            'due_date' => Carbon::tomorrow()->toDateString(),
        ];
        $project = $this->projectService->addProject($projectFormData);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Project One', $project->name);
        $this->assertEquals('project description', $project->description);
        $this->assertEquals(Carbon::tomorrow()->toDateString(), $project->due_date);
    }

    /**
     * Service updates a task
     *
     * @test
     * @group current
     */
    public function it_updates_a_task()
    {
        $projectData = [
            'user_id' => $this->user->id,
            'name' => 'Project One',
            'due_date' => Carbon::today()->toDateString(),
            'description' => 'Details about this task',
        ];
        $project = factory(Project::class)->create($projectData);

        $newData = [
            'name' => 'Updated Name',
            'description' => 'New description',
            'due_date' => Carbon::tomorrow()->toDateString()
        ];

        $this->projectService->updateProject($project, $newData);

        $this->assertEquals('Updated Name', $project->name);
        $this->assertEquals('New description', $project->description);
        $this->assertEquals(Carbon::tomorrow()->toDateString(), $project->due_date);
    }


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
        $overrides['user_id'] = $user->id;
        $projects = factory(Project::class, $amount)->create($overrides);
        //$amount > 1 ? $user->projects()->saveMany($projects) : $user->projects()->save($projects);

        return $projects;
    }

    protected function generateUserTasks(User $user, $amount = 5, Project $project = null, $overrides = [])
    {
        $tasks = collect([]);
        for ($i = 0; $i < $amount; $i++) {
            $overrides['user_id'] = $user->id;
            $overrides['project_id'] = $project ? $project->id : null;
            $tasks->push(factory(Task::class)->create($overrides));
        }

        return $tasks;
    }

}
