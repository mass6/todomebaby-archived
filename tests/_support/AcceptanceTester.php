<?php
use App\Project;
use App\Task;
use App\User;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    public function haveAnAccount($email = 'johndoe@test.com', $name = 'John Doe')
    {
        return factory(User::class)->create([
            'email'     => $email,
            'name'      => $name
        ]);
    }

    public function login($email, $password)
    {
        $I = $this;
        $I->amOnPage('/login');
        $I->submitLoginForm($email, $password);
    }

    public function submitLoginForm($email, $password)
    {
        $I = $this;
        $I->submitForm('#loginForm', [
            'email' => $email,
            'password' => $password
        ]);
    }
    public function loginAsARegisteredUser($email = 'johndoe@test.com', $name = 'John Doe')
    {
        $I = $this;
        $user = $I->haveAnAccount($email, $name);
        $I->login($email, 'secret');

        return $user;
    }

    public function amLoggedInAsARegisteredUser($email = 'johndoe@test.com', $name = 'John Doe')
    {
        return $this->loginAsARegisteredUser($email, $name);
    }

    public function haveAProject($user, $name = "My Project")
    {
        return Project::create([
            'name' => $name,
            'user_id' => $user->id
        ]);
    }

    public function haveTasks($user, $amount = 5, Project $project = null, $overrides = [])
    {
        $tasks = collect([]);
        for ($i = 0; $i < $amount; $i++) {
            $data = factory(Task::class)->make($overrides);
            $data->user_id = $user->id;
            $data->project_id = $project ? $project->id : null;
            $tasks->push(Task::create($data->toArray()));
        }

        return $tasks;
    }
}
