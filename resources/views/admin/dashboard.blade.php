@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Admin Dashboard 👨‍💼
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Manage users and monitor system statistics
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Students</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $stats['total_students'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Total Teachers -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Teachers</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $stats['total_teachers'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Total Courses -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Courses</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $stats['total_courses'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Total Enrollments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Enrollments</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $stats['total_enrollments'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Manage Users -->
            <a href="{{ route('admin.users') }}" class="block">
                <div
                    class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Manage Users</h3>
                            <p class="text-blue-100">Create, edit, and delete users</p>
                        </div>
                        <svg class="h-12 w-12 text-white opacity-80" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </a>

            <!-- View Statistics -->
            <a href="{{ route('admin.statistics') }}" class="block">
                <div
                    class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">View Statistics</h3>
                            <p class="text-purple-100">Detailed system analytics</p>
                        </div>
                        <svg class="h-12 w-12 text-white opacity-80" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </a>

            <!-- System Settings -->
            <a href="{{ route('admin.certificate-templates.index') }}" class="block">
                <div
                    class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Certificate Templates</h3>
                            <p class="text-amber-100">ออกแบบใบประกาศนียบัตร</p>
                        </div>
                        <svg class="h-12 w-12 text-white opacity-80" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Recent Users</h2>
                <div class="space-y-3">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 rounded-full {{ $user->isAdmin() ? 'bg-red-500' : ($user->isTeacher() ? 'bg-blue-500' : 'bg-green-500') }} flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded {{ $user->isAdmin() ? 'bg-red-100 text-red-800' : ($user->isTeacher() ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No users yet</p>
                    @endforelse
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users') }}"
                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-semibold text-sm">
                        View all users →
                    </a>
                </div>
            </div>

            <!-- Recent Courses -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Recent Courses</h2>
                <div class="space-y-3">
                    @forelse($recentCourses as $course)
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                By {{ $course->teacher->name }}
                            </p>
                            <div class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $course->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No courses yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
