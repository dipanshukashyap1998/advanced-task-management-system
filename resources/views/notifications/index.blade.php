@extends('layouts.app')

@section('content')
    <!-- Main content area -->
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
                        <p class="mt-2 text-gray-600">Stay updated with your task notifications</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="markAllAsRead()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Mark All as Read
                        </button>
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

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap gap-4">
                    <div>
                        <label for="read_status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="read_status" id="read_status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Notifications</option>
                            <option value="unread" {{ request('read_status') === 'unread' ? 'selected' : '' }}>Unread
                            </option>
                            <option value="read" {{ request('read_status') === 'read' ? 'selected' : '' }}>Read</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply Filter
                        </button>
                        <a href="{{ route('notifications.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Clear Filter
                        </a>
                    </div>
                </form>
            </div>

            <!-- Notifications List -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Notifications ({{ $notifications->total() }})
                    </h2>
                </div>

                @if ($notifications->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($notifications as $notification)
                            <div
                                class="p-6 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition-colors">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        @if (!$notification->read_at)
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                        @else
                                            <div class="w-2 h-2 bg-gray-300 rounded-full mt-2"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $notification->data['message'] ?? 'New notification' }}
                                            </p>
                                            <div class="flex items-center space-x-2">
                                                @if (!$notification->read_at)
                                                    <button onclick="markAsRead({{ $notification->id }})"
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        Mark as Read
                                                    </button>
                                                @endif
                                                <span class="text-xs text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        @if (isset($notification->data['task_id']))
                                            <div class="mt-2">
                                                <a href="{{ route('tasks.show', $notification->data['task_id']) }}"
                                                    class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    View Task
                                                </a>
                                            </div>
                                        @endif
                                        @if ($notification->read_at)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Read {{ $notification->read_at->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $notifications->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if (request('read_status'))
                                Try adjusting your filter to see more notifications.
                            @else
                                You'll receive notifications for task assignments and due dates here.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
        function markAsRead(notificationId) {
            fetch(`{{ route('notifications.mark-read', ':id') }}`.replace(':id', notificationId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while marking the notification as read.');
                });
        }

        function markAllAsRead() {
            if (!confirm('Are you sure you want to mark all notifications as read?')) {
                return;
            }

            fetch('{{ route('notifications.mark-all-read') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while marking all notifications as read.');
                });
        }

        // Auto-refresh notifications every 30 seconds
        setInterval(function() {
            // You could add a function here to refresh the notification count in the header
            // For now, we'll just check if there are new notifications
            fetch('{{ route('notifications.unread-count') }}')
                .then(response => response.json())
                .then(data => {
                    // Update notification badge in header if it exists
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline' : 'none';
                    }
                })
                .catch(error => console.error('Error fetching notification count:', error));
        }, 30000);
    </script>
@endsection
