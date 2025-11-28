@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <nav class="flex text-sm">
            <a href="{{ route('student.courses.index') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">คอร์สเรียน</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="{{ route('student.courses.show', $course) }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 truncate max-w-xs">{{ $course->title }}</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-900 dark:text-white font-medium truncate max-w-xs">{{ $lesson->title }}</span>
        </nav>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Lesson Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1">
                        <!-- Lesson Title -->
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-3">{{ $lesson->title }}
                        </h1>

                        <!-- Lesson Meta -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                โมดูล {{ $lesson->module->order }}: {{ $lesson->module->title }}
                            </div>
                            <div class="flex items-center">
                                @switch($lesson->content_type)
                                    @case('VIDEO')
                                        <svg class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    @break

                                    @case('PDF')
                                        <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    @break

                                    @case('PPT')
                                        <svg class="w-4 h-4 mr-1 text-orange-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7">
                                            </path>
                                        </svg>
                                    @break

                                    @case('GDRIVE')
                                        <i class="fab fa-google-drive text-yellow-500 mr-1"></i>
                                    @break

                                    @case('CANVA')
                                        <i class="fas fa-palette text-cyan-500 mr-1"></i>
                                    @break

                                    @default
                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                @endswitch
                                {{ $lesson->content_type_label }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                บทเรียนที่ {{ $lesson->order }}
                            </div>
                        </div>
                    </div>

                    <!-- Completion Status Badge -->
                    @if ($isCompleted)
                        <div
                            class="shrink-0 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 px-4 py-2 rounded-full text-sm font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            เรียนเสร็จแล้ว
                        </div>
                    @endif
                </div>
            </div>

            <!-- Course Progress Mini Bar -->
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">ความคืบหน้าคอร์ส</span>
                    <span
                        class="font-semibold text-blue-600 dark:text-blue-400">{{ $course->getProgressForStudent(auth()->id()) }}%</span>
                </div>
                <div class="mt-2 w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                        style="width: {{ $course->getProgressForStudent(auth()->id()) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Lesson Content Area -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <!-- Content based on type -->
                    <div class="p-6 md:p-8">
                        @switch($lesson->content_type)
                            @case('TEXT')
                                <div class="prose prose-lg dark:prose-invert max-w-none">
                                    <div class="text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">
                                        {!! nl2br(e($lesson->content_text)) !!}
                                    </div>
                                </div>
                            @break

                            @case('VIDEO')
                                @if ($lesson->content_url)
                                    @php
                                        // Check if it's a YouTube URL
$isYouTube = false;
$youtubeId = null;
$videoUrl = $lesson->content_url;

// Match YouTube URL patterns
if (
    preg_match(
        '/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                                                $videoUrl,
                                                $matches,
                                            )
                                        ) {
                                            $isYouTube = true;
                                            $youtubeId = $matches[1];
                                        }
                                    @endphp

                                    <div class="aspect-video bg-gray-900 rounded-xl overflow-hidden shadow-inner">
                                        @if ($isYouTube)
                                            <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1"
                                                class="w-full h-full" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen>
                                            </iframe>
                                        @else
                                            <video controls class="w-full h-full" id="lessonVideo">
                                                <source src="{{ $lesson->content_display_url }}" type="video/mp4">
                                                <source src="{{ $lesson->content_display_url }}" type="video/webm">
                                                บราวเซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                                            </video>
                                        @endif
                                    </div>
                                    @if ($lesson->content_text)
                                        <div class="mt-6 prose dark:prose-invert max-w-none">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">รายละเอียดเพิ่มเติม
                                            </h3>
                                            <div class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                                {!! nl2br(e($lesson->content_text)) !!}
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-400">ไม่พบไฟล์วิดีโอ</p>
                                    </div>
                                @endif
                            @break

                            @case('PDF')
                                @if ($lesson->content_url)
                                    <div class="text-center">
                                        <!-- PDF Preview Frame -->
                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 mb-6">
                                            <iframe src="{{ $lesson->content_display_url }}"
                                                class="w-full h-96 md:h-[600px] rounded-lg border-0" allowfullscreen></iframe>
                                        </div>

                                        <div class="flex justify-center gap-4">
                                            <a href="{{ $lesson->content_display_url }}" target="_blank"
                                                class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors font-medium shadow-md">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                                เปิดในหน้าต่างใหม่
                                            </a>
                                            <a href="{{ $lesson->content_display_url }}" download
                                                class="inline-flex items-center bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors font-medium shadow-md">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                ดาวน์โหลด
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-400">ไม่พบไฟล์ PDF</p>
                                    </div>
                                @endif
                            @break

                            @case('PPT')
                                @if ($lesson->content_url)
                                    <div class="text-center">
                                        <div
                                            class="bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 rounded-xl p-12 mb-6">
                                            <svg class="w-24 h-24 text-orange-500 mx-auto mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7">
                                                </path>
                                            </svg>
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">งานนำเสนอ
                                                PowerPoint</h3>
                                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                                คลิกปุ่มด้านล่างเพื่อดาวน์โหลดหรือเปิดงานนำเสนอ</p>
                                        </div>

                                        <a href="{{ $lesson->content_display_url }}" target="_blank"
                                            class="inline-flex items-center bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition-colors font-medium shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            ดาวน์โหลดงานนำเสนอ
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-400">ไม่พบไฟล์ PowerPoint</p>
                                    </div>
                                @endif
                            @break

                            @case('GDRIVE')
                                @if ($lesson->content_url)
                                    <div class="text-center">
                                        <!-- Google Drive Preview Frame -->
                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 mb-6">
                                            <iframe src="{{ $lesson->content_display_url }}"
                                                class="w-full h-96 md:h-[600px] rounded-lg border-0" allow="autoplay"
                                                allowfullscreen></iframe>
                                        </div>

                                        <div class="flex justify-center gap-4">
                                            <a href="{{ $lesson->content_url }}" target="_blank"
                                                class="inline-flex items-center bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition-colors font-medium shadow-md">
                                                <i class="fab fa-google-drive mr-2"></i>
                                                เปิดใน Google Drive
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <i class="fab fa-google-drive text-6xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-600 dark:text-gray-400">ไม่พบลิงก์ Google Drive</p>
                                    </div>
                                @endif
                            @break

                            @case('CANVA')
                                @if ($lesson->content_url)
                                    <div class="text-center">
                                        <!-- Canva Preview Frame -->
                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 mb-6">
                                            <div
                                                style="position: relative; width: 100%; height: 0; padding-top: 75%; overflow: hidden; border-radius: 8px;">
                                                <iframe loading="lazy" src="{{ $lesson->getCanvaEmbedUrl() }}"
                                                    style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none;"
                                                    allowfullscreen="allowfullscreen" allow="fullscreen"></iframe>
                                            </div>
                                        </div>

                                        <div class="flex justify-center gap-4">
                                            <a href="{{ $lesson->content_url }}" target="_blank"
                                                class="inline-flex items-center bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition-colors font-medium shadow-md">
                                                <i class="fas fa-palette mr-2"></i>
                                                เปิดใน Canva
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <i class="fas fa-palette text-6xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-600 dark:text-gray-400">ไม่พบลิงก์ Canva</p>
                                    </div>
                                @endif
                            @break

                            @default
                                <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400">ไม่สามารถแสดงเนื้อหาประเภทนี้ได้</p>
                                </div>
                        @endswitch
                    </div>

                    <!-- Complete Lesson Section -->
                    <div
                        class="px-6 md:px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                        @if (!$isCompleted)
                            <button id="completeLessonBtn" onclick="completeLesson()"
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all flex items-center justify-center font-semibold text-lg shadow-lg transform hover:scale-[1.02]">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                ทำเครื่องหมายว่าเรียนเสร็จ
                            </button>
                        @else
                            <div
                                class="bg-green-100 dark:bg-green-800/30 border-2 border-green-400 dark:border-green-600 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl text-center">
                                <div class="flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold text-lg">บทเรียนนี้เรียนเสร็จสมบูรณ์แล้ว ✓</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Lesson Navigation Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                        นำทางบทเรียน
                    </h3>

                    <div class="space-y-3">
                        @if ($previousLesson)
                            <a href="{{ route('student.courses.learn-lesson', [$course, $previousLesson]) }}"
                                class="flex items-center w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <div class="text-left flex-1 min-w-0">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">บทก่อนหน้า</div>
                                    <div class="font-medium truncate">{{ $previousLesson->title }}</div>
                                </div>
                            </a>
                        @endif

                        @if ($nextLesson)
                            <a href="{{ route('student.courses.learn-lesson', [$course, $nextLesson]) }}"
                                class="flex items-center w-full bg-blue-100 dark:bg-blue-800/50 hover:bg-blue-200 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-200 px-4 py-3 rounded-lg transition-colors">
                                <div class="text-left flex-1 min-w-0">
                                    <div class="text-xs text-blue-500 dark:text-blue-400">บทถัดไป</div>
                                    <div class="font-medium truncate">{{ $nextLesson->title }}</div>
                                </div>
                                <svg class="w-5 h-5 ml-2 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif

                        <div class="pt-2">
                            <a href="{{ route('student.courses.show', $course) }}"
                                class="flex items-center justify-center w-full bg-gray-800 dark:bg-gray-600 hover:bg-gray-900 dark:hover:bg-gray-500 text-white px-4 py-3 rounded-lg transition-colors font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                กลับหน้าคอร์ส
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Learning Stats Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        สถิติการเรียน
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400 text-sm">บทเรียนที่เรียนเสร็จ</span>
                            <span class="font-semibold text-green-600 dark:text-green-400">
                                {{ $course->getCompletedLessonsCount(auth()->id()) }} /
                                {{ $course->getTotalLessonsAttribute() }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400 text-sm">บทเรียนที่เหลือ</span>
                            <span class="font-semibold text-orange-600 dark:text-orange-400">
                                {{ $course->getTotalLessonsAttribute() - $course->getCompletedLessonsCount(auth()->id()) }}
                            </span>
                        </div>

                        <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400 text-sm">ความคืบหน้า</span>
                                <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">
                                    {{ $course->getProgressForStudent(auth()->id()) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500"
                                    style="width: {{ $course->getProgressForStudent(auth()->id()) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Info Card -->
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg p-5 text-white">
                    <h3 class="font-semibold mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        โมดูลปัจจุบัน
                    </h3>
                    <h4 class="font-medium text-lg mb-2">{{ $lesson->module->title }}</h4>
                    @if ($lesson->module->description)
                        <p class="text-sm text-white/80">{{ Str::limit($lesson->module->description, 100) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        function completeLesson() {
            const btn = document.getElementById('completeLessonBtn');
            const originalText = btn.innerHTML;

            // Show loading state
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                กำลังบันทึก...
            `;

            // Send AJAX request
            fetch(`{{ route('student.courses.complete-lesson', [$course, $lesson]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success state
                        btn.innerHTML = `
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        เรียนเสร็จแล้ว! ✓
                    `;
                        btn.className =
                            'w-full bg-green-100 dark:bg-green-800/30 border-2 border-green-400 text-green-700 dark:text-green-200 px-6 py-4 rounded-xl flex items-center justify-center font-semibold text-lg';

                        // Update progress bar
                        const progressBars = document.querySelectorAll('.bg-gradient-to-r.from-blue-500');
                        progressBars.forEach(bar => {
                            if (data.progress !== undefined) {
                                bar.style.width = data.progress + '%';
                            }
                        });

                        // Show success notification
                        showNotification('🎉 บทเรียนนี้เรียนเสร็จสมบูรณ์แล้ว!', 'success');

                        // Redirect to next lesson after 2 seconds if available
                        @if ($nextLesson)
                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('student.courses.learn-lesson', [$course, $nextLesson]) }}';
                            }, 2000);
                        @else
                            // No next lesson, go back to course page after 2 seconds
                            setTimeout(() => {
                                window.location.href = '{{ route('student.courses.show', $course) }}';
                            }, 2000);
                        @endif
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาด');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    showNotification('เกิดข้อผิดพลาด กรุณาลองใหม่', 'error');
                });
        }

        function showNotification(message, type) {
            const container = document.getElementById('toast-container');
            const notification = document.createElement('div');

            const bgColor = type === 'success' ?
                'bg-green-100 border-green-400 text-green-800' :
                'bg-red-100 border-red-400 text-red-800';

            notification.className =
                `${bgColor} px-6 py-4 rounded-lg shadow-lg border-2 transform transition-all duration-300 translate-x-full`;
            notification.innerHTML = `
                <div class="flex items-center">
                    ${type === 'success' 
                        ? '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                        : '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    }
                    <span class="font-medium">${message}</span>
                </div>
            `;

            container.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 10);

            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }
    </script>
@endsection
