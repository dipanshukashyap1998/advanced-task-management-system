<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return; // No users to assign tasks to
        }

        Task::create([
            'title' => 'Complete project proposal',
            'description' => 'Write and finalize the project proposal document.',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addDays(7),
            'user_id' => $users->random()->id,
        ]);

        Task::create([
            'title' => 'Review code changes',
            'description' => 'Review the latest code changes in the repository.',
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => now()->addDays(3),
            'user_id' => $users->random()->id,
        ]);

        Task::create([
            'title' => 'Update documentation',
            'description' => 'Update the project documentation with new features.',
            'status' => 'completed',
            'priority' => 'low',
            'due_date' => now()->subDays(1),
            'user_id' => $users->random()->id,
        ]);
    }
}