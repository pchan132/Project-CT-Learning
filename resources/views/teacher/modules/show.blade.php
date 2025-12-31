@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        รายละเอียด Module
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('teacher.courses.modules.index', $course) }}"
                        class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $module->title }}</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            <a href="{{ route('teacher.courses.index') }}"
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">รายวิชาของฉัน</a>
                            <span class="mx-2">/</span>
                            <a href="{{ route('teacher.courses.show', $course) }}"
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">{{ $course->title }}</a>
                            <span class="mx-2">/</span>
                            Module ที่ {{ $module->order }}
                        </p>
                    </div>
                </div>
                <div class="flex space-x-2">

                    <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg flex items-center">
                        <i class="fas fa-edit mr-2"></i>แก้ไข Module
                    </a>
                </div>
            </div>
        </div>

        <!-- Module Info Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl mb-6">
            <div class="px-6 py-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                        <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $module->order }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">ลำดับที่</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                        <div class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $lessons->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">บทเรียนทั้งหมด</div>
                    </div>
                    <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl">
                        <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">
                            {{ $module->quizzes->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">แบบทดสอบ</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $module->created_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">สร้างเมื่อ</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg mb-6">
                <span class="font-medium"><i class="fas fa-check-circle mr-2"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Quizzes Section -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-clipboard-question text-orange-500 mr-2"></i>แบบทดสอบใน Module
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        จัดการแบบทดสอบสำหรับ "{{ $module->title }}"
                    </p>
                </div>
                <a href="{{ route('teacher.courses.modules.quizzes.create', [$course, $module]) }}"
                    class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i>สร้างแบบทดสอบ
                </a>
            </div>

            @if ($module->quizzes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach ($module->quizzes as $quiz)
                        <div
                            class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-orange-200 dark:border-orange-800">
                            <!-- Card Header -->
                            <div class="px-6 py-4 bg-orange-500 dark:bg-orange-600">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center">
                                            <i class="fas fa-clipboard-question text-white text-xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-white">{{ $quiz->title }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="px-6 py-4">
                                @if ($quiz->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                        {{ $quiz->description }}</p>
                                @endif

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-question-circle text-orange-500 w-5"></i>
                                        <span class="ml-2">คำถาม: <strong>{{ $quiz->questions->count() }}</strong>
                                            ข้อ</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-check-circle text-green-500 w-5"></i>
                                        <span class="ml-2">คะแนนผ่าน: <strong>{{ $quiz->passing_score }}%</strong></span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        @if ($quiz->time_limit)
                                            <i class="fas fa-clock text-blue-500 w-5"></i>
                                            <span class="ml-2">เวลา: <strong>{{ $quiz->time_limit }}</strong> นาที</span>
                                        @else
                                            <i class="fas fa-infinity text-blue-500 w-5"></i>
                                            <span class="ml-2">ไม่จำกัดเวลา</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div
                                class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-orange-200 dark:border-orange-800">
                                <div class="flex items-center justify-between space-x-2">
                                    <a href="{{ route('teacher.courses.modules.quizzes.show', [$course, $module, $quiz]) }}"
                                        class="flex-1 text-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition font-medium shadow-sm">
                                        <i class="fas fa-eye mr-1"></i>ดู
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.quizzes.edit', [$course, $module, $quiz]) }}"
                                        class="flex-1 text-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition font-medium shadow-sm">
                                        <i class="fas fa-edit mr-1"></i>แก้ไข
                                    </a>
                                    <form
                                        action="{{ route('teacher.courses.modules.quizzes.destroy', [$course, $module, $quiz]) }}"
                                        method="POST" class="flex-1"
                                        onsubmit="return confirm('คุณต้องการลบแบบทดสอบนี้ใช่หรือไม่? ข้อมูลทั้งหมดจะถูกลบอย่างถาวร');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition font-medium shadow-sm">
                                            <i class="fas fa-trash mr-1"></i>ลบ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 dark:bg-orange-900 rounded-full mb-4">
                        <i class="fas fa-clipboard-question text-orange-500 dark:text-orange-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มีแบบทดสอบใน Module นี้</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">เพิ่มแบบทดสอบเพื่อประเมินความเข้าใจของนักเรียน</p>
                    <a href="{{ route('teacher.courses.modules.quizzes.create', [$course, $module]) }}"
                        class="inline-flex items-center bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition">
                        <i class="fas fa-plus mr-2"></i>สร้างแบบทดสอบ
                    </a>
                </div>
            @endif
        </div>

        <!-- Lessons List -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 justify-between items-center flex">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-book-open text-green-500 "></i>รายการบทเรียนใน Module
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        จัดการบทเรียนทั้งหมดใน "{{ $module->title }}"
                    </p>
                </div>

                <div class="flex">
                    <a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>เพิ่มบทเรียน
                    </a>
                </div>
            </div>

            @if ($lessons->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($lessons as $lesson)
                        <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 flex-1">
                                    <!-- Order Badge -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-12 w-12 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                            <span
                                                class="text-green-600 dark:text-green-400 font-bold text-lg">{{ $lesson->order }}</span>
                                        </div>
                                    </div>

                                    <!-- Lesson Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $lesson->title }}</h3>
                                            @if ($lesson->content_type)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $lesson->content_type === 'PDF' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' : '' }}
                                                {{ $lesson->content_type === 'VIDEO' ? 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300' : '' }}
                                                {{ $lesson->content_type === 'TEXT' ? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' : '' }}">
                                                    {{ $lesson->content_type_label ?? ucfirst($lesson->content_type) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div
                                            class="mt-1 flex items-center space-x-3 text-sm text-gray-500 dark:text-gray-400">
                                            <span>บทเรียนที่ {{ $lesson->order }}</span>
                                            @if ($lesson->content_url)
                                                @if (method_exists($lesson, 'isFileContent') && $lesson->isFileContent())
                                                    <span>• <i class="fas fa-file mr-1"></i>มีไฟล์แนบ</span>
                                                @elseif(method_exists($lesson, 'isVideoContent') && $lesson->isVideoContent())
                                                    <span>• <i class="fas fa-video mr-1"></i>วิดีโอ</span>
                                                @endif
                                            @endif
                                            @if ($lesson->duration ?? false)
                                                <span>• <i class="fas fa-clock mr-1"></i>{{ $lesson->duration }}
                                                    นาที</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('teacher.courses.modules.lessons.show', [$course, $module, $lesson]) }}"
                                        class="p-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.lessons.edit', [$course, $module, $lesson]) }}"
                                        class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form
                                        action="{{ route('teacher.courses.modules.lessons.destroy', [$course, $module, $lesson]) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('คุณต้องการลบบทเรียนนี้ใช่หรือไม่?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <i class="fas fa-book-open text-gray-400 dark:text-gray-500 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มีบทเรียนใน Module นี้</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">เริ่มต้นโดยการสร้างบทเรียนแรกสำหรับ Module นี้</p>
                    <a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}"
                        class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition">
                        <i class="fas fa-plus mr-2"></i>สร้างบทเรียนแรก
                    </a>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('teacher.courses.modules.index', $course) }}"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปที่รายการ Modules
            </a>
        </div>
    </div>
@endsection
