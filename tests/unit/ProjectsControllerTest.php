<?php

use App\Http\Controllers\ProjectsController;
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
        $this->service->shouldReceive('findById')->with(1)->andReturn('task');
        $controller = new ProjectsController;
        $response = $controller->show(1, $this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('task', $response->getData());
    }

    /**
     * @test
     * @group isolated
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
        $this->service->shouldReceive('getTasksByProjectId')->with(1)->andReturn(collect(['tasks']));

        $controller = new ProjectsController;
        $response = $controller->getTasksByProject($project, $this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($project->name, $response->getData()->listName);
        $this->assertEquals(['tasks'], $response->getData()->tasks);
    }


    ///**
    // * @test
    // */
    //public function testGetTasksByProject()
    //{
    //    $project = m::mock('App\Project');
    //    $project->shouldReceive('getAttribute')->with('id')->andReturn(1);
    //    $project->shouldReceive('getAttribute')->with('name')->andReturn('Project One');
    //    $this->service->shouldReceive('getTasksByProjectId')->with($project->id)->andReturn(collect(['tasks']));
    //
    //    $controller = new TasksController();
    //    $response = $controller->getTasksByProject($project, $this->service);
    //
    //    $this->assertInstanceOf(JsonResponse::class, $response);
    //    $this->assertEquals($project->name, $response->getData()->listName);
    //    $this->assertEquals(['tasks'], $response->getData()->tasks);
    //}


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

}
