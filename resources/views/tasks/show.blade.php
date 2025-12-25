@extends('layouts.app')

@section('content')
    <!-- Main content area -->
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <a href="{{ route('tasks.index') }}"
                            class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                            Back to Tasks
                        </a>
                        <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $task->title }}</h1>
                    </div>
                    <div class="flex space-x-3">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if ($task->priority === 'urgent') bg-red-100 text-red-800
                            @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($task->priority) }} Priority
                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if ($task->status === 'completed') bg-green-100 text-green-800
                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($task->status === 'cancelled') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Task Description -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                        <div class="prose max-w-none">
                            @if ($task->description)
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
                            @else
                                <p class="text-gray-500 italic">No description provided.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Task Details -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Details</h2>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created By</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $task->creator->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $task->created_at->format('M d, Y \a\t g:i A') }}
                                </dd>
                            </div>
                            @if ($task->due_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 {{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-red-600 font-medium' : '' }}">
                                        {{ $task->due_date->format('M d, Y \a\t g:i A') }}
                                        @if ($task->due_date->isPast() && $task->status !== 'completed')
                                            <span class="text-red-500">(Overdue)</span>
                                        @endif
                                    </dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $task->updated_at->format('M d, Y \a\t g:i A') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Assigned Users -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Assigned Users</h2>
                            <button onclick="openAssignmentModal()"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Assign
                            </button>
                        </div>

                        @if ($task->assignees->count() > 0)
                            <div class="space-y-3">
                                @foreach ($task->assignees as $assignee)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                                    <span
                                                        class="text-sm font-medium text-white">{{ substr($assignee->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $assignee->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $assignee->email }}</p>
                                            </div>
                                        </div>
                                        @if ($task->created_by === auth()->id() || auth()->user()->is_admin ?? false)
                                            <button
                                                onclick="removeAssignee({{ $task->id }}, {{ $assignee->id }}, '{{ $assignee->name }}')"
                                                class="text-red-600 hover:text-red-800 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No users assigned to this task.</p>
                        @endif
                    </div>

                    <!-- Assignment History -->
                    @if ($task->assignments->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Assignment History</h2>
                            <div class="space-y-3">
                                @foreach ($task->assignments->sortByDesc('created_at') as $assignment)
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">
                                                Assigned to <span class="font-medium">{{ $assignment->user->name }}</span>
                                                @if ($assignment->assignedBy)
                                                    by {{ $assignment->assignedBy->name }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $assignment->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Assignment Modal -->
    <div id="assignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Assign Users to Task</h3>
                    <button onclick="closeAssignmentModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form id="assignmentForm" onsubmit="submitAssignment(event)">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Users</label>
                        <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md">
                            @foreach ($allUsers as $user)
                                <label class="flex items-center p-3 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 user-checkbox">
                                    <div class="ml-3 flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span
                                                    class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAssignmentModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Assign Users
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTaskId = {{ $task->id }};

        function openAssignmentModal() {
            document.getElementById('assignmentModal').classList.remove('hidden');
        }

        function closeAssignmentModal() {
            document.getElementById('assignmentModal').classList.add('hidden');
            // Reset form
            document.getElementById('assignmentForm').reset();
        }

        function submitAssignment(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const selectedUsers = formData.getAll('user_ids[]');

            if (selectedUsers.length === 0) {
                alert('Please select at least one user to assign.');
                return;
            }

            fetch(`{{ route('tasks.assign', ':id') }}`.replace(':id', currentTaskId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_ids: selectedUsers
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        location.reload();
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while assigning users.');
                });
        }

        function removeAssignee(taskId, userId, userName) {
            if (!confirm(`Are you sure you want to remove ${userName} from this task?`)) {
                return;
            }

            fetch(`{{ route('tasks.remove-assignee', ['taskId' => ':taskId', 'userId' => ':userId']) }}`.replace(':taskId',
                    taskId).replace(':userId', userId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        location.reload();
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the assignee.');
                });
        }

        // Close modal when clicking outside
        document.getElementById('assignmentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssignmentModal();
            }
        });
    </script>
@endsection
