@extends('layouts.app')

@section('content')
    <!-- Main content area -->
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Tasks</h1>
                        <p class="mt-2 text-gray-600">Manage and track all your tasks</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('tasks.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Task
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" action="{{ route('tasks.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Search tasks...">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') === $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Priority Filter -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" id="priority"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Priorities</option>
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority }}"
                                        {{ request('priority') === $priority ? 'selected' : '' }}>
                                        {{ ucfirst($priority) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Due Date Filter -->
                        <div>
                            <label for="due_date_filter" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <select name="due_date_filter" id="due_date_filter"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('due_date_filter') === 'today' ? 'selected' : '' }}>Today
                                </option>
                                <option value="this_week"
                                    {{ request('due_date_filter') === 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month"
                                    {{ request('due_date_filter') === 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="overdue" {{ request('due_date_filter') === 'overdue' ? 'selected' : '' }}>
                                    Overdue</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div>
                            <label for="sort_by" class="block text-sm font-medium text-gray-700">Sort By</label>
                            <select name="sort_by" id="sort_by"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="created_at"
                                    {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Created Date
                                </option>
                                <option value="due_date" {{ request('sort_by') === 'due_date' ? 'selected' : '' }}>Due Date
                                </option>
                                <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>Title
                                </option>
                                <option value="priority" {{ request('sort_by') === 'priority' ? 'selected' : '' }}>Priority
                                </option>
                                <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Additional Filters -->
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="assigned_to_me" value="1"
                                {{ request('assigned_to_me') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Assigned to me</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="created_by_me" value="1"
                                {{ request('created_by_me') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Created by me</span>
                        </label>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply Filters
                        </button>
                        <a href="{{ route('tasks.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tasks List -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Tasks ({{ $tasks->total() }})
                    </h2>
                </div>

                @if ($tasks->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($tasks as $task)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                                <a href="{{ route('tasks.show', $task->id) }}"
                                                    class="hover:text-blue-600">
                                                    {{ $task->title }}
                                                </a>
                                            </h3>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($task->priority === 'urgent') bg-red-100 text-red-800
                                                @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                                @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($task->status === 'completed') bg-green-100 text-green-800
                                                @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                @elseif($task->status === 'cancelled') bg-gray-100 text-gray-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                            {{ Str::limit($task->description, 150) }}
                                        </p>
                                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                            <span>Created by
                                                {{ $task->creator ? $task->creator->name : 'Unknown User' }}</span>
                                            @if ($task->due_date)
                                                <span>Due: {{ $task->due_date->format('M d, Y') }}</span>
                                            @endif
                                            <span>{{ $task->assignees->count() }}
                                                assignee{{ $task->assignees->count() !== 1 ? 's' : '' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('tasks.show', $task->id) }}"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            View
                                        </a>
                                        @if ($task->created_by === auth()->id() || auth()->user()->is_admin)
                                            <a href="{{ route('tasks.edit', $task->id) }}"
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this task?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if (request()->hasAny(['search', 'status', 'priority', 'assigned_to_me', 'created_by_me']))
                                Try adjusting your filters to see more results.
                            @else
                                Get started by creating your first task.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
