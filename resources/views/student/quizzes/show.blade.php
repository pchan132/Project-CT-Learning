@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ $quiz->title }}
    </h2>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('student.courses.index') }}" class="hover:text-blue-600">รายวิชาของฉัน</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('student.courses.show', $course->id) }}" class="hover:text-blue-600">{{ $course->title }}</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <span>{{ $quiz->title }}</span>
        </div>

        @if (session('success'))
            <div
                class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Quiz Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="p-6 md:p-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $quiz->title }}</h1>
                        <p class="text-gray-600 dark:text-gray-400">Module: {{ $module->title }}</p>
                    </div>
                    @if ($hasPassed)
                        <span
                            class="px-4 py-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-sm font-bold">
                            <i class="fas fa-check-circle mr-1"></i>ผ่านแล้ว
                        </span>
                    @endif
                </div>

                @if ($quiz->description)
                    <p class="text-gray-700 dark:text-gray-300 mb-6">{{ $quiz->description }}</p>
                @endif

                <!-- Quiz Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-question-circle text-2xl text-blue-500"></i>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">จำนวนคำถาม</p>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ $quiz->questions->count() }} ข้อ</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-2xl text-green-500"></i>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">คะแนนผ่าน</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ $quiz->passing_score }}%</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-clock text-2xl text-purple-500"></i>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">เวลาจำกัด</p>
                                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                    {{ $quiz->time_limit ? $quiz->time_limit . ' นาที' : 'ไม่จำกัด' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div
                    class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>คำแนะนำ
                    </h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-yellow-700 dark:text-yellow-400">
                        <li>คุณสามารถทำแบบทดสอบนี้ได้ไม่จำกัดครั้ง</li>
                        <li>คะแนนที่ดีที่สุดจะถูกบันทึก</li>
                        <li>คุณต้องได้คะแนนอย่างน้อย {{ $quiz->passing_score }}% จึงจะถือว่าผ่าน</li>
                        @if ($quiz->time_limit)
                            <li>คุณมีเวลา {{ $quiz->time_limit }} นาทีในการทำแบบทดสอบ</li>
                        @endif
                        <li>เมื่อเริ่มทำแบบทดสอบแล้ว คุณต้องทำให้เสร็จในครั้งเดียว</li>
                    </ul>
                </div>

                <!-- Start Quiz Button -->
                @if ($quiz->questions->count() > 0)
                    <form action="{{ route('student.quizzes.start', $quiz->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-lg shadow-lg">
                            <i class="fas fa-play-circle mr-2"></i>
                            @if ($attempts->count() > 0)
                                ทำแบบทดสอบอีกครั้ง
                            @else
                                เริ่มทำแบบทดสอบ
                            @endif
                        </button>
                    </form>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-600 dark:text-gray-400">แบบทดสอบนี้ยังไม่มีคำถาม</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Attempts History -->
        @if ($attempts->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-history text-blue-500 mr-2"></i>ประวัติการทำแบบทดสอบ
                    </h3>
                </div>
                <div class="p-6">
                    @if ($bestAttempt)
                        <div
                            class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">คะแนนสูงสุดของคุณ</p>
                                    <p class="text-4xl font-bold text-green-600 dark:text-green-400">
                                        {{ $bestAttempt->score }}%</p>
                                </div>
                                <div class="text-right">
                                    @if ($bestAttempt->passed)
                                        <span class="px-4 py-2 bg-green-500 text-white rounded-full text-sm font-bold">
                                            <i class="fas fa-check-circle mr-1"></i>ผ่าน
                                        </span>
                                    @else
                                        <span class="px-4 py-2 bg-red-500 text-white rounded-full text-sm font-bold">
                                            <i class="fas fa-times-circle mr-1"></i>ไม่ผ่าน
                                        </span>
                                    @endif
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $bestAttempt->completed_at ? $bestAttempt->completed_at->diffForHumans() : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-3">
                        @foreach ($attempts as $attempt)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : 'กำลังทำ...' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            เวลาที่ใช้: {{ $attempt->duration }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <p
                                                class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $attempt->score }}%
                                            </p>
                                            @if ($attempt->passed)
                                                <span class="text-xs text-green-600 font-semibold">ผ่าน</span>
                                            @else
                                                <span class="text-xs text-red-600 font-semibold">ไม่ผ่าน</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('student.attempts.result', $attempt->id) }}"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                            <i class="fas fa-eye mr-1"></i>ดูผลลัพธ์
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
