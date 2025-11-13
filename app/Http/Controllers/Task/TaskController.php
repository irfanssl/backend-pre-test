<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(TaskRequest $request, TaskService $task){
        $data = $request->validated();
        $creator = $request->user();
        $task = $task->createTask($data, $creator);
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Create Task',
            'data' => $task
        ]);
    }
    public function update(){
        return 'update';
    }
    public function updateStatus(){
        return 'update status';
    }
    public function updateReport(){
        return 'update report';
    }
    public function show(){
        return 'show';
    }
}
