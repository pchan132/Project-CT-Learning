@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        รายวิชาของฉัน
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">รายวิชาของฉัน</h1>
            <p class="text-gray-600 dark:text-gray-400">รายวิชาที่คุณลงทะเบียนเรียน</p>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="ค้นหาด้วยชื่อรายวิชา หรือ อาจารย์ผู้สอน..."
                    class="w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <button type="button" id="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <p id="searchResultCount" class="mt-2 text-sm text-gray-500 dark:text-gray-400 hidden"></p>
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

        @if ($courses->count() > 0)
            <div id="coursesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($courses as $course)
                    <div class="course-card bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300"
                        data-title="{{ strtolower($course->title) }}"
                        data-teacher="{{ strtolower($course->teacher->name) }}">
                        <!-- Course Cover -->
                        @if ($course->cover_image_url)
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600">
                                <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @else
                            <div
                                class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-book text-6xl text-white opacity-50"></i>
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                {{ $course->title }}
                            </h3>

                            <!-- Teacher -->
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>{{ $course->teacher->name }}
                            </p>

                            <!-- Progress -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    <span>ความคืบหน้า</span>
                                    <span class="font-semibold">{{ $course->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ $course->progress }}%"></div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-4">
                                <span>
                                    <i class="fas fa-book-open mr-1"></i>
                                    {{ $course->completed_lessons }}/{{ $course->total_lessons }} บทเรียน
                                </span>
                                @if ($course->progress >= 100)
                                    <span class="text-green-600 dark:text-green-400 font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>เรียนจบแล้ว
                                    </span>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('student.courses.show', $course) }}"
                                class="block w-full text-center py-3 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                                @if ($course->progress >= 100)
                                    <i class="fas fa-redo mr-2"></i>ทบทวน
                                @elseif ($course->progress > 0)
                                    <i class="fas fa-play mr-2"></i>เรียนต่อ
                                @else
                                    <i class="fas fa-play-circle mr-2"></i>เริ่มเรียน
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-graduation-cap text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">ยังไม่ได้ลงทะเบียนรายวิชาใดๆ</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">เริ่มเรียนรู้วันนี้ด้วยการลงทะเบียนรายวิชาที่สนใจ</p>
                <a href="{{ route('student.courses.index') }}"
                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    <i class="fas fa-search mr-2"></i>ค้นหารายวิชา
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const clearBtn = document.getElementById('clearSearch');
                const resultCount = document.getElementById('searchResultCount');
                const courseCards = document.querySelectorAll('.course-card');

                if (!searchInput || courseCards.length === 0) return;

                function filterCourses() {
                    const query = searchInput.value.toLowerCase().trim();
                    let visibleCount = 0;

                    // Show/hide clear button
                    if (query.length > 0) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }

                    courseCards.forEach(card => {
                        const title = card.dataset.title || '';
                        const teacher = card.dataset.teacher || '';

                        if (query === '' || title.includes(query) || teacher.includes(query)) {
                            card.classList.remove('hidden');
                            visibleCount++;
                        } else {
                            card.classList.add('hidden');
                        }
                    });

                    // Show result count
                    if (query.length > 0) {
                        resultCount.classList.remove('hidden');
                        resultCount.textContent = `พบ ${visibleCount} รายวิชา จากการค้นหา "${searchInput.value}"`;
                    } else {
                        resultCount.classList.add('hidden');
                    }
                }

                searchInput.addEventListener('input', filterCourses);

                clearBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    filterCourses();
                    searchInput.focus();
                });

                // Support Enter key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        searchInput.value = '';
                        filterCourses();
                    }
                });
            });
        </script>
    @endpush
@endsection
