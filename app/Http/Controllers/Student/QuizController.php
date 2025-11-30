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
    public function show($course, $module, Quiz $quiz)
    {
        $course = \App\Models\Course::findOrFail($course);
        $module = \App\Models\Module::findOrFail($module);
        
        // Check if student enrolled
        if (!$course->isEnrolledByStudent(auth()->id())) {
            abort(403, 'คุณต้องลงทะเบียนคอร์สก่อน');
        }
        
        // Check if already passed
        $hasPassed = $quiz->hasPassedByStudent(auth()->id());
        
        // Get best attempt
        $bestAttempt = $quiz->getBestAttemptForStudent(auth()->id());
        
        // Get all attempts
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
        
        // Load questions with answers
        $quiz->load('questions.answers');
        
        return view('student.quizzes.show', compact('course', 'module', 'quiz', 'hasPassed', 'bestAttempt', 'attempts'));
    }

    /**
     * Start quiz attempt
     */
    public function start(Quiz $quiz)
    {
        // Get course through quiz
        $module = $quiz->module;
        $course = $module->course;
        
        // Check if student enrolled
        if (!$course->isEnrolledByStudent(auth()->id())) {
            return redirect()->back()->with('error', 'คุณต้องลงทะเบียนคอร์สก่อน');
        }
        
        // Create new attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id(),
            'score' => 0,
            'passed' => false,
            'started_at' => now(),
        ]);
        
        return redirect()->route('student.attempts.take', $attempt->id);
    }

    /**
     * Show quiz taking page
     */
    public function take(QuizAttempt $attempt)
    {
        // Check authorization
        if ($attempt->student_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // If already completed, redirect to result
        if ($attempt->completed_at) {
            return redirect()->route('student.attempts.result', $attempt->id);
        }

        $quiz = $attempt->quiz;
        $module = $quiz->module;
        $course = $module->course;
        
        // Load questions and answers
        $quiz->load('questions.answers');

        // Check time limit
        $timeLimit = $quiz->time_limit;
        $timeRemaining = null;
        
        if ($timeLimit) {
            $elapsedMinutes = $attempt->started_at->diffInMinutes(now());
            $timeRemaining = max(0, $timeLimit - $elapsedMinutes);
            
            // Auto submit if time expired
            if ($timeRemaining <= 0) {
                return $this->submit(new Request(['answers' => []]), $attempt);
            }
        }

        // Load course with modules for sidebar
        $course->load(['modules.lessons', 'modules.quizzes']);

        return view('student.quizzes.take-fullscreen', compact('course', 'module', 'quiz', 'attempt', 'timeRemaining'));
    }

    /**
     * Submit quiz answers
     */
    public function submit(Request $request, QuizAttempt $attempt)
    {
        // Check authorization
        if ($attempt->student_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // If already completed
        if ($attempt->completed_at) {
            return redirect()->route('student.attempts.result', $attempt->id);
        }
        
        $quiz = $attempt->quiz;
        $module = $quiz->module;
        $course = $module->course;

        $validated = $request->validate([
            'answers' => 'nullable|array',
            'answers.*' => 'exists:answers,id',
        ]);

        $studentAnswers = $validated['answers'] ?? [];
        $quiz->load('questions.answers');
        
        // Calculate score
        $totalQuestions = $quiz->questions->count();
        $correctAnswers = 0;
        $answerDetails = [];
        
        foreach ($quiz->questions as $question) {
            $studentAnswerId = $studentAnswers[$question->id] ?? null;
            $correctAnswer = $question->answers()->where('is_correct', true)->first();
            $isCorrect = false;

            if ($studentAnswerId && $correctAnswer && $studentAnswerId == $correctAnswer->id) {
                $correctAnswers++;
                $isCorrect = true;
            }

            $answerDetails[$question->id] = $studentAnswerId;
        }
        
        $scorePercent = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        $passed = $scorePercent >= $quiz->passing_score;
        
        // Update attempt
        $attempt->update([
            'score' => $scorePercent,
            'passed' => $passed,
            'answers' => $answerDetails,
            'completed_at' => now(),
        ]);
        
        return redirect()
            ->route('student.attempts.result', $attempt->id)
            ->with('success', $passed ? 'ยินดีด้วย! คุณสอบผ่าน!' : 'คุณสอบไม่ผ่าน ลองใหม่ได้');
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

        // If not completed yet
        if (!$attempt->completed_at) {
            return redirect()->route('student.attempts.take', $attempt->id);
        }

        $quiz = $attempt->quiz;
        $module = $quiz->module;
        $course = $module->course;
        
        $quiz->load('questions.answers');
        
        // Calculate correct answers for display
        $totalQuestions = $quiz->questions->count();
        $correctAnswers = 0;
        
        foreach ($quiz->questions as $question) {
            $userAnswerId = $attempt->answers[$question->id] ?? null;
            $correctAnswer = $question->answers()->where('is_correct', true)->first();
            
            if ($userAnswerId && $correctAnswer && $userAnswerId == $correctAnswer->id) {
                $correctAnswers++;
            }
        }
        
        return view('student.quizzes.result', compact('course', 'module', 'quiz', 'attempt', 'totalQuestions', 'correctAnswers'));
    }
}
