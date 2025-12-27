<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;

    /**
     * TaskController constructor.
     *
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $tasks = $this->taskService->getAllTasks($request);
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTaskRequest $request
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $task = $this->taskService->getTaskById((int) $id);
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $task = $this->taskService->updateTask((int) $id, $request->validated());
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $this->taskService->deleteTask((int) $id);
        return response()->json(null, 204);
    }

    /**
     * Assign users to a task.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'assigned_by' => 'nullable|exists:users,id',
        ]);

        $assignments = $this->taskService->assignUsersToTask((int) $id, $validated['user_ids'], $validated['assigned_by'] ?? null);
        return response()->json($assignments, 201);
    }

    /**
     * Get assignees for a task.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignees(string $id)
    {
        $assignees = $this->taskService->getTaskAssignees((int) $id);
        return response()->json($assignees);
    }

    /**
     * Remove an assignee from a task.
     *
     * @param string $id
     * @param string $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAssignee(string $id, string $userId)
    {
        $this->taskService->removeAssigneeFromTask((int) $id, (int) $userId);
        return response()->json(['message' => 'Assignee removed successfully']);
    }
}