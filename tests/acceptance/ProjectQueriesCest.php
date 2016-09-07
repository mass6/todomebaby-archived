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
    public function tryToTest(AcceptanceTester $I)
    {
    }
}
