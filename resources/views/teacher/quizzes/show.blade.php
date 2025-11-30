@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ $quiz->title }}
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('teacher.courses.index') }}" class="hover:text-blue-600">คอร์ส</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('teacher.courses.show', $course->id) }}" class="hover:text-blue-600">{{ $course->title }}</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('teacher.courses.modules.quizzes.index', [$course->id, $module->id]) }}"
                class="hover:text-blue-600">{{ $module->title }}</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <span>{{ $quiz->title }}</span>
        </div>

        @if (session('success'))
            <div
                class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header with Actions -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Module: {{ $module->title }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('teacher.courses.modules.quizzes.edit', [$course->id, $module->id, $quiz->id]) }}"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold">
                    <i class="fas fa-edit mr-2"></i>แก้ไขแบบทดสอบ
                </a>
                <form
                    action="{{ route('teacher.courses.modules.quizzes.destroy', [$course->id, $module->id, $quiz->id]) }}"
                    method="POST"
                    onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบแบบทดสอบนี้? ข้อมูลทั้งหมดจะถูกลบอย่างถาวร');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold">
                        <i class="fas fa-trash mr-2"></i>ลบแบบทดสอบ
                    </button>
                </form>
            </div>
        </div>

        <!-- Quiz Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">จำนวนคำถาม</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $quiz->questions->count() }}</p>
                    </div>
                    <i class="fas fa-question-circle text-4xl text-blue-500"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">คะแนนผ่าน</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $quiz->passing_score }}%</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl text-green-500"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">เวลาจำกัด</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $quiz->time_limit ?? '∞' }}
                        </p>
                    </div>
                    <i class="fas fa-clock text-4xl text-purple-500"></i>
                </div>
                @if ($quiz->time_limit)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">นาที</p>
                @else
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ไม่จำกัด</p>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">จำนวนครั้งที่ทำ</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $quiz->attempts->count() }}
                        </p>
                    </div>
                    <i class="fas fa-users text-4xl text-orange-500"></i>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if ($quiz->description)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    <i class="fas fa-align-left text-blue-500 mr-2"></i>คำอธิบาย
                </h3>
                <p class="text-gray-700 dark:text-gray-300">{{ $quiz->description }}</p>
            </div>
        @endif

        <!-- Questions List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-list text-blue-500 mr-2"></i>คำถามทั้งหมด ({{ $quiz->questions->count() }} ข้อ)
                </h3>
            </div>
            <div class="p-6">
                @if ($quiz->questions->count() > 0)
                    <div class="space-y-4">
                        @foreach ($quiz->questions as $question)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $question->order }}. {{ $question->question_text }}
                                    </h4>
                                </div>
                                <div class="space-y-2 ml-6">
                                    @foreach ($question->answers as $answer)
                                        <div class="flex items-center gap-2">
                                            @if ($answer->is_correct)
                                                <i class="fas fa-check-circle text-green-500"></i>
                                                <span
                                                    class="text-green-700 dark:text-green-400 font-semibold">{{ $answer->answer_text }}</span>
                                            @else
                                                <i class="fas fa-circle text-gray-400"></i>
                                                <span
                                                    class="text-gray-700 dark:text-gray-300">{{ $answer->answer_text }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-question-circle text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-600 dark:text-gray-400">ยังไม่มีคำถาม</p>
                        <a href="{{ route('teacher.courses.modules.quizzes.edit', [$course->id, $module->id, $quiz->id]) }}"
                            class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            <i class="fas fa-plus mr-2"></i>เพิ่มคำถาม
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        @if ($quiz->attempts->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-chart-bar text-blue-500 mr-2"></i>สถิติการทำแบบทดสอบ
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $totalAttempts = $quiz->attempts->count();
                        $passedAttempts = $quiz->attempts->where('passed', true)->count();
                        $failedAttempts = $totalAttempts - $passedAttempts;
                        $averageScore = round($quiz->attempts->avg('score'), 2);
                        $passRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 2) : 0;
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">ทำทั้งหมด</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalAttempts }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">ผ่าน</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $passedAttempts }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">ไม่ผ่าน</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $failedAttempts }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">คะแนนเฉลี่ย</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $averageScore }}%</p>
                        </div>
                    </div>

                    <!-- Pass Rate Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-300 font-semibold">อัตราการผ่าน</span>
                            <span class="text-gray-700 dark:text-gray-300 font-semibold">{{ $passRate }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                            <div class="bg-green-500 h-4 rounded-full" style="width: {{ $passRate }}%"></div>
                        </div>
                    </div>

                    <!-- Recent Attempts -->
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 mt-6">
                        ความพยายามล่าสุด
                    </h4>
                    <div class="space-y-2">
                        @foreach ($quiz->attempts->sortByDesc('completed_at')->take(10) as $attempt)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-user-circle text-2xl text-gray-500"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $attempt->student->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $attempt->completed_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="text-lg font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $attempt->score }}%
                                    </span>
                                    @if ($attempt->passed)
                                        <span
                                            class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-xs font-semibold">
                                            ผ่าน
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full text-xs font-semibold">
                                            ไม่ผ่าน
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
