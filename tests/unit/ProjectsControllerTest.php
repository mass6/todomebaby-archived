<?php

use App\Http\Controllers\ProjectsController;
use App\Project;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use \Mockery as m;

class ProjectsControllerTest extends TestCase
{

    use DatabaseMigrations;

    private $service;


    public function __construct()
    {
        $this->service = m::mock('App\Services\ProjectService');
    }

    public function tearDown()
    {
        m::close();
    }



    /**
     * @test
     */
    public function testShow()
    {
        $mProject = m::mock('App\Project');
        $mProject->shouldReceive('toArray')->once()->andReturn(['project']);
        $controller = new ProjectsController;
        $response = $controller->show($mProject);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertInternalType('array', $response->getData());
    }

    /**
     * @test
     */
    public function testGetActiveProjects()
    {
        $this->service->shouldReceive('getActiveProjects')->once()->andReturn(collect(['projects']));
        $controller = new ProjectsController;
        $response = $controller->getActive($this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(['projects'], $response->getData());
    }


    /**
     * @test
     */
    public function testGetTasksByProject()
    {
        $project = m::mock('App\Project');
        $project->shouldReceive('getAttribute')->with('name')->andReturn('Project One');
        $project->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $mRequest = m::mock('Illuminate\Http\Request');
        $mRequest->shouldReceive('get')->once()->with('with-completed')->andReturn(true);
        $this->service->shouldReceive('getTasksByProjectId')->with(1, true)->andReturn(collect(['tasks']));

        $controller = new ProjectsController;
        $response = $controller->getTasksByProject($project, $this->service, $mRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($project->name, $response->getData()->listName);
        $this->assertEquals(['tasks'], $response->getData()->tasks);
    }


    public function testStore()
    {
        $mockRequest = m::mock('Illuminate\Http\Request');
        $mockRequest->shouldReceive('all')->once()->andReturn(['name' => 'foo']);
        $this->service->shouldReceive('addProject')->with(['name' => 'foo'])->once()->andReturn('bar');

        $controller = new ProjectsController;
        $response = $controller->store($mockRequest, $this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('bar', $response->getData());
    }

    /**
     * @test
     */
    public function testUpdate()
    {
        $mProject = m::mock('App\Project');
        $mRequest = m::mock('Illuminate\Http\Request');
        $mRequest->shouldReceive('all')->once()->andReturn(['name' => 'foo']);
        $this->service->shouldReceive('updateProject')->with($mProject, ['name' => 'foo'])->once()->andReturn('bar');

        $controller = new ProjectsController;
        $response = $controller->update($mProject, $mRequest, $this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('bar', $response->getData());
    }

    /**
     * @test
     */
    public function testDestroy()
    {
        $mProject = m::mock('App\Project');
        $this->service->shouldReceive('deleteProject')->with($mProject)->andReturn(true);
        $controller = new ProjectsController;
        $response = $controller->destroy($mProject, $this->service);

        $this->assertEquals(200, $response->getStatusCode());
    }

}
