<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased font-sans">
    @if (auth()->check())
        <div x-data="{ sidebarOpen: false }" class="h-screen flex overflow-hidden bg-gray-50">
            <!-- Sidebar -->
            <div class="hidden md:flex md:flex-shrink-0">
                <div class="flex flex-col w-64">
                    <div class="flex flex-col h-0 flex-1 bg-white shadow-lg border-r border-gray-200">
                        <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                            <div class="flex items-center flex-shrink-0 px-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="text-xl font-bold text-gray-900">TaskManager</span>
                                </div>
                            </div>
                            <nav class="mt-5 flex-1 px-2 space-y-1">
                                <a href="{{ route('dashboard') }}"
                                    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                    </svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('tasks.index') }}"
                                    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('tasks.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tasks.*') ? 'text-blue-500' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    All Tasks
                                </a>
                                <a href="{{ route('notifications.index') }}"
                                    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('notifications.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('notifications.*') ? 'text-blue-500' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z">
                                        </path>
                                    </svg>
                                    Notifications
                                    <span x-show="notificationCount > 0" x-text="notificationCount"
                                        class="ml-auto inline-flex items-center justify-center h-5 w-5 text-xs font-medium text-white bg-red-500 rounded-full notification-badge">
                                    </span>
                                </a>
                            </nav>
                        </div>
                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span
                                            class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile sidebar -->
            <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden" x-transition>
                <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:enter-from="opacity-0" x-transition:enter-to="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-from="opacity-100" x-transition:leave-to="opacity-0"
                    class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:enter-from="-translate-x-full" x-transition:enter-to="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-300 transform"
                    x-transition:leave-from="translate-x-0" x-transition:leave-to="-translate-x-full"
                    class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button x-show="sidebarOpen" type="button"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            @click="sidebarOpen = false">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-xl font-bold text-gray-900">TaskManager</span>
                            </div>
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            <a href="{{ route('dashboard') }}"
                                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3">
                    <button type="button"
                        class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                        @click="sidebarOpen = true">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                @yield('content')
            </div>
        </div>
    @else
        @yield('content')
    @endif

    @if (auth()->check())
        <script>
            // Real-time notifications
            document.addEventListener('DOMContentLoaded', function() {
                let notificationCount = 0;

                // Function to update notification count
                function updateNotificationCount() {
                    fetch('{{ route('notifications.unread-count') }}')
                        .then(response => response.json())
                        .then(data => {
                            notificationCount = data.count;
                            const badges = document.querySelectorAll('.notification-badge');
                            badges.forEach(badge => {
                                badge.textContent = data.count;
                                badge.style.display = data.count > 0 ? 'inline' : 'none';
                            });
                        })
                        .catch(error => console.error('Error fetching notification count:', error));
                }

                // Update notification count on page load
                updateNotificationCount();

                // Update notification count every 30 seconds
                setInterval(updateNotificationCount, 30000);

                // Listen for notification clicks
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.notification-item')) {
                        const notificationId = e.target.closest('.notification-item').dataset.id;
                        if (notificationId) {
                            fetch(`{{ route('notifications.mark-read', ':id') }}`.replace(':id',
                                    notificationId), {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content')
                                    }
                                })
                                .then(() => {
                                    updateNotificationCount();
                                })
                                .catch(error => console.error('Error marking notification as read:', error));
                        }
                    }
                });
            });
        </script>
    @endif
</body>

</html>
