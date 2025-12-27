<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of tasks with filtering and sorting options.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Task::with(['assignees', 'creator' => function($q) {
            $q->withTrashed();
        }]);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('due_date_filter')) {
            $now = now();
            switch ($request->due_date_filter) {
                case 'today':
                    $query->whereDate('due_date', $now->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('due_date', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'this_month':
                    $query->whereYear('due_date', $now->year)
                          ->whereMonth('due_date', $now->month);
                    break;
                case 'overdue':
                    $query->where('due_date', '<', $now->toDateString())
                          ->where('status', '!=', 'completed');
                    break;
            }
        }

        if ($request->filled('assigned_to_me')) {
            $query->whereHas('assignees', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if ($request->filled('created_by_me')) {
            $query->where('created_by', Auth::id());
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
        $statuses = ['pending', 'in_progress', 'completed'];
        $priorities = ['low', 'medium', 'high'];

        return view('tasks.index', compact('tasks', 'statuses', 'priorities'));
    }

    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $allUsers = User::select('id', 'name', 'email')->get();
        return view('tasks.create', compact('allUsers'));
    }

    /**
     * Store a newly created task in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after:today',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'created_by' => Auth::id(),
        ]);

        if ($request->user_ids) {
            foreach ($request->user_ids as $userId) {
                $task->assignments()->create([
                    'assigned_to' => $userId,
                    'assigned_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('tasks.show', $task->id)->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $task = Task::with(['assignees', 'creator' => function($q) {
            $q->withTrashed();
        }, 'assignments.assignedBy' => function($q) {
            $q->withTrashed();
        }])->findOrFail($id);

        // Check if user has permission to view this task
        $canView = $task->created_by === Auth::id() ||
                  $task->assignees->contains(Auth::id()) ||
                  Auth::check(); // Allow all authenticated users to view tasks

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
        $canAssign = $task->created_by === Auth::id() ||
                    $task->assignees->contains(Auth::id()) ||
                    Auth::user()->is_admin;

        if (!$canAssign) {
            return response()->json(['error' => 'You do not have permission to assign users to this task.'], 403);
        }

        // Assign users to task
        foreach ($request->user_ids as $userId) {
            if (!$task->assignees()->where('assigned_to', $userId)->exists()) {
                $task->assignments()->create([
                    'assigned_to' => $userId,
                    'assigned_by' => Auth::id(),
                ]);
            }
        }

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
        $canRemove = $task->created_by === Auth::id() ||
                    Auth::user()->is_admin;

        if (!$canRemove) {
            return response()->json(['error' => 'You do not have permission to remove assignees from this task.'], 403);
        }

        $task->assignments()->where('assigned_to', $userId)->delete();

        return response()->json(['message' => 'Assignee removed successfully']);
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);

        // Check if user can edit this task
        $canEdit = $task->created_by === Auth::id() || Auth::user()->is_admin;

        if (!$canEdit) {
            abort(403, 'You do not have permission to edit this task.');
        }

        $allUsers = User::select('id', 'name', 'email')->get();

        return view('tasks.edit', compact('task', 'allUsers'));
    }

    /**
     * Update the specified task in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after:today',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task = Task::findOrFail($id);

        // Check if user can edit this task
        $canEdit = $task->created_by === Auth::id() || Auth::user()->is_admin;

        if (!$canEdit) {
            abort(403, 'You do not have permission to edit this task.');
        }

        $task->update($request->only(['title', 'description', 'status', 'priority', 'due_date']));

        // Update assignments
        $task->assignments()->delete(); // Remove existing
        if ($request->user_ids) {
            foreach ($request->user_ids as $userId) {
                $task->assignments()->create([
                    'assigned_to' => $userId,
                    'assigned_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('tasks.show', $id)->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        // Check if user can delete this task
        $canDelete = $task->created_by === Auth::id() || Auth::user()->is_admin;

        if (!$canDelete) {
            abort(403, 'You do not have permission to delete this task.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}