<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;

class CourseController extends Controller
{
    // ควบคุมการทำงานเกี่ยวกับคอร์สเรียนของนักเรียน
    // เช่น การแสดงรายการคอร์สที่นักเรียนลงทะเบียนเรียนไว้
    // หรือการแสดงรายละเอียดคอร์สเรียนต่างๆ
    public function index(){
        $courses = Course::with('teacher')->whereHas('enrollments', function($query){
            $query->where('student_id', auth()->id());
        })->get();
        return view('student.courses.index', compact('courses'));
    }

    public function enroll(Course $course){
        Enrollment::firstOrCreate([
            'course_id' => $course->id,
            'student_id' => auth()->id(),
        ]);
        return redirect()->route('student.courses.index')->with('success', 'ลงทะเบียนเรียนสำเร็จ');
    }
}
