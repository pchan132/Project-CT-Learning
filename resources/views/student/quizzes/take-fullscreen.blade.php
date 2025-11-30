<!DOCTYPE html>
<html lang="th" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $quiz->title }} - {{ $quiz->module->course->title }} | CT Learning</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

        /* Timer animations */
        @keyframes timerPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }

            50% {
                transform: scale(1.02);
                box-shadow: 0 0 20px 5px rgba(239, 68, 68, 0.5);
            }
        }

        @keyframes timerShake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-2px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(2px);
            }
        }

        .timer-warning {
            animation: timerPulse 1s ease-in-out infinite;
        }

        .timer-danger {
            animation: timerShake 0.5s ease-in-out infinite, timerPulse 0.5s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-gray-900 text-white antialiased">
    @php
        $course = $quiz->module->course;
    @endphp

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
                                    $isLessonCompleted = $moduleLesson->isCompletedBy(auth()->id());
                                @endphp
                                <a href="{{ route('student.courses.learn-lesson', [$course, $moduleLesson]) }}"
                                    class="flex items-center p-3 rounded-lg transition-all {{ $isLessonCompleted ? 'bg-gray-700/30 text-gray-300 hover:bg-gray-700/50' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">

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
                                        @else
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-500"></div>
                                        @endif
                                    </div>

                                    <!-- Lesson Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium truncate">{{ $moduleLesson->title }}</p>
                                        <div class="flex items-center text-xs text-gray-500">
                                            @switch($moduleLesson->content_type)
                                                @case('VIDEO')
                                                    <i class="fas fa-play-circle mr-1"></i> Video
                                                @break

                                                @case('PDF')
                                                    <i class="fas fa-file-pdf mr-1"></i> PDF
                                                @break

                                                @case('CANVA')
                                                    <i class="fas fa-palette mr-1"></i> Canva
                                                @break

                                                @default
                                                    <i class="fas fa-file-alt mr-1"></i> Document
                                            @endswitch
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            {{-- Quiz for module --}}
                            @if ($module->quizzes && $module->quizzes->count() > 0)
                                @foreach ($module->quizzes as $moduleQuiz)
                                    @php
                                        $quizAttempt = $moduleQuiz
                                            ->attempts()
                                            ->where('student_id', auth()->id())
                                            ->latest()
                                            ->first();
                                        $isQuizCompleted = $quizAttempt && $quizAttempt->completed_at;
                                        $isCurrentQuiz = $moduleQuiz->id === $quiz->id;
                                    @endphp
                                    <div
                                        class="flex items-center p-3 rounded-lg transition-all {{ $isCurrentQuiz ? 'bg-orange-600 text-white' : ($isQuizCompleted ? 'bg-gray-700/30 text-gray-300' : 'text-orange-400') }}">
                                        <div class="shrink-0 mr-3">
                                            @if ($isQuizCompleted)
                                                <div
                                                    class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            @elseif ($isCurrentQuiz)
                                                <div
                                                    class="w-5 h-5 rounded-full bg-white flex items-center justify-center">
                                                    <i class="fas fa-question text-xs text-orange-600"></i>
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
                                                {{ $moduleQuiz->questions->count() }} ข้อ</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Certificate Button - Show when course is 100% complete AND all quizzes passed --}}
            @php
                $courseProgress = $course->getProgressForStudent(auth()->id());
                $allQuizzesPassed = true;
                foreach ($course->modules as $mod) {
                    foreach ($mod->quizzes as $moduleQuiz) {
                        if (!$moduleQuiz->hasPassedByStudent(auth()->id())) {
                            $allQuizzesPassed = false;
                            break 2;
                        }
                    }
                }
                $canGetCertificate = $courseProgress >= 100 && $allQuizzesPassed;

                // Check if certificate already exists
                $existingCertificate = null;
                if ($canGetCertificate) {
                    $existingCertificate = \App\Models\Certificate::where('student_id', auth()->id())
                        ->where('course_id', $course->id)
                        ->first();
                }
            @endphp
            @if ($canGetCertificate)
                <div class="p-3 border-t border-gray-700">
                    @if ($existingCertificate)
                        {{-- Certificate already exists - link to view --}}
                        <a href="{{ route('student.certificates.show', $existingCertificate->id) }}"
                            class="w-full bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white px-4 py-3 rounded-xl transition-all flex items-center justify-center font-semibold shadow-lg">
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
                            class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white px-4 py-3 rounded-xl transition-all flex items-center justify-center font-semibold shadow-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                                🎉 รับเกียรติบัตร
                            </button>
                        </form>
                    @endif
                </div>
            @endif
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
                        <span class="text-orange-400 font-medium truncate max-w-[300px]">{{ $quiz->title }}</span>
                    </nav>
                </div>

                <!-- Right: Timer & Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Quiz Timer - Prominent -->
                    @if ($quiz->time_limit)
                        <div class="flex items-center bg-gradient-to-r from-red-600 to-red-700 px-5 py-2 rounded-xl shadow-lg border-2 border-red-500/50"
                            id="timerContainer">
                            <svg class="w-6 h-6 text-white mr-3 animate-pulse" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex flex-col items-center">
                                <span class="text-xs text-red-200 font-medium">เวลาที่เหลือ</span>
                                <span id="timer"
                                    class="text-2xl font-mono text-white font-bold tracking-wider">{{ $quiz->time_limit }}:00</span>
                            </div>
                        </div>
                    @endif

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
                        <svg id="sunIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
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
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                            </path>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Quiz Content -->
            <main class="flex-1 overflow-y-auto bg-gray-900">
                <div class="max-w-4xl mx-auto p-6">
                    <!-- Quiz Header -->
                    <div class="mb-6">
                        <div class="flex items-center text-sm text-gray-400 mb-2">
                            <span>โมดูล {{ $quiz->module->order }}</span>
                            <span class="mx-2">•</span>
                            <span class="flex items-center text-orange-400">
                                <i class="fas fa-question-circle mr-1"></i> แบบทดสอบ
                            </span>
                            <span class="mx-2">•</span>
                            <span>{{ $quiz->questions->count() }} ข้อ</span>
                        </div>
                        <h1 class="text-2xl font-bold text-white">{{ $quiz->title }}</h1>
                        @if ($quiz->description)
                            <p class="text-gray-400 mt-2">{{ $quiz->description }}</p>
                        @endif
                    </div>

                    <!-- Quiz Form -->
                    <form action="{{ route('student.attempts.submit', $attempt->id) }}" method="POST"
                        id="quizForm">
                        @csrf

                        <!-- Progress Bar -->
                        <div class="bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-white">ความคืบหน้า</h3>
                                <span id="progress-text" class="text-sm text-gray-400">0 /
                                    {{ $quiz->questions->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-3">
                                <div id="progress-bar"
                                    class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-300"
                                    style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Questions -->
                        @foreach ($quiz->questions as $index => $question)
                            <div class="bg-gray-800 rounded-xl shadow-lg p-6 md:p-8 mb-6 question-card"
                                data-question="{{ $index + 1 }}">
                                <!-- Question Header -->
                                <div class="flex items-start gap-4 mb-6">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white">{{ $question->question_text }}
                                        </h3>
                                    </div>
                                </div>

                                <!-- Answers -->
                                <div class="space-y-3 ml-16">
                                    @foreach ($question->answers as $answer)
                                        <label
                                            class="flex items-start p-4 border-2 border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-900/20 transition answer-option">
                                            <input type="radio" name="answers[{{ $question->id }}]"
                                                value="{{ $answer->id }}"
                                                class="mt-1 w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500 answer-input"
                                                data-question="{{ $index + 1 }}" required>
                                            <span class="ml-3 text-gray-200 flex-1">{{ $answer->answer_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Submit Button -->
                        <div class="bg-gray-800 rounded-xl shadow-lg p-6 sticky bottom-4">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-gray-400 text-center sm:text-left">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    ตรวจสอบคำตอบก่อนส่ง คุณจะไม่สามารถแก้ไขได้หลังจากส่งแล้ว
                                </div>
                                <button type="submit" id="submitBtn"
                                    class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 font-bold text-lg shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                                    <i class="fas fa-paper-plane mr-2"></i>ส่งคำตอบ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Progress tracking
        const totalQuestions = {{ $quiz->questions->count() }};
        const answerInputs = document.querySelectorAll('.answer-input');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const submitBtn = document.getElementById('submitBtn');

        function updateProgress() {
            const answeredQuestions = new Set();
            answerInputs.forEach(input => {
                if (input.checked) {
                    answeredQuestions.add(input.dataset.question);
                }
            });

            const answered = answeredQuestions.size;
            const percentage = (answered / totalQuestions) * 100;

            progressBar.style.width = percentage + '%';
            progressText.textContent = answered + ' / ' + totalQuestions;

            submitBtn.disabled = answered < totalQuestions;
        }

        answerInputs.forEach(input => {
            input.addEventListener('change', updateProgress);
        });

        // Visual feedback for selected answer
        document.querySelectorAll('.answer-option').forEach(label => {
            label.addEventListener('click', function() {
                const container = this.closest('.space-y-3');
                container.querySelectorAll('.answer-option').forEach(opt => {
                    opt.classList.remove('border-blue-500', 'bg-blue-900/20');
                    opt.classList.add('border-gray-600');
                });
                this.classList.remove('border-gray-600');
                this.classList.add('border-blue-500', 'bg-blue-900/20');
            });
        });

        // Confirm before leaving page
        let isSubmitting = false;
        window.addEventListener('beforeunload', function(e) {
            if (!isSubmitting) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        document.getElementById('quizForm').addEventListener('submit', function(e) {
            isSubmitting = true;

            const answeredQuestions = new Set();
            answerInputs.forEach(input => {
                if (input.checked) {
                    answeredQuestions.add(input.dataset.question);
                }
            });

            if (answeredQuestions.size < totalQuestions) {
                e.preventDefault();
                alert('กรุณาตอบคำถามให้ครบทุกข้อ');
                isSubmitting = false;
                return;
            }

            if (!confirm('คุณแน่ใจหรือไม่ที่จะส่งคำตอบ? คุณจะไม่สามารถแก้ไขได้หลังจากส่งแล้ว')) {
                e.preventDefault();
                isSubmitting = false;
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังส่งคำตอบ...';
        });

        @if ($quiz->time_limit)
            // Timer countdown
            const timerElement = document.getElementById('timer');
            const timerContainer = document.getElementById('timerContainer');
            const timeLimit = {{ $quiz->time_limit }};
            const startTime = new Date('{{ $attempt->started_at->toIso8601String() }}');
            const endTime = new Date(startTime.getTime() + timeLimit * 60000);

            function updateTimer() {
                const now = new Date();
                const remaining = endTime - now;

                if (remaining <= 0) {
                    timerElement.textContent = '0:00';
                    isSubmitting = true;
                    document.getElementById('quizForm').submit();
                    return;
                }

                const minutes = Math.floor(remaining / 60000);
                const seconds = Math.floor((remaining % 60000) / 1000);

                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                // Remove all animation classes first
                timerContainer.classList.remove('timer-warning', 'timer-danger', 'from-red-600', 'to-red-700',
                    'from-orange-500', 'to-orange-600', 'from-yellow-500', 'to-yellow-600', 'border-red-500/50',
                    'border-orange-400/50', 'border-yellow-400/50');

                if (minutes < 2) {
                    // DANGER: Less than 2 minutes - red flashing
                    timerContainer.classList.add('timer-danger', 'from-red-600', 'to-red-700', 'border-red-500/50');
                    timerContainer.style.background = 'linear-gradient(to right, #dc2626, #b91c1c)';
                } else if (minutes < 5) {
                    // WARNING: Less than 5 minutes - orange pulsing
                    timerContainer.classList.add('timer-warning', 'from-orange-500', 'to-orange-600',
                        'border-orange-400/50');
                    timerContainer.style.background = 'linear-gradient(to right, #f97316, #ea580c)';
                } else if (minutes < 10) {
                    // CAUTION: Less than 10 minutes - yellow
                    timerContainer.classList.add('from-yellow-500', 'to-yellow-600', 'border-yellow-400/50');
                    timerContainer.style.background = 'linear-gradient(to right, #eab308, #ca8a04)';
                } else {
                    // Normal - green/blue
                    timerContainer.style.background = 'linear-gradient(to right, #059669, #047857)';
                }

                setTimeout(updateTimer, 1000);
            }

            updateTimer();
        @endif

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

        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const body = document.body;
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                body.classList.remove('bg-gray-900', 'text-white');
                body.classList.add('bg-gray-100', 'text-gray-900');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
                localStorage.setItem('learn-theme', 'light');
                updateThemeStyles('light');
            } else {
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
            const contentCards = document.querySelectorAll('.bg-gray-800');

            if (theme === 'light') {
                sidebar.classList.remove('bg-gray-800', 'border-gray-700');
                sidebar.classList.add('bg-white', 'border-gray-200', 'shadow-lg');
                header.classList.remove('bg-gray-800', 'border-gray-700');
                header.classList.add('bg-white', 'border-gray-200', 'shadow-sm');
                main.classList.remove('bg-gray-900');
                main.classList.add('bg-gray-100');

                contentCards.forEach(card => {
                    card.classList.remove('bg-gray-800');
                    card.classList.add('bg-white', 'shadow-lg');
                });

                // Update text colors
                document.querySelectorAll('.text-white').forEach(el => {
                    if (!el.closest('.bg-gradient-to-r') && !el.closest('.bg-green-500') && !el.closest(
                            '.bg-blue-600') && !el.closest('.bg-orange-600')) {
                        el.classList.remove('text-white');
                        el.classList.add('text-gray-900');
                    }
                });

                document.querySelectorAll('.text-gray-400').forEach(el => {
                    el.classList.remove('text-gray-400');
                    el.classList.add('text-gray-600');
                });

                document.querySelectorAll('.text-gray-200').forEach(el => {
                    el.classList.remove('text-gray-200');
                    el.classList.add('text-gray-700');
                });

                document.querySelectorAll('.border-gray-600').forEach(el => {
                    el.classList.remove('border-gray-600');
                    el.classList.add('border-gray-300');
                });

            } else {
                sidebar.classList.remove('bg-white', 'border-gray-200', 'shadow-lg');
                sidebar.classList.add('bg-gray-800', 'border-gray-700');
                header.classList.remove('bg-white', 'border-gray-200', 'shadow-sm');
                header.classList.add('bg-gray-800', 'border-gray-700');
                main.classList.remove('bg-gray-100');
                main.classList.add('bg-gray-900');

                document.querySelectorAll('.bg-white.shadow-lg').forEach(card => {
                    card.classList.remove('bg-white', 'shadow-lg');
                    card.classList.add('bg-gray-800');
                });

                document.querySelectorAll('.text-gray-900').forEach(el => {
                    if (!el.closest('.bg-gradient-to-r')) {
                        el.classList.remove('text-gray-900');
                        el.classList.add('text-white');
                    }
                });

                document.querySelectorAll('.text-gray-600').forEach(el => {
                    el.classList.remove('text-gray-600');
                    el.classList.add('text-gray-400');
                });

                document.querySelectorAll('.text-gray-700').forEach(el => {
                    el.classList.remove('text-gray-700');
                    el.classList.add('text-gray-200');
                });

                document.querySelectorAll('.border-gray-300').forEach(el => {
                    el.classList.remove('border-gray-300');
                    el.classList.add('border-gray-600');
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

        // Initialize progress
        updateProgress();
    </script>
</body>

</html>
