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

}
