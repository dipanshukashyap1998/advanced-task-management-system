<?php

namespace App\Services;

use App\Events\TaskAssigned;
use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    protected TaskRepositoryInterface $taskRepository;

    /**
     * TaskService constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get all tasks with optional filters.
     *
     * @param Request $request
     * @return Collection
     */
    public function getAllTasks(Request $request): Collection
    {
        $cacheKey = 'tasks_' . md5(serialize($request->all()));
        return Cache::remember($cacheKey, 3600, function () use ($request) {
            return $this->taskRepository->all($request);
        });
    }

    /**
     * Get a task by ID.
     *
     * @param int $id
     * @return Task
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getTaskById(int $id): Task
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Task not found");
        }
        return $task;
    }

    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        $task = $this->taskRepository->create($data);
        broadcast(new TaskCreated($task))->toOthers();
        return $task;
    }

    /**
     * Update an existing task.
     *
     * @param int $id
     * @param array $data
     * @return Task
     * @throws \Exception
     */
    public function updateTask(int $id, array $data): Task
    {
        $task = $this->getTaskById($id); // This will throw if not found
        $this->taskRepository->update($id, $data);
        $updatedTask = $task->fresh();
        broadcast(new TaskUpdated($updatedTask))->toOthers();
        return $updatedTask;
    }

    /**
     * Delete a task by ID.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function deleteTask(int $id): void
    {
        if (!$this->taskRepository->delete($id)) {
            throw new \Exception('Task not found');
        }
        broadcast(new TaskDeleted($id))->toOthers();
    }

    /**
     * Assign users to a task.
     *
     * @param int $id
     * @param array $userIds
     * @param int|null $assignedBy
     * @return array
     * @throws \Exception
     */
    public function assignUsersToTask(int $id, array $userIds, ?int $assignedBy): array
    {
        $task = $this->getTaskById($id); // Ensure task exists
        $assignments = $this->taskRepository->assignUsers($id, $userIds, $assignedBy);
        broadcast(new TaskAssigned($task->fresh(['assignees'])))->toOthers();
        return $assignments;
    }

    /**
     * Get assignees for a task.
     *
     * @param int $id
     * @return Collection
     * @throws \Exception
     */
    public function getTaskAssignees(int $id): Collection
    {
        $this->getTaskById($id); // Ensure task exists
        return $this->taskRepository->getAssignees($id);
    }

    /**
     * Remove an assignee from a task.
     *
     * @param int $id
     * @param int $userId
     * @return void
     * @throws \Exception
     */
    public function removeAssigneeFromTask(int $id, int $userId): void
    {
        $this->getTaskById($id); // Ensure task exists
        if (!$this->taskRepository->removeAssignee($id, $userId)) {
            throw new \Exception('Assignment not found');
        }
    }
}