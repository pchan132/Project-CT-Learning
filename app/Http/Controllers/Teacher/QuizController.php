<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of quizzes for a module
     */
    public function index($courseId, Module $module)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        $module->load('quizzes.questions');
        
        // Make sure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('teacher.quizzes.index', compact('course', 'module'));
    }

    /**
     * Show the form for creating a new quiz
     */
    public function create($courseId, Module $module)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        // Make sure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('teacher.quizzes.create', compact('course', 'module'));
    }

    /**
     * Store a newly created quiz
     */
    public function store(Request $request, $courseId, Module $module)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        // Make sure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
        ]);

        $quiz = $module->quizzes()->create($validated);

        return redirect()
            ->route('teacher.courses.modules.quizzes.edit', [$courseId, $module->id, $quiz->id])
            ->with('success', 'สร้าง Quiz สำเร็จ! ตอนนี้เพิ่มคำถามได้เลย');
    }

    /**
     * Display the specified quiz
     */
    public function show($courseId, Module $module, Quiz $quiz)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        // Make sure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $quiz->load('questions.answers', 'attempts.student');
        
        return view('teacher.quizzes.show', compact('course', 'module', 'quiz'));
    }

    /**
     * Show the form for editing the quiz
     */
    public function edit($courseId, Module $module, Quiz $quiz)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        // Make sure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $quiz->load('questions.answers');
        
        return view('teacher.quizzes.edit', compact('course', 'module', 'quiz'));
    }

    /**
     * Update the specified quiz
     */
    public function update(Request $request, $courseId, Module $module, Quiz $quiz)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        // Make sure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
        ]);

        $quiz->update($validated);

        return redirect()
            ->route('teacher.courses.modules.quizzes.show', [$courseId, $module->id, $quiz->id])
            ->with('success', 'อัพเดท Quiz สำเร็จ!');
    }

    /**
     * Remove the specified quiz
     */
    public function destroy($courseId, Module $module, Quiz $quiz)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        // Make sure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $quiz->delete();

        return redirect()
            ->route('teacher.courses.modules.show', [$courseId, $module->id])
            ->with('success', 'ลบ Quiz สำเร็จ!');
    }

    /**
     * Store a question for the quiz
     */
    public function storeQuestion(Request $request, $courseId, Module $module, Quiz $quiz)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'answers' => 'required|array|min:2|max:6',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
        ]);

        // ตรวจสอบว่ามีคำตอบที่ถูกต้องอย่างน้อย 1 ข้อ
        $hasCorrectAnswer = collect($validated['answers'])->contains('is_correct', true);
        if (!$hasCorrectAnswer) {
            return back()->withErrors(['answers' => 'ต้องมีคำตอบที่ถูกต้องอย่างน้อย 1 ข้อ'])->withInput();
        }

        DB::transaction(function () use ($quiz, $validated) {
            $nextOrder = $quiz->questions()->max('order') + 1;

            $question = $quiz->questions()->create([
                'question_text' => $validated['question_text'],
                'order' => $nextOrder,
            ]);

            foreach ($validated['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => $answerData['is_correct'],
                    'order' => $index + 1,
                ]);
            }
        });

        return back()->with('success', 'เพิ่มคำถามสำเร็จ!');
    }

    /**
     * Update a question
     */
    public function updateQuestion(Request $request, $courseId, Module $module, Quiz $quiz, Question $question)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        if ($course->teacher_id !== auth()->id() || $question->quiz_id !== $quiz->id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'answers' => 'required|array|min:2|max:6',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
        ]);

        $hasCorrectAnswer = collect($validated['answers'])->contains('is_correct', true);
        if (!$hasCorrectAnswer) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'ต้องมีคำตอบที่ถูกต้องอย่างน้อย 1 ข้อ'], 422);
            }
            return back()->withErrors(['answers' => 'ต้องมีคำตอบที่ถูกต้องอย่างน้อย 1 ข้อ'])->withInput();
        }

        DB::transaction(function () use ($question, $validated) {
            $question->update(['question_text' => $validated['question_text']]);
            $question->answers()->delete();

            foreach ($validated['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => $answerData['is_correct'],
                    'order' => $index + 1,
                ]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'อัปเดตคำถามสำเร็จ!',
                'question' => $question->fresh()->load('answers')
            ]);
        }

        return back()->with('success', 'อัปเดตคำถามสำเร็จ!');
    }

    /**
     * Delete a question
     */
    public function destroyQuestion($courseId, Module $module, Quiz $quiz, Question $question)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        if ($course->teacher_id !== auth()->id() || $question->quiz_id !== $quiz->id) {
            abort(403, 'Unauthorized action.');
        }

        $deletedOrder = $question->order;
        $question->delete();

        $quiz->questions()->where('order', '>', $deletedOrder)->decrement('order');

        return back()->with('success', 'ลบคำถามสำเร็จ!');
    }

    /**
     * Reorder questions
     */
    public function reorderQuestions(Request $request, $courseId, Module $module, Quiz $quiz)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        if ($course->teacher_id !== auth()->id()) {
            return response()->json(['success' => false], 403);
        }

        $order = $request->input('order', []);
        foreach ($order as $index => $questionId) {
            Question::where('id', $questionId)->where('quiz_id', $quiz->id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
