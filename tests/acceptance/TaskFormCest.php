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
        $project = $I->haveAProject($user, 'Project One');
        $I->login($user->email);
        $I->click('Today');

        // Edit task data
        $I->waitForElement('#task-form-container', 4);

        // Title
        $I->fillField('task-title', 'New task title');
        // Due Date
        $I->click('#task-due-date');
        $I->waitForElement('th.today', 4);
        $I->click('th.today');
        // Tags
        $I->fillField('Tags', '@foo, bar,');
        // Project
        $I->waitForElement('#task-project', 4);
        $I->selectOption('Project', 'Project One');
        // Priority
        $I->selectOption('Priority', 'Medium');
        // Details
        $I->fillField('task-details', 'New task details');

        $I->click('Save Task');
        $I->wait(1);
        $I->dontSee('Save Task');

        $I->seeRecord('tasks', [
            'title' => 'New task title',
            'user_id' => $user->id,
            'due_date' => Carbon::today()->toDateString(),
            'project_id' => $project->id,
            'priority' => '2',
            'details' => 'New task details',
            'complete' => 0,
            'next' => 0,
        ]);
        $I->seeRecord('tags', [
            'name' => '@foo',
            'user_id' => $user->id,
        ]);
        $I->seeRecord('tags', [
            'name' => 'bar',
            'user_id' => $user->id,
        ]);
    }

    public function it_populates_the_tags_field_with_existing_tags(AcceptanceTester $I)
    {
        $I->wantToTest('Task form populates existing tags');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $task = $I->haveTasksWithTags(1, $user, ['@foo', 'bar'], null, ['due_date' => Carbon::today()->toDateString()])->first();

        $I->login($user->email);
        $I->click('#scheduled-today');
        $I->waitForText($task->title, 4);
        $I->click($task->title);
        $I->wait(1);
        $I->see('@foo', '.token-label');
        $I->see('bar', '.token-label');
    }

    public function it_updates_a_task(AcceptanceTester $I)
    {
        $I->wantTo('Update a task');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $I->haveTasksWithTags(1, $user, ['@foo', 'bar'], null, ['due_date' => Carbon::today()->toDateString()]);
        putenv('DISABLE_GLOBAL_SCOPES=true');
        $task = Task::with('project')->withoutGlobalScopes()->first();

        $I->login($user->email);
        $I->click('#scheduled-today');
        $I->waitForText($task->title, 4);
        $I->click($task->title);

        // Edit task data

        // Title
        $I->fillField('task-title', 'Updated title');
        // Project
        $I->selectOption('Project', '-- None --');
        // Tags
        $I->executeJS('$("#task-tagsinput").tokenfield("setTokens", "@foo,@baz,bop")');
        // Priority
        $I->selectOption('Priority', 'High');
        // Details
        $I->fillField('task-details', 'Updated details');

        $I->click('Save Task');

        // Verify Changes
        $I->waitForText('Updated title', 4);
        $I->wait(1);
        $I->dontSee('Save Task');

        $I->seeRecord('tasks', [
            'title' => 'Updated title',
            'user_id' => $user->id,
            'due_date' => Carbon::today()->toDateString(),
            'priority' => '3',
            'details' => 'Updated details'
        ]);
        $I->seeRecord('tags', [
            'name' => '@foo',
            'user_id' => $user->id,
        ]);
        $I->seeRecord('tags', [
            'name' => '@baz',
            'user_id' => $user->id,
        ]);
        $I->seeRecord('tags', [
            'name' => 'bop',
            'user_id' => $user->id,
        ]);
    }

    public function it_deletes_a_task(AcceptanceTester $I)
    {
        $I->wantTo('Delete a task');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $task = $I->haveTasks($user, 1, null, ['due_date' => Carbon::today()->toDateString()])->first();

        $I->login($user->email);
        $I->waitForText($task->title, 4);
        $I->click($task->title);

        // Delete task data
        $I->waitForText('Delete', 4, '#delete-task-button');
        $I->click('Delete');
        // See and confirm delete modal
        $I->waitForElement('.sweet-alert.visible', 4);
        $I->click('button.confirm');

        $I->wait(1);
        $I->dontSeeRecord('tasks', ['title' => $task->title]);
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
