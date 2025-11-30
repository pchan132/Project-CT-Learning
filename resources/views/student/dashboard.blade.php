@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        <i class="fas fa-home mr-2"></i>แดชบอร์ดนักเรียน
    </h2>
@endsection

@section('content')
    @php
        $user = auth()->user();
        $enrolledCourses = $user
            ->enrolledCourses()
            ->with(['modules.lessons', 'teacher'])
            ->get();
        $enrolledCoursesCount = $enrolledCourses->count();

        // Get in-progress course
        $inProgressCourse = null;
        $nextLesson = null;
        $courseProgress = 0;

        foreach ($enrolledCourses as $course) {
            $progress = $course->getProgressForStudent($user->id);
            if ($progress > 0 && $progress < 100) {
                $inProgressCourse = $course;
                $courseProgress = $progress;
                foreach ($course->modules()->ordered()->get() as $module) {
                    foreach ($module->lessons()->ordered()->get() as $lesson) {
                        if (!$lesson->isCompletedByStudent($user->id)) {
                            $nextLesson = $lesson;
                            break 2;
                        }
                    }
                }
                break;
            }
        }

        if (!$inProgressCourse) {
            foreach ($enrolledCourses as $course) {
                if ($course->getProgressForStudent($user->id) == 0) {
                    $inProgressCourse = $course;
                    $nextLesson = $course->modules()->ordered()->first()?->lessons()->ordered()->first();
                    break;
                }
            }
        }

        $completedLessonsCount = App\Models\LessonCompletion::where('student_id', $user->id)->count();
        $certificates = App\Models\Certificate::where('student_id', $user->id)->count();
        $recentAttempts = App\Models\QuizAttempt::where('student_id', $user->id)
            ->with('quiz.module.course')
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->take(3)
            ->get();
        $passedQuizzes = App\Models\QuizAttempt::where('student_id', $user->id)->where('passed', true)->count();
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Continue Learning Hero -->
        @if ($inProgressCourse)
            <div
                class="bg-gradient-to-r from-blue-600 via-blue-700 to-purple-700 rounded-2xl shadow-xl mb-8 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex-1">
                            <p class="text-blue-100 text-sm font-medium mb-1">
                                <i class="fas fa-hand-wave mr-1"></i>ยินดีต้อนรับกลับมา, {{ $user->name }}!
                            </p>
                            <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">เรียนต่อจากที่ค้างไว้</h2>
                            <p class="text-white/90 text-lg mb-4">{{ $inProgressCourse->title }}</p>

                            <div class="mb-4">
                                <div class="flex items-center justify-between text-sm text-blue-100 mb-2">
                                    <span>ความคืบหน้า</span>
                                    <span class="font-bold text-white">{{ $courseProgress }}%</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-3">
                                    <div class="bg-white rounded-full h-3 transition-all duration-500"
                                        style="width: {{ $courseProgress }}%"></div>
                                </div>
                            </div>

                            @if ($nextLesson)
                                <p class="text-blue-100 text-sm">
                                    <i class="fas fa-play-circle mr-2"></i>บทถัดไป: {{ $nextLesson->title }}
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            @if ($nextLesson)
                                <a href="{{ route('student.courses.learn-lesson', [$inProgressCourse, $nextLesson]) }}"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-white text-blue-700 font-bold rounded-xl hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-play mr-2"></i>เรียนต่อ
                                </a>
                            @endif
                            <a href="{{ route('student.courses.show', $inProgressCourse) }}"
                                class="inline-flex items-center justify-center px-6 py-3 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition border border-white/30">
                                <i class="fas fa-list mr-2"></i>ดูเนื้อหา
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-purple-700 rounded-2xl shadow-xl mb-8 p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">
                    ยินดีต้อนรับ, {{ $user->name }}! 👋
                </h2>
                <p class="text-blue-100 mb-6">เริ่มต้นการเรียนรู้ของคุณโดยการสำรวจคอร์สที่น่าสนใจ</p>
                <a href="{{ route('student.courses.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-white text-blue-700 font-bold rounded-xl hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-search mr-2"></i>สำรวจคอร์ส
                </a>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('student.courses.my-courses') }}"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $enrolledCoursesCount }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">คอร์สที่ลงทะเบียน</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-book text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </a>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $completedLessonsCount }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">บทเรียนที่เสร็จ</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $passedQuizzes }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">แบบทดสอบที่ผ่าน</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-trophy text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <a href="{{ route('student.certificates.index') }}"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:border-yellow-300 dark:hover:border-yellow-600 transition-all duration-200 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $certificates }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ใบประกาศนียบัตร</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-certificate text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- My Courses -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-book-open text-blue-500 mr-2"></i>คอร์สของฉัน
                        </h3>
                        <a href="{{ route('student.courses.my-courses') }}"
                            class="text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline flex items-center">
                            ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    @if ($enrolledCourses->count() > 0)
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($enrolledCourses->take(4) as $course)
                                @php $progress = $course->getProgressForStudent($user->id); @endphp
                                <a href="{{ route('student.courses.show', $course) }}"
                                    class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if ($course->cover_image_url)
                                                <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                                                    class="w-16 h-16 rounded-lg object-cover shadow">
                                            @else
                                                <div
                                                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center shadow">
                                                    <i class="fas fa-book text-white text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $course->title }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-user-tie mr-1"></i>{{ $course->teacher->name }}
                                            </p>
                                            <div class="mt-2 flex items-center space-x-3">
                                                <div
                                                    class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2 overflow-hidden">
                                                    <div class="h-2 rounded-full transition-all duration-500 {{ $progress == 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                                                        style="width: {{ $progress }}%"></div>
                                                </div>
                                                <span
                                                    class="text-sm font-medium {{ $progress == 100 ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400' }}">{{ $progress }}%</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @if ($progress == 100)
                                                <span
                                                    class="px-3 py-1.5 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 text-xs font-medium rounded-full flex items-center">
                                                    <i class="fas fa-check mr-1"></i>เสร็จสิ้น
                                                </span>
                                            @elseif($progress > 0)
                                                <span
                                                    class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-xs font-medium rounded-full flex items-center">
                                                    <i class="fas fa-spinner fa-spin mr-1"></i>กำลังเรียน
                                                </span>
                                            @else
                                                <span
                                                    class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full flex items-center">
                                                    <i class="fas fa-clock mr-1"></i>ยังไม่เริ่ม
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div
                                class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-book-open text-gray-400 text-3xl"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2 text-lg">ยังไม่มีคอร์สเรียน</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">เริ่มต้นการเรียนรู้ของคุณวันนี้!</p>
                            <a href="{{ route('student.courses.index') }}"
                                class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow hover:shadow-lg">
                                <i class="fas fa-search mr-2"></i>ค้นหาคอร์ส
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Recent Quiz Results -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-clipboard-check text-purple-500 mr-2"></i>แบบทดสอบล่าสุด
                        </h3>
                    </div>
                    @if ($recentAttempts->count() > 0)
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($recentAttempts as $attempt)
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white text-sm truncate pr-2">
                                            {{ $attempt->quiz->title }}</h4>
                                        <span
                                            class="text-lg font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-500' }}">{{ $attempt->score }}%</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span
                                            class="truncate pr-2">{{ $attempt->quiz->module->course->title ?? '' }}</span>
                                        @if ($attempt->passed)
                                            <span class="text-green-600 flex items-center">
                                                <i class="fas fa-check-circle mr-1"></i>ผ่าน
                                            </span>
                                        @else
                                            <span class="text-red-500 flex items-center">
                                                <i class="fas fa-times-circle mr-1"></i>ไม่ผ่าน
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <div
                                class="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clipboard text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">ยังไม่มีผลแบบทดสอบ</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-5">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>การดำเนินการด่วน
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('student.courses.index') }}"
                            class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-search text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">ค้นหาคอร์สใหม่</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">สำรวจคอร์สที่น่าสนใจ</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                        </a>
                        <a href="{{ route('teachers.index') }}"
                            class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-chalkboard-teacher text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">อาจารย์ผู้สอน</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ดูอาจารย์ทั้งหมด</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                        </a>
                        <a href="{{ route('student.courses.my-courses') }}"
                            class="flex items-center p-3 bg-green-50 dark:bg-green-900/30 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-book-reader text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">คอร์สของฉัน</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ดูคอร์สที่ลงทะเบียน</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                        </a>
                        <a href="{{ route('student.certificates.index') }}"
                            class="flex items-center p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-certificate text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">ใบประกาศนียบัตร</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ดาวน์โหลดใบประกาศ</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
