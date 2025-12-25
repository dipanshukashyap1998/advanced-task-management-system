<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Get all tasks with optional filters.
     *
     * @param Request $request
     * @return Collection
     */
    public function all(Request $request): Collection
    {
        $query = Task::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        return $query->get();
    }

    /**
     * Find a task by ID with relationships.
     *
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task
    {
        return Task::with(['assignments.assignedTo', 'user'])->find($id);
    }

    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Update an existing task.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $task = Task::find($id);
        if (!$task) {
            return false;
        }
        return $task->update($data);
    }

    /**
     * Delete a task by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $task = Task::find($id);
        if (!$task) {
            return false;
        }
        return $task->delete();
    }

    /**
     * Assign users to a task.
     *
     * @param int $id
     * @param array $userIds
     * @param int|null $assignedBy
     * @return array
     */
    public function assignUsers(int $id, array $userIds, ?int $assignedBy): array
    {
        $assignments = [];
        foreach ($userIds as $userId) {
            // Check if already assigned
            if (!TaskAssignment::where('task_id', $id)->where('assigned_to', $userId)->exists()) {
                $assignments[] = TaskAssignment::create([
                    'task_id' => $id,
                    'assigned_to' => $userId,
                    'assigned_by' => $assignedBy,
                ]);
            }
        }
        return $assignments;
    }

    /**
     * Get assignees for a task.
     *
     * @param int $id
     * @return Collection
     */
    public function getAssignees(int $id): Collection
    {
        $task = Task::find($id);
        if (!$task) {
            return collect();
        }
        return $task->assignments()->with('assignedTo')->get()->pluck('assignedTo');
    }

    /**
     * Remove an assignee from a task.
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function removeAssignee(int $id, int $userId): bool
    {
        $assignment = TaskAssignment::where('task_id', $id)->where('assigned_to', $userId)->first();
        if (!$assignment) {
            return false;
        }
        return $assignment->delete();
    }
}