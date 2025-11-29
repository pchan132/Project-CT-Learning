@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
    @php
        $totalStudents = $courses->sum(function ($course) {
            return $course->enrollments->count();
        });
        $totalLessons = $courses->sum(function ($course) {
            return $course->lessons->count();
        });
        $totalModules = $courses->sum(function ($course) {
            return $course->modules->count();
        });
        $totalQuizzes = $courses->sum(function ($course) {
            return $course->modules->sum(function ($module) {
                return $module->quizzes->count();
            });
        });

        // Recent activity - latest enrollments
        $recentEnrollments = \App\Models\Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Welcome Hero -->
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-xl mb-8 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium mb-1">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>ยินดีต้อนรับ, คุณครู!
                        </p>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                            {{ auth()->user()->name }} 👨‍🏫
                        </h1>
                        <p class="text-white/80">จัดการคอร์สและติดตามความก้าวหน้าของนักเรียน</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('teacher.courses.create') }}"
                            class="inline-flex items-center px-5 py-3 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus mr-2"></i>สร้างคอร์สใหม่
                        </a>
                        <a href="{{ route('teacher.courses.index') }}"
                            class="inline-flex items-center px-5 py-3 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition border border-white/30">
                            <i class="fas fa-list mr-2"></i>คอร์สทั้งหมด
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('teacher.courses.index') }}"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:border-indigo-300 dark:hover:border-indigo-600 transition-all duration-200 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $courses->count() }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">คอร์สของฉัน</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-book text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                </div>
            </a>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalStudents }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">นักเรียนทั้งหมด</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalModules }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">โมดูลทั้งหมด</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-folder text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalLessons }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">บทเรียนทั้งหมด</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- My Courses -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-book-open text-indigo-500 mr-2"></i>คอร์สของฉัน
                        </h3>
                        <a href="{{ route('teacher.courses.index') }}"
                            class="text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:underline flex items-center">
                            ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    @if ($courses->count() > 0)
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($courses->take(5) as $course)
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if ($course->cover_image_url)
                                                <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                                                    class="w-16 h-16 rounded-lg object-cover shadow">
                                            @else
                                                <div
                                                    class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow">
                                                    <i class="fas fa-book text-white text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $course->title }}</h4>
                                            <div
                                                class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-1 space-x-4">
                                                <span class="flex items-center">
                                                    <i class="fas fa-users mr-1"></i>{{ $course->enrollments->count() }}
                                                    นักเรียน
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-folder mr-1"></i>{{ $course->modules->count() }} โมดูล
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-file-alt mr-1"></i>{{ $course->lessons->count() }}
                                                    บทเรียน
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 flex space-x-1">
                                            <a href="{{ route('teacher.courses.students', $course) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 dark:text-green-300 dark:bg-green-900/50 dark:hover:bg-green-900/70 rounded-lg transition"
                                                title="ดูนักเรียน">
                                                <i class="fas fa-users mr-1"></i>นักเรียน
                                            </a>
                                            <a href="{{ route('teacher.courses.show', $course) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:text-indigo-300 dark:bg-indigo-900/50 dark:hover:bg-indigo-900/70 rounded-lg transition"
                                                title="จัดการคอร์ส">
                                                <i class="fas fa-cog mr-1"></i>จัดการ
                                            </a>
                                            <a href="{{ route('teacher.courses.edit', $course) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-100 hover:bg-yellow-200 dark:text-yellow-300 dark:bg-yellow-900/50 dark:hover:bg-yellow-900/70 rounded-lg transition"
                                                title="แก้ไข">
                                                <i class="fas fa-edit mr-1"></i>แก้ไข
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div
                                class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-book-open text-gray-400 text-3xl"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2 text-lg">ยังไม่มีคอร์ส</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">เริ่มต้นสร้างคอร์สแรกของคุณ</p>
                            <a href="{{ route('teacher.courses.create') }}"
                                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i>สร้างคอร์สใหม่
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Recent Enrollments -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user-plus text-green-500 mr-2"></i>นักเรียนลงทะเบียนล่าสุด
                        </h3>
                    </div>
                    @if ($recentEnrollments->count() > 0)
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($recentEnrollments as $enrollment)
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($enrollment->student->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 dark:text-white text-sm truncate">
                                                {{ $enrollment->student->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $enrollment->course->title }}</p>
                                        </div>
                                        <span
                                            class="text-xs text-gray-400">{{ $enrollment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <div
                                class="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user-plus text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">ยังไม่มีนักเรียนลงทะเบียน</p>
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
                        <a href="{{ route('teacher.courses.create') }}"
                            class="flex items-center p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-plus text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">สร้างคอร์สใหม่</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">เพิ่มคอร์สเรียนใหม่</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                        </a>
                        <a href="{{ route('teacher.profile.edit') }}"
                            class="flex items-center p-3 bg-cyan-50 dark:bg-cyan-900/30 rounded-lg hover:bg-cyan-100 dark:hover:bg-cyan-900/50 transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-user-edit text-cyan-600 dark:text-cyan-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">แก้ไขโปรไฟล์</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">อัปเดตรูปและข้อมูล</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                        </a>
                        <a href="{{ route('teachers.index') }}"
                            class="flex items-center p-3 bg-green-50 dark:bg-green-900/30 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-chalkboard-teacher text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">อาจารย์ทั้งหมด</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ดูอาจารย์ผู้สอนทั้งหมด</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                        </a>
                        <a href="{{ route('teacher.courses.index') }}"
                            class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-list text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">จัดการคอร์ส</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ดูและแก้ไขคอร์สทั้งหมด</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                        </a>
                    </div>
                </div>

                <!-- Stats Summary -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-md p-5 text-white">
                    <h3 class="font-bold mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>สรุปภาพรวม
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-indigo-100">แบบทดสอบ</span>
                            <span class="font-bold">{{ $totalQuizzes }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-indigo-100">บทเรียนทั้งหมด</span>
                            <span class="font-bold">{{ $totalLessons }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-indigo-100">นักเรียนเฉลี่ย/คอร์ส</span>
                            <span
                                class="font-bold">{{ $courses->count() > 0 ? round($totalStudents / $courses->count(), 1) : 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
