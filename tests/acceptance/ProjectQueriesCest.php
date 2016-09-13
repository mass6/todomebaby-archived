<?php


class ProjectQueriesCest
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
    public function it_displays_open_tasks_for_selected_project(AcceptanceTester $I)
    {
        // given I have a project name "Project One" with 2 active tasks
        $I->wantTo('View all open tasks for a selected project');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $project = $I->haveAProject($user, 'Project One');
        $tasks = $I->haveTasks($user, 2, $project);

        // when I click Project One
        $I->login($user->email);
        $I->click('Today');
        $I->waitForText($project->name, 4);
        $I->click($project->name);

        // Then I should see two projects listed for Project one
        $I->waitForText($project->name, 4, '.list-heading');
        $I->waitForText($tasks[0]->title, 4);
        $I->waitForText($tasks[1]->title, 4);
    }
}
