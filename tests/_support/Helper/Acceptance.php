<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    public function _beforeSuite($settings = []) {
        $this->cli = $this->getModule('Cli');
        $this->cli->runShellCommand('cp .env.testing .env');
        $this->cli->runShellCommand('php artisan migrate');
    }
    public function _afterSuite() {
        //$this->cli->runShellCommand('ssh vagrant@127.0.0.1 -p 2222` cd ~/Code/todomebaby` mysql -u homestead -p' . getenv('DB_PASSWORD') . ' todomebaby-test < database/db_dump.sql &> /dev/null');
        $this->cli->runShellCommand('cp .env.production .env');
    }
}
