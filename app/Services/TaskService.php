<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
        return $this->taskRepository->all($request);
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
        return $this->taskRepository->create($data);
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
        return $task->fresh();
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
        $this->getTaskById($id); // Ensure task exists
        return $this->taskRepository->assignUsers($id, $userIds, $assignedBy);
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