@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        ผลการทำแบบทดสอบ
    </h2>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('student.courses.index') }}" class="hover:text-blue-600">คอร์สของฉัน</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('student.courses.show', $course->id) }}" class="hover:text-blue-600">{{ $course->title }}</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('student.courses.modules.quizzes.show', [$course->id, $module->id, $quiz->id]) }}"
                class="hover:text-blue-600">{{ $quiz->title }}</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <span>ผลลัพธ์</span>
        </div>

        <!-- Result Header -->
        <div
            class="bg-gradient-to-r {{ $attempt->passed ? 'from-green-500 to-emerald-500' : 'from-red-500 to-orange-500' }} text-white rounded-xl shadow-2xl p-8 md:p-12 mb-6">
            <div class="text-center">
                @if ($attempt->passed)
                    <i class="fas fa-check-circle text-7xl mb-4 animate-bounce"></i>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">ยินดีด้วย! คุณผ่านแบบทดสอบ</h1>
                    <p class="text-xl opacity-90">คุณได้คะแนนมากกว่าเกณฑ์ผ่าน</p>
                @else
                    <i class="fas fa-times-circle text-7xl mb-4"></i>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">ไม่ผ่าน</h1>
                    <p class="text-xl opacity-90">คุณสามารถลองทำใหม่ได้อีกครั้ง</p>
                @endif
            </div>
        </div>

        <!-- Score Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center">
                <i class="fas fa-percentage text-4xl {{ $attempt->passed ? 'text-green-500' : 'text-red-500' }} mb-3"></i>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">คะแนนที่ได้</p>
                <p class="text-4xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                    {{ $attempt->score }}%</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center">
                <i class="fas fa-check-circle text-4xl text-green-500 mb-3"></i>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">ตอบถูก</p>
                <p class="text-4xl font-bold text-green-600">{{ $correctAnswers }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center">
                <i class="fas fa-times-circle text-4xl text-red-500 mb-3"></i>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">ตอบผิด</p>
                <p class="text-4xl font-bold text-red-600">{{ $totalQuestions - $correctAnswers }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center">
                <i class="fas fa-clock text-4xl text-blue-500 mb-3"></i>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">เวลาที่ใช้</p>
                <p class="text-4xl font-bold text-blue-600">{{ $attempt->duration }}</p>
            </div>
        </div>

        <!-- Passing Score Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-700 dark:text-gray-300 font-semibold">เกณฑ์ผ่าน: {{ $quiz->passing_score }}%</span>
                <span class="text-gray-700 dark:text-gray-300 font-semibold">คะแนนของคุณ: {{ $attempt->score }}%</span>
            </div>
            <div class="relative w-full bg-gray-200 dark:bg-gray-700 rounded-full h-6">
                <div class="absolute top-0 left-0 h-6 {{ $attempt->passed ? 'bg-green-500' : 'bg-red-500' }} rounded-full transition-all duration-500"
                    style="width: {{ $attempt->score }}%"></div>
                <div class="absolute top-0 h-6 w-1 bg-yellow-500" style="left: {{ $quiz->passing_score }}%">
                    <div class="absolute -top-8 -left-2 text-xs font-semibold text-yellow-600 dark:text-yellow-400">ผ่าน
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 mb-6">
            <a href="{{ route('student.courses.modules.quizzes.show', [$course->id, $module->id, $quiz->id]) }}"
                class="flex-1 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-center shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปหน้าแบบทดสอบ
            </a>
            @if (!$attempt->passed)
                <form action="{{ route('student.quizzes.start', $quiz->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold shadow-lg">
                        <i class="fas fa-redo mr-2"></i>ทำใหม่อีกครั้ง
                    </button>
                </form>
            @endif
        </div>

        <!-- Detailed Answers -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <svg class="w-5 h-5 inline mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    รายละเอียดคำตอบ
                </h3>
            </div>
            <div class="p-6 space-y-6">
                @foreach ($quiz->questions as $index => $question)
                    @php
                        $userAnswerId = $attempt->answers[$question->id] ?? null;
                        $userAnswer = $question->answers->firstWhere('id', $userAnswerId);
                        $correctAnswer = $question->answers->firstWhere('is_correct', true);
                        $isCorrect = $correctAnswer && $userAnswerId == $correctAnswer->id;
                    @endphp

                    <div
                        class="border-2 {{ $isCorrect ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-red-500 bg-red-50 dark:bg-red-900/20' }} rounded-lg p-6">
                        <!-- Question -->
                        <div class="flex items-start gap-4 mb-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 {{ $isCorrect ? 'bg-green-500' : 'bg-red-500' }} text-white rounded-full flex items-center justify-center font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ $question->question_text }}</h4>

                                <!-- Result Badge -->
                                @if ($isCorrect)
                                    <span
                                        class="inline-flex items-center px-3 py-1 bg-green-500 text-white rounded-full text-sm font-semibold">
                                        <i class="fas fa-check mr-1"></i>ถูกต้อง
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">
                                        <i class="fas fa-times mr-1"></i>ผิด
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Answers -->
                        <div class="space-y-3 ml-14">
                            @foreach ($question->answers as $answer)
                                @php
                                    $isUserAnswer = $answer->id == $userAnswerId;
                                    $isCorrectAnswer = $correctAnswer && $answer->id == $correctAnswer->id;
                                @endphp
                                <div
                                    class="flex items-start p-3 rounded-lg
                                {{ $isUserAnswer && $isCorrect ? 'bg-green-200 dark:bg-green-800 border-2 border-green-500' : '' }}
                                {{ $isUserAnswer && !$isCorrect ? 'bg-red-200 dark:bg-red-800 border-2 border-red-500' : '' }}
                                {{ $isCorrectAnswer && !$isCorrect ? 'bg-green-100 dark:bg-green-900 border-2 border-green-400 border-dashed' : '' }}
                                {{ !$isUserAnswer && !$isCorrectAnswer ? 'bg-gray-100 dark:bg-gray-700' : '' }}">

                                    <div class="flex-shrink-0 mt-1">
                                        @if ($isUserAnswer && $isCorrect)
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        @elseif ($isUserAnswer && !$isCorrect)
                                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                        @elseif ($isCorrectAnswer)
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        @else
                                            <i class="far fa-circle text-gray-400 text-xl"></i>
                                        @endif
                                    </div>

                                    <div class="ml-3 flex-1">
                                        <p class="text-gray-900 dark:text-white font-medium">{{ $answer->answer_text }}</p>
                                        @if ($isUserAnswer)
                                            <span
                                                class="text-xs {{ $isCorrect ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }} font-semibold">
                                                คำตอบของคุณ
                                            </span>
                                        @endif
                                        @if ($isCorrectAnswer && !$isCorrect)
                                            <span class="text-xs text-green-700 dark:text-green-300 font-semibold">
                                                คำตอบที่ถูกต้อง
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom Actions -->
        <div class="mt-6 flex gap-4">
            <a href="{{ route('student.courses.show', $course->id) }}"
                class="flex-1 py-4 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-bold text-center shadow-lg">
                <i class="fas fa-book mr-2"></i>กลับไปยังคอร์ส
            </a>
            @if (!$attempt->passed)
                <form action="{{ route('student.quizzes.start', $quiz->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold shadow-lg">
                        <i class="fas fa-redo mr-2"></i>ลองทำใหม่
                    </button>
                </form>
            @else
                <a href="{{ route('student.courses.show', $course->id) }}"
                    class="flex-1 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-center shadow-lg">
                    <i class="fas fa-forward mr-2"></i>เรียนต่อ
                </a>
            @endif
        </div>
    </div>

    <script>
        // Animate score on load
        window.addEventListener('load', function() {
            const scoreElements = document.querySelectorAll('.animate-bounce');
            scoreElements.forEach(el => {
                setTimeout(() => {
                    el.classList.remove('animate-bounce');
                }, 2000);
            });
        });
    </script>
@endsection
