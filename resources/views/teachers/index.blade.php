@extends('layouts.app')

@section('title', 'อาจารย์ทั้งหมด')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                <i class="fas fa-chalkboard-teacher text-blue-500 mr-3"></i>
                ผู้สอนทั้งหมด
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-lg">
                พบกับผู้สอนที่พร้อมถ่ายทอดความรู้ให้คุณ
            </p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-xl mx-auto mb-8">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="ค้นหาชื่อผู้สอน..."
                    class="w-full pl-12 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm">
                <button type="button" id="clearSearch"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <p id="searchResultCount" class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center hidden"></p>
        </div>

        <!-- Teachers Grid -->
        @if ($teachers->count() > 0)
            <div id="teachersGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($teachers as $teacher)
                    <a href="{{ route('teachers.show', $teacher) }}"
                        class="teacher-card group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
                        data-name="{{ strtolower($teacher->name) }}"
                        data-position="{{ strtolower($teacher->position ?? '') }}">
                        <!-- Profile Image -->
                        <div
                            class="relative aspect-square bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 overflow-hidden">
                            @if ($teacher->profile_image_url)
                                <img src="{{ $teacher->profile_image_url }}" alt="{{ $teacher->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div
                                        class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                                        <span class="text-4xl font-bold text-white">{{ $teacher->initials }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Course Count Badge -->
                            <div
                                class="absolute top-3 right-3 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                {{ $teacher->teaching_courses_count }} รายวิชา
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-5 text-center">
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $teacher->name }}
                            </h3>
                            @if ($teacher->position)
                                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mt-1">
                                    {{ $teacher->position }}
                                </p>
                            @endif
                            {{-- <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-envelope mr-1"></i>
                                {{ $teacher->email }}
                            </p> --}}

                            <!-- View Profile Button -->
                            <div class="mt-4">
                                <span
                                    class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 font-medium group-hover:underline">
                                    ดูโปรไฟล์และรายวิชา
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div
                    class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="fas fa-user-slash text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มีผู้สอน</h3>
                <p class="text-gray-500 dark:text-gray-400">ยังไม่มีผู้สอนในระบบขณะนี้</p>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const clearBtn = document.getElementById('clearSearch');
                const resultCount = document.getElementById('searchResultCount');
                const teacherCards = document.querySelectorAll('.teacher-card');

                if (!searchInput || teacherCards.length === 0) return;

                function filterTeachers() {
                    const query = searchInput.value.toLowerCase().trim();
                    let visibleCount = 0;

                    // Show/hide clear button
                    if (query.length > 0) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }

                    teacherCards.forEach(card => {
                        const name = card.dataset.name || '';
                        const position = card.dataset.position || '';

                        if (query === '' || name.includes(query) || position.includes(query)) {
                            card.classList.remove('hidden');
                            visibleCount++;
                        } else {
                            card.classList.add('hidden');
                        }
                    });

                    // Show result count
                    if (query.length > 0) {
                        resultCount.classList.remove('hidden');
                        resultCount.textContent = `พบ ${visibleCount} ผู้สอน จากการค้นหา "${searchInput.value}"`;
                    } else {
                        resultCount.classList.add('hidden');
                    }
                }

                searchInput.addEventListener('input', filterTeachers);

                clearBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    filterTeachers();
                    searchInput.focus();
                });

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        searchInput.value = '';
                        filterTeachers();
                    }
                });
            });
        </script>
    @endpush
@endsection
