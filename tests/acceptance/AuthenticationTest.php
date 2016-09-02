<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{

    use DatabaseMigrations;


    /**
     * A user must be logged in to access the app
     *
     * @test
     */
    public function it_denies_access_to_webapp_if_user_is_not_logged_in()
    {
        $user = factory(User::class)->create([]);

        $this->visit('/web')
            ->seePageIs('/login')
            ->see('Login to your account');
    }
    /**
     * Test user is redirected to main webapp page after login.
     *
     * @test
     * @filter current
     */
    public function it_redirects_user_to_webapp_after_login()
    {
        $user = factory(User::class)->create([]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type('secret', 'password')
            ->press('Sign in')
            ->seePageIs('/web');
    }
}
