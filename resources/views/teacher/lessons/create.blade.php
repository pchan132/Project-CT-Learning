@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        สร้างบทเรียนใหม่
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">สร้างบทเรียนใหม่</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        คอร์ส: {{ $course->title }} / Module: {{ $module->title }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
            <form action="{{ route('teacher.courses.modules.lessons.store', [$course, $module]) }}" method="POST"
                enctype="multipart/form-data">
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
                            ชื่อบทเรียน <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                            placeholder="เช่น บทเรียนที่ 1: การติดตั้งโปรแกรม" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            ลำดับการแสดงผลของบทเรียนใน Module (ลำดับถัดไป: {{ $nextOrder }})
                        </p>
                    </div>

                    <!-- Content Type -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ประเภทเนื้อหา <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label
                                class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                <input type="radio" name="content_type" value="PDF"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600"
                                    @if (old('content_type') == 'PDF') checked @endif>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300"><i
                                        class="fas fa-file-pdf mr-2 text-red-500"></i>PDF Document</span>
                            </label>
                            <label
                                class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                <input type="radio" name="content_type" value="VIDEO"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600"
                                    @if (old('content_type') == 'VIDEO') checked @endif>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300"><i
                                        class="fas fa-video mr-2 text-purple-500"></i>วิดีโอ (YouTube, Vimeo, etc.)</span>
                            </label>
                            <label
                                class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                <input type="radio" name="content_type" value="TEXT"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600"
                                    @if (old('content_type') == 'TEXT') checked @endif>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300"><i
                                        class="fas fa-align-left mr-2 text-gray-500"></i>ข้อความ</span>
                            </label>
                        </div>
                        @error('content_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dynamic Content Fields -->
                    <div id="content-fields">
                        <!-- File Upload (PDF/PPT) -->
                        <div id="file-field" class="mb-6 hidden">
                            <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                อัปโหลดไฟล์ <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="file" name="file" accept=".pdf,.ppt,.pptx"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('file') border-red-500 @enderror">
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                รองรับไฟล์ PDF, PowerPoint (PPT, PPTX) ขนาดสูงสุด 10MB
                            </p>
                            <!-- File Preview -->
                            <div id="file-preview" class="mt-3 hidden">
                                <div
                                    class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                            <div>
                                                <p id="file-name" class="text-sm font-medium text-gray-900 dark:text-white">
                                                </p>
                                                <p id="file-size" class="text-xs text-gray-500 dark:text-gray-400"></p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="clearFileInput()"
                                            class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Video URL -->
                        <div id="video-field" class="mb-6 hidden">
                            <label for="content_url"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                URL วิดีโอ <span class="text-red-500">*</span>
                            </label>
                            <input type="url" id="content_url" name="content_url" value="{{ old('content_url') }}"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('content_url') border-red-500 @enderror"
                                placeholder="https://www.youtube.com/watch?v=...">
                            @error('content_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                ใส่ URL จาก YouTube, Vimeo หรือแพลตฟอร์มวิดีโออื่นๆ
                            </p>
                        </div>

                        <!-- Text Content -->
                        <div id="text-field" class="mb-6 hidden">
                            <label for="content_text"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                เนื้อหาข้อความ <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content_text" name="content_text" rows="8"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('content_text') border-red-500 @enderror"
                                placeholder="พิมพ์เนื้อหาบทเรียนที่นี่...">{{ old('content_text') }}</textarea>
                            @error('content_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Module Info -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-xl">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">ข้อมูล Module</h3>
                        <div class="flex items-center">
                            <div
                                class="h-10 w-10 rounded-xl bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-3">
                                <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $module->order }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $module->title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $module->lessons->count() }}
                                    บทเรียนใน Module นี้</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 flex justify-end space-x-3">
                    <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                        class="inline-flex items-center px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        ยกเลิก
                    </a>
                    <button type="submit" id="submit-btn"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2" id="submit-icon"></i>
                        <span id="submit-text">สร้างบทเรียน</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-2xl">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-green-600 mb-4"></div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">กำลังสร้างบทเรียน...</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">กรุณารอสักครู่</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // File preview and validation
        function clearFileInput() {
            document.getElementById('file').value = '';
            document.getElementById('file-preview').classList.add('hidden');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Show/hide content fields based on content type
        document.addEventListener('DOMContentLoaded', function() {
            const contentTypes = document.querySelectorAll('input[name="content_type"]');
            const fileField = document.getElementById('file-field');
            const videoField = document.getElementById('video-field');
            const textField = document.getElementById('text-field');
            const fileInput = document.getElementById('file');
            let editorInstance = null;

            // File upload preview
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file size (10MB)
                        if (file.size > 10 * 1024 * 1024) {
                            alert('ไฟล์มีขนาดใหญ่เกิน 10MB กรุณาเลือกไฟล์ใหม่');
                            clearFileInput();
                            return;
                        }

                        // Show preview
                        document.getElementById('file-name').textContent = file.name;
                        document.getElementById('file-size').textContent = formatFileSize(file.size);
                        document.getElementById('file-preview').classList.remove('hidden');
                    }
                });
            }

            // Form submission loading
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submit-btn');
            const submitIcon = document.getElementById('submit-icon');
            const submitText = document.getElementById('submit-text');
            const loadingOverlay = document.getElementById('loading-overlay');

            form.addEventListener('submit', function(e) {
                // Show loading
                submitBtn.disabled = true;
                submitIcon.className = 'fas fa-spinner fa-spin mr-2';
                submitText.textContent = 'กำลังบันทึก...';
                loadingOverlay.classList.remove('hidden');
            });

            // Initialize TinyMCE
            function initTinyMCE() {
                if (editorInstance) {
                    tinymce.remove('#content_text');
                    editorInstance = null;
                }

                tinymce.init({
                    selector: '#content_text',
                    height: 400,
                    menubar: false,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                    content_style: 'body { font-family:Inter,sans-serif; font-size:14px }',
                    skin: document.documentElement.classList.contains('dark') ? 'oxide-dark' : 'oxide',
                    content_css: document.documentElement.classList.contains('dark') ? 'dark' : 'default',
                    setup: function(editor) {
                        editorInstance = editor;
                    }
                });
            }

            function toggleFields() {
                const selectedType = document.querySelector('input[name="content_type"]:checked');
                if (!selectedType) return;

                const value = selectedType.value;

                fileField.classList.add('hidden');
                videoField.classList.add('hidden');
                textField.classList.add('hidden');

                // Clear required attributes
                document.getElementById('file').removeAttribute('required');
                document.getElementById('content_url').removeAttribute('required');
                document.getElementById('content_text').removeAttribute('required');

                switch (value) {
                    case 'PDF':
                        fileField.classList.remove('hidden');
                        document.getElementById('file').setAttribute('required', '');
                        if (editorInstance) {
                            tinymce.remove('#content_text');
                            editorInstance = null;
                        }
                        break;
                    case 'VIDEO':
                        videoField.classList.remove('hidden');
                        document.getElementById('content_url').setAttribute('required', '');
                        if (editorInstance) {
                            tinymce.remove('#content_text');
                            editorInstance = null;
                        }
                        break;
                    case 'TEXT':
                        textField.classList.remove('hidden');
                        document.getElementById('content_text').setAttribute('required', '');
                        setTimeout(initTinyMCE, 100);
                        break;
                }
            }

            contentTypes.forEach(radio => {
                radio.addEventListener('change', toggleFields);
            });

            // Initialize on page load
            toggleFields();
        });
    </script>
@endpush
