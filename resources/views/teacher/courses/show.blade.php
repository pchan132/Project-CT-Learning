@extends('layouts.app')

@section('title', $course->title)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                        <a href="{{ route('teacher.courses.index') }}"
                            class="hover:text-indigo-600 dark:hover:text-indigo-400">
                            My Courses
                        </a>
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span>{{ $course->title }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $course->title }}
                    </h1>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('teacher.courses.edit', $course) }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Course
                    </a>
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Module
                    </a>
                </div>
            </div>
        </div>

        <!-- Course Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg mb-8">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Course Image -->
                    @if ($course->cover_image_url)
                        <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                            class="w-full md:w-48 h-48 object-cover rounded-lg shadow-lg">
                    @else
                        <div
                            class="w-full md:w-48 h-48 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif

                    <!-- Details and Stats -->
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $course->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $course->description }}</p>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $course->total_modules }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Modules</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ $course->total_lessons }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lessons</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                    {{ $course->enrollments->count() }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Students</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-lg font-bold text-gray-600 dark:text-gray-400">
                                    {{ $course->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Created</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Course Modules</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Manage modules and content structure
                        </p>
                    </div>
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="hidden sm:inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Module
                    </a>
                </div>
            </div>

            @if ($course->modules->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($course->modules as $module)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start">
                                    <!-- Module Number Badge -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center shadow-lg">
                                            <span class="text-white text-lg font-bold">{{ $module->order }}</span>
                                        </div>
                                    </div>

                                    <!-- Module Info -->
                                    <div class="ml-4">
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                {{ $module->title }}</h4>
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                                {{ $module->total_lessons }} lessons
                                            </span>
                                        </div>
                                        @if ($module->description)
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $module->description }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Module Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}"
                                        class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                                        class="text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                                        class="text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Show Recent Lessons -->
                            @if ($module->lessons->count() > 0)
                                <div class="mt-4 ml-16 space-y-2">
                                    @foreach ($module->lessons->take(3) as $lesson)
                                        <div
                                            class="flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <div
                                                class="h-6 w-6 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mr-3">
                                                <span
                                                    class="text-gray-600 dark:text-gray-300 text-xs font-semibold">{{ $lesson->order }}</span>
                                            </div>
                                            <span class="flex-1 font-medium">{{ $lesson->title }}</span>
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium 
                                            @if ($lesson->content_type === 'PDF') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300
                                            @elseif($lesson->content_type === 'VIDEO') bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300
                                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                                                {{ $lesson->content_type }}
                                            </span>
                                        </div>
                                    @endforeach

                                    @if ($module->lessons->count() > 3)
                                        <div class="text-sm text-gray-500 dark:text-gray-400 pt-2">
                                            <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                                                class="hover:text-indigo-600 dark:hover:text-indigo-400 font-medium">
                                                + {{ $module->lessons->count() - 3 }} more lessons
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">No modules yet</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 mb-6">Create your first module to start building your
                        course content</p>
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create First Module
                    </a>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('teacher.courses.index') }}"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to My Courses
            </a>
        </div>
    </div>
@endsection

