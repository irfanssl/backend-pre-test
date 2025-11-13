<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskReportRequest;
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
        $task = Task::find($task);
        if($task === null){
            return response()->json([
                'status' => 'Fail',
                'message' => 'Task not found',
                'data' => null
            ], 404);
        }
        $task = $task->load('status');
        $updater = $taskStatusRequest->user();
        $statusId = $taskStatusRequest->status_id;
        $taskService = $taskService->changeStatus($task, $statusId, $updater);
        return response()->json([
            'status' => 'Success',
            'message' => 'Success update task status',
            'data' => $taskService
        ]);
    }
    public function updateReport($task, TaskReportRequest $taskReportRequest, TaskService $taskService){
        $task = Task::find($task);
        if($task === null){
            return response()->json([
                'status' => 'Fail',
                'message' => 'Task not found',
                'data' => null
            ], 404);
        }
        $report = $taskReportRequest->validated();
        $report = $report['report'];
        $user = $taskReportRequest->user();
        $taskService = $taskService->updateReport($task, $report, $user);
        return response()->json([
            'status' => 'Success',
            'message' => 'Success update task report',
            'data' => $taskService
        ]);
    }
    public function show($task){
        $task = Task::find($task);
        if($task === null){
            return response()->json([
                'status' => 'Fail',
                'message' => 'Task not found',
                'data' => null
            ], 404);
        }
        $task = $task->load(['status', 'creator', 'assignee']);
        $task = [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status_id' => $task->status_id,
            'status' => [
                'name' => $task->status->name
            ],
            "creator_id" => $task->creator_id,
            "creator" => [
                "name"=> $task->creator->name
            ],
            "assignee_id" => $task->assignee_id,
            "assignee" => [
                "name"=> $task->assignee->name
            ],
            "report" => $task->report
        ];
        return response()->json($task);
    }
}
