@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        แบบทดสอบ - {{ $module->title }}
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('teacher.courses.index') }}" class="hover:text-blue-600">รายวิชา</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('teacher.courses.show', $course->id) }}" class="hover:text-blue-600">{{ $course->title }}</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <span>{{ $module->title }}</span>
        </div>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">แบบทดสอบทั้งหมด</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Module: {{ $module->title }}</p>
            </div>
            <a href="{{ route('teacher.courses.modules.quizzes.create', [$course->id, $module->id]) }}"
                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-lg">
                <i class="fas fa-plus-circle mr-2"></i>สร้างแบบทดสอบใหม่
            </a>
        </div>

        @if (session('success'))
            <div
                class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Quizzes List -->
        @if ($module->quizzes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($module->quizzes as $quiz)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
                                <span
                                    class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $quiz->questions->count() }} ข้อ
                                </span>
                            </div>

                            @if ($quiz->description)
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                    {{ $quiz->description }}</p>
                            @endif

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-gray-700 dark:text-gray-300">คะแนนผ่าน:
                                        <strong>{{ $quiz->passing_score }}%</strong></span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        เวลา:
                                        <strong>{{ $quiz->time_limit ? $quiz->time_limit . ' นาที' : 'ไม่จำกัด' }}</strong>
                                    </span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-users text-purple-500 mr-2"></i>
                                    <span class="text-gray-700 dark:text-gray-300">ทำแล้ว:
                                        <strong>{{ $quiz->attempts->count() }} ครั้ง</strong></span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('teacher.courses.modules.quizzes.show', [$course->id, $module->id, $quiz->id]) }}"
                                    class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    <i class="fas fa-eye mr-1"></i>ดู
                                </a>
                                <a href="{{ route('teacher.courses.modules.quizzes.edit', [$course->id, $module->id, $quiz->id]) }}"
                                    class="flex-1 text-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                    <i class="fas fa-edit mr-1"></i>แก้ไข
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-clipboard-question text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">ยังไม่มีแบบทดสอบ</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">เริ่มสร้างแบบทดสอบแรกของคุณเลย</p>
                <a href="{{ route('teacher.courses.modules.quizzes.create', [$course->id, $module->id]) }}"
                    class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                    <i class="fas fa-plus-circle mr-2"></i>สร้างแบบทดสอบ
                </a>
            </div>
        @endif
    </div>
@endsection