<!-- Keep original complex styles below for reference only -->
<style style="display:none;">
    /* Define custom neon color constants for easy use */
    :root {
        --neon-cyan: #06b6d4;
        /* Tailwind cyan-500 */
    }

    .futuristic-card {
        /* Clean White Background with soft, luminous shadow */
        background-color: white;
        border: 1px solid #e0f7fa;
        /* Light cyan border for definition */
        /* Initial subtle glow shadow */
        box-shadow: 0 10px 30px rgba(0, 188, 212, 0.05);
        transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .futuristic-card:hover {
        /* Stronger neon glow and pronounced lift on hover */
        box-shadow: 0 10px 20px -3px rgba(6, 182, 212, 0.4), 0 5px 10px -2px rgba(6, 182, 212, 0.2);
        transform: translateY(-4px);
        /* More pronounced lift */
    }

    .text-neon-cyan {
        color: var(--neon-cyan);
    }

    .bg-neon-cyan {
        background-color: var(--neon-cyan);
    }

    /* Soft pulse animation for data activity indicator */
    @keyframes soft-pulse {

        0%,
        100% {
            opacity: 1;
            text-shadow: 0 0 5px var(--neon-cyan);
        }

        50% {
            opacity: 0.7;
            text-shadow: 0 0 15px var(--neon-cyan);
        }
    }

    .animate-soft-pulse {
        animation: soft-pulse 2s infinite ease-in-out;
    }
</style>

<!-- Main Container: Light Mode Base -->
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 bg-gray-50 min-h-screen text-gray-900">

    <!-- Header Section: High-Tech Look -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-wider">
                    {{ $course->title }}
                </h1>
                <p class="mt-2 text-sm text-gray-500">
                    <a href="{{ route('teacher.courses.index') }}"
                        class="text-cyan-600 hover:text-cyan-800 transition duration-300">คอร์สของฉัน</a>
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="font-medium text-gray-700">รายละเอียดคอร์ส</span>
                </p>
            </div>
            <div class="flex space-x-4">
                <!-- Edit Course Button: High-Contrast Secondary Action (Animated) -->
                <a href="{{ route('teacher.courses.edit', $course) }}"
                    class="bg-white border-2 border-cyan-500 text-cyan-600 hover:bg-cyan-50 font-semibold py-3 px-6 rounded-xl transition duration-300 ease-in-out shadow-lg shadow-cyan-500/10 hover:shadow-xl hover:shadow-cyan-500/30 transform hover:scale-[1.02]">
                    <i class="fas fa-edit mr-2"></i>แก้ไขคอร์ส
                </a>
                <!-- Add Module Button: Primary Neon Action (Animated) -->
                <a href="{{ route('teacher.courses.modules.create', $course) }}"
                    class="bg-neon-cyan hover:bg-cyan-400 text-white font-bold py-3 px-6 rounded-xl transition duration-300 ease-in-out shadow-xl shadow-cyan-500/50 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>เพิ่ม Module
                </a>
            </div>
        </div>
    </div>

    <!-- Course Info Card: Elevated Futuristic Card -->
    <div class="futuristic-card rounded-2xl mb-8 shadow-2xl hover:shadow-cyan-500/40">
        <div class="px-6 py-8 sm:p-10">
            <div class="flex items-start">
                <!-- Course Image/Placeholder -->
                @if ($course->cover_image_url)
                    <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                        class="h-32 w-32 rounded-xl object-cover mr-10 shadow-lg border-2 border-cyan-300">
                @else
                    <div
                        class="h-32 w-32 bg-gray-100 rounded-xl flex items-center justify-center mr-10 border-2 border-cyan-300 shadow-inner">
                        <!-- Animated Placeholder Icon -->
                        <i class="fas fa-book text-neon-cyan text-5xl animate-soft-pulse"></i>
                    </div>
                @endif

                <!-- Details and Stats -->
                <div class="flex-1">
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $course->description }}</p>

                    <!-- Core Stats Row: Data Visuals -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 border-t pt-4 border-gray-200">
                        <div class="text-left">
                            <div class="text-4xl font-extrabold text-cyan-600">{{ $course->total_modules }}</div>
                            <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">Modules (Units)</div>
                        </div>
                        <div class="text-left">
                            <div class="text-4xl font-extrabold text-blue-600">{{ $course->total_lessons }}</div>
                            <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">บทเรียน (Files)</div>
                        </div>
                        <div class="text-left">
                            <div class="text-4xl font-extrabold text-purple-600">
                                {{ $course->enrollments->count() }}</div>
                            <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">นักเรียน (Users)</div>
                        </div>
                        <div class="text-left">
                            <div class="text-3xl font-extrabold text-gray-600">
                                {{ $course->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">สร้างเมื่อ (Log)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules Section: Clean Digital List -->
    <div class="futuristic-card rounded-2xl shadow-2xl">
        <div class="px-6 py-6 sm:px-8 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Modules ในคอร์ส</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-600">
                        จัดการ Modules และโครงสร้างเนื้อหาทั้งหมดในคอร์สนี้
                    </p>
                </div>
                <!-- Secondary Add Module Button -->
                <a href="{{ route('teacher.courses.modules.create', $course) }}"
                    class="inline-flex items-center px-4 py-2 border border-cyan-500 text-sm leading-4 font-medium rounded-xl text-cyan-600 bg-cyan-50 hover:bg-cyan-100/50 focus:outline-none transition duration-150 transform hover:translate-x-1">
                    <i class="fas fa-plus mr-2"></i>เพิ่ม Module
                </a>
            </div>
        </div>

        @if ($course->modules->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach ($course->modules as $module)
                    <div class="px-6 py-6 transition duration-300 ease-in-out hover:bg-cyan-50/50 hover:shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <!-- Module Order Circle (Accent) -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-12 w-12 rounded-full bg-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-500/20 border border-cyan-400">
                                        <span class="text-white text-lg font-bold">{{ $module->order }}</span>
                                    </div>
                                </div>

                                <!-- Module Title and Info -->
                                <div class="ml-5">
                                    <div class="flex items-center">
                                        <h4 class="text-xl font-semibold text-gray-900">{{ $module->title }}</h4>
                                        <span
                                            class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-300">
                                            {{ $module->total_lessons }} บทเรียน
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Module ID: {{ $module->order }}
                                    </p>
                                </div>
                            </div>

                            <!-- Module Actions: Subtle hover for controls (Animated) -->
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}"
                                    class="text-gray-500 hover:text-cyan-600 transition duration-150 text-sm font-medium p-2 rounded-lg transform hover:scale-110">
                                    <i class="fas fa-eye mr-1"></i>ดู (View)
                                </a>
                                <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                                    class="text-gray-500 hover:text-green-600 transition duration-150 text-sm font-medium p-2 rounded-lg transform hover:scale-110">
                                    <i class="fas fa-list mr-1"></i>บทเรียน (Lessons)
                                </a>
                                <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                                    class="text-gray-500 hover:text-purple-600 transition duration-150 text-sm font-medium p-2 rounded-lg transform hover:scale-110">
                                    <i class="fas fa-edit mr-1"></i>แก้ไข (Edit)
                                </a>
                            </div>
                        </div>

                        <!-- Show first few lessons in this module -->
                        @if ($module->lessons->count() > 0)
                            <div class="mt-5 ml-17 pl-1">
                                <!-- Neon Line Indicator -->
                                <div
                                    class="text-sm font-semibold text-cyan-600 mb-2 border-l-4 pl-4 border-cyan-400 tracking-wider">
                                    // LOG: Latest Lessons Accessed
                                </div>
                                <div class="space-y-2">
                                    @foreach ($module->lessons->take(3) as $lesson)
                                        <div
                                            class="flex items-center text-sm text-gray-700 transition duration-200 hover:text-gray-900 hover:bg-cyan-50 p-1 rounded-md">
                                            <div
                                                class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center mr-3 border border-gray-300">
                                                <span
                                                    class="text-gray-500 text-xs font-semibold">{{ $lesson->order }}</span>
                                            </div>
                                            <span class="font-medium truncate">{{ $lesson->title }}</span>
                                            <span
                                                class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                    @if ($lesson->content_type === 'PDF') bg-red-100 text-red-700 border border-red-300
                                                    @elseif($lesson->content_type === 'VIDEO') bg-purple-100 text-purple-700 border border-purple-300
                                                    @elseif($lesson->content_type === 'TEXT') bg-gray-100 text-gray-700 border border-gray-300 @endif">
                                                {{ $lesson->content_type }}
                                            </span>
                                        </div>
                                    @endforeach

                                    @if ($module->lessons->count() > 3)
                                        <div class="text-sm text-gray-500 pt-2 border-t border-gray-200 mt-2">
                                            <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                                                class="hover:text-cyan-600 font-medium transition duration-150">
                                                ... ดูบทเรียนทั้งหมดอีก {{ $module->lessons->count() - 3 }} บทเรียน
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-folder-open text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">ACCESS PENDING: No Modules Found</h3>
                <p class="text-gray-600 mb-6">เริ่มต้นการสร้าง Module แรกเพื่อเปิดใช้งานคอร์ส</p>
                <a href="{{ route('teacher.courses.modules.create', $course) }}"
                    class="bg-neon-cyan hover:bg-cyan-400 text-white font-bold py-3 px-6 rounded-xl shadow-xl shadow-cyan-500/50 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>สร้าง Module แรก (INIT)
                </a>
            </div>
        @endif
    </div>

    <!-- Detailed Statistics: Data Panels -->
    @if ($course->total_lessons > 0)
        <div class="mt-10">
            <h2 class="text-xl font-bold text-gray-900 mb-4 border-b border-cyan-400 pb-2">SYSTEM STATUS:
                สรุปสถิติเนื้อหาคอร์ส</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Stat Card 1 (Modules) -->
                <div
                    class="futuristic-card rounded-xl border border-cyan-300/50 transition duration-300 hover:border-cyan-500">
                    <div class="px-5 py-6">
                        <div class="text-center">
                            <i class="fas fa-cubes text-cyan-500 text-3xl mb-2"></i>
                            <div class="text-4xl font-extrabold text-cyan-600">{{ $course->total_modules }}</div>
                            <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">Modules ทั้งหมด</div>
                        </div>
                    </div>
                </div>
                <!-- Stat Card 2 (Lessons) -->
                <div
                    class="futuristic-card rounded-xl border border-cyan-300/50 transition duration-300 hover:border-cyan-500">
                    <div class="px-5 py-6">
                        <div class="text-center">
                            <i class="fas fa-file-alt text-blue-500 text-3xl mb-2"></i>
                            <div class="text-4xl font-extrabold text-blue-600">{{ $course->total_lessons }}</div>
                            <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">บทเรียนทั้งหมด</div>
                        </div>
                    </div>
                </div>
                <!-- Stat Card 3 (PDF) -->
                <div
                    class="futuristic-card rounded-xl border border-cyan-300/50 transition duration-300 hover:border-cyan-500">
                    <div class="px-5 py-6">
                        <div class="text-center">
                            <i class="fas fa-file-pdf text-red-500 text-3xl mb-2"></i>
                            <div class="text-4xl font-extrabold text-red-600">
                                {{ $course->lessons()->where('content_type', 'PDF')->count() }}</div>
                            <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">Data Type: PDF</div>
                        </div>
                    </div>
                </div>
                <!-- Stat Card 4 (Video) -->
                <div
                    class="futuristic-card rounded-xl border border-cyan-300/50 transition duration-300 hover:border-cyan-500">
                    <div class="px-5 py-6">
                        <div class="text-center">
                            <i class="fas fa-video text-purple-500 text-3xl mb-2"></i>
                            <div class="text-4xl font-extrabold text-purple-600">
                                {{ $course->lessons()->where('content_type', 'VIDEO')->count() }}</div>
                            <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">Data Type: VIDEO
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Footer / Back Button: Terminal Style -->
    <div class="mt-12 pt-6 border-t-2 border-gray-200">
        <a href="{{ route('teacher.courses.index') }}"
            class="text-gray-500 hover:text-cyan-600 font-medium transition duration-150 transform hover:scale-[1.02] inline-block">
            <i class="fas fa-arrow-left mr-2"></i>// Execute: RETURN_TO_DASHBOARD
        </a>
    </div>
</div>
</x-app-layout>
