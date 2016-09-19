<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use App\Services\TaskService;
use App\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class TagsController
 * @package App\Http\Controllers
 */
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


    /**
     * Get tags flagged as context
     *
     * @param TagService $tagService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContexts(TagService $tagService)
    {
        return response()->json($tagService->getContexts());
    }


    /**
     * Get tags suggestion for TypeAhead
     *
     * @param            $query
     * @param TagService $tagService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagSuggestions($query, TagService $tagService)
    {
        return response()->json($tagService->getSuggestions($query));
    }
}
