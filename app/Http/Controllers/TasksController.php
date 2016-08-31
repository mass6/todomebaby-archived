<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;

class TasksController extends Controller
{
    public function all()
    {
        $tasks = Task::where('complete', false)->get();

        return view('tasks', ['tasks' => $tasks]);
    }
}
