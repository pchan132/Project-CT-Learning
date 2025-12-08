@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        <i class="fas fa-search mr-2"></i>ค้นหารายวิชา
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>สำรวจรายวิชา
            </h1>
            <p class="text-gray-600 dark:text-gray-400">ค้นหาและลงทะเบียนรายวิชาที่คุณสนใจ</p>
        </div>

        <!-- Search Bar -->
        <div class="mb-8">
            <form action="{{ route('student.courses.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="ค้นหาคอร์ส... (ชื่อคอร์ส, คำอธิบาย)"
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium flex items-center">
                    <i class="fas fa-search mr-2"></i>ค้นหา
                </button>
                @if ($search ?? false)
                    <a href="{{ route('student.courses.index') }}"
                        class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition font-medium flex items-center">
                        <i class="fas fa-times mr-2"></i>ล้าง
                    </a>
                @endif
            </form>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-book-reader text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600 dark:text-blue-400">รายวิชาที่ลงทะเบียน</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $enrolledCourses->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/30 rounded-xl p-4 border border-green-100 dark:border-green-800">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-plus-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-green-600 dark:text-green-400">รายวิชาที่สามารถลงทะเบียน</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $availableCourses->total() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4 border border-purple-100 dark:border-purple-800">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-purple-600 dark:text-purple-400">รายวิชาทั้งหมด</p>
                        <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">
                            {{ $enrolledCourses->total() + $availableCourses->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                {{ session('info') }}
            </div>
        @endif

        <!-- Enrolled Courses Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center">
                <i class="fas fa-book-reader text-blue-500 mr-3"></i>
                รายวิชาที่ลงทะเบียนแล้ว
                @if ($enrolledCourses->total() > 0)
                    <span
                        class="ml-3 px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-full">
                        {{ $enrolledCourses->total() }} รายวิชา
                    </span>
                @endif
            </h2>

            @if ($enrolledCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($enrolledCourses as $course)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group">
                            <!-- Course Cover Image -->
                            @if ($course->cover_image_url)
                                <div class="h-44 bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                                    <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                                        alt="{{ $course->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @else
                                <div
                                    class="h-44 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-book text-5xl text-white opacity-50"></i>
                                </div>
                            @endif

                            <div class="p-5">
                                <!-- Course Title -->
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    {{ $course->title }}</h3>

                                <!-- Teacher Info -->
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <i class="fas fa-user-tie mr-2 text-gray-400"></i>
                                    {{ $course->teacher->name }}
                                </div>

                                <!-- Course Stats -->
                                <div
                                    class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <span class="flex items-center">
                                        <i class="fas fa-folder mr-1"></i>{{ $course->total_modules }} โมดูล
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-file-alt mr-1"></i>{{ $course->total_lessons }} บทเรียน
                                    </span>
                                </div>

                                <!-- Progress Bar -->
                                @php $progress = $course->getProgressForStudent(auth()->id()); @endphp
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        <span>ความคืบหน้า</span>
                                        <span
                                            class="font-semibold {{ $progress == 100 ? 'text-green-600' : '' }}">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                        <div class="{{ $progress == 100 ? 'bg-green-500' : 'bg-blue-500' }} h-2 rounded-full transition-all duration-500"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('student.courses.show', $course) }}"
                                        class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition text-center font-medium flex items-center justify-center">
                                        <i class="fas fa-play mr-2"></i>
                                        {{ $progress == 100 ? 'ทบทวน' : ($progress > 0 ? 'เรียนต่อ' : 'เริ่มเรียน') }}
                                    </a>
                                    <form action="{{ route('student.courses.unenroll', $course) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('คุณแน่ใจหรือไม่ที่จะถอนการลงทะเบียนรายวิชานี้?')"
                                            class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-2.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination for Enrolled Courses -->
                @if ($enrolledCourses->hasPages())
                    <div class="mt-6">
                        {{ $enrolledCourses->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div
                    class="text-center py-12 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div
                        class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book-open text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">ยังไม่มีรายวิชาที่ลงทะเบียน</h3>
                    <p class="text-gray-600 dark:text-gray-400">เริ่มต้นโดยการลงทะเบียนรายวิชาจากรายการด้านล่าง</p>
                </div>
            @endif
        </div>

        <!-- Available Courses Section -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center">
                <i class="fas fa-plus-circle text-green-500 mr-3"></i>
                รายวิชาที่สามารถลงทะเบียนได้
                @if ($availableCourses->total() > 0)
                    <span
                        class="ml-3 px-3 py-1 text-sm bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded-full">
                        {{ $availableCourses->total() }} รายวิชา
                    </span>
                @endif
            </h2>

            @if ($availableCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($availableCourses as $course)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group">
                            <!-- Course Cover Image -->
                            @if ($course->cover_image_url)
                                <div class="h-44 bg-gradient-to-br from-green-500 to-blue-600 overflow-hidden">
                                    <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                                        alt="{{ $course->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @else
                                <div
                                    class="h-44 bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-book text-5xl text-white opacity-50"></i>
                                </div>
                            @endif

                            <div class="p-5">
                                <!-- Course Title -->
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    {{ $course->title }}</h3>

                                <!-- Course Description -->
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                    {{ $course->description }}</p>

                                <!-- Teacher Info -->
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <i class="fas fa-user-tie mr-2 text-gray-400"></i>
                                    ผู้สอน: {{ $course->teacher->name }}
                                </div>

                                <!-- Course Stats -->
                                <div
                                    class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <span class="flex items-center">
                                        <i class="fas fa-folder mr-1"></i>{{ $course->total_modules }} โมดูล
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-file-alt mr-1"></i>{{ $course->total_lessons }} บทเรียน
                                    </span>
                                </div>

                                <!-- View Details & Enroll Button -->
                                <a href="{{ route('student.courses.preview', $course) }}"
                                    class="w-full bg-green-600 text-white px-4 py-2.5 rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    ดูรายละเอียด & ลงทะเบียน
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination for Available Courses -->
                @if ($availableCourses->hasPages())
                    <div class="mt-6">
                        {{ $availableCourses->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div
                    class="text-center py-12 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div
                        class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">ไม่มีรายวิชาใหม่ในขณะนี้</h3>
                    <p class="text-gray-600 dark:text-gray-400">รายวิชาที่มีอยู่ทั้งหมดคุณได้ลงทะเบียนไปแล้ว</p>
                </div>
            @endif
        </div>
    </div>
@endsection
