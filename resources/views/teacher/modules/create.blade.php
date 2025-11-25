@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        สร้าง Module ใหม่
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('teacher.courses.modules.index', $course) }}"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">สร้าง Module ใหม่</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        คอร์ส: {{ $course->title }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
            <form action="{{ route('teacher.courses.modules.store', $course) }}" method="POST">
                @csrf

                <!-- Error Messages -->
                @if ($errors->any())
                    <div
                        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded m-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="px-6 py-6 sm:p-8">
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ชื่อ Module <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                            placeholder="เช่น บทที่ 1: พื้นฐานการเขียนโปรแกรม" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ลำดับที่ <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', $nextOrder) }}"
                            min="1"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('order') border-red-500 @enderror"
                            required>
                        @error('order')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            ลำดับการแสดงผลของ Module ในคอร์ส (ลำดับถัดไป: {{ $nextOrder }})
                        </p>
                    </div>

                    <!-- Course Info -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">ข้อมูลคอร์ส</h3>
                        <div class="flex items-center">
                            @if ($course->cover_image_url)
                                <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                                    class="h-16 w-16 rounded-xl object-cover mr-4">
                            @else
                                <div
                                    class="h-16 w-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-book text-white text-2xl"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $course->title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $course->modules->count() }} modules
                                    ในคอร์สนี้</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 flex justify-end space-x-3">
                    <a href="{{ route('teacher.courses.modules.index', $course) }}"
                        class="inline-flex items-center px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        ยกเลิก
                    </a>
                    <button type="submit" id="submit-btn"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2" id="submit-icon"></i>
                        <span id="submit-text">สร้าง Module</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50"
            style="display: none;">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-2xl">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mb-4"></div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">กำลังสร้าง Module...</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">กรุณารอสักครู่</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submit-btn');
            const submitIcon = document.getElementById('submit-icon');
            const submitText = document.getElementById('submit-text');
            const loadingOverlay = document.getElementById('loading-overlay');

            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    submitBtn.disabled = true;
                    submitIcon.className = 'fas fa-spinner fa-spin mr-2';
                    submitText.textContent = 'กำลังสร้าง...';
                    loadingOverlay.style.display = 'flex';
                }, {
                    once: true
                });
            }
        });
    </script>
@endpush
