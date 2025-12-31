@extends('layouts.app')

@section('content')
    <!-- Main content area -->
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create New Task</h1>
                        <p class="mt-2 text-gray-600">Add a new task to your task list</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('tasks.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Back to Tasks
                        </a>
                    </div>
                </div>
            </div>

            <!-- Create Form -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Task Information</h2>
                </div>

                <form method="POST" action="{{ route('tasks.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('title') border-red-300 @enderror"
                            required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status and Priority -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('status') border-red-300 @enderror"
                                required>
                                <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In
                                    Progress
                                </option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" id="priority"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('priority') border-red-300 @enderror"
                                required>
                                <option value="low" {{ old('priority', 'medium') === 'low' ? 'selected' : '' }}>
                                    Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>
                                    Medium</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>
                                    High</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('due_date') border-red-300 @enderror">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assign Users -->
                    <div>
                        <label for="user_ids" class="block text-sm font-medium text-gray-700">Assign Users</label>
                        <select name="user_ids[]" id="user_ids" multiple
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('user_ids') border-red-300 @enderror">
                            @foreach ($allUsers as $user)
                                <option value="{{ $user->id }}"
                                    {{ in_array($user->id, old('user_ids', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Hold Ctrl (or Cmd) to select multiple users.</p>
                        @error('user_ids')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('tasks.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
