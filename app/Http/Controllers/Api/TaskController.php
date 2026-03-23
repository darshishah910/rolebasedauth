<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;

        // ✅ PERMISSION MIDDLEWARE
        $this->middleware('permission:view_task')->only('index');
        $this->middleware('permission:create_task')->only('store');
        $this->middleware('permission:edit_task')->only('update');
        $this->middleware('permission:delete_task')->only('destroy');
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getAll()
        ]);
    }

    public function store(TaskRequest $request)
    {
        $task = $this->service->create(
            $request->validated(),
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Task created',
            'data' => $task
        ]);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = $this->service->update($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated',
            'data' => $task
        ]);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted'
        ]);
    }
}