<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use App\Services\TaskService;
use App\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;

class TagsController extends Controller
{


    /**
     * @param Tag         $tag
     * @param TaskService $taskService
     *
     * @return mixed
     */
    public function getTasksByTag(Tag $tag, TaskService $taskService)
    {
        return response()->json(['listName' => $tag->name, 'tasks' => $taskService->findByTag($tag)]);
    }

    public function getContexts(TagService $tagService)
    {
        return response()->json($tagService->getContexts());
    }
}
