@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        สร้างแบบทดสอบใหม่
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('teacher.courses.index') }}" class="hover:text-blue-600">รายวิชา</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('teacher.courses.show', $course->id) }}" class="hover:text-blue-600">{{ $course->title }}</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('teacher.courses.modules.quizzes.index', [$course->id, $module->id]) }}"
                class="hover:text-blue-600">{{ $module->title }}</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <span>สร้างแบบทดสอบใหม่</span>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">สร้างแบบทดสอบใหม่</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Module: {{ $module->title }}</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <form action="{{ route('teacher.courses.modules.quizzes.store', [$course->id, $module->id]) }}"
                    method="POST" id="quizForm">
                    @csrf

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-heading text-blue-500 mr-2"></i>ชื่อแบบทดสอบ <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror"
                            value="{{ old('title') }}" placeholder="เช่น แบบทดสอบท้ายบท PHP Basics">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-align-left text-blue-500 mr-2"></i>คำอธิบาย
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror"
                            placeholder="อธิบายเกี่ยวกับแบบทดสอบนี้...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Passing Score and Time Limit -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Passing Score -->
                        <div>
                            <label for="passing_score"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>คะแนนผ่าน (%) <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="number" name="passing_score" id="passing_score" required min="0"
                                max="100"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('passing_score') border-red-500 @enderror"
                                value="{{ old('passing_score', 80) }}" placeholder="80">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">ระบุคะแนนขั้นต่ำที่ถือว่าผ่าน (0-100)
                            </p>
                            @error('passing_score')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time Limit -->
                        <div>
                            <label for="time_limit"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clock text-blue-500 mr-2"></i>เวลาจำกัด (นาที)
                            </label>
                            <input type="number" name="time_limit" id="time_limit" min="1"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('time_limit') border-red-500 @enderror"
                                value="{{ old('time_limit') }}" placeholder="ไม่จำกัดเวลา">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">ว่างไว้หากไม่ต้องการจำกัดเวลา</p>
                            @error('time_limit')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div
                        class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            <div class="text-sm text-blue-800 dark:text-blue-300">
                                <p class="font-semibold mb-1">หมายเหตุ:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>หลังจากสร้างแบบทดสอบแล้ว คุณจะสามารถเพิ่มคำถามได้ในหน้าแก้ไข</li>
                                    <li>นักเรียนจะต้องได้คะแนนตามที่กำหนดจึงจะถือว่าผ่าน</li>
                                    <li>นักเรียนสามารถทำแบบทดสอบซ้ำได้ไม่จำกัดครั้ง</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('teacher.courses.modules.quizzes.index', [$module->course_id, $module->id]) }}"
                            class="px-6 py-3 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 font-semibold">
                            <i class="fas fa-times mr-2"></i>ยกเลิก
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-lg">
                            <i class="fas fa-check mr-2"></i>สร้างแบบทดสอบ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Prevent double submission
        document.getElementById('quizForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังสร้าง...';
        });

        // Validate passing score range
        document.getElementById('passing_score').addEventListener('change', function() {
            const value = parseInt(this.value);
            if (value < 0) this.value = 0;
            if (value > 100) this.value = 100;
        });

        // Validate time limit
        document.getElementById('time_limit').addEventListener('change', function() {
            const value = parseInt(this.value);
            if (value < 0) this.value = '';
        });
    </script>
@endsection
