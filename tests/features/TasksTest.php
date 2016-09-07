<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TasksTest extends TestCase
{

    use DatabaseMigrations;


    /**
     * Test a user can view his pending tasks
     *
     * @test
     */
    public function it_lists_all_pending_tasks()
    {
        //$tasks = factory(App\Task::class, 5)->create(['complete' => false ]);

        //$this->visit('/web/lists/all')->see('All Tasks')->see($tasks[0]->title)->see($tasks[1]->title)->see($tasks[2]->title)->see($tasks[3]->title)->see($tasks[4]->title);
    }
}
