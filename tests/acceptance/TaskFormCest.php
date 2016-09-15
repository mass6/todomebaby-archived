<?php

use App\Task;
use Carbon\Carbon;

class TaskFormCest
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
    public function it_adds_a_new_task(AcceptanceTester $I)
    {
        $I->wantTo('Add a task');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->haveAProject($user, 'Project One');
        $I->login($user->email);
        $I->click('Today');
        $I->waitForElement('#task-form-container', 4);
        $I->fillField('task-title', 'New task title');
        $I->click('#task-due-date');
        $I->waitForElement('.picker__button--today', 4);
        $I->click('.picker__button--today');
        $I->waitForElement('#task-project', 4);
        $I->selectOption('Project', 'Project One');
        $I->selectOption('Priority', 'Medium');
        $I->fillField('task-details', 'New task details');
        $I->click('Save Task');

        $I->waitForText('New task title', 4, '.task-title');
        $I->see('(Project One)', 'span.project-link');
        $I->wait(1);
        $I->dontSee('Save Task');
    }

    public function it_updates_a_task(AcceptanceTester $I)
    {
        $I->wantTo('Update a task');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->haveTasks($user, 1, null, ['due_date' => Carbon::today()->toDateString()]);
        putenv('DISABLE_GLOBAL_SCOPES=true');
        $task = Task::with('project')->withoutGlobalScopes()->first();

        $I->login($user->email);
        $I->click('#scheduled-today');
        $I->waitForText($task->title, 4);
        $I->click($task->title);

        // Edit task data
        $I->fillField('task-title', 'Updated title');
        $I->selectOption('Project', '-- None --');
        $I->selectOption('Priority', 'High');
        $I->fillField('task-details', 'Updated details');
        $I->click('Save Task');

        // Verify Changes
        $I->waitForText('Updated title', 4);
        $I->wait(1);
        $I->dontSee('Save Task');
    }

    public function it_resets_the_task_form(AcceptanceTester $I)
    {
        $I->wantToTest('Reset Form button');
        $I->am('Registered User');
        $I->amLoggedInAsARegisteredUser();

        $I->click('Today');
        $I->waitForElement('#task-form-container', 4);
        $I->fillField('task-title', 'New Task Title');
        $I->see('Save Task');
        $I->click('Cancel');

        $I->wait(1);
        $I->dontSee('Save Task');
        $I->dontSee('New Task Title');
    }
}
