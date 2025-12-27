@extends('layouts.app')

@section('content')
    <!-- Main content area -->
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <!-- Welcome section -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                    <p class="mt-2 text-gray-600">Here's what's happening with your tasks today.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('tasks.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        View All Tasks
                    </a>
                    <a href="{{ route('notifications.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z">
                            </path>
                        </svg>
                        Notifications
                        @if ($recentNotifications->count() > 0)
                            <span
                                class="ml-2 inline-flex items-center justify-center h-5 w-5 text-xs font-medium text-white bg-red-500 rounded-full">{{ $recentNotifications->count() }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tasks</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $taskStats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 font-medium">+12%</span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $taskStats['pending'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-yellow-600 font-medium">{{ $taskStats['in_progress'] }}</span>
                    <span class="text-gray-500 ml-2">in progress</span>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $taskStats['completed'] }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 font-medium">+8%</span>
                    <span class="text-gray-500 ml-2">completion rate</span>
                </div>
            </div>

            <!-- Overdue Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Overdue</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $taskStats['overdue'] }}</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-red-600 font-medium">Needs attention</span>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Priority Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Task Priorities</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600">Urgent</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900 mr-2">{{ $priorityStats['urgent'] }}</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full"
                                    style="width: {{ $taskStats['total'] > 0 ? ($priorityStats['urgent'] / $taskStats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600">High</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900 mr-2">{{ $priorityStats['high'] }}</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full"
                                    style="width: {{ $taskStats['total'] > 0 ? ($priorityStats['high'] / $taskStats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600">Medium</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900 mr-2">{{ $priorityStats['medium'] }}</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full"
                                    style="width: {{ $taskStats['total'] > 0 ? ($priorityStats['medium'] / $taskStats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600">Low</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900 mr-2">{{ $priorityStats['low'] }}</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full"
                                    style="width: {{ $taskStats['total'] > 0 ? ($priorityStats['low'] / $taskStats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Performance</h3>
                <div class="h-64">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tasks Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Tasks</h3>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentTasks as $task)
                        <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center
                                    {{ $task->status === 'completed'
                                        ? 'bg-green-100'
                                        : ($task->status === 'in_progress'
                                            ? 'bg-blue-100'
                                            : 'bg-yellow-100') }}">
                                    <svg class="w-5 h-5
                                        {{ $task->status === 'completed'
                                            ? 'text-green-600'
                                            : ($task->status === 'in_progress'
                                                ? 'text-blue-600'
                                                : 'text-yellow-600') }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">Due {{ $task->due_date->format('M d, Y') }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $task->priority === 'urgent'
                                        ? 'bg-red-100 text-red-800'
                                        : ($task->priority === 'high'
                                            ? 'bg-orange-100 text-orange-800'
                                            : ($task->priority === 'medium'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-green-100 text-green-800')) }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No recent tasks found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tasks Due Soon -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Due Soon</h3>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        Next 7 days
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($tasksDueSoon as $task)
                        <div class="flex items-center p-3 bg-orange-50 rounded-lg border border-orange-200">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $task->title }}</p>
                                <p class="text-xs text-orange-600">Due {{ $task->due_date->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white text-gray-800">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No tasks due soon.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Notifications Panel -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Notifications</h3>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
            </div>
            <div class="space-y-3">
                @forelse($recentNotifications as $notification)
                    <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                {{ $notification->data['message'] ?? 'New notification' }}</p>
                            <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if (!$notification->read_at)
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent notifications.</p>
                @endforelse
            </div>
        </div>
    </main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const performanceData = @json($performanceData);
        const labels = Object.keys(performanceData);
        const data = Object.values(performanceData);

        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tasks Completed',
                    data: data,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
