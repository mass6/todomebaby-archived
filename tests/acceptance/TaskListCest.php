<?php

use App\Project;
use App\Task;
use App\UserScope;
use \Carbon\Carbon;

class TaskListCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->runShellCommand('php artisan migrate:refresh');
    }

    public function _after(AcceptanceTester $I)
    {
        $I->runShellCommand('php artisan migrate:reset');
    }

    // tests

    /**
     * @param AcceptanceTester $I
     * @group today
     */
    public function it_displays_the_specified_task_data(AcceptanceTester $I)
    {
        $I->wantToTest('Task list displays all specified task data');
        $I->am('Registered User');

        $project = $I->haveAProject($user = $I->loginAsARegisteredUser(), 'Project One');
        $tasks = $I->haveTasks($user, 1, $project);
        putenv('DISABLE_GLOBAL_SCOPES=true');
        $task = Task::with('project')->withoutGlobalScopes()->first();

        $I->click('Future');
        $I->waitForText('Future',4, '.list-heading');
        $I->waitForText($task->title, 4);
        $I->see('Project One', '.project-link');
        $I->see($task->priority, 'a.dropdown-toggle');
    }

    /**
     * @param AcceptanceTester $I
     * @group today
     */
    public function it_displays_tasks_due_today(AcceptanceTester $I)
    {
        $I->wantTo('View tasks due today');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $tasks = $I->haveTasks($user, 3, null, ['due_date' => Carbon::today()->toDateString()]);
        $tasks->last()->due_date = Carbon::tomorrow()->toDateString();
        $tasks->last()->save();

        $I->login($user->email);
        $I->click('#scheduled-today');
        $I->waitForText('Today',4, '.list-heading');
        $I->waitForText($tasks->first()->title, 4);
        $I->see($tasks[0]->title);
        $I->see($tasks[1]->title);
        $I->dontSee($tasks[2]->title);
    }


    /**
     * @param AcceptanceTester $I
     */
    public function it_displays_tasks_due_tomorrow(AcceptanceTester $I)
    {
        $I->wantTo('View tasks due tomorrow');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $tasks = factory(Task::class, 5)->make(['complete' => false ]);
        $user->tasks()->saveMany($tasks);
        $I->login($user->email, 'secret');

        // set 2 tasks due tomorrow
        $tasks[0]->due_date = Carbon::tomorrow()->toDateString();
        $tasks[1]->due_date = Carbon::tomorrow()->toDateString();
        // set 1 task due today
        $tasks[2]->due_date = Carbon::today()->toDateString();
        // set 1 task due in future
        $tasks[3]->due_date = Carbon::today()->addDays(10)->toDateString();

        $tasks[0]->save();
        $tasks[1]->save();
        $tasks[2]->save();
        $tasks[3]->save();

        $I->click('Tomorrow');
        $I->waitForText('Tomorrow',4, '.list-heading');
        $I->waitForText($tasks[0]->title);
        $I->see($tasks[1]->title);
        $I->dontSee($tasks[2]->title);
        $I->dontSee($tasks[3]->title);
        $I->dontSee($tasks[4]->title);
    }
    /**
     * @param AcceptanceTester $I
     */
    public function it_displays_tasks_due_this_week(AcceptanceTester $I)
    {
        $I->wantTo('View tasks due this week');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->login($user->email, 'secret');

        $tasks = factory(Task::class, 5)->make(['complete' => false ]);
        $user->tasks()->saveMany($tasks);

        // set 2 tasks due this week
        $tasks[0]->due_date = Carbon::today()->startOfWeek()->toDateString();
        $tasks[1]->due_date = Carbon::today()->endOfWeek()->toDateString();
        // set 1 task due next week
        $tasks[2]->due_date = Carbon::today()->addDays(7)->toDateString();

        $tasks[0]->save();
        $tasks[1]->save();
        $tasks[2]->save();

        $I->click('This Week');
        $I->waitForText('This Week',4, '.list-heading');
        $I->waitForText($tasks[0]->title, 4);
        $I->waitForText($tasks[1]->title, 4);
        $I->dontSee($tasks[2]->title);
    }
    /**
     * @param AcceptanceTester $I
     */
    public function it_displays_tasks_due_next_week(AcceptanceTester $I)
    {
        $I->wantTo('View tasks due next week');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->login($user->email, 'secret');

        $tasks = factory(Task::class, 5)->make(['complete' => false ]);
        $user->tasks()->saveMany($tasks);

        // set 2 tasks due next week
        $tasks[0]->due_date = Carbon::today()->endOfWeek()->addDays(1)->toDateString();
        $tasks[1]->due_date = Carbon::today()->endOfWeek()->addDays(7)->toDateString();
        // set 2 tasks due this week
        $tasks[2]->due_date = Carbon::today()->startOfWeek()->toDateString();
        $tasks[3]->due_date = Carbon::today()->endOfWeek()->toDateString();

        // set 1 task due in future
        $tasks[4]->due_date = Carbon::today()->addDays(15)->toDateString();

        $tasks[0]->save();
        $tasks[1]->save();
        $tasks[2]->save();
        $tasks[3]->save();
        $tasks[4]->save();

        $I->click('Next Week');
        $I->waitForText('Next Week',4, '.list-heading');
        $I->waitForText($tasks[0]->title, 4);
        $I->see($tasks[1]->title);
        $I->dontSee($tasks[2]->title);
        $I->dontSee($tasks[3]->title);
        $I->dontSee($tasks[4]->title);
    }
    /**
     * @param AcceptanceTester $I
     */
    public function it_displays_tasks_due_in_future(AcceptanceTester $I)
    {
        $I->wantTo('View tasks due in future');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->login($user->email, 'secret');

        $tasks = factory(Task::class, 5)->make(['complete' => false ]);
        $user->tasks()->saveMany($tasks);

        // set 1 task due in future (> next week)
        $tasks[0]->due_date = Carbon::today()->addDays(15)->toDateString();

        // set 1 task without a no due date
        $tasks[1]->due_date = null;

        // set 2 tasks due today
        $tasks[2]->due_date = Carbon::today()->toDateString();
        $tasks[3]->due_date = Carbon::today()->toDateString();

        $tasks[0]->save();
        $tasks[1]->save();
        $tasks[2]->save();
        $tasks[3]->save();

        $I->click('Future');
        $I->waitForText('Future',4, '.list-heading');
        $I->waitForText($tasks[0]->title, 4);
        $I->see($tasks[1]->title);
        $I->dontSee($tasks[2]->title);
        $I->dontSee($tasks[3]->title);
    }
}
