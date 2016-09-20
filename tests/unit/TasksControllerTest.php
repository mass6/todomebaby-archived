<?php

use App\Http\Controllers\TasksController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use \Mockery as m;

class TasksControllerTest extends TestCase
{

    use DatabaseMigrations;

    private $service;


    public function __construct()
    {
        $this->service = m::mock('App\Services\TaskService');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testStore()
    {
        $mockRequest = m::mock('Illuminate\Http\Request');
        $mockRequest->shouldReceive('all')->once()->andReturn(['title' => 'foo']);
        $this->service->shouldReceive('addTask')->with(['title' => 'foo'])->once()->andReturn('bar');

        $controller = new TasksController;
        $response = $controller->store($mockRequest, $this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('bar', $response->getData());
    }

    /**
     * @test
     */
    public function testUpdate()
    {
        $mTask = m::mock('App\Task');
        $mRequest = m::mock('Illuminate\Http\Request');
        $mRequest->shouldReceive('all')->once()->andReturn(['title' => 'foo']);
        $this->service->shouldReceive('updateTask')->with($mTask, ['title' => 'foo'])->once()->andReturn('bar');

        $controller = new TasksController;
        $response = $controller->update($mTask, $mRequest, $this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('bar', $response->getData());
    }

    /**
     * @test
     */
    public function testShow()
    {
        $this->service->shouldReceive('findById')->with(1)->andReturn(collect(['task']));
        $controller = new TasksController;
        $response = $controller->show(1, $this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(['task'], $response->getData());
    }

    /**
     * @test
     */
    public function testDestroy()
    {
        $mTask = m::mock('App\Task');
        $this->service->shouldReceive('deleteTask')->with($mTask)->andReturn(true);
        $controller = new TasksController;
        $response = $controller->destroy($mTask, $this->service);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function testGetTasksDueToday()
    {
        $mRequest = m::mock('Illuminate\Http\Request');
        $mRequest->shouldReceive('get')->once()->with('with-completed')->andReturn(true);
        $this->service->shouldReceive('getTasksDueToday')->once()->with(true)->andReturn(collect(['tasks']));
        $controller = new TasksController;
        $response = $controller->getTasksDueToday($this->service, $mRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('Today', $response->getData()->listName);
        $this->assertEquals(['tasks'], $response->getData()->tasks);
    }

    /**
     * @test
     */
    public function testGetTasksDueTomorrow()
    {
        $mRequest = m::mock('Illuminate\Http\Request');
        $mRequest->shouldReceive('get')->once()->with('with-completed')->andReturn(true);
        $this->service->shouldReceive('getTasksDueTomorrow')->once()->with(true)->andReturn(collect(['tasks']));
        $controller = new TasksController;
        $response = $controller->getTasksDueTomorrow($this->service, $mRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('Tomorrow', $response->getData()->listName);
        $this->assertEquals(['tasks'], $response->getData()->tasks);
    }
    /**
     * @test
     */
    public function testGetTasksDueThisWeek()
    {
        $mRequest = m::mock('Illuminate\Http\Request');
        $mRequest->shouldReceive('get')->once()->with('with-completed')->andReturn(true);
        $this->service->shouldReceive('getTasksDueThisWeek')->once()->with(true)->andReturn(collect(['tasks']));
        $controller = new TasksController;
        $response = $controller->getTasksDueThisWeek($this->service, $mRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('This Week', $response->getData()->listName);
        $this->assertEquals(['tasks'], $response->getData()->tasks);
    }
    /**
     * @test
     */
    public function testGetTasksDueNextWeek()
    {
        $mRequest = m::mock('Illuminate\Http\Request');
        $mRequest->shouldReceive('get')->once()->with('with-completed')->andReturn(true);
        $this->service->shouldReceive('getTasksDueNextWeek')->once()->with(true)->andReturn(collect(['tasks']));
        $controller = new TasksController();
        $response = $controller->getTasksDueNextWeek($this->service, $mRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('Next Week', $response->getData()->listName);
        $this->assertEquals(['tasks'], $response->getData()->tasks);
    }
    /**
     * @test
     */
    public function testGetTasksDueInFuture()
    {
        $mRequest = m::mock('Illuminate\Http\Request');
        $mRequest->shouldReceive('get')->once()->with('with-completed')->andReturn(true);
        $this->service->shouldReceive('getTasksDueInFuture')->once()->with(true)->andReturn(collect(['tasks']));
        $controller = new TasksController();
        $response = $controller->getTasksDueInFuture($this->service, $mRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('Future', $response->getData()->listName);
        $this->assertEquals(['tasks'], $response->getData()->tasks);
    }

    /**
     * @test
     */
    public function testScheduledTaskCounts()
    {
        $this->service->shouldReceive('tasksDueTodayCount')->once()->andReturn(2);
        $this->service->shouldReceive('tasksDueTomorrowCount')->once()->andReturn(2);
        $this->service->shouldReceive('tasksDueThisWeekCount')->once()->andReturn(2);
        $this->service->shouldReceive('tasksDueNextWeekCount')->once()->andReturn(2);
        $this->service->shouldReceive('tasksDueInFutureCount')->once()->andReturn(2);

        $controller = new TasksController;
        $response = $controller->getScheduledTaskCounts($this->service);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(2, $response->getData()->today);
        $this->assertEquals(2, $response->getData()->tomorrow);
        $this->assertEquals(2, $response->getData()->thisWeek);
        $this->assertEquals(2, $response->getData()->nextWeek);
        $this->assertEquals(2, $response->getData()->future);
    }

}
