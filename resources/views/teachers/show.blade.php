@extends('layouts.app')

@section('title', $teacher->name . ' - อาจารย์')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('teachers.index') }}"
                class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                กลับไปหน้าอาจารย์ทั้งหมด
            </a>
        </div>

        <!-- Teacher Profile Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="md:flex">
                <!-- Profile Image Section -->
                <div class="md:w-1/3 bg-gradient-to-br from-blue-500 to-purple-600 p-8 flex items-center justify-center">
                    <div class="text-center">
                        @if ($teacher->profile_image_url)
                            <img src="{{ $teacher->profile_image_url }}" alt="{{ $teacher->name }}"
                                class="w-48 h-48 md:w-56 md:h-56 rounded-full object-cover border-4 border-white shadow-2xl mx-auto">
                        @else
                            <div
                                class="w-48 h-48 md:w-56 md:h-56 rounded-full bg-white/20 flex items-center justify-center mx-auto border-4 border-white/50">
                                <span class="text-6xl md:text-7xl font-bold text-white">{{ $teacher->initials }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Section -->
                <div class="md:w-2/3 p-8">
                    <div class="mb-6">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $teacher->name }}
                        </h1>
                        @if ($teacher->position)
                            <p class="text-xl text-blue-600 dark:text-blue-400 font-semibold">
                                {{ $teacher->position }}
                            </p>
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $courses->count() }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">คอร์สที่สอน</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/30 rounded-xl p-4 text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                {{ $courses->sum('enrollments_count') }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">นักเรียนทั้งหมด</div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4 text-center col-span-2 md:col-span-1">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                {{ $teacher->created_at->diffInMonths(now()) ?: 1 }}+
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">เดือนที่สอน</div>
                        </div>
                    </div>

                    <!-- Bio -->
                    @if ($teacher->bio)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-quote-left text-blue-500 mr-2"></i>เกี่ยวกับอาจารย์
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ $teacher->bio }}
                            </p>
                        </div>
                    @endif

                    <!-- Contact -->
                    <div class="mt-6 flex items-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-envelope mr-2"></i>
                        <span>{{ $teacher->email }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Section -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                <i class="fas fa-book-open text-blue-500 mr-2"></i>
                คอร์สที่สอน ({{ $courses->count() }} คอร์ส)
            </h2>

            @if ($courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($courses as $course)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <!-- Course Image -->
                            <div
                                class="aspect-video bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 relative">
                                @if ($course->cover_image_url)
                                    <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                                        alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-book text-6xl text-blue-300 dark:text-blue-700"></i>
                                    </div>
                                @endif

                                <!-- Student Count Badge -->
                                <div
                                    class="absolute bottom-3 right-3 bg-black/70 text-white text-xs font-medium px-3 py-1 rounded-full">
                                    <i class="fas fa-users mr-1"></i>{{ $course->enrollments_count }} นักเรียน
                                </div>
                            </div>

                            <!-- Course Info -->
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    {{ $course->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                    {{ $course->description ?? 'ไม่มีคำอธิบาย' }}
                                </p>

                                <!-- Action Button -->
                                @auth
                                    @if (auth()->user()->isTeacher() && auth()->id() === $teacher->id)
                                        <a href="{{ route('teacher.courses.show', $course) }}"
                                            class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                                            จัดการคอร์ส
                                        </a>
                                    @else
                                        <a href="{{ route('courses.preview', $course) }}"
                                            class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                                            ดูรายละเอียดคอร์ส
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block w-full text-center bg-gray-600 hover:bg-gray-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                                        เข้าสู่ระบบเพื่อดูคอร์ส
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl">
                    <div
                        class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-book-open text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มีคอร์สที่เปิดสอน</h3>
                    <p class="text-gray-500 dark:text-gray-400">อาจารย์ยังไม่ได้เปิดสอนคอร์สในขณะนี้</p>
                </div>
            @endif
        </div>
    </div>
@endsection
