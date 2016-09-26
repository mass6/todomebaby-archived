<?php

use Carbon\Carbon;

class ProjectFormCest
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
    public function it_adds_a_new_project(AcceptanceTester $I)
    {
        $I->wantTo('Add a project');
        $I->am('Registered User');
        $I->loginAsARegisteredUser();

        $I->waitForElement('#add-project', 4);
        $I->click('#add-project');
        $I->waitForText('New Project', 4);

        $I->fillField('Name', 'Project One Name');
        $I->fillField('Description', 'Project One Description');
        $I->click('Save Project');

        $I->waitForText('Project One Name', 4, '.project-link' );
        $I->waitForText('Project One Name', 4, '.list-heading' );
    }


    public function it_updates_a_project(AcceptanceTester $I)
    {
        $I->wantTo('Update a project');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $project = $I->haveAProject($user, 'Project One');
        $I->login($user->email);

        $I->waitForText('Project One', 4, '.project-link');
        $I->click('Project One');
        $I->waitForElement('span.project-edit',4);
        $I->click('span.project-edit');
        $I->waitForText('Edit Project', 4);
        $I->seeInField('Name', 'Project One');
        $I->seeInField('Description', $project->description);
        $I->seeInField('Due Date', $project->due_date);

        // Update Project
        $I->fillField('Name', 'Updated Name');
        $I->fillField('Description', 'New Description');
        $I->click('#project-due-date');
        $I->waitForElement('.picker__button--today', 4);
        $I->click('.picker__button--today');
        $I->click('Save Project');

        // Verify changes
        $I->waitForText('Updated Name', 4, '.project-link' );
        $I->seeRecord('projects', [
            'name' => 'Updated Name',
            'description' => 'New Description',
            'due_date' => Carbon::today()->toDateString(),
        ]);
    }


    public function it_deletes_a_project(AcceptanceTester $I)
    {
        // Given I have a project with 1 task
        $I->wantTo('Delete a project');
        $I->am('Registered User');
        $user = $I->haveAnAccount();
        $project = $I->haveAProject($user, 'Project One');
        $task = $I->haveTasks($user, 1, $project, ['due_date' => Carbon::today()->toDateString()])->first();

        // When I login and go to the project edit page
        $I->login($user->email);
        $I->waitForText($project->name, 4, '.project-link');
        $I->click($project->name);
        $I->waitForElement('span.project-edit',4);
        $I->click('span.project-edit');
        $I->waitForText('Edit Project', 4);

        // And I delete the project
        $I->waitForText('Delete', 4, '#delete-project-button');
        $I->click('Delete');
        $I->waitForElement('.sweet-alert.visible', 4);
        $I->click('button.confirm');

        // Then the project and associatd task should be deleted
        $I->wait(1);
        $I->dontSeeRecord('projects', ['name' => $project->name]);
        $I->dontSeeRecord('tasks', ['title' => $task->title]);
    }
}
