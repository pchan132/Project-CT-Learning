@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        จัดการเนื้อหาวิชา
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Course Header -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-8 text-white">
                <div class="flex justify-between items-start mb-4">
                    <a href="{{ route('teacher.courses.show', $course) }}"
                        class="text-blue-100 hover:text-white flex items-center text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>กลับไปหน้าหลักของคอร์ส
                    </a>
                    <div class="flex items-center space-x-3 text-sm bg-white/20 px-4 py-2 rounded-full backdrop-blur-sm">
                        <span>{{ $modules->count() }} โมดูล</span>
                        <span>·</span>
                        <span>{{ $modules->sum(function ($m) {return $m->lessons->count();}) }} บทเรียน</span>
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-2">จัดการเนื้อหาวิชา</h1>
                <p class="text-blue-100">{{ $course->title }}</p>
            </div>
        </div>

        <!-- Module Header and Add Button -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">โมดูลและบทเรียน</h2>
            <a href="{{ route('teacher.courses.modules.create', $course) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow-lg flex items-center transition-all hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>เพิ่มโมดูล
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg relative mb-6">
                <span class="font-medium"><i class="fas fa-check-circle mr-2"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg relative mb-6">
                <span class="font-medium"><i class="fas fa-times-circle mr-2"></i></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Modules List -->
        <div class="space-y-4" id="modules-list">
            @if ($modules->count() > 0)
                @foreach ($modules as $module)
                    <!-- Module Card -->
                    <div class="module-item bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 cursor-move"
                        data-id="{{ $module->id }}">
                        <!-- Module Header Row -->
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 flex-1">
                                    <!-- Order Badge -->
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center font-bold">
                                        {{ $module->order }}
                                    </div>

                                    <!-- Module Info -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $module->title }}
                                        </h3>
                                        <div class="flex items-center space-x-3 mt-1">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                                <i class="fas fa-book-reader mr-1"></i>
                                                {{ $module->lessons->count() }} บทเรียน
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Module Actions -->
                                <div class="flex items-center space-x-2">
                                    <!-- Edit Module -->
                                    <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                                        class="p-2 text-gray-500 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete Module -->
                                    <form action="{{ route('teacher.courses.modules.destroy', [$course, $module]) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบ Module นี้?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Nested Lesson List -->
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @if ($module->lessons->count() > 0)
                                @foreach ($module->lessons as $lesson)
                                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 flex-1">
                                                <!-- Lesson Order -->
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg flex items-center justify-center text-sm font-semibold">
                                                    {{ $lesson->order }}
                                                </div>

                                                <!-- Lesson Info -->
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900 dark:text-white">
                                                        {{ $lesson->title }}
                                                    </h4>
                                                    <div class="flex items-center space-x-2 mt-1">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            <i
                                                                class="fas fa-{{ $lesson->content_type === 'video' ? 'play-circle' : 'file-alt' }} mr-1"></i>
                                                            {{ ucfirst($lesson->content_type ?? 'video') }}
                                                        </span>
                                                        @if ($lesson->duration)
                                                            <span class="text-xs text-gray-400 dark:text-gray-500">·</span>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                <i class="fas fa-clock mr-1"></i>{{ $lesson->duration }}
                                                                นาที
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Lesson Actions -->
                                            <div class="flex items-center space-x-1">
                                                <!-- Edit Lesson -->
                                                <a href="{{ route('teacher.courses.modules.lessons.edit', [$course, $module, $lesson]) }}"
                                                    class="p-2 text-gray-500 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>

                                                <!-- Delete Lesson -->
                                                <form
                                                    action="{{ route('teacher.courses.modules.lessons.destroy', [$course, $module, $lesson]) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบบทเรียนนี้?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    ยังไม่มีบทเรียนใน Module นี้
                                </div>
                            @endif

                            <!-- Add Lesson Button -->
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                                <a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 w-full py-2.5 flex items-center justify-center border-2 border-dashed border-blue-200 dark:border-blue-800 hover:border-blue-400 dark:hover:border-blue-600 rounded-lg transition">
                                    <i class="fas fa-plus mr-2"></i>เพิ่มบทเรียน
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- No Modules Found -->
                <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <i class="fas fa-folder-open text-gray-400 dark:text-gray-500 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มี Modules</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">เริ่มต้นการสร้าง Module แรกสำหรับคอร์สนี้</p>
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-all hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>สร้าง Module แรก
                    </a>
                </div>
            @endif
        </div>

        <!-- Bottom Back Button -->
        <div class="mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('teacher.courses.show', $course) }}"
                class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปที่รายละเอียดคอร์ส
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modulesList = document.getElementById('modules-list');

            if (modulesList && typeof Sortable !== 'undefined') {
                new Sortable(modulesList, {
                    animation: 150,
                    handle: '.module-item',
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        // Get all module IDs in new order
                        const moduleIds = Array.from(modulesList.querySelectorAll('.module-item'))
                            .map(item => item.dataset.id);

                        // Send AJAX request to update order
                        fetch('{{ route('teacher.courses.modules.reorder', $course) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    order: moduleIds
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update order badges
                                    modulesList.querySelectorAll('.module-item').forEach((item,
                                        index) => {
                                        const orderBadge = item.querySelector(
                                            '.flex-shrink-0.w-10.h-10');
                                        if (orderBadge) {
                                            orderBadge.textContent = index + 1;
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
