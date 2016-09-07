<?php


class LoginCest
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
    public function it_logs_users_in(AcceptanceTester $I)
    {
        $I->wantTo('Login as registered user');
        $I->am('Registered User');
        $I->haveAnAccount('johndoe@test.com', 'John Doe');
        $I->login('johndoe@test.com', 'secret');

        $I->seeInCurrentUrl('/web');
    }
    public function it_does_not_allow_access_if_not_authentication_fails(AcceptanceTester $I)
    {
        $I->wantToTest('access is denied if not authentication fails');
        $I->am('Guest');

        $I->expect('Access will be denied with invalid credentials');
        $I->login('invalid@test.com', 'invalidpass');

        $I->dontSeeInCurrentUrl('/web');
        $I->seeCurrentUrlEquals('/login');
    }
    public function it_redirects_user_to_login_page_if_not_logged_in(AcceptanceTester $I)
    {
        $I->wantToTest('users are redirected to login page if not logged in');
        $I->am('not logged in');

        $I->expect('to be redirected to login page');
        $I->amOnPage('/web');

        $I->dontSeeInCurrentUrl('/web');
        $I->seeCurrentUrlEquals('/login');
    }

    public function it_shows_todays_tasklist_by_default_after_login(AcceptanceTester $I)
    {
        $I->wantToTest('users are redirected to todays tasklist after login');
        $I->amLoggedInAsARegisteredUser();

        $I->waitForText('Due Today', 4);
    }
}
