<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        // ตรวจสอบว่าครูเป็นเจ้าของคอร์สหรือไม่
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $modules = $course->modules()->ordered()->get();
        
        return view('teacher.modules.index', compact('course', 'modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        // ตรวจสอบว่าครูเป็นเจ้าของคอร์สหรือไม่
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // หาลำดับถัดไปสำหรับ module ใหม่
        $nextOrder = $course->modules()->max('order') + 1;

        return view('teacher.modules.create', compact('course', 'nextOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        // ตรวจสอบว่าครูเป็นเจ้าของคอร์สหรือไม่
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
        ]);

        // ตรวจสอบว่า order ซ้ำหรือไม่
        $existingModule = $course->modules()->where('order', $data['order'])->first();
        if ($existingModule) {
            // ถ้าซ้ำ ให้ shift modules ที่มี order >= ที่กรอก
            $course->modules()->where('order', '>=', $data['order'])->increment('order');
        }

        $data['course_id'] = $course->id;

        Module::create($data);

        return redirect()
            ->route('teacher.courses.modules.index', $course)
            ->with('success', 'Module ถูกสร้างเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Module $module)
    {
        // ตรวจสอบว่า module เป็นของคอร์สนี้และครูเป็นเจ้าของคอร์ส
        if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        $lessons = $module->lessons()->ordered()->get();

        return view('teacher.modules.show', compact('course', 'module', 'lessons'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Module $module)
    {
        // ตรวจสอบว่า module เป็นของคอร์สนี้และครูเป็นเจ้าของคอร์ส
        if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('teacher.modules.edit', compact('course', 'module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Module $module)
    {
        // ตรวจสอบว่า module เป็นของคอร์สนี้และครูเป็นเจ้าของคอร์ส
        if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
        ]);

        // ถ้าเปลี่ยน order
        if ($data['order'] != $module->order) {
            $existingModule = $course->modules()
                ->where('order', $data['order'])
                ->where('id', '!=', $module->id)
                ->first();

            if ($existingModule) {
                // ถ้ามี module อื่นอยู่ใน order นั้น ให้ shift
                if ($data['order'] > $module->order) {
                    // ย้ายไปข้างหลัง - shift modules ระหว่าง old+1 ถึง new ลง
                    $course->modules()
                        ->where('order', '>', $module->order)
                        ->where('order', '<=', $data['order'])
                        ->decrement('order');
                } else {
                    // ย้ายไปข้างหน้า - shift modules ระหว่าง new ถึง old-1 ขึ้น
                    $course->modules()
                        ->where('order', '>=', $data['order'])
                        ->where('order', '<', $module->order)
                        ->increment('order');
                }
            }
        }

        $module->update($data);

        return redirect()
            ->route('teacher.courses.modules.index', $course)
            ->with('success', 'Module ถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Module $module)
    {
        // ตรวจสอบว่า module เป็นของคอร์สนี้และครูเป็นเจ้าของคอร์ส
        if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        // ตรวจสอบว่ามี lessons อยู่หรือไม่
        if ($module->lessons()->count() > 0) {
            return redirect()
                ->route('teacher.courses.modules.index', $course)
                ->with('error', 'ไม่สามารถลบ Module ที่มี Lessons อยู่ได้ กรุณาลบ Lessons ก่อน');
        }

        // เก็บ order ปัจจุบันไว้
        $deletedOrder = $module->order;

        $module->delete();

        // Shift modules ที่มี order > ที่ลบ ลงมา 1 ตำแหน่ง
        $course->modules()
            ->where('order', '>', $deletedOrder)
            ->decrement('order');

        return redirect()
            ->route('teacher.courses.modules.index', $course)
            ->with('success', 'Module ถูกลบเรียบร้อยแล้ว');
    }

    /**
     * Reorder modules via drag-and-drop
     */
    public function reorder(Request $request, Course $course)
    {
        // ตรวจสอบว่าครูเป็นเจ้าของคอร์สหรือไม่
        if ($course->teacher_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = $request->input('order', []);

        foreach ($order as $index => $moduleId) {
            Module::where('id', $moduleId)
                ->where('course_id', $course->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}