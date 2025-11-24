@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        จัดการบทเรียน
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">จัดการบทเรียน - {{ $module->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        <a href="{{ route('teacher.courses.index') }}"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">คอร์สของฉัน</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('teacher.courses.modules.index', $course) }}"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">{{ $course->title }}</a>
                        <span class="mx-2">/</span>
                        {{ $module->title }}
                    </p>
                </div>
                <a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i>เพิ่มบทเรียน
                </a>
            </div>
        </div>

        <!-- Module Info Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-14 w-14 rounded-xl bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                            <span class="text-blue-600 dark:text-blue-400 font-bold text-lg">{{ $module->order }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $module->title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Module ที่ {{ $module->order }} ในคอร์ส
                            {{ $course->title }}</p>
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

        <!-- Lessons List -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
            @if ($lessons->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700" id="lessons-list">
                    @foreach ($lessons as $lesson)
                        <div class="lesson-item px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-move"
                            data-id="{{ $lesson->id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-12 w-12 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                            <span
                                                class="text-green-600 dark:text-green-400 font-bold text-lg">{{ $lesson->order }}</span>
                                        </div>
                                    </div>
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
                                                @if ($lesson->isFileContent())
                                                    <span>• <i class="fas fa-file mr-1"></i>มีไฟล์แนบ</span>
                                                @elseif($lesson->isVideoContent())
                                                    <span>• <i class="fas fa-video mr-1"></i>วิดีโอ</span>
                                                @endif
                                            @endif
                                            @if ($lesson->content_text)
                                                <span>• <i class="fas fa-align-left mr-1"></i>เนื้อหาข้อความ</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
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

        <!-- Statistics -->
        @if ($lessons->count() > 0)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <div class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $lessons->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">บทเรียนทั้งหมด</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <div class="text-4xl font-bold text-red-600 dark:text-red-400">
                            {{ $lessons->where('content_type', 'PDF')->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">บทเรียน PDF</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <div class="text-4xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $lessons->where('content_type', 'VIDEO')->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">บทเรียนวิดีโอ</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปที่ Module
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lessonsList = document.getElementById('lessons-list');

            if (lessonsList && typeof Sortable !== 'undefined') {
                new Sortable(lessonsList, {
                    animation: 150,
                    handle: '.lesson-item',
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        // Get all lesson IDs in new order
                        const lessonIds = Array.from(lessonsList.querySelectorAll('.lesson-item'))
                            .map(item => item.dataset.id);

                        // Send AJAX request to update order
                        fetch('{{ route('teacher.courses.modules.lessons.reorder', [$course, $module]) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    order: lessonIds
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update order badges
                                    lessonsList.querySelectorAll('.lesson-item').forEach((item,
                                        index) => {
                                        const orderBadge = item.querySelector(
                                            '.h-12.w-12 span');
                                        if (orderBadge) {
                                            orderBadge.textContent = index + 1;
                                        }
                                        // Update "บทเรียนที่" text
                                        const orderText = item.querySelector(
                                            '.flex-1 .mt-1 span:first-child');
                                        if (orderText) {
                                            orderText.textContent = 'บทเรียนที่ ' + (index +
                                                1);
                                        }
                                    });
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            }
        });
    </script>
@endpush
