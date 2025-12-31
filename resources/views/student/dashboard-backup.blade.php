@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        แดชบอร์ดนักเรียน
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                ยินดีต้อนรับ, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-gray-600 dark:text-gray-400">ติดตามความคืบหน้าการเรียนของคุณได้ที่นี่</p>
        </div>

        @php
            $enrolledCoursesCount = auth()->user()->enrolledCourses()->count();
            $completedLessonsCount = App\Models\LessonCompletion::where('student_id', auth()->id())->count();
            $quizAttempts = App\Models\QuizAttempt::where('student_id', auth()->id())->get();
            $passedQuizzes = $quizAttempts->where('passed', true)->unique('quiz_id')->count();

            // Calculate overall progress
            $totalLessons = 0;
            $completedLessons = 0;
            foreach (auth()->user()->enrolledCourses as $course) {
                foreach ($course->modules as $module) {
                    $totalLessons += $module->lessons->count();
                    $completedLessons += App\Models\LessonCompletion::where('student_id', auth()->id())
                        ->whereIn('lesson_id', $module->lessons->pluck('id'))
                        ->count();
                }
            }
            $overallProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        @endphp

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <i class="fas fa-book text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">รายวิชาที่ลงทะเบียน</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $enrolledCoursesCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <i class="fas fa-check-circle text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">บทเรียนที่เรียนเสร็จ</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $completedLessonsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <i class="fas fa-trophy text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">แบบทดสอบที่ผ่าน</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $passedQuizzes }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                        <i class="fas fa-chart-line text-2xl text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">ความคืบหน้ารวม</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $overallProgress }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Courses & Quiz Scores -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Courses -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-book text-blue-500 mr-2"></i>รายวิชาล่าสุด
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $recentCourses = auth()->user()->enrolledCourses()->latest()->take(3)->get();
                    @endphp

                    @if ($recentCourses->count() > 0)
                        <div class="space-y-4">
                            @foreach ($recentCourses as $course)
                                <a href="{{ route('student.courses.show', $course->id) }}"
                                    class="block p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1">
                                            @if ($course->image_path)
                                                <img src="{{ asset('storage/' . $course->image_path) }}"
                                                    alt="{{ $course->title }}"
                                                    class="w-12 h-12 rounded-lg object-cover mr-4">
                                            @else
                                                <div
                                                    class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                                                    <i class="fas fa-book text-blue-600 dark:text-blue-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $course->title }}
                                                </h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">โดย
                                                    {{ $course->teacher->name }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right ml-4">
                                            @php
                                                $progress = $course->getProgressForStudent(auth()->id());
                                            @endphp
                                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">ความคืบหน้า</div>
                                            <div class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                                {{ $progress }}%</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-book-open text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">ยังไม่มีรายวิชาเรียน</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">เริ่มต้นโดยการลงทะเบียนรายวิชาแรกของคุณ</p>
                            <a href="{{ route('student.courses.index') }}"
                                class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-semibold">
                                <i class="fas fa-plus-circle mr-2"></i>ดูรายวิชาที่เปิดสอน
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Quiz Scores -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-clipboard-question text-purple-500 mr-2"></i>แบบทดสอบล่าสุด
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $recentAttempts = App\Models\QuizAttempt::where('student_id', auth()->id())
                            ->with('quiz')
                            ->latest('completed_at')
                            ->take(5)
                            ->get();
                    @endphp

                    @if ($recentAttempts->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentAttempts as $attempt)
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900 dark:text-white text-sm">
                                            {{ $attempt->quiz->title }}</h4>
                                        @if ($attempt->passed)
                                            <span
                                                class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-xs font-semibold">
                                                ผ่าน
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full text-xs font-semibold">
                                                ไม่ผ่าน
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-400">{{ $attempt->completed_at->diffForHumans() }}</span>
                                        <span
                                            class="text-lg font-bold {{ $attempt->passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $attempt->score }}%
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-clipboard-question text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">ยังไม่มีแบบทดสอบ</h3>
                            <p class="text-gray-600 dark:text-gray-400">เริ่มเรียนรายวิชาเพื่อทำแบบทดสอบ</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <i class="fas fa-search text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold mb-2">ค้นหารายวิชาใหม่</h3>
                <p class="text-blue-100 mb-4 text-sm">สำรวจรายวิชาที่น่าสนใจและลงทะเบียนเรียนได้ทันที</p>
                <a href="{{ route('student.courses.index') }}"
                    class="inline-flex items-center bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 font-semibold">
                    <i class="fas fa-search mr-2"></i>สำรวจรายวิชา
                </a>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <i class="fas fa-book-reader text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold mb-2">เรียนต่อ</h3>
                <p class="text-green-100 mb-4 text-sm">กลับไปยังรายวิชาที่คุณกำลังเรียนอยู่</p>
                <a href="{{ route('student.courses.my-courses') }}"
                    class="inline-flex items-center bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 font-semibold">
                    <i class="fas fa-book mr-2"></i>รายวิชาของฉัน
                </a>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <i class="fas fa-trophy text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold mb-2">ผลสัมฤทธิ์</h3>
                <p class="text-purple-100 mb-4 text-sm">ดูคะแนนแบบทดสอบและความก้าวหน้าของคุณ</p>
                <a href="{{ route('student.courses.my-courses') }}"
                    class="inline-flex items-center bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 font-semibold">
                    <i class="fas fa-chart-line mr-2"></i>ดูผลการเรียน
                </a>
            </div>
        </div>
    </div>
@endsection
