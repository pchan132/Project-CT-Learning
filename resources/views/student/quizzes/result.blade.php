@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        ผลการทำแบบทดสอบ
    </h2>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('student.courses.index') }}" class="hover:text-blue-600">รายวิชาของฉัน</a>
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
                    <i class="fas fa-check-circle text-8xl mb-6 animate-bounce"></i>
                    <h1 class="text-4xl md:text-5xl font-bold mb-3">ผ่าน!</h1>
                    <p class="text-xl opacity-90">คุณผ่านแบบทดสอบนี้แล้ว</p>
                @else
                    <i class="fas fa-times-circle text-8xl mb-6"></i>
                    <h1 class="text-4xl md:text-5xl font-bold mb-3">ไม่ผ่าน</h1>
                    <p class="text-xl opacity-90">ลองทำใหม่อีกครั้งนะ</p>
                @endif
            </div>
        </div>

        <!-- Score Summary - แสดงแค่คะแนนและเกณฑ์ผ่าน -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">คะแนนที่ได้</p>
                    <p class="text-5xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                        {{ $attempt->score }}%
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">เกณฑ์ผ่าน</p>
                    <p class="text-5xl font-bold text-gray-600 dark:text-gray-300">
                        {{ $quiz->passing_score }}%
                    </p>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="relative w-full bg-gray-200 dark:bg-gray-700 rounded-full h-6">
                <div class="absolute top-0 left-0 h-6 {{ $attempt->passed ? 'bg-green-500' : 'bg-red-500' }} rounded-full transition-all duration-500"
                    style="width: {{ min($attempt->score, 100) }}%"></div>
                <div class="absolute top-0 h-6 w-1 bg-yellow-500" style="left: {{ $quiz->passing_score }}%">
                    <div
                        class="absolute -top-7 -left-3 text-xs font-semibold text-yellow-600 dark:text-yellow-400 whitespace-nowrap">
                        เกณฑ์ผ่าน
                    </div>
                </div>
            </div>

            <!-- Summary Info -->
            <div class="mt-6 flex justify-center gap-8 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-2">
                    <i class="fas fa-question-circle text-blue-500"></i>
                    <span>จำนวนข้อ: {{ $totalQuestions }} ข้อ</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-purple-500"></i>
                    <span>เวลาที่ใช้: {{ $attempt->duration }}</span>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <div class="text-sm text-blue-700 dark:text-blue-300">
                    <p class="font-semibold mb-1">หมายเหตุ</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>คุณสามารถทำแบบทดสอบนี้ได้อีกไม่จำกัดครั้ง</li>
                        <li>คะแนนที่ดีที่สุดจะถูกบันทึกไว้</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('student.courses.show', $course->id) }}"
                class="flex-1 py-4 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-bold text-center shadow-lg transition-colors">
                <i class="fas fa-book mr-2"></i>กลับไปยังรายวิชา
            </a>
            <form action="{{ route('student.quizzes.start', $quiz->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full py-4 {{ $attempt->passed ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg font-bold shadow-lg transition-colors">
                    <i class="fas fa-redo mr-2"></i>ทำแบบทดสอบอีกครั้ง
                </button>
            </form>
        </div>
    </div>

    <script>
        // Animate on load
        window.addEventListener('load', function() {
            const bounceEl = document.querySelector('.animate-bounce');
            if (bounceEl) {
                setTimeout(() => {
                    bounceEl.classList.remove('animate-bounce');
                }, 2000);
            }
        });
    </script>
@endsection
