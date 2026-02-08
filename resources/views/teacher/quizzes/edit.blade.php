@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        จัดการแบบทดสอบ: {{ $quiz->title }}
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Success Message -->
        @if (session('success'))
            <div
                class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Errors -->
        @if ($errors->any())
            <div
                class="mb-6 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Quiz Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $quiz->title }}</h3>
                    @if ($quiz->description)
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $quiz->description }}</p>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">คะแนนผ่าน</div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $quiz->passing_score }}%
                            </div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">เวลาทำข้อสอบ</div>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ $quiz->time_limit ? $quiz->time_limit . ' นาที' : 'ไม่จำกัด' }}
                            </div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">จำนวนคำถาม</div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $quiz->questions->count() }} ข้อ</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('teacher.courses.modules.quizzes.show', [$course->id, $module->id, $quiz->id]) }}"
                    class="ml-4 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fas fa-eye mr-2"></i>ดูแบบทดสอบ
                </a>
            </div>
        </div>

        <!-- Add Question Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-plus-circle text-green-600 mr-2"></i>เพิ่มคำถามใหม่
            </h3>

            <form method="POST"
                action="{{ route('teacher.courses.modules.quizzes.questions.store', [$course->id, $module->id, $quiz->id]) }}"
                id="add-question-form">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        คำถาม <span class="text-red-500">*</span>
                    </label>
                    <textarea name="question_text" rows="3" required
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="ใส่คำถามที่นี่..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        ตัวเลือกคำตอบ (2-6 ข้อ) <span class="text-red-500">*</span>
                    </label>
                    <div id="answers-container" class="space-y-3">
                        <!-- Answer 1 -->
                        <div class="answer-item flex items-center gap-3">
                            <input type="radio" name="correct_answer" value="0" class="w-5 h-5 text-green-600"
                                title="เลือกคำตอบที่ถูกต้อง">
                            <input type="text" name="answers[0][answer_text]" required
                                class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg"
                                placeholder="ตัวเลือกที่ 1">
                            <button type="button" onclick="removeAnswer(this)"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <!-- Answer 2 -->
                        <div class="answer-item flex items-center gap-3">
                            <input type="radio" name="correct_answer" value="1" class="w-5 h-5 text-green-600"
                                title="เลือกคำตอบที่ถูกต้อง">
                            <input type="text" name="answers[1][answer_text]" required
                                class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg"
                                placeholder="ตัวเลือกที่ 2">
                            <button type="button" onclick="removeAnswer(this)"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" onclick="addAnswer()"
                        class="mt-3 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        <i class="fas fa-plus mr-2"></i>เพิ่มตัวเลือก
                    </button>

                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>คลิกที่วงกลมเพื่อเลือกคำตอบที่ถูกต้อง
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="submit-btn"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold disabled:opacity-50">
                        <i class="fas fa-plus-circle mr-2"></i><span id="submit-text">เพิ่มคำถาม</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Questions List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-list text-blue-600 mr-2"></i>รายการคำถาม ({{ $quiz->questions->count() }} ข้อ)
            </h3>

            @if ($quiz->questions->count() > 0)
                <div id="questions-list" class="space-y-4">
                    @foreach ($quiz->questions as $question)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                            data-question-id="{{ $question->id }}">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span
                                            class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-3 py-1 rounded-full text-sm font-semibold">
                                            ข้อ {{ $loop->iteration }}
                                        </span>
                                        <button class="text-gray-400 hover:text-gray-600 cursor-move"
                                            title="ลากเพื่อเรียงลำดับ">
                                            <i class="fas fa-grip-vertical"></i>
                                        </button>
                                    </div>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $question->question_text }}</p>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <button onclick="editQuestion({{ $question->id }})"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST"
                                        action="{{ route('teacher.courses.modules.quizzes.questions.destroy', [$course->id, $module->id, $quiz->id, $question->id]) }}"
                                        onsubmit="return confirm('ยืนยันการลบคำถามนี้?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="ml-4 space-y-2">
                                @foreach ($question->answers as $answer)
                                    <div class="flex items-center gap-2">
                                        @if ($answer->is_correct)
                                            <i class="fas fa-check-circle text-green-500"></i>
                                            <span
                                                class="text-green-700 dark:text-green-400 font-medium">{{ $answer->answer_text }}</span>
                                            <span
                                                class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 px-2 py-1 rounded">ถูกต้อง</span>
                                        @else
                                            <i class="fas fa-circle text-gray-400"></i>
                                            <span
                                                class="text-gray-600 dark:text-gray-400">{{ $answer->answer_text }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-question-circle text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">ยังไม่มีคำถาม กรุณาเพิ่มคำถามด้านบน</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Question Modal -->
    <div id="edit-question-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 transition-opacity bg-gray-500/75 dark:bg-gray-900/75" onclick="closeEditModal()">
            </div>

            <!-- Modal Content -->
            <div class="relative z-10 w-full max-w-2xl p-6 mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-edit text-yellow-500 mr-2"></i>แก้ไขคำถาม
                    </h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="edit-question-form" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-left">
                            คำถาม <span class="text-red-500">*</span>
                        </label>
                        <textarea name="question_text" id="edit-question-text" rows="3" required
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="ใส่คำถามที่นี่..."></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-left">
                            ตัวเลือกคำตอบ (2-6 ข้อ) <span class="text-red-500">*</span>
                        </label>
                        <div id="edit-answers-container" class="space-y-3">
                            <!-- Dynamic answers will be added here -->
                        </div>

                        <button type="button" onclick="addEditAnswer()"
                            class="mt-3 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            <i class="fas fa-plus mr-2"></i>เพิ่มตัวเลือก
                        </button>

                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-left">
                            <i class="fas fa-info-circle mr-1"></i>คลิกที่วงกลมเพื่อเลือกคำตอบที่ถูกต้อง
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                            ยกเลิก
                        </button>
                        <button type="submit" id="edit-submit-btn"
                            class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold disabled:opacity-50">
                            <i class="fas fa-save mr-2"></i><span id="edit-submit-text">บันทึกการแก้ไข</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        @php
            $questionsForJs = $quiz->questions
                ->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'question_text' => $q->question_text,
                        'answers' => $q->answers
                            ->map(function ($a) {
                                return [
                                    'id' => $a->id,
                                    'answer_text' => $a->answer_text,
                                    'is_correct' => $a->is_correct,
                                ];
                            })
                            ->values(),
                    ];
                })
                ->values();
        @endphp
        <script>
            let answerCount = 2;
            const maxAnswers = 6;

            // Store questions data for editing
            const questionsData = @json($questionsForJs);

            // Edit Modal Variables
            let editAnswerCount = 0;
            let currentEditQuestionId = null;

            function editQuestion(questionId) {
                const question = questionsData.find(q => q.id === questionId);
                if (!question) return;

                currentEditQuestionId = questionId;

                // Set question text
                document.getElementById('edit-question-text').value = question.question_text;

                // Clear and populate answers
                const container = document.getElementById('edit-answers-container');
                container.innerHTML = '';
                editAnswerCount = 0;

                question.answers.forEach((answer, index) => {
                    addEditAnswerWithData(answer.answer_text, answer.is_correct);
                });

                // Set form action
                const form = document.getElementById('edit-question-form');
                form.action =
                    `{{ url('teacher/courses/' . $course->id . '/modules/' . $module->id . '/quizzes/' . $quiz->id . '/questions') }}/${questionId}`;

                // Show modal
                document.getElementById('edit-question-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                document.getElementById('edit-question-modal').classList.add('hidden');
                document.body.style.overflow = '';
                currentEditQuestionId = null;
            }

            function addEditAnswerWithData(text = '', isCorrect = false) {
                if (editAnswerCount >= maxAnswers) {
                    alert('สามารถเพิ่มได้สูงสุด 6 ตัวเลือก');
                    return;
                }

                const container = document.getElementById('edit-answers-container');
                const newAnswer = document.createElement('div');
                newAnswer.className = 'edit-answer-item flex items-center gap-3';
                newAnswer.innerHTML = `
                    <input type="radio" name="edit_correct_answer" value="${editAnswerCount}" class="w-5 h-5 text-green-600" title="เลือกคำตอบที่ถูกต้อง" ${isCorrect ? 'checked' : ''}>
                    <input type="text" name="answers[${editAnswerCount}][answer_text]" required value="${escapeHtml(text)}"
                        class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg"
                        placeholder="ตัวเลือกที่ ${editAnswerCount + 1}">
                    <button type="button" onclick="removeEditAnswer(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" ${editAnswerCount < 2 ? 'disabled' : ''}>
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                container.appendChild(newAnswer);
                editAnswerCount++;
                updateEditRemoveButtons();
            }

            function addEditAnswer() {
                addEditAnswerWithData('', false);
            }

            function removeEditAnswer(button) {
                if (editAnswerCount <= 2) return;

                const container = document.getElementById('edit-answers-container');
                const answerItem = button.closest('.edit-answer-item');
                answerItem.remove();
                editAnswerCount--;

                // Reindex answers
                const answers = container.querySelectorAll('.edit-answer-item');
                answers.forEach((item, index) => {
                    item.querySelector('input[type="radio"]').value = index;
                    item.querySelector('input[type="text"]').name = `answers[${index}][answer_text]`;
                    item.querySelector('input[type="text"]').placeholder = `ตัวเลือกที่ ${index + 1}`;
                });

                updateEditRemoveButtons();
            }

            function updateEditRemoveButtons() {
                const buttons = document.querySelectorAll('.edit-answer-item button[onclick^="removeEditAnswer"]');
                buttons.forEach(btn => {
                    btn.disabled = editAnswerCount <= 2;
                });
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Handle edit form submission with AJAX
            const editForm = document.getElementById('edit-question-form');
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Check if at least one answer is marked as correct
                const correctAnswer = document.querySelector('input[name="edit_correct_answer"]:checked');
                if (!correctAnswer) {
                    alert('กรุณาเลือกคำตอบที่ถูกต้อง');
                    return;
                }

                // Build form data
                const correctAnswerIndex = parseInt(correctAnswer.value);
                const answerItems = document.querySelectorAll('.edit-answer-item');
                const questionText = document.getElementById('edit-question-text').value;

                const answersData = [];
                answerItems.forEach((item, index) => {
                    answersData.push({
                        answer_text: item.querySelector('input[type="text"]').value,
                        is_correct: index === correctAnswerIndex
                    });
                });

                // Show loading state
                const submitBtn = document.getElementById('edit-submit-btn');
                const submitText = document.getElementById('edit-submit-text');
                submitBtn.disabled = true;
                submitText.textContent = 'กำลังบันทึก...';

                try {
                    const response = await fetch(editForm.action, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            question_text: questionText,
                            answers: answersData
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Update questionsData
                        const questionIndex = questionsData.findIndex(q => q.id === currentEditQuestionId);
                        if (questionIndex !== -1) {
                            questionsData[questionIndex].question_text = questionText;
                            questionsData[questionIndex].answers = answersData.map((a, i) => ({
                                id: i,
                                answer_text: a.answer_text,
                                is_correct: a.is_correct
                            }));
                        }

                        // Update the DOM
                        updateQuestionInDOM(currentEditQuestionId, questionText, answersData);

                        // Show success message
                        showToast('อัปเดตคำถามสำเร็จ!', 'success');

                        // Close modal
                        closeEditModal();
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาด');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast(error.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitText.textContent = 'บันทึกการแก้ไข';
                }
            });

            function updateQuestionInDOM(questionId, questionText, answers) {
                const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
                if (!questionCard) return;

                // Update question text
                const questionTextEl = questionCard.querySelector('.text-gray-900.dark\\:text-white.font-medium');
                if (questionTextEl) {
                    questionTextEl.textContent = questionText;
                }

                // Update answers
                const answersContainer = questionCard.querySelector('.ml-4.space-y-2');
                if (answersContainer) {
                    answersContainer.innerHTML = answers.map(answer => `
                        <div class="flex items-center gap-2">
                            ${answer.is_correct 
                                ? `<i class="fas fa-check-circle text-green-500"></i>
                                                   <span class="text-green-700 dark:text-green-400 font-medium">${escapeHtml(answer.answer_text)}</span>
                                                   <span class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 px-2 py-1 rounded">ถูกต้อง</span>`
                                : `<i class="fas fa-circle text-gray-400"></i>
                                                   <span class="text-gray-600 dark:text-gray-400">${escapeHtml(answer.answer_text)}</span>`
                            }
                        </div>
                    `).join('');
                }
            }

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-[100] px-6 py-3 rounded-lg shadow-lg text-white font-medium transform transition-all duration-300 ${
                    type === 'success' ? 'bg-green-600' : 'bg-red-600'
                }`;
                toast.innerHTML = `
                    <div class="flex items-center gap-2">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('edit-question-modal').classList.contains(
                        'hidden')) {
                    closeEditModal();
                }
            });

            function addAnswer() {
                if (answerCount >= maxAnswers) {
                    alert('สามารถเพิ่มได้สูงสุด 6 ตัวเลือก');
                    return;
                }

                const container = document.getElementById('answers-container');
                const newAnswer = document.createElement('div');
                newAnswer.className = 'answer-item flex items-center gap-3';
                newAnswer.innerHTML = `
            <input type="radio" name="correct_answer" value="${answerCount}" class="w-5 h-5 text-green-600" title="เลือกคำตอบที่ถูกต้อง">
            <input type="text" name="answers[${answerCount}][answer_text]" required
                class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg"
                placeholder="ตัวเลือกที่ ${answerCount + 1}">
            <button type="button" onclick="removeAnswer(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                <i class="fas fa-trash"></i>
            </button>
        `;
                container.appendChild(newAnswer);
                answerCount++;
                updateRemoveButtons();
            }

            function removeAnswer(button) {
                const container = document.getElementById('answers-container');
                const answerItem = button.closest('.answer-item');
                answerItem.remove();
                answerCount--;

                // Reindex answers
                const answers = container.querySelectorAll('.answer-item');
                answers.forEach((item, index) => {
                    item.querySelector('input[type="radio"]').value = index;
                    item.querySelector('input[type="text"]').name = `answers[${index}][answer_text]`;
                    item.querySelector('input[type="text"]').placeholder = `ตัวเลือกที่ ${index + 1}`;
                });

                updateRemoveButtons();
            }

            function updateRemoveButtons() {
                const buttons = document.querySelectorAll('.answer-item button[onclick^="removeAnswer"]');
                buttons.forEach(btn => {
                    btn.disabled = answerCount <= 2;
                });
            }

            // Form submission handling
            const form = document.getElementById('add-question-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            let isSubmitting = false;

            form.addEventListener('submit', function(e) {
                // Prevent double submission
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }

                // Check if at least one answer is marked as correct
                const correctAnswer = document.querySelector('input[name="correct_answer"]:checked');
                if (!correctAnswer) {
                    e.preventDefault();
                    alert('กรุณาเลือกคำตอบที่ถูกต้อง');
                    return;
                }

                // Add hidden inputs for is_correct
                const correctAnswerIndex = correctAnswer.value;
                const answers = document.querySelectorAll('.answer-item');

                // Remove old hidden inputs if any
                document.querySelectorAll('input[name*="[is_correct]"]').forEach(el => el.remove());

                // Add new hidden inputs
                answers.forEach((item, index) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `answers[${index}][is_correct]`;
                    input.value = index == correctAnswerIndex ? '1' : '0';
                    form.appendChild(input);
                });

                // Disable submit button
                isSubmitting = true;
                submitBtn.disabled = true;
                submitText.textContent = 'กำลังเพิ่ม...';
            });

            // Sortable questions
            @if ($quiz->questions->count() > 0)
                const questionsList = document.getElementById('questions-list');
                new Sortable(questionsList, {
                    animation: 150,
                    handle: '.fa-grip-vertical',
                    onEnd: function(evt) {
                        const order = Array.from(questionsList.children).map(el => el.dataset.questionId);

                        fetch('{{ route('teacher.courses.modules.quizzes.questions.reorder', [$course->id, $module->id, $quiz->id]) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                order
                            })
                        });
                    }
                });
            @endif
        </script>
    @endpush
@endsection
