<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Show quiz to student
     */
    public function show(Quiz $quiz)
    {
        // Check if already passed
        $hasPassed = $quiz->hasPassedByStudent(auth()->id());
        
        // Get best attempt
        $bestAttempt = $quiz->getBestAttemptForStudent(auth()->id());
        
        // Load questions with answers
        $quiz->load('questions.answers', 'module.course');
        
        return view('student.quizzes.show', compact('quiz', 'hasPassed', 'bestAttempt'));
    }

    /**
     * Start quiz attempt
     */
    public function start(Quiz $quiz)
    {
        $quiz->load('questions.answers');
        
        return view('student.quizzes.take', compact('quiz'));
    }

    /**
     * Submit quiz answers
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'started_at' => 'required|date',
        ]);

        $quiz->load('questions.answers');
        
        // Calculate score
        $correctAnswers = 0;
        $totalQuestions = $quiz->questions->count();
        
        foreach ($quiz->questions as $question) {
            $submittedAnswerId = $validated['answers'][$question->id] ?? null;
            
            if ($submittedAnswerId && $question->isCorrectAnswer($submittedAnswerId)) {
                $correctAnswers++;
            }
        }
        
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;
        $passed = $score >= $quiz->passing_score;
        
        // Store attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id(),
            'score' => $score,
            'passed' => $passed,
            'answers' => $validated['answers'],
            'started_at' => $validated['started_at'],
            'completed_at' => now(),
        ]);
        
        return redirect()
            ->route('student.quizzes.result', $attempt->id)
            ->with('success', 'ส่งคำตอบสำเร็จ!');
    }

    /**
     * Show quiz result
     */
    public function result(QuizAttempt $attempt)
    {
        // Make sure this is the student's attempt
        if ($attempt->student_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงผลการทดสอบนี้');
        }
        
        $attempt->load('quiz.questions.answers', 'quiz.module.course');
        
        return view('student.quizzes.result', compact('attempt'));
    }
}
