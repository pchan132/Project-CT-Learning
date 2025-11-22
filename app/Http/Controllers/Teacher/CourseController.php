<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

// ควบคุมการทำงานเกี่ยวกับคอร์สเรียนของครูผู้สอน
// เช่น การสร้างคอร์สใหม่ แก้ไขคอร์ส หรือลบคอร์ส
// หรือ CRUD operation ต่างๆ บนคอร์สเรียน
class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // คอร์สทั้งหมดที่ครูผู้สอนคนนี้เป็นเจ้าของ
        // เก็บไว้ในตัวแปร $courses
        $courses = Course::where('teacher_id', auth()->id())->get();
        // ส่ง $courses ไปที่มุมมอง teacher.courses.index
        return view('teacher.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // แสดงฟอร์มสร้างคอร์สใหม่ ไปที่มุมมอง teacher.courses.create
        return view('teacher.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_image_url' => 'nullable|url',
        ]);

        $data['teacher_id'] = auth()-> id();

        if ($request->hasFile('cover_image_url')){
            $data['cover_image_url'] = $request->file('cover_image_url')->store('cover_images', 'public');
        }

        Course::create($data);
        return redirect()->route('teacher.courses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // แสดงรายละเอียดของคอร์สเรียนที่ระบุ
        $this->authorize('view', $course);
        return view('teacher.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
        $this->authorize('update', $course);
        return view('teacher.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        // แก้ไขข้อมูลคอร์สเรียน
        $this->authorize('update', $course);
       $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_image_url' => 'nullable|url',
        ]);
        $course->update($data);
        return redirect()->route('teacher.courses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        // ลบคอร์สเรียน
        $this->authorize('delete', $course);
        $course->delete();
        return redirect()->route('teacher.courses.index');
    }
}
