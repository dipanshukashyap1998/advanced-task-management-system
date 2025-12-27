<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with task overview, user performance, and notifications.
     */
    public function index()
    {
        $user = auth()->user();

        // Task Statistics
        $taskStats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::where('due_date', '<', now())
                            ->whereNotIn('status', ['completed', 'cancelled'])
                            ->count(),
        ];

        // User's Task Performance
        $userTaskStats = [
            'assigned' => $user->assignedTasks()->count(),
            'completed' => $user->assignedTasks()
                               ->whereHas('task', function($q) {
                                   $q->where('status', 'completed');
                               })->count(),
            'pending' => $user->assignedTasks()
                             ->whereHas('task', function($q) {
                                 $q->where('status', 'pending');
                             })->count(),
            'overdue' => $user->assignedTasks()
                             ->whereHas('task', function($q) {
                                 $q->where('due_date', '<', now())
                                   ->whereNotIn('status', ['completed', 'cancelled']);
                             })->count(),
        ];

        // Performance Data for Chart (last 12 months)
        $performanceData = DB::table('task_assignments')
            ->join('tasks', 'task_assignments.task_id', '=', 'tasks.id')
            ->where('task_assignments.assigned_to', $user->id)
            ->where('tasks.status', 'completed')
            ->where('tasks.updated_at', '>=', now()->subMonths(12))
            ->select(DB::raw('DATE_FORMAT(tasks.updated_at, "%Y-%m") as month, COUNT(*) as completed'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('completed', 'month')
            ->toArray();

        // Recent Tasks
        $recentTasks = Task::with(['assignments.assignedTo', 'user'])
                          ->orderBy('created_at', 'desc')
                          ->limit(5)
                          ->get();

        // User's Recent Tasks
        $userRecentTasks = $user->assignedTasks()
                               ->with('task')
                               ->orderBy('created_at', 'desc')
                               ->limit(5)
                               ->get()
                               ->pluck('task');

        // Recent Notifications
        $recentNotifications = $user->notifications()
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get();

        // Priority Distribution
        $priorityStats = [
            'low' => Task::where('priority', 'low')->count(),
            'medium' => Task::where('priority', 'medium')->count(),
            'high' => Task::where('priority', 'high')->count(),
            'urgent' => Task::where('priority', 'urgent')->count(),
        ];

        // Tasks Due Soon (next 7 days)
        $tasksDueSoon = Task::where('due_date', '>=', now())
                           ->where('due_date', '<=', now()->addDays(7))
                           ->whereNotIn('status', ['completed', 'cancelled'])
                           ->orderBy('due_date')
                           ->limit(5)
                           ->get();

        return view('dashboard', compact(
            'taskStats',
            'userTaskStats',
            'recentTasks',
            'userRecentTasks',
            'recentNotifications',
            'priorityStats',
            'tasksDueSoon',
            'performanceData'
        ));
    }
}