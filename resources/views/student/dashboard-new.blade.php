@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        แดชบอร์ด
    </h2>
@endsection

@section('content')
    @php
        $user = auth()->user();
        $enrolledCourses = $user
            ->enrolledCourses()
            ->with(['teacher', 'modules.lessons'])
            ->get();
        $completedLessonsCount = App\Models\LessonCompletion::where('student_id', $user->id)->count();
        $certificates = App\Models\Certificate::where('student_id', $user->id)->get();

        // Find course to continue (most recent with progress < 100%)
        $continueCourse = null;
        $continueLesson = null;
        foreach ($enrolledCourses as $course) {
            $progress = $course->getProgressForStudent($user->id);
            if ($progress > 0 && $progress < 100) {
                $continueCourse = $course;
                // Find next incomplete lesson
                foreach ($course->modules()->ordered()->get() as $module) {
                    foreach ($module->lessons()->ordered()->get() as $lesson) {
                        if (!$lesson->isCompletedByStudent($user->id)) {
                            $continueLesson = $lesson;
                            break 2;
                        }
                    }
                }
                break;
            }
        }

        // Calculate stats
        $totalLessons = 0;
        $completedLessons = 0;
        foreach ($enrolledCourses as $course) {
            $totalLessons += $course->total_lessons;
            $completedLessons += $course->getCompletedLessonsCount($user->id);
        }
        $overallProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        // Recent quiz attempts
        $recentAttempts = App\Models\QuizAttempt::where('student_id', $user->id)
            ->with('quiz')
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->take(3)
            ->get();
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Welcome Banner -->
        <div
            class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-xl p-6 md:p-8 mb-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>

            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold mb-2">
                            สวัสดี, {{ $user->name }}! 👋
                        </h1>
                        <p class="text-blue-100 text-sm md:text-base">
                            @if ($continueCourse)
                                คุณกำลังเรียน {{ $enrolledCourses->count() }} คอร์ส • ความคืบหน้ารวม {{ $overallProgress }}%
                            @else
                                เริ่มต้นการเรียนรู้ใหม่วันนี้
                            @endif
                        </p>
                    </div>

                    @if ($continueCourse && $continueLesson)
                        <a href="{{ route('student.courses.learn-lesson', [$continueCourse, $continueLesson]) }}"
                            class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-colors shadow-lg">
                            <i class="fas fa-play-circle mr-2"></i>
                            เรียนต่อ
                        </a>
                    @else
                        <a href="{{ route('student.courses.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-colors shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            ค้นหาคอร์ส
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Continue Learning Section -->
        @if ($continueCourse)
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-play text-blue-500 mr-2"></i>
                    เรียนต่อจากที่ค้างไว้
                </h2>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-5">
                        <div class="flex flex-col md:flex-row gap-5">
                            <!-- Course Image -->
                            <div class="w-full md:w-48 h-32 md:h-auto flex-shrink-0">
                                @if ($continueCourse->cover_image_url)
                                    <img src="{{ asset('storage/' . $continueCourse->cover_image_url) }}"
                                        alt="{{ $continueCourse->title }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-white text-3xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Course Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 truncate">
                                    {{ $continueCourse->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <i class="fas fa-user mr-1"></i> {{ $continueCourse->teacher->name }}
                                </p>

                                <!-- Progress -->
                                @php $progress = $continueCourse->getProgressForStudent($user->id); @endphp
                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">ความคืบหน้า</span>
                                        <span
                                            class="font-semibold text-blue-600 dark:text-blue-400">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2.5 rounded-full transition-all duration-500"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                <!-- Next Lesson -->
                                @if ($continueLesson)
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">บทถัดไป:</span> {{ $continueLesson->title }}
                                        </div>
                                        <a href="{{ route('student.courses.learn-lesson', [$continueCourse, $continueLesson]) }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-play mr-2"></i>
                                            เรียนต่อ
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            คอร์สที่ลงทะเบียน</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $enrolledCourses->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            บทเรียนที่เสร็จ</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $completedLessons }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">ใบประกาศ</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $certificates->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-certificate text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            ความคืบหน้ารวม</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $overallProgress }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Courses & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- My Courses -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-book-reader text-blue-500 mr-2"></i>
                        คอร์สของฉัน
                    </h2>
                    <a href="{{ route('student.courses.my-courses') }}"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        ดูทั้งหมด →
                    </a>
                </div>

                @if ($enrolledCourses->count() > 0)
                    <div class="space-y-4">
                        @foreach ($enrolledCourses->take(3) as $course)
                            @php $progress = $course->getProgressForStudent($user->id); @endphp
                            <a href="{{ route('student.courses.show', $course) }}"
                                class="block bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-4 hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-800 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 flex-shrink-0">
                                        @if ($course->cover_image_url)
                                            <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                                                alt="{{ $course->title }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-book text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $course->title }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $course->teacher->name }}
                                        </p>
                                        <div class="mt-2 flex items-center gap-3">
                                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                                <div class="h-1.5 rounded-full {{ $progress == 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                                                    style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span
                                                class="text-sm font-medium {{ $progress == 100 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                                {{ $progress }}%
                                            </span>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-8 text-center">
                        <div
                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book-open text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มีคอร์สเรียน</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">เริ่มต้นการเรียนรู้ด้วยการลงทะเบียนคอร์สแรก
                        </p>
                        <a href="{{ route('student.courses.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>
                            ค้นหาคอร์ส
                        </a>
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-clock text-purple-500 mr-2"></i>
                    กิจกรรมล่าสุด
                </h2>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
                    @if ($recentAttempts->count() > 0)
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($recentAttempts as $attempt)
                                <div class="p-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                            {{ $attempt->passed ? 'bg-green-100 dark:bg-green-900/50' : 'bg-red-100 dark:bg-red-900/50' }}">
                                            <i
                                                class="fas {{ $attempt->passed ? 'fa-check' : 'fa-times' }} 
                                               {{ $attempt->passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $attempt->quiz->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $attempt->completed_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <span
                                            class="text-lg font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $attempt->score }}%
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <i class="fas fa-clipboard-list text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">ยังไม่มีกิจกรรม</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Links -->
                <div class="mt-6 space-y-3">
                    <a href="{{ route('student.courses.index') }}"
                        class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900/50 dark:hover:to-blue-800/50 transition-colors">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-search text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">ค้นหาคอร์สใหม่</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">สำรวจคอร์สที่น่าสนใจ</p>
                        </div>
                    </a>

                    <a href="{{ route('student.certificates.index') }}"
                        class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-800/30 rounded-xl hover:from-yellow-100 hover:to-yellow-200 dark:hover:from-yellow-900/50 dark:hover:to-yellow-800/50 transition-colors">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-certificate text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">ใบประกาศนียบัตร</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $certificates->count() }} ใบประกาศ</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
