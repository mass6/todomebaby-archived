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
        $user = $I->haveAnAccount();
        $project = $I->haveAProject($user, 'Project One');
        $tags = $I->haveTasksWithTags(1, $user, ['@foo', 'bar'], $project, ['priority' => 'high'])->first()->tags;
        putenv('DISABLE_GLOBAL_SCOPES=true');
        $task = Task::with('project', 'tags')->withoutGlobalScopes()->first();

        $I->login($user->email);

        $I->click('Later');
        $I->waitForText('Later',4, '.list-heading');
        $I->waitForText($task->title, 4);
        $I->see('Project One', '.project-link');
        //$I->see($task->priority, 'a.dropdown-toggle');
        $I->seeElement('i.priority-flag.text-danger');
        $I->see($tags[0]->name, '.tag-selectable');
        $I->see('#' . $tags[1]->name, '.tag-selectable');
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
        $tasks->last()->setDueDate(Carbon::tomorrow()->toDateString());

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
        $tasks[0]->setDueDate(Carbon::tomorrow()->toDateString());
        $tasks[1]->setDueDate(Carbon::tomorrow()->toDateString());
        // set 1 task due today
        $tasks[2]->setDueDate(Carbon::today()->toDateString());
        // set 1 task due later
        $tasks[3]->setDueDate(Carbon::today()->addDays(10)->toDateString());

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
        $tasks[0]->setDueDate(Carbon::today()->startOfWeek()->toDateString());
        $tasks[1]->setDueDate(Carbon::today()->endOfWeek()->toDateString());
        // set 1 task due next week
        $tasks[2]->setDueDate(Carbon::today()->addDays(7)->toDateString());

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
        $task = $I->haveTasks($user, 1, null, ['due_date' => Carbon::today()->endOfWeek()->addDays(1)->toDateString()])->first();

        $I->login($user->email, 'secret');
        $I->waitForText('Today',4, '.list-heading');
        $I->dontSee($task->title);

        $I->click('Next Week');
        $I->waitForText($task->title, 4);
    }
    /**
     * @param AcceptanceTester $I
     */
    public function it_displays_tasks_due_later(AcceptanceTester $I)
    {
        $I->wantTo('View tasks due later');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->login($user->email, 'secret');

        $tasks = factory(Task::class, 5)->make(['complete' => false ]);
        $user->tasks()->saveMany($tasks);

        // set 1 task due later (> next week)
        $tasks[0]->setDueDate(Carbon::today()->addDays(15)->toDateString());

        // set 1 task without a no due date
        $tasks[1]->setDueDate(null);

        // set 2 tasks due today
        $tasks[2]->setDueDate(Carbon::today()->toDateString());
        $tasks[3]->setDueDate(Carbon::today()->toDateString());

        $I->click('Later');
        $I->waitForText('Later',4, '.list-heading');
        $I->waitForText($tasks[0]->title, 4);
        $I->see($tasks[1]->title);
        $I->dontSee($tasks[2]->title);
        $I->dontSee($tasks[3]->title);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_finds_tasks_by_tag(AcceptanceTester $I)
    {
        $I->wantTo('Find tasks by Tag');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $tasks = $I->haveTasksWithTags(3, $user, ['@bazz'], null, ['due_date' => Carbon::tomorrow()->toDateString()]);
        $tasks->first()->setDueDate(Carbon::today()->toDateString());

        $I->login($user->email);
        $I->waitForText($tasks->first()->title);
        $I->dontSee($tasks[1]->title);
        $I->dontSee($tasks[2]->title);
        $I->see('@bazz', '.tag-selectable');
        $I->click('@bazz');

        $I->waitForText('@bazz', 4, '.list-heading');
        $I->see($tasks->first()->title);
        $I->see($tasks[1]->title);
        $I->see($tasks[2]->title);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_finds_tasks_by_project_link(AcceptanceTester $I)
    {
        // Given I have a project with 3 associated tasks, 1 of which are due today
        $I->wantTo('Find tasks by Project link');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $project = $I->haveAProject($user, 'Project One');
        $tasks = $I->haveTasks($user, 3, $project);
        $tasks->first()->setDueDate(Carbon::today()->toDateString());

        // When I login and click the project link next to the first task in the task list
        $I->login($user->email);
        $I->waitForText($tasks->first()->title);
        $I->dontSee($tasks[1]->title);
        $I->see($project->name, '.project-link');
        $I->click($project->name);

        $I->waitForText($project->name, 4, '.list-heading');
        $I->see($tasks[0]->title);
        $I->see($tasks[1]->title);
        $I->see($tasks[2]->title);
    }
}
