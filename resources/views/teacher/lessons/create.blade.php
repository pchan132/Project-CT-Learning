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
                        รายวิชา: {{ $course->title }} / Module: {{ $module->title }}
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

                    <!-- Required Duration -->
                    <div class="mb-6">
                        <label for="required_duration_minutes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-clock mr-1 text-blue-500"></i>ระยะเวลาที่ต้องเรียน (นาที) <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="number" id="required_duration_minutes" name="required_duration_minutes"
                            value="{{ old('required_duration_minutes', 1) }}" min="1" max="1440"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('required_duration_minutes') border-red-500 @enderror"
                            required>
                        @error('required_duration_minutes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            <i
                                class="fas fa-info-circle mr-1"></i>ระยะเวลาขั้นต่ำที่นักเรียนต้องอยู่ในหน้าบทเรียนนี้ก่อนจะสามารถไปบทถัดไปได้
                            (ค่าเริ่มต้น: 1 นาที)
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
                                <input type="radio" name="content_type" value="GDRIVE"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600"
                                    @if (old('content_type') == 'GDRIVE') checked @endif>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300"><i
                                        class="fab fa-google-drive mr-2 text-yellow-500"></i>Google Drive (วิดีโอ, เอกสาร,
                                    รูปภาพ,
                                    PDF, Google Docs, Google Sheets, Google Slides)</span>
                            </label>
                            <label
                                class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                <input type="radio" name="content_type" value="CANVA"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600"
                                    @if (old('content_type') == 'CANVA') checked @endif>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300"><i
                                        class="fas fa-palette mr-2 text-cyan-500"></i>Canva</span>
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
                                                <p id="file-name"
                                                    class="text-sm font-medium text-gray-900 dark:text-white">
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

                        <!-- Video Options -->
                        <div id="video-field" class="mb-6 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                ประเภทวิดีโอ <span class="text-red-500">*</span>
                            </label>

                            <div class="space-y-3 mb-4">
                                <label
                                    class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <input type="radio" name="video_type" value="url"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600"
                                        checked>
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-link mr-2 text-blue-500"></i>URL วิดีโอ (YouTube, Vimeo)
                                    </span>
                                </label>
                                {{-- <label
                                    class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <input type="radio" name="video_type" value="upload"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-upload mr-2 text-green-500"></i>อัปโหลดไฟล์วิดีโอ
                                    </span>
                                </label> --}}
                            </div>

                            <!-- URL Input -->
                            <div id="video-url-input" class="video-option">
                                <label for="content_url"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    URL วิดีโอ
                                </label>
                                <input type="url" id="content_url" name="content_url"
                                    value="{{ old('content_url') }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('content_url') border-red-500 @enderror"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                @error('content_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    ใส่ URL จาก YouTube, Vimeo หรือแพลตฟอร์มวิดีโออื่นๆ
                                </p>
                            </div>

                            <!-- Upload Input -->
                            <div id="video-upload-input" class="video-option hidden">
                                <label for="video_file"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    อัปโหลดไฟล์วิดีโอ
                                </label>
                                <input type="file" id="video_file" name="video_file"
                                    accept="video/mp4,video/webm,video/ogg,video/quicktime"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('video_file') border-red-500 @enderror">
                                @error('video_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    รองรับไฟล์ MP4, WebM, OGG, MOV ขนาดสูงสุด 100MB
                                </p>
                                <!-- Video Preview -->
                                <div id="video-preview" class="mt-3 hidden">
                                    <div
                                        class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-video text-purple-500 text-2xl"></i>
                                                <div>
                                                    <p id="video-name"
                                                        class="text-sm font-medium text-gray-900 dark:text-white"></p>
                                                    <p id="video-size" class="text-xs text-gray-500 dark:text-gray-400">
                                                    </p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="clearVideoInput()"
                                                class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Google Drive Field -->
                        <div id="gdrive-field" class="mb-6 hidden">
                            <label for="gdrive_url"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                ลิงก์ Google Drive <span class="text-red-500">*</span>
                            </label>
                            <input type="url" id="gdrive_url" name="gdrive_url" value="{{ old('gdrive_url') }}"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('gdrive_url') border-red-500 @enderror"
                                placeholder="https://drive.google.com/file/d/xxxxx/view">
                            @error('gdrive_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div
                                class="mt-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i> วิธีการใช้งาน Google Drive
                                </h4>
                                <ol
                                    class="text-sm text-yellow-700 dark:text-yellow-300 list-decimal list-inside space-y-1">
                                    <li>เปิดไฟล์ใน Google Drive ของคุณ</li>
                                    <li>คลิกขวาที่ไฟล์ → แชร์ → เปลี่ยนเป็น "ทุกคนที่มีลิงก์"</li>
                                    <li>คัดลอกลิงก์และวางที่นี่</li>
                                </ol>
                                <p class="mt-2 text-xs text-yellow-600 dark:text-yellow-400">
                                    รองรับ: ไฟล์ PDF, เอกสาร, Slides, Sheets, รูปภาพ, วิดีโอ
                                </p>
                            </div>
                        </div>

                        <!-- Canva Field -->
                        <div id="canva-field" class="mb-6 hidden">
                            <label for="canva_url"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                ลิงก์ Canva <span class="text-red-500">*</span>
                            </label>
                            <input type="url" id="canva_url" name="canva_url" value="{{ old('canva_url') }}"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('canva_url') border-red-500 @enderror"
                                placeholder="https://www.canva.com/design/xxxxx/view">
                            @error('canva_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div
                                class="mt-3 p-4 bg-cyan-50 dark:bg-cyan-900/20 rounded-lg border border-cyan-200 dark:border-cyan-800">
                                <h4 class="text-sm font-medium text-cyan-800 dark:text-cyan-200 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i> วิธีการใช้งาน Canva
                                </h4>
                                <ol class="text-sm text-cyan-700 dark:text-cyan-300 list-decimal list-inside space-y-1">
                                    <li>เปิดงานออกแบบใน Canva</li>
                                    <li>คลิก "แชร์" → "ลิงก์เพิ่มเติม" → "ฝัง"</li>
                                    <li>คัดลอก URL จากโค้ด iframe หรือใช้ลิงก์ View</li>
                                </ol>
                                <p class="mt-2 text-xs text-cyan-600 dark:text-cyan-400">
                                    รองรับ: Presentation, Document, Infographic, Poster และอื่นๆ
                                </p>
                            </div>
                        </div>

                        <!-- Text Content -->
                        <div id="text-field" class="mb-6 hidden">
                            <label for="content_text"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                เนื้อหาข้อความ <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content_text" name="content_text" style="display: none;">{{ old('content_text') }}</textarea>
                            <div id="quill-editor" style="height: 400px;"></div>
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
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            style="display: none;">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-2xl max-w-md w-full mx-4">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-green-600 mb-4"></div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2" id="upload-status">
                        กำลังสร้างบทเรียน...</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center" id="upload-message">กรุณารอสักครู่</p>

                    <!-- Progress Bar -->
                    <div id="progress-container" class="w-full mt-4 hidden">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div id="progress-bar" class="bg-green-600 h-2.5 rounded-full transition-all duration-300"
                                style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center" id="progress-text">0%</p>
                    </div>
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

        function clearVideoInput() {
            document.getElementById('video_file').value = '';
            document.getElementById('video-preview').classList.add('hidden');
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
            const gdriveField = document.getElementById('gdrive-field');
            const canvaField = document.getElementById('canva-field');
            const fileInput = document.getElementById('file');
            const videoFileInput = document.getElementById('video_file');
            const videoTypeRadios = document.querySelectorAll('input[name="video_type"]');
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

            // Video upload preview
            if (videoFileInput) {
                videoFileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file size (100MB)
                        if (file.size > 100 * 1024 * 1024) {
                            alert('ไฟล์วิดีโอมีขนาดใหญ่เกิน 100MB กรุณาเลือกไฟล์ใหม่');
                            clearVideoInput();
                            return;
                        }

                        // Show preview
                        document.getElementById('video-name').textContent = file.name;
                        document.getElementById('video-size').textContent = formatFileSize(file.size);
                        document.getElementById('video-preview').classList.remove('hidden');
                    }
                });
            }

            // Video type toggle
            videoTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const videoUrlInput = document.getElementById('video-url-input');
                    const videoUploadInput = document.getElementById('video-upload-input');

                    if (this.value === 'url') {
                        videoUrlInput.classList.remove('hidden');
                        videoUploadInput.classList.add('hidden');
                        document.getElementById('content_url').removeAttribute('disabled');
                        document.getElementById('video_file').setAttribute('disabled', 'disabled');
                    } else {
                        videoUrlInput.classList.add('hidden');
                        videoUploadInput.classList.remove('hidden');
                        document.getElementById('content_url').setAttribute('disabled', 'disabled');
                        document.getElementById('video_file').removeAttribute('disabled');
                    }
                });
            });

            // Form submission loading
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submit-btn');
            const submitIcon = document.getElementById('submit-icon');
            const submitText = document.getElementById('submit-text');
            const loadingOverlay = document.getElementById('loading-overlay');
            const uploadStatus = document.getElementById('upload-status');
            const uploadMessage = document.getElementById('upload-message');
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // ป้องกันการ submit ซ้ำ
                    if (submitBtn.disabled) {
                        e.preventDefault();
                        return false;
                    }

                    // ตรวจสอบว่ามีการอัปโหลดวิดีโอหรือไฟล์ขนาดใหญ่
                    const contentType = document.querySelector('input[name="content_type"]:checked')?.value;
                    const videoFile = document.getElementById('video_file')?.files[0];
                    const pdfFile = document.getElementById('file')?.files[0];

                    let hasLargeFile = false;
                    let fileType = '';

                    if (contentType === 'VIDEO' && videoFile) {
                        hasLargeFile = true;
                        fileType = 'วิดีโอ';
                    } else if (contentType === 'PDF' && pdfFile) {
                        hasLargeFile = true;
                        fileType = 'ไฟล์';
                    }

                    // แสดง loading
                    submitBtn.disabled = true;
                    submitIcon.className = 'fas fa-spinner fa-spin mr-2';
                    submitText.textContent = 'กำลังบันทึก...';
                    loadingOverlay.style.display = 'flex';

                    if (hasLargeFile) {
                        uploadStatus.textContent = `กำลังอัปโหลด${fileType}...`;
                        uploadMessage.textContent = 'กรุณาอย่าปิดหน้าต่างนี้ การอัปโหลดอาจใช้เวลาสักครู่';
                        progressContainer.classList.remove('hidden');

                        // จำลอง progress (เนื่องจาก HTML form ไม่สามารถแสดง progress จริงได้)
                        let progress = 0;
                        const interval = setInterval(() => {
                            if (progress < 90) {
                                progress += Math.random() * 10;
                                if (progress > 90) progress = 90;
                                progressBar.style.width = progress + '%';
                                progressText.textContent = Math.round(progress) + '%';
                            }
                        }, 500);

                        // เก็บ interval ID เพื่อ clear ภายหลัง
                        form.dataset.progressInterval = interval;
                    } else {
                        uploadStatus.textContent = 'กำลังสร้างบทเรียน...';
                        uploadMessage.textContent = 'กรุณารอสักครู่';
                    }
                }, {
                    once: true
                });
            }

            // Initialize Quill Rich Text Editor
            function initQuillEditor() {
                if (typeof Quill === 'undefined') {
                    console.error('Quill not loaded');
                    return;
                }

                if (editorInstance) {
                    // Remove existing editor
                    const editorContainer = document.querySelector('#content_text').parentNode;
                    editorContainer.innerHTML =
                        '<textarea id="content_text" name="content_text" style="display:none;"></textarea><div id="quill-editor" style="height: 400px;"></div>';
                }

                // Create Quill editor with image upload handler
                const quillEditor = document.getElementById('quill-editor');
                const textarea = document.getElementById('content_text');

                editorInstance = window.initQuillWithImageUpload('#quill-editor');

                // Set initial content if exists
                if (textarea.value) {
                    editorInstance.root.innerHTML = textarea.value;
                }

                // Update textarea on content change
                editorInstance.on('text-change', function() {
                    textarea.value = editorInstance.root.innerHTML;
                });

                // Handle Word paste - Quill handles it automatically
                editorInstance.clipboard.addMatcher(Node.ELEMENT_NODE, function(node, delta) {
                    // Clean Word formatting but keep basic styles
                    return delta;
                });
            }

            function toggleFields() {
                const selectedType = document.querySelector('input[name="content_type"]:checked');
                if (!selectedType) return;

                const value = selectedType.value;

                fileField.classList.add('hidden');
                videoField.classList.add('hidden');
                textField.classList.add('hidden');
                gdriveField.classList.add('hidden');
                canvaField.classList.add('hidden');

                // Clear required attributes
                document.getElementById('file').removeAttribute('required');
                document.getElementById('content_url').removeAttribute('required');
                document.getElementById('content_text').removeAttribute('required');
                document.getElementById('gdrive_url').removeAttribute('required');
                document.getElementById('canva_url').removeAttribute('required');

                switch (value) {
                    case 'PDF':
                        fileField.classList.remove('hidden');
                        document.getElementById('file').setAttribute('required', '');
                        if (editorInstance) {
                            const quillEditor = document.getElementById('quill-editor');
                            if (quillEditor) quillEditor.innerHTML = '';
                            editorInstance = null;
                        }
                        break;
                    case 'VIDEO':
                        videoField.classList.remove('hidden');
                        document.getElementById('content_url').setAttribute('required', '');
                        if (editorInstance) {
                            const quillEditor = document.getElementById('quill-editor');
                            if (quillEditor) quillEditor.innerHTML = '';
                            editorInstance = null;
                        }
                        break;
                    case 'TEXT':
                        textField.classList.remove('hidden');
                        document.getElementById('content_text').setAttribute('required', '');
                        setTimeout(initQuillEditor, 100);
                        break;
                    case 'GDRIVE':
                        gdriveField.classList.remove('hidden');
                        document.getElementById('gdrive_url').setAttribute('required', '');
                        if (editorInstance) {
                            const quillEditor = document.getElementById('quill-editor');
                            if (quillEditor) quillEditor.innerHTML = '';
                            editorInstance = null;
                        }
                        break;
                    case 'CANVA':
                        canvaField.classList.remove('hidden');
                        document.getElementById('canva_url').setAttribute('required', '');
                        if (editorInstance) {
                            const quillEditor = document.getElementById('quill-editor');
                            if (quillEditor) quillEditor.innerHTML = '';
                            editorInstance = null;
                        }
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
