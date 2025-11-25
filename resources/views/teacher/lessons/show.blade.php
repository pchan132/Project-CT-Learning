@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        รายละเอียดบทเรียน
    </h2>
@endsection

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
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">คอร์สของฉัน</a>
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
                        <h4 class="text-lg font-medium text-gray-900 mb-2">วิดีโอ</h4>
                        @if ($lesson->content_url)
                            <p class="text-sm text-gray-500 mb-4">URL: {{ $lesson->content_url }}</p>

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
                                            class="w-full min-h-[600px] border border-gray-300 rounded-lg"
                                            title="{{ $lesson->title }}" allowfullscreen>
                                        </iframe>
                                    </div>
                                @endif
                            @else
                                <!-- Other Video Embed -->
                                <div class="mt-6">
                                    <video controls class="w-full max-w-2xl mx-auto" title="{{ $lesson->title }}">
                                        <source src="{{ $lesson->content_url }}" type="video/mp4">
                                        เบราว์เซอร์ของคุณไม่รองรับแท็กวิดีโอ
                                    </video>
                                </div>
                            @endif
                        @else
                            <p class="text-red-600">ไม่พบ URL วิดีโอ</p>
                        @endif
                    </div>
                @elseif($lesson->isTextContent())
                    <!-- Text Content -->
                    <div>
                        <div class="prose prose-lg max-w-none dark:prose-invert">
                            <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-lg">
                                {!! $lesson->content_text ?: '<p class="text-gray-500">ไม่มีเนื้อหาข้อความ</p>' !!}
                            </div>
                        </div>
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
                            {{ $lesson->created_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">อัปเดตล่าสุด</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                            {{ $lesson->updated_at->format('d/m/Y H:i:s') }}</dd>
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
