<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use App\Models\TaskAssignment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    /**
     * Get all tasks with optional filters.
     *
     * @param Request $request
     * @return Collection
     */
    public function all(Request $request): Collection;

    /**
     * Find a task by ID with relationships.
     *
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task;

    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task;

    /**
     * Update an existing task.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a task by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Assign users to a task.
     *
     * @param int $id
     * @param array $userIds
     * @param int|null $assignedBy
     * @return array
     */
    public function assignUsers(int $id, array $userIds, ?int $assignedBy): array;

    /**
     * Get assignees for a task.
     *
     * @param int $id
     * @return Collection
     */
    public function getAssignees(int $id): Collection;

    /**
     * Remove an assignee from a task.
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function removeAssignee(int $id, int $userId): bool;
}