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
        $I->click('Updated Name');
        $I->waitForElement('span.project-edit',4);
        $I->click('span.project-edit');
        $I->waitForText('Edit Project', 4);
        $I->seeInField('Name', 'Updated Name');
        $I->seeInField('Description', 'New Description');
        $I->seeInField('Due Date', Carbon::today()->toDateString());
    }
}
