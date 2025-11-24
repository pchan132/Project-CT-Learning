<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Show form for creating questions
     */
    public function create($courseId, $moduleId, Quiz $quiz)
    {
        $quiz->load('questions.answers');
        $module = $quiz->module;
        
        return view('teacher.questions.create', compact('module', 'quiz'));
    }

    /**
     * Store a new question with answers
     */
    public function store(Request $request, $courseId, $moduleId, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'answers' => 'required|array|min:2',
            'answers.*.text' => 'required|string',
            'correct_answer' => 'required|integer|min:0',
        ]);

        // สร้างคำถาม
        $question = $quiz->questions()->create([
            'question_text' => $validated['question_text'],
            'order' => $quiz->questions()->count() + 1,
        ]);

        // สร้างคำตอบ
        foreach ($validated['answers'] as $index => $answerData) {
            $question->answers()->create([
                'answer_text' => $answerData['text'],
                'is_correct' => ($index == $validated['correct_answer']),
            ]);
        }

        return back()->with('success', 'เพิ่มคำถามสำเร็จ!');
    }

    /**
     * Show form for editing a question
     */
    public function edit($courseId, $moduleId, Quiz $quiz, Question $question)
    {
        $question->load('answers');
        $module = $quiz->module;
        
        return view('teacher.questions.edit', compact('module', 'quiz', 'question'));
    }

    /**
     * Update a question
     */
    public function update(Request $request, $courseId, $moduleId, Quiz $quiz, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'answers' => 'required|array|min:2',
            'answers.*.text' => 'required|string',
            'correct_answer' => 'required|integer|min:0',
        ]);

        // Update question
        $question->update([
            'question_text' => $validated['question_text'],
        ]);

        // Delete old answers and create new ones
        $question->answers()->delete();
        
        foreach ($validated['answers'] as $index => $answerData) {
            $question->answers()->create([
                'answer_text' => $answerData['text'],
                'is_correct' => ($index == $validated['correct_answer']),
            ]);
        }

        return redirect()
            ->route('teacher.courses.modules.quizzes.questions.create', [$courseId, $moduleId, $quiz->id])
            ->with('success', 'อัพเดทคำถามสำเร็จ!');
    }

    /**
     * Delete a question
     */
    public function destroy($courseId, $moduleId, Quiz $quiz, Question $question)
    {
        $question->delete();

        return back()->with('success', 'ลบคำถามสำเร็จ!');
    }
}
