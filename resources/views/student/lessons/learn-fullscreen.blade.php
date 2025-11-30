<!DOCTYPE html>
<html lang="th" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $lesson->title }} - {{ $course->title }} | CT Learning</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Quill CSS for content display -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        /* Quill Content Styles for Dark Mode */
        .ql-content {
            font-size: 1.125rem;
            line-height: 1.75;
            color: #e5e7eb;
        }

        .ql-content h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            margin-top: 1.5rem;
            color: #fff;
        }

        .ql-content h2 {
            font-size: 1.875rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            margin-top: 1.25rem;
            color: #fff;
        }

        .ql-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
            color: #fff;
        }

        .ql-content p {
            margin-bottom: 1rem;
        }

        .ql-content ul,
        .ql-content ol {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .ql-content ul {
            list-style-type: disc;
        }

        .ql-content ol {
            list-style-type: decimal;
        }

        .ql-content li {
            margin-bottom: 0.5rem;
        }

        .ql-content blockquote {
            border-left: 4px solid #6366f1;
            padding-left: 1rem;
            margin: 1rem 0;
            color: #9ca3af;
            font-style: italic;
        }

        .ql-content pre {
            background: #1f2937;
            border-radius: 0.5rem;
            padding: 1rem;
            overflow-x: auto;
            margin: 1rem 0;
        }

        .ql-content code {
            background: #374151;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-family: 'Fira Code', monospace;
            font-size: 0.875em;
        }

        .ql-content pre code {
            background: transparent;
            padding: 0;
        }

        .ql-content a {
            color: #60a5fa;
            text-decoration: underline;
        }

        .ql-content a:hover {
            color: #93c5fd;
        }

        .ql-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        .ql-content strong {
            font-weight: 700;
            color: #fff;
        }

        .ql-content em {
            font-style: italic;
        }

        .ql-content u {
            text-decoration: underline;
        }

        .ql-content s {
            text-decoration: line-through;
        }

        .ql-content .ql-align-center {
            text-align: center;
        }

        .ql-content .ql-align-right {
            text-align: right;
        }

        .ql-content .ql-align-justify {
            text-align: justify;
        }

        .ql-content .ql-indent-1 {
            padding-left: 3em;
        }

        .ql-content .ql-indent-2 {
            padding-left: 6em;
        }

        .ql-content .ql-indent-3 {
            padding-left: 9em;
        }

        .ql-content .ql-indent-4 {
            padding-left: 12em;
        }

        .ql-content .ql-indent-5 {
            padding-left: 15em;
        }

        .ql-content .ql-video {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 0.5rem;
        }

        /* Light mode overrides */
        .light-mode .ql-content {
            color: #374151;
        }

        .light-mode .ql-content h1,
        .light-mode .ql-content h2,
        .light-mode .ql-content h3,
        .light-mode .ql-content strong {
            color: #111827;
        }

        .light-mode .ql-content blockquote {
            color: #6b7280;
        }

        .light-mode .ql-content pre {
            background: #f3f4f6;
        }

        .light-mode .ql-content code {
            background: #e5e7eb;
        }

        .light-mode .ql-content a {
            color: #2563eb;
        }

        .light-mode .ql-content a:hover {
            color: #1d4ed8;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #4B5563;
            border-radius: 3px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #6B7280;
        }
    </style>
</head>

<body class="bg-gray-900 text-white antialiased">
    <div class="flex h-screen overflow-hidden" id="app">
        <!-- Sidebar - Course Navigation -->
        <aside class="w-80 bg-gray-800 flex flex-col border-r border-gray-700 shrink-0" id="sidebar">
            <!-- Course Header -->
            <div class="p-4 border-b border-gray-700">
                <h2 class="font-bold text-lg text-white truncate mb-1">{{ $course->title }}</h2>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-full bg-gray-700 rounded-full h-2 mr-3">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300"
                            style="width: {{ $course->getProgressForStudent(auth()->id()) }}%"></div>
                    </div>
                    <span
                        class="text-sm font-semibold text-blue-400 shrink-0">{{ $course->getProgressForStudent(auth()->id()) }}%</span>
                </div>
                <!-- Back to Course Button -->
                <a href="{{ route('student.courses.show', $course) }}"
                    class="flex items-center justify-center w-full bg-gray-700 hover:bg-gray-600 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    กลับหน้าคอร์ส
                </a>

                {{-- Certificate Button - Show when course is 100% complete AND all quizzes passed --}}
                @php
                    $sidebarCourseProgress = $course->getProgressForStudent(auth()->id());
                    $sidebarAllQuizzesPassed = true;
                    foreach ($course->modules as $mod) {
                        foreach ($mod->quizzes as $quiz) {
                            if (!$quiz->hasPassedByStudent(auth()->id())) {
                                $sidebarAllQuizzesPassed = false;
                                break 2;
                            }
                        }
                    }
                    $sidebarCanGetCertificate = $sidebarCourseProgress >= 100 && $sidebarAllQuizzesPassed;

                    // Check if certificate already exists
                    $sidebarExistingCertificate = null;
                    if ($sidebarCanGetCertificate) {
                        $sidebarExistingCertificate = \App\Models\Certificate::where('student_id', auth()->id())
                            ->where('course_id', $course->id)
                            ->first();
                    }
                @endphp
                @if ($sidebarCanGetCertificate)
                    @if ($sidebarExistingCertificate)
                        {{-- Certificate already exists - link to view --}}
                        <a href="{{ route('student.certificates.show', $sidebarExistingCertificate->id) }}"
                            class="mt-3 w-full bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white px-4 py-3 rounded-lg transition-all flex items-center justify-center font-semibold shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                            🏆 ดูเกียรติบัตร
                        </a>
                    @else
                        {{-- Certificate not yet generated - link to generate --}}
                        <form action="{{ route('student.certificates.generate', $course) }}" method="POST"
                            class="w-full mt-3">
                            @csrf
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white px-4 py-3 rounded-lg transition-all flex items-center justify-center font-semibold shadow-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                                🎉 รับเกียรติบัตร
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            <!-- Lessons List -->
            <div class="flex-1 overflow-y-auto sidebar-scroll p-3">
                @foreach ($course->modules as $module)
                    <div class="mb-4">
                        <!-- Module Header -->
                        <button onclick="toggleModule({{ $module->id }})"
                            class="w-full flex items-center justify-between p-3 bg-gray-700/50 hover:bg-gray-700 rounded-lg transition-colors group">
                            <div class="flex items-center min-w-0">
                                @php
                                    $moduleLessons = $module->lessons;
                                    $completedInModule = $moduleLessons
                                        ->filter(fn($l) => $l->isCompletedBy(auth()->id()))
                                        ->count();
                                    $totalInModule = $moduleLessons->count();
                                    $isModuleComplete = $completedInModule === $totalInModule && $totalInModule > 0;
                                @endphp
                                @if ($isModuleComplete)
                                    <div
                                        class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center shrink-0 mr-3">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="w-6 h-6 rounded-full bg-gray-600 flex items-center justify-center shrink-0 mr-3 text-xs font-bold text-gray-300">
                                        {{ $module->order }}
                                    </div>
                                @endif
                                <span class="font-medium text-white truncate">{{ $module->title }}</span>
                            </div>
                            <div class="flex items-center shrink-0 ml-2">
                                <span
                                    class="text-xs text-gray-400 mr-2">{{ $completedInModule }}/{{ $totalInModule }}</span>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform module-arrow"
                                    id="arrow-{{ $module->id }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>

                        <!-- Module Lessons -->
                        <div class="mt-2 space-y-1 pl-2" id="module-{{ $module->id }}">
                            @foreach ($module->lessons as $moduleLesson)
                                @php
                                    $isCurrentLesson = $moduleLesson->id === $lesson->id;
                                    $isLessonCompleted = $moduleLesson->isCompletedBy(auth()->id());
                                @endphp
                                <a href="{{ route('student.courses.learn-lesson', [$course, $moduleLesson]) }}"
                                    class="flex items-center p-3 rounded-lg transition-all {{ $isCurrentLesson ? 'bg-blue-600 text-white' : ($isLessonCompleted ? 'bg-gray-700/30 text-gray-300 hover:bg-gray-700/50' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white') }}">

                                    <!-- Status Icon -->
                                    <div class="shrink-0 mr-3">
                                        @if ($isLessonCompleted)
                                            <div
                                                class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @elseif ($isCurrentLesson)
                                            <div class="w-5 h-5 rounded-full bg-white flex items-center justify-center">
                                                <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                                            </div>
                                        @else
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-500"></div>
                                        @endif
                                    </div>

                                    <!-- Lesson Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium truncate">{{ $moduleLesson->title }}</p>
                                        <div
                                            class="flex items-center text-xs {{ $isCurrentLesson ? 'text-blue-200' : 'text-gray-500' }}">
                                            @switch($moduleLesson->content_type)
                                                @case('VIDEO')
                                                    <i class="fas fa-play-circle mr-1"></i> Video
                                                @break

                                                @case('PDF')
                                                    <i class="fas fa-file-pdf mr-1"></i> PDF Document
                                                @break

                                                @case('CANVA')
                                                    <i class="fas fa-palette mr-1"></i> Canva
                                                @break

                                                @case('GDRIVE')
                                                    <i class="fab fa-google-drive mr-1"></i> Google Drive
                                                @break

                                                @default
                                                    <i class="fas fa-file-alt mr-1"></i> Document
                                            @endswitch
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            {{-- Quiz for module (shown after all lessons) --}}
                            @if ($module->quizzes && $module->quizzes->count() > 0)
                                @foreach ($module->quizzes as $quiz)
                                    @php
                                        $quizAttempt = $quiz
                                            ->attempts()
                                            ->where('student_id', auth()->id())
                                            ->latest()
                                            ->first();
                                        $isQuizCompleted = $quizAttempt && $quizAttempt->completed_at;
                                    @endphp
                                    <a href="{{ route('student.courses.modules.quizzes.show', [$course, $module, $quiz]) }}"
                                        class="flex items-center p-3 rounded-lg transition-all {{ $isQuizCompleted ? 'bg-gray-700/30 text-gray-300' : 'text-orange-400 hover:bg-gray-700/50' }}">
                                        <div class="shrink-0 mr-3">
                                            @if ($isQuizCompleted)
                                                <div
                                                    class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div
                                                    class="w-5 h-5 rounded-full bg-orange-500/20 flex items-center justify-center">
                                                    <i class="fas fa-question text-xs text-orange-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium truncate">แบบทดสอบ •
                                                {{ $quiz->questions_count ?? $quiz->questions->count() }} ข้อ</p>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header Bar -->
            <header class="h-14 bg-gray-800 border-b border-gray-700 flex items-center justify-between px-4 shrink-0">
                <!-- Left: Breadcrumb -->
                <div class="flex items-center min-w-0">
                    <a href="{{ route('student.courses.show', $course) }}"
                        class="text-gray-400 hover:text-white mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <nav class="flex items-center text-sm truncate">
                        <span class="text-gray-400 truncate max-w-[200px]">{{ $course->title }}</span>
                        <span class="mx-2 text-gray-600">/</span>
                        <span class="text-orange-400 font-medium truncate max-w-[300px]">{{ $lesson->title }}</span>
                    </nav>
                </div>

                <!-- Right: Timer & Actions -->
                <div class="flex items-center space-x-2">
                    <!-- Study Timer -->
                    <div class="flex items-center bg-gray-700/50 px-3 py-1.5 rounded-lg">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="studyTimer" class="text-sm font-mono text-white">00:00</span>
                    </div>

                    <!-- Toggle Sidebar -->
                    <button onclick="toggleSidebar()"
                        class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                        title="ซ่อน/แสดง Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </button>

                    <!-- Light/Dark Mode Toggle -->
                    <button onclick="toggleTheme()"
                        class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                        title="เปลี่ยนธีม" id="themeToggleBtn">
                        <!-- Sun icon (for dark mode - click to go light) -->
                        <svg id="sunIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <!-- Moon icon (for light mode - click to go dark) -->
                        <svg id="moonIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>

                    <!-- Fullscreen Toggle -->
                    <button onclick="toggleFullscreen()"
                        class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                        title="เต็มหน้าจอ">
                        <svg id="fullscreenIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                            </path>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Lesson Content -->
            <main class="flex-1 overflow-y-auto bg-gray-900">
                <div class="max-w-5xl mx-auto p-6">
                    <!-- Lesson Header -->
                    <div class="mb-6">
                        <div class="flex items-center text-sm text-gray-400 mb-2">
                            <span>โมดูล {{ $lesson->module->order }}</span>
                            <span class="mx-2">•</span>
                            <span>บทที่ {{ $lesson->order }}</span>
                            <span class="mx-2">•</span>
                            <span class="flex items-center">
                                @switch($lesson->content_type)
                                    @case('VIDEO')
                                        <i class="fas fa-play-circle mr-1 text-purple-400"></i> Video
                                    @break

                                    @case('PDF')
                                        <i class="fas fa-file-pdf mr-1 text-red-400"></i> PDF
                                    @break

                                    @case('CANVA')
                                        <i class="fas fa-palette mr-1 text-cyan-400"></i> Canva
                                    @break

                                    @default
                                        <i class="fas fa-file-alt mr-1 text-blue-400"></i> Document
                                @endswitch
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-white">{{ $lesson->title }}</h1>
                        @if ($isCompleted)
                            <span class="inline-flex items-center mt-2 text-sm text-green-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                เรียนเสร็จแล้ว
                            </span>
                        @endif
                    </div>

                    <!-- Content Area -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-2xl">
                        @switch($lesson->content_type)
                            @case('VIDEO')
                                @if ($lesson->content_url)
                                    @php
                                        $isYouTube = false;
                                        $youtubeId = null;
                                        if (
                                            preg_match(
                                                '/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                                                $lesson->content_url,
                                                $matches,
                                            )
                                        ) {
                                            $isYouTube = true;
                                            $youtubeId = $matches[1];
                                        }
                                    @endphp
                                    <div class="aspect-video bg-black">
                                        @if ($isYouTube)
                                            <iframe
                                                src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1"
                                                class="w-full h-full" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen>
                                            </iframe>
                                        @else
                                            <video controls class="w-full h-full">
                                                <source src="{{ $lesson->content_display_url }}" type="video/mp4">
                                            </video>
                                        @endif
                                    </div>
                                @endif
                            @break

                            @case('PDF')
                                @if ($lesson->content_url)
                                    <iframe src="{{ $lesson->content_display_url }}" class="w-full h-[70vh]"
                                        allowfullscreen></iframe>
                                    <!-- PDF Action Buttons -->
                                    <div class="flex justify-center gap-4 p-4 bg-gray-700/50">
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
                                            class="inline-flex items-center bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-500 transition-colors font-medium shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            ดาวน์โหลด
                                        </a>
                                    </div>
                                @endif
                            @break

                            @case('CANVA')
                                @if ($lesson->content_url)
                                    <div style="position: relative; width: 100%; height: 0; padding-top: 56.25%;">
                                        <iframe loading="lazy" src="{{ $lesson->getCanvaEmbedUrl() }}"
                                            style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none;"
                                            allowfullscreen="allowfullscreen" allow="fullscreen"></iframe>
                                    </div>
                                    <!-- Canva Action Button -->
                                    <div class="flex justify-center gap-4 p-4 bg-gray-700/50">
                                        <a href="{{ $lesson->content_url }}" target="_blank"
                                            class="inline-flex items-center bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition-colors font-medium shadow-md">
                                            <i class="fas fa-palette mr-2"></i>
                                            เปิดใน Canva
                                        </a>
                                    </div>
                                @endif
                            @break

                            @case('GDRIVE')
                                @if ($lesson->content_url)
                                    <iframe src="{{ $lesson->content_display_url }}" class="w-full h-[70vh]"
                                        allow="autoplay" allowfullscreen></iframe>
                                    <!-- Google Drive Action Button -->
                                    <div class="flex justify-center gap-4 p-4 bg-gray-700/50">
                                        <a href="{{ $lesson->content_url }}" target="_blank"
                                            class="inline-flex items-center bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition-colors font-medium shadow-md">
                                            <i class="fab fa-google-drive mr-2"></i>
                                            เปิดใน Google Drive
                                        </a>
                                    </div>
                                @endif
                            @break

                            @case('PPT')
                                @if ($lesson->content_url)
                                    <div class="p-12 text-center">
                                        <div class="bg-gradient-to-br from-orange-500/20 to-amber-500/20 rounded-xl p-12 mb-6">
                                            <svg class="w-24 h-24 text-orange-500 mx-auto mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7">
                                                </path>
                                            </svg>
                                            <h3 class="text-xl font-semibold text-white mb-2">งานนำเสนอ PowerPoint</h3>
                                            <p class="text-gray-400 mb-6">คลิกปุ่มด้านล่างเพื่อดาวน์โหลดหรือเปิดงานนำเสนอ</p>
                                        </div>
                                        <a href="{{ $lesson->content_display_url }}" target="_blank"
                                            class="inline-flex items-center bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition-colors font-medium shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            ดาวน์โหลดงานนำเสนอ
                                        </a>
                                    </div>
                                @endif
                            @break

                            @case('TEXT')
                                <div class="p-6 md:p-8 lg:p-10">
                                    <div class="ql-content ql-editor">
                                        {!! $lesson->content_text !!}
                                    </div>
                                </div>
                            @break

                            @default
                                <div class="p-12 text-center text-gray-400">
                                    <i class="fas fa-file-alt text-4xl mb-4"></i>
                                    <p>ไม่สามารถแสดงเนื้อหาประเภทนี้ได้</p>
                                </div>
                        @endswitch
                    </div>

                    <!-- Description -->
                    @if ($lesson->content_text && $lesson->content_type !== 'TEXT')
                        <div class="mt-6 bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-white mb-3">รายละเอียด</h3>
                            <div class="text-gray-300 whitespace-pre-wrap">{{ $lesson->content_text }}</div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        @if (!$isCompleted)
                            <button id="completeLessonBtn" onclick="completeLesson()"
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all flex items-center justify-center font-semibold text-lg shadow-lg">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                บทเรียนนี้เรียนเสร็จสมบูรณ์แล้ว ✓
                            </button>
                        @else
                            <div
                                class="w-full bg-green-500/20 border-2 border-green-500 text-green-400 px-6 py-4 rounded-xl flex items-center justify-center font-semibold text-lg">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                บทเรียนนี้เรียนเสร็จสมบูรณ์แล้ว ✓
                            </div>
                        @endif

                        @if ($nextLesson)
                            <a href="{{ route('student.courses.learn-lesson', [$course, $nextLesson]) }}"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-xl transition-all flex items-center justify-center font-semibold text-lg">
                                บทถัดไป
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Study Timer
        let studySeconds = 0;
        const timerElement = document.getElementById('studyTimer');

        setInterval(() => {
            studySeconds++;
            const mins = Math.floor(studySeconds / 60).toString().padStart(2, '0');
            const secs = (studySeconds % 60).toString().padStart(2, '0');
            timerElement.textContent = `${mins}:${secs}`;
        }, 1000);

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }

        // Toggle Module
        function toggleModule(moduleId) {
            const content = document.getElementById('module-' + moduleId);
            const arrow = document.getElementById('arrow-' + moduleId);
            content.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        // Fullscreen
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Complete Lesson
        function completeLesson() {
            const btn = document.getElementById('completeLessonBtn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                กำลังบันทึก...
            `;

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
                        btn.innerHTML = `
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        เรียนเสร็จแล้ว! ✓
                    `;
                        btn.className =
                            'w-full bg-green-500/20 border-2 border-green-500 text-green-400 px-6 py-4 rounded-xl flex items-center justify-center font-semibold text-lg cursor-default';

                        showNotification('🎉 บทเรียนนี้เรียนเสร็จสมบูรณ์แล้ว!', 'success');

                        @if ($nextLesson)
                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('student.courses.learn-lesson', [$course, $nextLesson]) }}';
                            }, 1500);
                        @else
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
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
            const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';

            notification.className =
                `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            notification.innerHTML = `<span class="font-medium">${message}</span>`;

            container.appendChild(notification);
            setTimeout(() => notification.classList.remove('translate-x-full'), 10);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // Theme Toggle (Light/Dark Mode)
        function toggleTheme() {
            const html = document.documentElement;
            const body = document.body;
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');

            if (html.classList.contains('dark')) {
                // Switch to Light Mode
                html.classList.remove('dark');
                body.classList.remove('bg-gray-900', 'text-white');
                body.classList.add('bg-gray-100', 'text-gray-900');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
                localStorage.setItem('learn-theme', 'light');
                updateThemeStyles('light');
            } else {
                // Switch to Dark Mode
                html.classList.add('dark');
                body.classList.remove('bg-gray-100', 'text-gray-900');
                body.classList.add('bg-gray-900', 'text-white');
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
                localStorage.setItem('learn-theme', 'dark');
                updateThemeStyles('dark');
            }
        }

        function updateThemeStyles(theme) {
            const sidebar = document.getElementById('sidebar');
            const header = document.querySelector('header');
            const main = document.querySelector('main');

            // Update sidebar header elements
            const sidebarHeader = sidebar.querySelector('.border-b');
            const courseTitle = sidebar.querySelector('h2');
            const backButton = sidebar.querySelector('a[href*="courses"]');
            const moduleButtons = sidebar.querySelectorAll('button[onclick*="toggleModule"]');
            const moduleTitles = sidebar.querySelectorAll('button span.font-medium');
            const lessonLinks = sidebar.querySelectorAll('#sidebar a[href*="learn-lesson"]');
            const progressText = sidebar.querySelector('.text-blue-400');

            // Update content area elements
            const contentCards = document.querySelectorAll('.bg-gray-800');
            const lessonTitle = main.querySelector('h1');
            const metaText = main.querySelectorAll('.text-gray-400');
            const descriptionBox = main.querySelector('.bg-gray-800.rounded-xl.p-6');

            if (theme === 'light') {
                // Sidebar
                sidebar.classList.remove('bg-gray-800', 'border-gray-700');
                sidebar.classList.add('bg-white', 'border-gray-200', 'shadow-lg');

                if (sidebarHeader) {
                    sidebarHeader.classList.remove('border-gray-700');
                    sidebarHeader.classList.add('border-gray-200');
                }
                if (courseTitle) {
                    courseTitle.classList.remove('text-white');
                    courseTitle.classList.add('text-gray-900');
                }
                if (backButton) {
                    backButton.classList.remove('bg-gray-700', 'hover:bg-gray-600', 'text-white');
                    backButton.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                }

                moduleButtons.forEach(btn => {
                    btn.classList.remove('bg-gray-700/50', 'hover:bg-gray-700');
                    btn.classList.add('bg-gray-100', 'hover:bg-gray-200');
                });

                moduleTitles.forEach(title => {
                    title.classList.remove('text-white');
                    title.classList.add('text-gray-900');
                });

                lessonLinks.forEach(link => {
                    if (!link.classList.contains('bg-blue-600')) {
                        link.classList.remove('text-gray-400', 'text-gray-300', 'hover:text-white',
                            'bg-gray-700/30', 'hover:bg-gray-700/50');
                        link.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-100');
                    }
                });

                // Header
                header.classList.remove('bg-gray-800', 'border-gray-700');
                header.classList.add('bg-white', 'border-gray-200', 'shadow-sm');

                // Main content
                main.classList.remove('bg-gray-900');
                main.classList.add('bg-gray-100');

                // Content cards
                contentCards.forEach(card => {
                    card.classList.remove('bg-gray-800');
                    card.classList.add('bg-white', 'shadow-lg');
                });

                // Lesson title
                if (lessonTitle) {
                    lessonTitle.classList.remove('text-white');
                    lessonTitle.classList.add('text-gray-900');
                }

                // Meta text
                metaText.forEach(text => {
                    text.classList.remove('text-gray-400');
                    text.classList.add('text-gray-600');
                });

            } else {
                // Sidebar
                sidebar.classList.remove('bg-white', 'border-gray-200', 'shadow-lg');
                sidebar.classList.add('bg-gray-800', 'border-gray-700');

                if (sidebarHeader) {
                    sidebarHeader.classList.remove('border-gray-200');
                    sidebarHeader.classList.add('border-gray-700');
                }
                if (courseTitle) {
                    courseTitle.classList.remove('text-gray-900');
                    courseTitle.classList.add('text-white');
                }
                if (backButton) {
                    backButton.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                    backButton.classList.add('bg-gray-700', 'hover:bg-gray-600', 'text-white');
                }

                moduleButtons.forEach(btn => {
                    btn.classList.remove('bg-gray-100', 'hover:bg-gray-200');
                    btn.classList.add('bg-gray-700/50', 'hover:bg-gray-700');
                });

                moduleTitles.forEach(title => {
                    title.classList.remove('text-gray-900');
                    title.classList.add('text-white');
                });

                lessonLinks.forEach(link => {
                    if (!link.classList.contains('bg-blue-600')) {
                        link.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-100');
                        link.classList.add('text-gray-400', 'hover:text-white', 'hover:bg-gray-700/50');
                    }
                });

                // Header
                header.classList.remove('bg-white', 'border-gray-200', 'shadow-sm');
                header.classList.add('bg-gray-800', 'border-gray-700');

                // Main content
                main.classList.remove('bg-gray-100');
                main.classList.add('bg-gray-900');

                // Content cards
                contentCards.forEach(card => {
                    card.classList.remove('bg-white', 'shadow-lg');
                    card.classList.add('bg-gray-800');
                });

                // Lesson title
                if (lessonTitle) {
                    lessonTitle.classList.remove('text-gray-900');
                    lessonTitle.classList.add('text-white');
                }

                // Meta text
                metaText.forEach(text => {
                    text.classList.remove('text-gray-600');
                    text.classList.add('text-gray-400');
                });
            }
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('learn-theme') || 'dark';
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');

            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('bg-gray-900', 'text-white');
                document.body.classList.add('bg-gray-100', 'text-gray-900');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
                updateThemeStyles('light');
            } else {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
