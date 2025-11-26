@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        กำลังทำ: {{ $quiz->title }}
    </h2>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Timer (if time limit exists) -->
        @if ($quiz->time_limit)
            <div id="timerCard"
                class="bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl shadow-lg p-6 mb-6 sticky top-4 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-3xl"></i>
                        <div>
                            <p class="text-sm opacity-90">เวลาที่เหลือ</p>
                            <p id="timer" class="text-3xl font-bold">{{ $quiz->time_limit }}:00</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90">เริ่มเมื่อ</p>
                        <p class="text-lg font-semibold">{{ $attempt->started_at->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quiz Form -->
        <form action="{{ route('student.attempts.submit', $attempt->id) }}" method="POST" id="quizForm">
            @csrf

            <!-- Progress Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">ความคืบหน้า</h3>
                    <span id="progress-text" class="text-sm text-gray-600 dark:text-gray-400">0 /
                        {{ $quiz->questions->count() }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                    <div id="progress-bar" class="bg-blue-500 h-3 rounded-full transition-all duration-300"
                        style="width: 0%"></div>
                </div>
            </div>

            <!-- Questions -->
            @foreach ($quiz->questions as $index => $question)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 md:p-8 mb-6 question-card"
                    data-question="{{ $index + 1 }}">
                    <!-- Question Header -->
                    <div class="flex items-start gap-4 mb-6">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-xl">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $question->question_text }}
                            </h3>
                        </div>
                    </div>

                    <!-- Answers -->
                    <div class="space-y-3 ml-16">
                        @foreach ($question->answers as $answer)
                            <label
                                class="flex items-start p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition answer-option">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}"
                                    class="mt-1 w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500 answer-input"
                                    data-question="{{ $index + 1 }}" required>
                                <span
                                    class="ml-3 text-gray-800 dark:text-gray-200 flex-1">{{ $answer->answer_text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Submit Button -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky bottom-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        ตรวจสอบคำตอบก่อนส่ง คุณจะไม่สามารถแก้ไขได้หลังจากส่งแล้ว
                    </div>
                    <button type="submit" id="submitBtn"
                        class="px-8 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-lg shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane mr-2"></i>ส่งคำตอบ
                    </button>
                </div>
            </div>
        </form>
    </div>

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

            // Enable submit button when all questions are answered
            submitBtn.disabled = answered < totalQuestions;
        }

        answerInputs.forEach(input => {
            input.addEventListener('change', updateProgress);
        });

        // Visual feedback for selected answer
        document.querySelectorAll('.answer-option').forEach(label => {
            label.addEventListener('click', function() {
                // Remove active class from siblings
                const container = this.closest('.space-y-3');
                container.querySelectorAll('.answer-option').forEach(opt => {
                    opt.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                });
                // Add active class to selected
                this.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
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

            // Check if all questions are answered
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
            const timerCard = document.getElementById('timerCard');
            const timeLimit = {{ $quiz->time_limit }};
            const startTime = new Date('{{ $attempt->started_at->toIso8601String() }}');
            const endTime = new Date(startTime.getTime() + timeLimit * 60000);

            function updateTimer() {
                const now = new Date();
                const remaining = endTime - now;

                if (remaining <= 0) {
                    // Time's up - auto submit
                    isSubmitting = true;
                    document.getElementById('quizForm').submit();
                    return;
                }

                const minutes = Math.floor(remaining / 60000);
                const seconds = Math.floor((remaining % 60000) / 1000);

                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                // Change color when time is running out
                if (minutes < 5) {
                    timerCard.className =
                        'bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl shadow-lg p-6 mb-6 sticky top-4 z-10 animate-pulse';
                } else if (minutes < 10) {
                    timerCard.className =
                        'bg-gradient-to-r from-orange-500 to-yellow-500 text-white rounded-xl shadow-lg p-6 mb-6 sticky top-4 z-10';
                }

                setTimeout(updateTimer, 1000);
            }

            updateTimer();
        @endif

        // Initialize progress
        updateProgress();
    </script>
@endsection
