<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                    class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">สร้างบทเรียนใหม่</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        คอร์ส: {{ $course->title }} / Module: {{ $module->title }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('teacher.courses.modules.lessons.store', [$course, $module]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded m-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="px-4 py-5 sm:p-6">
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            ชื่อบทเรียน <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-500 @enderror"
                            placeholder="เช่น บทเรียนที่ 1: การติดตั้งโปรแกรม" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            ลำดับที่ <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', $nextOrder) }}"
                            min="1"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('order') border-red-500 @enderror"
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ประเภทเนื้อหา <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="content_type" value="PDF"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                    @if (old('content_type') == 'PDF') checked @endif>
                                <span class="ml-2 text-sm text-gray-700">PDF Document</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="content_type" value="VIDEO"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                    @if (old('content_type') == 'VIDEO') checked @endif>
                                <span class="ml-2 text-sm text-gray-700">วิดีโอ (YouTube, Vimeo, etc.)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="content_type" value="TEXT"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                    @if (old('content_type') == 'TEXT') checked @endif>
                                <span class="ml-2 text-sm text-gray-700">ข้อความ</span>
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
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                อัปโหลดไฟล์ <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="file" name="file" accept=".pdf,.ppt,.pptx"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('file') border-red-500 @enderror">
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                รองรับไฟล์ PDF, PowerPoint (PPT, PPTX) ขนาดสูงสุด 10MB
                            </p>
                        </div>

                        <!-- Video URL -->
                        <div id="video-field" class="mb-6 hidden">
                            <label for="content_url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL วิดีโอ <span class="text-red-500">*</span>
                            </label>
                            <input type="url" id="content_url" name="content_url" value="{{ old('content_url') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('content_url') border-red-500 @enderror"
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
                            <label for="content_text" class="block text-sm font-medium text-gray-700 mb-2">
                                เนื้อหาข้อความ <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content_text" name="content_text" rows="8"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('content_text') border-red-500 @enderror"
                                placeholder="พิมพ์เนื้อหาบทเรียนที่นี่...">{{ old('content_text') }}</textarea>
                            @error('content_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Module Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">ข้อมูล Module</h3>
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                <span class="text-indigo-600 font-semibold text-sm">{{ $module->order }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $module->title }}</p>
                                <p class="text-sm text-gray-500">{{ $module->lessons->count() }} บทเรียนใน Module นี้
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-save mr-2"></i>สร้างบทเรียน
                    </button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            // Show/hide content fields based on content type
            document.addEventListener('DOMContentLoaded', function() {
                const contentTypes = document.querySelectorAll('input[name="content_type"]');
                const fileField = document.getElementById('file-field');
                const videoField = document.getElementById('video-field');
                const textField = document.getElementById('text-field');

                function toggleFields() {
                    const selectedType = document.querySelector('input[name="content_type"]:checked').value;

                    fileField.classList.add('hidden');
                    videoField.classList.add('hidden');
                    textField.classList.add('hidden');

                    // Clear required attributes
                    document.getElementById('file').removeAttribute('required');
                    document.getElementById('content_url').removeAttribute('required');
                    document.getElementById('content_text').removeAttribute('required');

                    switch (selectedType) {
                        case 'PDF':
                            fileField.classList.remove('hidden');
                            document.getElementById('file').setAttribute('required', '');
                            break;
                        case 'VIDEO':
                            videoField.classList.remove('hidden');
                            document.getElementById('content_url').setAttribute('required', '');
                            break;
                        case 'TEXT':
                            textField.classList.remove('hidden');
                            document.getElementById('content_text').setAttribute('required', '');
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
    </x-app-layout>
