<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->middleware('auth');
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of tasks with filtering and sorting options.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Task::with(['assignees', 'creator']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to_me')) {
            $query->whereHas('assignees', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if ($request->filled('created_by_me')) {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['title', 'status', 'priority', 'due_date', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $tasks = $query->paginate(15);

        // Get filter options
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        return view('tasks.index', compact('tasks', 'statuses', 'priorities'));
    }

    /**
     * Display the specified task.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $task = Task::with(['assignees', 'creator', 'assignments.assignedBy'])
                   ->findOrFail($id);

        // Check if user has permission to view this task
        $canView = $task->user_id === Auth::id() ||
                  $task->assignees->contains(Auth::id());

        if (!$canView) {
            abort(403, 'You do not have permission to view this task.');
        }

        $allUsers = User::select('id', 'name', 'email')->get();

        return view('tasks.show', compact('task', 'allUsers'));
    }

    /**
     * Assign users to a task via AJAX.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignUsers(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task = Task::findOrFail($id);

        // Check if user can assign to this task
        $canAssign = $task->user_id === Auth::id() ||
                    $task->assignees->contains(Auth::id());

        if (!$canAssign) {
            return response()->json(['error' => 'You do not have permission to assign users to this task.'], 403);
        }

        $this->taskService->assignUsersToTask($id, $request->user_ids, Auth::id());

        return response()->json(['message' => 'Users assigned successfully']);
    }

    /**
     * Remove an assignee from a task via AJAX.
     *
     * @param int $taskId
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAssignee($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);

        // Check if user can remove assignees
        $canRemove = $task->user_id === Auth::id();

        if (!$canRemove) {
            return response()->json(['error' => 'You do not have permission to remove assignees from this task.'], 403);
        }

        $this->taskService->removeAssigneeFromTask($taskId, $userId);

        return response()->json(['message' => 'Assignee removed successfully']);
    }
}