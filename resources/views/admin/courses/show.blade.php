@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $course->title }}
        </h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.courses.edit', $course) }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                แก้ไข
            </a>
            <a href="{{ route('admin.courses') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                กลับ
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Course Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    @if ($course->cover_image_url)
                        <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                            class="w-full h-64 object-cover">
                    @else
                        <div
                            class="w-full h-64 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-24 h-24 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $course->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $course->description }}</p>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                สร้างเมื่อ: {{ $course->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modules & Lessons -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">เนื้อหาคอร์ส</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($course->modules as $module)
                            <div class="p-6">
                                <div class="flex items-center mb-3">
                                    <span
                                        class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                        {{ $module->order }}
                                    </span>
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $module->title }}</h4>
                                </div>

                                @if ($module->lessons->count() > 0)
                                    <ul class="ml-11 space-y-2">
                                        @foreach ($module->lessons as $lesson)
                                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                @if ($lesson->content_type === 'VIDEO')
                                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                @elseif ($lesson->content_type === 'PDF')
                                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                @endif
                                                {{ $lesson->title }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="ml-11 text-sm text-gray-500 dark:text-gray-400">ยังไม่มีบทเรียน</p>
                                @endif

                                @if ($module->quizzes->count() > 0)
                                    <div class="mt-3 ml-11">
                                        @foreach ($module->quizzes as $quiz)
                                            <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                                    </path>
                                                </svg>
                                                {{ $quiz->title }} (เกณฑ์ผ่าน: {{ $quiz->passing_score }}%)
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">ยังไม่มีเนื้อหาในคอร์สนี้</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Teacher Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">ผู้สอน</h3>
                    <div class="flex items-center">
                        <div
                            class="h-14 w-14 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($course->teacher->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $course->teacher->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $course->teacher->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">สถิติ</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Modules</span>
                            <span
                                class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $course->modules_count }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">บทเรียน</span>
                            <span
                                class="text-lg font-bold text-green-600 dark:text-green-400">{{ $course->lessons_count }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">นักเรียนลงทะเบียน</span>
                            <span
                                class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $course->enrollments_count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Enrolled Students -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">นักเรียนที่ลงทะเบียน</h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @forelse ($course->enrollments as $enrollment)
                            <div class="p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-teal-500 flex items-center justify-center text-white text-sm font-bold">
                                    {{ substr($enrollment->student->name, 0, 1) }}
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $enrollment->student->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $enrollment->student->email }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400">
                                    {{ $enrollment->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                ยังไม่มีนักเรียนลงทะเบียน
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-4">Danger Zone</h3>
                    <p class="text-sm text-red-600 dark:text-red-400 mb-4">
                        การลบคอร์สจะลบข้อมูลทั้งหมด รวมถึง modules, lessons, quizzes และการลงทะเบียนของนักเรียน
                    </p>
                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                        onsubmit="return confirm('⚠️ คุณแน่ใจหรือไม่?\n\nการลบคอร์สนี้จะลบข้อมูลทั้งหมดอย่างถาวร!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                            ลบคอร์สนี้
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
