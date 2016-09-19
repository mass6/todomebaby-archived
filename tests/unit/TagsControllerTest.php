<?php

use App\Http\Controllers\TagsController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\JsonResponse;
use \Mockery as m;

class TagsControllerTest extends TestCase
{

    use DatabaseMigrations;

    private $service;


    public function __construct()
    {
        $this->service = m::mock('App\Services\TagService');
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_retrieves_tasks_by_tag()
    {
        $mTag = m::mock('App\Tag');
        $mTag->shouldReceive('getAttribute')->with('name')->once()->andReturn('foo');
        $mService = m::mock('App\Services\TaskService');
        $mService->shouldReceive('findByTag')->with($mTag)->once()->andReturn('tasks');

        $controller = new TagsController;
        $response = $controller->getTasksByTag($mTag, $mService);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('foo', $response->getData()->listName);
        $this->assertEquals('tasks', $response->getData()->tasks);
    }

    /**
     * @test
     */
    public function testGetContexts()
    {
        $this->service->shouldReceive('getContexts')->once()->andReturn('contexts');
        $controller = new TagsController;
        $response = $controller->getContexts($this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('contexts', $response->getData());
    }

    /**
     * @test
     */
    public function testGetTagSuggestions()
    {
        $this->service->shouldReceive('getSuggestions')->once()->with('query')->andReturn('suggestions');
        $controller = new TagsController;
        $response = $controller->getTagSuggestions('query', $this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('suggestions', $response->getData());
    }

}
