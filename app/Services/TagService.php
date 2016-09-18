<?php

namespace App\Services;

use App\Tag;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class TagService
 * @package App\Services
 */
class TagService
{


    /**
     * @var Tag
     */
    private $tag;

    /**
     * @var User
     */
    private $user;


    /**
     * TaskService constructor.
     */
    public function __construct()
    {
        $this->user = Auth::user();
        $this->tag = new Tag;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    // Query Services

    public function getContexts()
    {
        return $this->tag->where('name', 'like', '@%')->get()
            ->map(function($tag){
                $tag->taskCount = $tag->openTasks()->count();
                return $tag;
            });
    }


}
