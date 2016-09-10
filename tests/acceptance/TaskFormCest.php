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
        $I->haveAProject($I->loginAsARegisteredUser(), 'Project One');

        $I->click('Today');
        $I->waitForElement('#task-form-container', 4);
        $I->fillField('task-title', 'New task title');
        $I->click('#task-due-date');
        $I->click('Today');
        $I->waitForElement('#task-project', 4);
        $I->selectOption('Project', 'Project One');
        $I->selectOption('Priority', 'Medium');
        $I->fillField('task-details', 'New task details');
        $I->click('Save Task');

        $I->waitForElement('#task-title-selection-1', 4);
        $I->see('New Task Title', '.task-selectable');
        $I->see('(Project One)', 'span.project-link');
        $I->wait(1);
        $I->dontSee('Save Task');
    }

    //public function it_updates_a_task(AcceptanceTester $I)
    //{
    //    $I->wantTo('Update a task');
    //    $I->am('Registered User');
    //    $task = $I->haveTasks($I->haveAnAccount('johndoe@test.com'), 1)->first();
    //    //$I->loginAsARegisteredUser();
    //    $I->amOnPage('/web#/tasks/1/edit');
    //    $I->submitLoginForm('johndoe@test.com', 'secret');
    //    //$I->click('Today');
    //    $I->waitForText($task->title, 4);
    //}

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
