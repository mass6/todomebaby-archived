<?php

use Carbon\Carbon;

class SidebarCest
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

    public function it_displays_a_link_to_the_inbox(AcceptanceTester $I)
    {
        // given I have 2 active projects and 1 inactive project
        $I->wantToTest('I can see a link to the inbox in sidebar');
        $I->am('Registered User');

        // when I am at the webapp home page
        $I->loginAsARegisteredUser();

        // then I should only see links to each active project
        $I->waitForText('Inbox',4, '#inbox');
    }

    public function it_displays_open_task_counts_for_the_inbox(AcceptanceTester $I)
    {
        // given I have 3 open tasks, 2 of which are not assigned to any project
        $I->wantToTest('I can see task counts for the inbox');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $tasks = $I->haveTasks($user, 2);

        // when I am at the webapp home page
        $I->login($user->email);

        // then I should see open task counts next to each active project
        $I->waitForText('Inbox',4, '#inbox');
        $I->waitForText('2', 4,'#task-count-inbox');
    }

    public function it_displays_a_link_to_next_tasks(AcceptanceTester $I)
    {
        // given I have 2 active projects and 1 inactive project
        $I->wantToTest('I can see a link to next tasks in sidebar');
        $I->am('Registered User');

        // when I am at the webapp home page
        $I->loginAsARegisteredUser();

        // then I should only see links to each active project
        $I->waitForText('Next',4, '#next');
    }

    public function it_displays_open_task_counts_for_next_tasks(AcceptanceTester $I)
    {
        // given I have 3 open tasks, 2 of which are not assigned to any project
        $I->wantToTest('I can see task counts for next tasks');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->haveTasks($user, 2, null, ['next' => true]);

        // when I am at the webapp home page
        $I->login($user->email);

        // then I should see open task counts next to each active project
        $I->waitForText('Next',4, '#next');
        $I->waitForText('2', 4,'#task-count-next');
    }

    public function it_displays_links_to_all_active_projects(AcceptanceTester $I)
    {
        // given I have 2 active projects and 1 inactive project
        $I->wantToTest('I can see links to all active projects in sidebar');
        $I->am('Registered User');
        $projects = $I->haveProjects($user = $I->haveAnAccount(), 3);
        $projects->last()->update(['active' => false]);

        // when I am at the webapp home page
        $I->login($user->email);

        // then I should only see links to each active project
        $I->waitForText($projects[0]->name,4, 'a.project-link');
        $I->see($projects[1]->name, 'a.project-link');
        $I->dontSee($projects[2]->name. 'a.project-link');
    }


    public function it_displays_open_task_counts_for_each_projects(AcceptanceTester $I)
    {
        // given I have 2 active projects with 2 open tasks in one, and 3 active tasks in the other
        $I->wantToTest('I can see task counts for each active project in sidebar');
        $I->am('Registered User');
        $projects = $I->haveProjects($user = $I->haveAnAccount(), 2);
        $tasks = $I->haveTasks($user, 3, $projects->first());
        $tasks->first()->toggleComplete();
        $tasks = $I->haveTasks($user, 4, $projects->last());
        $tasks->first()->toggleComplete();

        //putenv('DISABLE_GLOBAL_SCOPES=true');
        //var_dump($user->tasks->toArray());

        // when I am at the webapp home page
        $I->login($user->email);

        // then I should see open task counts next to each active project
        $I->waitForText($projects[0]->name, 4);
        $I->see(2, '#project-' . $projects[0]->id . '-task-count');
        $I->see(3, '#project-' . $projects[1]->id . '-task-count');
    }


    public function it_displays_links_to_all_contexts(AcceptanceTester $I)
    {
        // given I have a context named @foo
        $I->wantToTest('I can see links to contexts in sidebar');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->haveTags($user, ['@foo']);

        // when I am at the webapp home page
        $I->login($user->email);

        // then I should only see links to each active project
        $I->waitForText('Contexts');
        $I->click('Contexts');
        $I->waitForText('@foo', 4, 'a.context-link');
    }



    public function it_displays_open_task_counts_for_each_context(AcceptanceTester $I)
    {
        // given I have 2 open tasks with @foo context
        $I->wantToTest('I can see task counts for each context in sidebar');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $tasks = $I->haveTasksWithTags(2, $user, ['@foo']);

        // when I am at the webapp home page
        $I->login($user->email);

        // then I should see open task counts next to @foo context
        $I->waitForText('Contexts');
        $I->click('Contexts');
        $I->waitForText('@foo', 4, 'a.context-link');
        $I->see(2, '#context-' . $tasks->first()->tags->first()->id . '-task-count');
    }

    public function it_displays_number_of_open_tasks_due_today(AcceptanceTester $I)
    {
        // given I have 3 tasks due today, 2 of which are still open
        $I->wantToTest('I can see number of tasks due today in sidebar');
        $I->am('Registered User');
        $tasks = $I->haveTasks($user = $I->haveAnAccount(), 3);

        $tasks[0]->due_date = Carbon::today()->toDateString();
        $tasks[0]->save();
        $tasks[1]->due_date = Carbon::today()->toDateString();
        $tasks[1]->save();
        $tasks[2]->due_date = Carbon::today()->toDateString();
        $tasks[2]->toggleComplete();

        // when I am at the webapp home page
        $I->login($user->email);
        $I->waitForElement('#task-count-today', 4);
        $I->see(2, '#task-count-today');
    }

    public function it_displays_number_of_open_tasks_due_tomorrow(AcceptanceTester $I)
    {
        // given I have 3 tasks due tomorrow, 2 of which are still open
        $I->wantToTest('I can see number of tasks due tomorrow in sidebar');
        $I->am('Registered User');
        $tasks = $I->haveTasks($user = $I->haveAnAccount(), 3);

        $tasks[0]->due_date = Carbon::tomorrow()->toDateString();
        $tasks[0]->save();
        $tasks[1]->due_date = Carbon::tomorrow()->toDateString();
        $tasks[1]->save();
        $tasks[2]->due_date = Carbon::tomorrow()->toDateString();
        $tasks[2]->toggleComplete();

        // when I am at the webapp home page
        $I->login($user->email);
        $I->waitForElement('#task-count-tomorrow', 4);
        $I->see(2, '#task-count-tomorrow');
    }

    public function it_displays_number_of_open_tasks_due_this_week(AcceptanceTester $I)
    {
        // given I have 3 tasks due this week, 2 of which are still open
        $I->wantToTest('I can see number of tasks due this week in sidebar');
        $I->am('Registered User');
        $tasks = $I->haveTasks($user = $I->haveAnAccount(), 3);

        $tasks[0]->due_date = Carbon::today()->startOfWeek()->toDateString();
        $tasks[0]->save();
        $tasks[1]->due_date = Carbon::today()->startOfWeek()->toDateString();
        $tasks[1]->save();
        $tasks[2]->due_date = Carbon::today()->startOfWeek()->toDateString();
        $tasks[2]->toggleComplete();

        // when I am at the webapp home page
        $I->login($user->email);
        $I->waitForElement('#task-count-this-week', 4);
        $I->see(2, '#task-count-this-week');
    }

    public function it_displays_number_of_open_tasks_due_next_week(AcceptanceTester $I)
    {
        // given I have 3 tasks due next week, 2 of which are still open
        $I->wantToTest('I can see number of tasks due next week in sidebar');
        $I->am('Registered User');
        $tasks = $I->haveTasks($user = $I->haveAnAccount(), 3);

        $tasks[0]->due_date = Carbon::today()->endOfWeek()->addDays(2)->toDateString();
        $tasks[0]->save();
        $tasks[1]->due_date = Carbon::today()->endOfWeek()->addDays(2)->toDateString();
        $tasks[1]->save();
        $tasks[2]->due_date = Carbon::today()->endOfWeek()->addDays(2)->toDateString();
        $tasks[2]->toggleComplete();

        // when I am at the webapp home page
        $I->login($user->email);
        $I->waitForElement('#task-count-next-week', 4);
        $I->see(2, '#task-count-next-week');
    }

    public function it_displays_number_of_open_tasks_due_in_later(AcceptanceTester $I)
    {
        // given I have 3 tasks due later, 2 of which are still open
        $I->wantToTest('I can see number of tasks due later in sidebar');
        $I->am('Registered User');
        $tasks = $I->haveTasks($user = $I->haveAnAccount(), 3);

        $tasks[0]->due_date = Carbon::today()->addDays(20)->toDateString();
        $tasks[0]->save();
        $tasks[2]->due_date = Carbon::today()->addDays(20)->toDateString();
        $tasks[2]->toggleComplete();

        // when I am at the webapp home page
        $I->login($user->email);
        $I->waitForElement('#task-count-later', 4);
        $I->see(2, '#task-count-later');
    }

    public function it_updates_scheduled_task_counts_after_task_is_saved(AcceptanceTester $I)
    {
        // given I have 2 tasks due in today, 1 tasks due tomorrow
        $I->wantToTest('Scheduled task counts in sidebar reflect changed made after task is saved');
        $I->am('Registered User');
        $tasks = $I->haveTasks($user = $I->haveAnAccount(), 3);

        $tasks[0]->due_date = Carbon::today()->toDateString();
        $tasks[0]->save();
        $tasks[1]->due_date = Carbon::today()->toDateString();
        $tasks[1]->save();
        $tasks[2]->due_date = Carbon::tomorrow()->toDateString();
        $tasks[2]->save();

        // when I set the due date of the last task for today
        $I->login($user->email);
        $I->click('Tomorrow');
        $I->waitForText($tasks[2]->title, 4);
        $I->click($tasks[2]->title);
        $I->waitForElement('#task-form-container', 4);
        $I->click('#task-due-date');
        $I->click('th.today');
        $I->click('Save Task');

        // then I see that the todays count is 3, and tomorrow's count is no longer visible
        $I->wait(2);
        $I->see(3, '#task-count-today');
        $I->dontSeeElement('#task-count-tomorrow');
    }


    public function it_updates_project_counts_after_task_is_saved(AcceptanceTester $I)
    {
        // given I have 1 project with 2 active tasks, and another project with 1 active task
        $I->wantToTest('Project task counts in sidebar reflect changed made after task is saved');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $project1 = $I->haveAProject($user, 'Project One');
        $project2 = $I->haveAProject($user, 'Project Two');
        $project1Tasks = $I->haveTasks($user, 2, $project1);
        $project2Tasks = $I->haveTasks($user, 1, $project2);

        // when I change the task's assigned project from Project One to Project Two
        $I->login($user->email);
        $I->waitForText($project2->name, 4);
        $I->click($project2->name);
        $I->waitForText($project2Tasks[0]->title, 4);
        $I->click($project2Tasks[0]->title);
        $I->selectOption('Project', $project1->name);
        $I->click('Save Task');

        // then I see that Project One's task count is 3, and Project Two's task count is no longer visible
        $I->wait(2);
        $I->see(3, '#project-' . $project1->id . '-task-count');
        $I->dontSeeElement('#project-' . $project2->name . '-task-count');
    }

}
