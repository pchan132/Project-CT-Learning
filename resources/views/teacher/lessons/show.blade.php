@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        รายละเอียดบทเรียน
    </h2>
@endsection

@push('scripts')
    <style>
        /* Quill Content Display Styles */
        .ql-editor-display {
            font-family: 'Sarabun', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }

        .ql-editor-display p {
            margin-bottom: 1em;
        }

        .ql-editor-display h1 {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 0.5em;
            margin-top: 0.67em;
        }

        .ql-editor-display h2 {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 0.5em;
            margin-top: 0.83em;
        }

        .ql-editor-display h3 {
            font-size: 1.17em;
            font-weight: bold;
            margin-bottom: 0.5em;
            margin-top: 1em;
        }

        .ql-editor-display h4 {
            font-size: 1em;
            font-weight: bold;
            margin-bottom: 0.5em;
            margin-top: 1.33em;
        }

        .ql-editor-display h5 {
            font-size: 0.83em;
            font-weight: bold;
            margin-bottom: 0.5em;
            margin-top: 1.67em;
        }

        .ql-editor-display h6 {
            font-size: 0.67em;
            font-weight: bold;
            margin-bottom: 0.5em;
            margin-top: 2.33em;
        }

        .ql-editor-display strong {
            font-weight: bold;
        }

        .ql-editor-display em {
            font-style: italic;
        }

        .ql-editor-display u {
            text-decoration: underline;
        }

        .ql-editor-display s {
            text-decoration: line-through;
        }

        .ql-editor-display ul {
            list-style-type: disc;
            padding-left: 1.5em;
            margin-bottom: 1em;
        }

        .ql-editor-display ol {
            list-style-type: decimal;
            padding-left: 1.5em;
            margin-bottom: 1em;
        }

        .ql-editor-display li {
            margin-bottom: 0.5em;
        }

        .ql-editor-display blockquote {
            border-left: 4px solid #ccc;
            padding-left: 1em;
            margin-left: 0;
            margin-right: 0;
            font-style: italic;
            color: #666;
        }

        .dark .ql-editor-display blockquote {
            border-left-color: #555;
            color: #aaa;
        }

        .ql-editor-display code {
            background-color: #f4f4f4;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        .dark .ql-editor-display code {
            background-color: #2d3748;
            color: #e2e8f0;
        }

        .ql-editor-display pre {
            background-color: #f4f4f4;
            padding: 1em;
            border-radius: 5px;
            overflow-x: auto;
            margin-bottom: 1em;
        }

        .dark .ql-editor-display pre {
            background-color: #2d3748;
        }

        .ql-editor-display a {
            color: #3b82f6;
            text-decoration: underline;
        }

        .dark .ql-editor-display a {
            color: #60a5fa;
        }

        .ql-editor-display img {
            max-width: 100%;
            height: auto;
            margin: 1em 0;
        }

        .ql-editor-display .ql-align-center {
            text-align: center;
        }

        .ql-editor-display .ql-align-right {
            text-align: right;
        }

        .ql-editor-display .ql-align-justify {
            text-align: justify;
        }

        .ql-editor-display .ql-indent-1 {
            padding-left: 3em;
        }

        .ql-editor-display .ql-indent-2 {
            padding-left: 6em;
        }

        .ql-editor-display .ql-indent-3 {
            padding-left: 9em;
        }

        .ql-editor-display .ql-indent-4 {
            padding-left: 12em;
        }

        .ql-editor-display .ql-indent-5 {
            padding-left: 15em;
        }

        .ql-editor-display .ql-indent-6 {
            padding-left: 18em;
        }

        .ql-editor-display .ql-indent-7 {
            padding-left: 21em;
        }

        .ql-editor-display .ql-indent-8 {
            padding-left: 24em;
        }

        .ql-editor-display sub {
            vertical-align: sub;
            font-size: smaller;
        }

        .ql-editor-display sup {
            vertical-align: super;
            font-size: smaller;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                        class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lesson->title }}</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            <a href="{{ route('teacher.courses.index') }}"
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">รายวิชาของฉัน</a>
                            <span class="mx-2">/</span>
                            <a href="{{ route('teacher.courses.show', $course) }}"
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">{{ $course->title }}</a>
                            <span class="mx-2">/</span>
                            <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}"
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">{{ $module->title }}</a>
                            <span class="mx-2">/</span>
                            บทเรียนที่ {{ $lesson->order }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('teacher.courses.modules.lessons.edit', [$course, $module, $lesson]) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg flex items-center">
                    <i class="fas fa-edit mr-2"></i>แก้ไขบทเรียน
                </a>
            </div>
        </div>

        <!-- Lesson Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $lesson->order }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">ลำดับที่</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="text-xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $lesson->content_type_label ?? ucfirst($lesson->content_type) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">ประเภทเนื้อหา</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $lesson->created_at->format('d/m/Y') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">สร้างเมื่อ</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $lesson->updated_at->format('d/m/Y') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">อัปเดตล่าสุด</div>
                </div>
            </div>
        </div>

        <!-- Content Display -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">เนื้อหาบทเรียน</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    แสดงเนื้อหาของบทเรียน "{{ $lesson->title }}"
                </p>
            </div>

            <div class="px-6 py-6">
                @if ($lesson->isFileContent())
                    <!-- PDF/PPT Content -->
                    <div class="text-center">
                        <div class="mb-4">
                            <i
                                class="fas fa-file-{{ $lesson->content_type === 'PDF' ? 'pdf' : 'powerpoint' }} text-6xl text-{{ $lesson->content_type === 'PDF' ? 'red' : 'orange' }}-500"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $lesson->content_type_label }}</h4>
                        @if ($lesson->content_url)
                            <p class="text-sm text-gray-500 mb-4">ไฟล์: {{ basename($lesson->content_url) }}</p>
                            <a href="{{ $lesson->content_display_url }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-external-link-alt mr-2"></i>เปิดไฟล์ในแท็บใหม่
                            </a>

                            <!-- PDF Embed -->
                            @if ($lesson->content_type === 'PDF')
                                <div class="mt-6">
                                    <iframe src="{{ $lesson->content_display_url }}"
                                        class="w-full min-h-[600px] border border-gray-300 rounded-lg"
                                        title="{{ $lesson->title }}">
                                    </iframe>
                                </div>
                            @endif
                        @else
                            <p class="text-red-600">ไม่พบไฟล์</p>
                        @endif
                    </div>
                @elseif($lesson->isVideoContent())
                    <!-- Video Content -->
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-video text-6xl text-purple-500"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">วิดีโอ</h4>
                        @if ($lesson->content_url)
                            @php
                                // ตรวจสอบว่าเป็น URL หรือไฟล์อัปโหลด
                                $isExternalUrl = filter_var($lesson->content_url, FILTER_VALIDATE_URL);
                            @endphp

                            @if ($isExternalUrl)
                                <!-- External URL Video (YouTube, Vimeo, etc.) -->
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">URL: {{ $lesson->content_url }}
                                </p>

                                <!-- YouTube Embed -->
                                @if (str_contains($lesson->content_url, 'youtube.com') || str_contains($lesson->content_url, 'youtu.be'))
                                    @php
                                        $videoId = '';
                                        if (str_contains($lesson->content_url, 'youtube.com/watch')) {
                                            parse_str(parse_url($lesson->content_url, PHP_URL_QUERY), $query);
                                            $videoId = $query['v'] ?? '';
                                        } elseif (str_contains($lesson->content_url, 'youtu.be/')) {
                                            $videoId = substr(parse_url($lesson->content_url, PHP_URL_PATH), 1);
                                        }
                                    @endphp
                                    @if ($videoId)
                                        <div class="mt-6">
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                class="w-full min-h-[600px] border border-gray-300 dark:border-gray-600 rounded-lg"
                                                title="{{ $lesson->title }}" allowfullscreen>
                                            </iframe>
                                        </div>
                                    @endif
                                @else
                                    <!-- Other External Video URL -->
                                    <div class="mt-6">
                                        <video controls
                                            class="w-full max-w-2xl mx-auto rounded-lg border border-gray-300 dark:border-gray-600"
                                            title="{{ $lesson->title }}">
                                            <source src="{{ $lesson->content_url }}" type="video/mp4">
                                            เบราว์เซอร์ของคุณไม่รองรับแท็กวิดีโอ
                                        </video>
                                    </div>
                                @endif
                            @else
                                <!-- Uploaded Video File -->
                                <div class="mb-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                        <i class="fas fa-upload mr-2"></i>วิดีโอที่อัปโหลด
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">ไฟล์:
                                    {{ basename($lesson->content_url) }}</p>

                                <div class="mt-6">
                                    <video controls
                                        class="w-full max-w-4xl mx-auto rounded-lg border border-gray-300 dark:border-gray-600 bg-black"
                                        title="{{ $lesson->title }}" preload="metadata">
                                        <source src="{{ asset('storage/' . $lesson->content_url) }}" type="video/mp4">
                                        <source src="{{ asset('storage/' . $lesson->content_url) }}" type="video/webm">
                                        <source src="{{ asset('storage/' . $lesson->content_url) }}" type="video/ogg">
                                        เบราว์เซอร์ของคุณไม่รองรับแท็กวิดีโอ
                                    </video>
                                </div>

                                <!-- Download Button -->
                                <div class="mt-4">
                                    <a href="{{ asset('storage/' . $lesson->content_url) }}"
                                        download="{{ basename($lesson->content_url) }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-download mr-2"></i>ดาวน์โหลดวิดีโอ
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-red-600 dark:text-red-400">ไม่พบ URL วิดีโอ</p>
                        @endif
                    </div>
                @elseif($lesson->isTextContent())
                    <!-- Text Content -->
                    <div>
                        <div class="prose prose-lg max-w-none dark:prose-invert">
                            <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-lg ql-editor-display">
                                {!! $lesson->content_text ?: '<p class="text-gray-500 dark:text-gray-400">ไม่มีเนื้อหาข้อความ</p>' !!}
                            </div>
                        </div>
                    </div>
                @elseif($lesson->isGoogleDriveContent())
                    <!-- Google Drive Content -->
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fab fa-google-drive text-6xl text-yellow-500"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Google Drive</h4>
                        @if ($lesson->content_url)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <a href="{{ $lesson->content_url }}" target="_blank"
                                    class="text-blue-600 hover:underline">
                                    {{ $lesson->content_url }}
                                </a>
                            </p>

                            <div class="flex justify-center gap-4 mb-6">
                                <a href="{{ $lesson->content_url }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <i class="fab fa-google-drive mr-2"></i>เปิดใน Google Drive
                                </a>
                            </div>

                            <!-- Google Drive Embed Preview -->
                            <div class="mt-6">
                                <iframe src="{{ $lesson->content_display_url }}"
                                    class="w-full min-h-[600px] border border-gray-300 dark:border-gray-600 rounded-lg"
                                    title="{{ $lesson->title }}" allow="autoplay" allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <p class="text-red-600 dark:text-red-400">ไม่พบลิงก์ Google Drive</p>
                        @endif
                    </div>
                @elseif($lesson->isCanvaContent())
                    <!-- Canva Content -->
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-palette text-6xl text-cyan-500"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Canva Design</h4>
                        @if ($lesson->content_url)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <a href="{{ $lesson->content_url }}" target="_blank"
                                    class="text-blue-600 hover:underline">
                                    {{ $lesson->content_url }}
                                </a>
                            </p>

                            <div class="flex justify-center gap-4 mb-6">
                                <a href="{{ $lesson->content_url }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                    <i class="fas fa-palette mr-2"></i>เปิดใน Canva
                                </a>
                            </div>

                            <!-- Canva Embed Preview -->
                            <div class="mt-6">
                                <div
                                    style="position: relative; width: 100%; height: 0; padding-top: 75%; overflow: hidden; border-radius: 8px; border: 1px solid #e5e7eb;">
                                    <iframe loading="lazy" src="{{ $lesson->getCanvaEmbedUrl() }}"
                                        style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none;"
                                        allowfullscreen="allowfullscreen" allow="fullscreen">
                                    </iframe>
                                </div>
                            </div>
                        @else
                            <p class="text-red-600 dark:text-red-400">ไม่พบลิงก์ Canva</p>
                        @endif
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-gray-500">ไม่มีเนื้อหาสำหรับบทเรียนนี้</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lesson Details -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">รายละเอียดบทเรียน</h3>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ชื่อบทเรียน</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $lesson->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ประเภทเนื้อหา</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                            {{ $lesson->content_type_label ?? ucfirst($lesson->content_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ลำดับที่</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $lesson->order }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Module</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $module->title }} (ลำดับ
                            {{ $module->order }})</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">สร้างเมื่อ</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                            {{ $lesson->created_at->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">อัปเดตล่าสุด</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                            {{ $lesson->updated_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปที่รายการบทเรียน
            </a>
        </div>
    </div>
@endsection
