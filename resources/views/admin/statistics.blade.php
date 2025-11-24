@extends('layouts.app')

@section('title', 'System Statistics')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                System Statistics 📊
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Detailed analytics and system performance
            </p>
        </div>

        <!-- Overall Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Admin: {{ $stats['admins'] }} | Teachers: {{ $stats['teachers'] }} | Students: {{ $stats['students'] }}
                </p>
            </div>

            <!-- Total Courses -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Courses</h3>
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_courses'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Modules: {{ $stats['total_modules'] }} | Lessons: {{ $stats['total_lessons'] }}
                </p>
            </div>

            <!-- Total Enrollments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Enrollments</h3>
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_enrollments'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Active learning sessions
                </p>
            </div>

            <!-- Completion Rate -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Completion</h3>
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($stats['average_completion'], 1) }}%</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Across all courses
                </p>
            </div>
        </div>

        <!-- Course Performance -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Course Performance</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Course</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Teacher</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Enrollments</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Modules</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Lessons</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Completions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($courseStats as $course)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $course->title }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $course->teacher->name }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $course->enrollments_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                    {{ $course->modules_count }}
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                    {{ $course->lessons_count }}
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                    {{ $course->lesson_completions_count }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Teachers -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Top Teachers</h2>
                <div class="space-y-3">
                    @foreach ($topTeachers as $teacher)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $teacher->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $teacher->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $teacher->courses_count }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">courses</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Students -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Most Active Students</h2>
                <div class="space-y-3">
                    @foreach ($topStudents as $student)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $student->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $student->enrollments_count }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">enrollments</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
