<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of quizzes for a module
     */
    public function index($courseId, Module $module)
    {
        $module->load('course', 'quizzes.questions');
        
        // Make sure teacher owns this course
        if ($module->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('teacher.quizzes.index', compact('module'));
    }

    /**
     * Show the form for creating a new quiz
     */
    public function create($courseId, Module $module)
    {
        // Make sure teacher owns this course
        if ($module->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('teacher.quizzes.create', compact('module'));
    }

    /**
     * Store a newly created quiz
     */
    public function store(Request $request, $courseId, Module $module)
    {
        // Make sure teacher owns this course
        if ($module->course->teacher_id !== auth()->id()) {
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
            ->route('teacher.courses.modules.quizzes.questions.create', [$courseId, $module->id, $quiz->id])
            ->with('success', 'สร้าง Quiz สำเร็จ! ตอนนี้เพิ่มคำถามได้เลย');
    }

    /**
     * Display the specified quiz
     */
    public function show($courseId, Module $module, Quiz $quiz)
    {
        // Make sure teacher owns this course
        if ($module->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $quiz->load('questions.answers', 'attempts.student');
        
        return view('teacher.quizzes.show', compact('module', 'quiz'));
    }

    /**
     * Show the form for editing the quiz
     */
    public function edit($courseId, Module $module, Quiz $quiz)
    {
        // Make sure teacher owns this course
        if ($module->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('teacher.quizzes.edit', compact('module', 'quiz'));
    }

    /**
     * Update the specified quiz
     */
    public function update(Request $request, $courseId, Module $module, Quiz $quiz)
    {
        // Make sure teacher owns this course
        if ($module->course->teacher_id !== auth()->id()) {
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
        // Make sure teacher owns this course
        if ($module->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $quiz->delete();

        return redirect()
            ->route('teacher.courses.modules.show', [$courseId, $module->id])
            ->with('success', 'ลบ Quiz สำเร็จ!');
    }
}
