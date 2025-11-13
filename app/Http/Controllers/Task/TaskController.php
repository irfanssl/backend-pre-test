<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStatusRequest;

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
    public function update($task, TaskRequest $request, TaskService $taskService){
        $task = Task::find($task);
        if($task === null){
            return response()->json([
                'status' => 'Fail',
                'message' => 'Task not found',
                'data' => null
            ], 404);
        }
        $data = $request->validated();
        $updater = $request->user();
        $task = $task->load('status');
        $taskService = $taskService->updateTask($task, $data, $updater);
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Update Task',
            'data' => $taskService
        ]);
    }
    public function updateStatus($task, TaskStatusRequest $taskStatusRequest, TaskService $taskService){
        $task = Task::find($task)->load('status');
        if($task === null){
            return response()->json([
                'status' => 'Fail',
                'message' => 'Task not found',
                'data' => null
            ], 404);
        }
        $updater = $taskStatusRequest->user();
        $statusId = $taskStatusRequest->status_id;
        $taskService = $taskService->changeStatus($task, $statusId, $updater);
        return response()->json([
            'status' => 'Success',
            'message' => 'Success update task status',
            'data' => $taskService
        ]);
    }
    public function updateReport(){
        return 'update report';
    }
    public function show(){
        return 'show';
    }
}
