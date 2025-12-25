<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessDueTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now();
        $next24Hours = $now->copy()->addHours(24);

        $tasks = Task::with(['assignments.assignedTo'])->where('due_date', '>=', $now)
                     ->where('due_date', '<=', $next24Hours)
                     ->whereIn('status', ['pending', 'in_progress'])
                     ->get();

        foreach ($tasks as $task) {
            // Notify all assigned users
            foreach ($task->assignments as $assignment) {
                $assignment->assignedTo->notify(new \App\Notifications\TaskDueSoonNotification($task));
                Log::info("Notification sent for Task ID: {$task->id} to User ID: {$assignment->assignedTo->id}");
            }
        }
    }
}
