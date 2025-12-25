<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskAssignmentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::all();

        if ($tasks->isEmpty() || $users->count() < 2) {
            return; // Need tasks and at least 2 users
        }

        foreach ($tasks as $task) {
            $assignedTo = $users->random();
            $assignedBy = $users->where('id', '!=', $assignedTo->id)->random();

            TaskAssignment::create([
                'task_id' => $task->id,
                'assigned_to' => $assignedTo->id,
                'assigned_by' => $assignedBy->id,
            ]);
        }
    }
}