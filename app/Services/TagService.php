<?php

namespace App\Services;

use App\Tag;
use App\User;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Get all tags flagged as context
     *
     * @return mixed
     */
    public function getContexts()
    {
        return $this->tag->where('name', 'like', '@%')->orderBy('name', 'asc')->get()
            ->map(function($tag){
                $tag->taskCount = $tag->openTasks()->count();
                return $tag;
            });
    }


    /**
     * Get tags suggestions for TypeAhead
     *
     * @param $query
     *
     * @return mixed
     */
    public function getSuggestions($query)
    {
        return Tag::where('name', 'like', "%$query%")
            ->get()
            ->map(function($tag){
                return ['value' => $tag->name];
            });
    }

}
