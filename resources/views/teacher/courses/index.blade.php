@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    My Courses 📚
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Manage your courses, modules, and lessons
                </p>
            </div>
            <a href="{{ route('teacher.courses.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Course
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Course Image -->
                    <div class="relative h-48 bg-gradient-to-r from-indigo-500 to-purple-600">
                        @if ($course->cover_image_url)
                            <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif

                        <!-- Stats Badge -->
                        <div class="absolute top-4 right-4 bg-white dark:bg-gray-800 rounded-lg px-3 py-1 shadow-lg">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $course->enrollments->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Course Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $course->title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                            {{ $course->description }}
                        </p>

                        <!-- Course Stats -->
                        <div class="flex items-center space-x-4 mb-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                {{ $course->modules->count() }} Modules
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ $course->lessons->count() }} Lessons
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('teacher.courses.modules.index', $course) }}"
                                class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                                📚 Modules
                            </a>
                            <a href="{{ route('teacher.courses.show', $course) }}"
                                class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                                👁️ View
                            </a>
                        </div>

                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('teacher.courses.edit', $course) }}"
                                class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('teacher.courses.destroy', $course) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this course? This will also delete all modules, lessons, and student progress.')"
                                class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">No courses yet</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Get started by creating your first course</p>
                        <div class="mt-6">
                            <a href="{{ route('teacher.courses.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Create Your First Course
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
