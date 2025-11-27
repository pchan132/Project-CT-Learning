<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\Lesson;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('student');
    }

    /**
     * แสดงหน้าดูรายละเอียดคอร์สก่อนลงทะเบียน (Preview)
     */
    public function preview(Course $course)
    {
        // ถ้าลงทะเบียนแล้ว redirect ไปหน้า show
        if ($course->isEnrolledByStudent(auth()->id())) {
            return redirect()->route('student.courses.show', $course);
        }

        // โหลดข้อมูลคอร์สพร้อม modules และ teacher
        $course->load(['modules.lessons', 'teacher']);
        $course->loadCount('enrollments');
        
        // นับจำนวน Quiz
        $course->quizCount = \App\Models\Quiz::whereIn('module_id', $course->modules->pluck('id'))->count();

        return view('student.courses.preview', compact('course'));
    }

    /**
     * แสดงรายการคอร์สทั้งหมดที่เปิดให้ลงทะเบียน
     */
    public function index()
    {
        // คอร์สที่นักเรียนลงทะเบียนแล้ว
        $enrolledCourses = Course::with(['teacher', 'enrollments' => function($query) {
            $query->where('student_id', auth()->id());
        }])
        ->whereHas('enrollments', function($query) {
            $query->where('student_id', auth()->id());
        })
        ->get();

        // คอร์สที่ยังไม่ได้ลงทะเบียน
        $availableCourses = Course::with('teacher')
            ->whereDoesntHave('enrollments', function($query) {
                $query->where('student_id', auth()->id());
            })
            ->get();

        return view('student.courses.index', compact('enrolledCourses', 'availableCourses'));
    }

    /**
     * แสดงรายละเอียดคอร์สและบทเรียน
     */
    public function show(Course $course)
    {
        // ตรวจสอบว่านักเรียนลงทะเบียนคอร์สนี้หรือไม่
        if (!$course->isEnrolledByStudent(auth()->id())) {
            return redirect()->route('student.courses.index')
                ->with('error', 'คุณยังไม่ได้ลงทะเบียนคอร์สนี้');
        }

        // โหลดข้อมูลคอร์สพร้อม modules และ lessons
        $course->load(['modules.lessons', 'teacher']);

        // ดึงข้อมูลการเรียนเสร็จของนักเรียน
        $completedLessons = LessonCompletion::where('student_id', auth()->id())
            ->whereHas('lesson.module', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->pluck('lesson_id')
            ->toArray();

        // คำนวณ progress
        $progress = $course->getProgressForStudent(auth()->id());

        return view('student.courses.show', compact('course', 'completedLessons', 'progress'));
    }

    /**
     * ลงทะเบียนเรียนคอร์ส
     */
    public function enroll(Course $course)
    {
        // ตรวจสอบว่าลงทะเบียนแล้วหรือไม่
        if ($course->isEnrolledByStudent(auth()->id())) {
            return redirect()->route('student.courses.index')
                ->with('info', 'คุณลงทะเบียนคอร์สนี้แล้ว');
        }

        // สร้างการลงทะเบียน
        Enrollment::create([
            'course_id' => $course->id,
            'student_id' => auth()->id(),
        ]);

        return redirect()->route('student.courses.show', $course)
            ->with('success', 'ลงทะเบียนเรียนสำเร็จ');
    }

    /**
     * ถอนการลงทะเบียนคอร์ส
     */
    public function unenroll(Course $course)
    {
        $enrollment = Enrollment::where('course_id', $course->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($enrollment) {
            // ลบข้อมูลการเรียนเสร็จทั้งหมดในคอร์สนี้
            LessonCompletion::where('student_id', auth()->id())
                ->whereHas('lesson.module', function($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->delete();

            // ลบการลงทะเบียน
            $enrollment->delete();

            return redirect()->route('student.courses.index')
                ->with('success', 'ถอนการลงทะเบียนสำเร็จ');
        }

        return redirect()->route('student.courses.index')
            ->with('error', 'ไม่พบข้อมูลการลงทะเบียน');
    }

    /**
     * แสดงรายการคอร์สที่ลงทะเบียนแล้ว (สำหรับ dashboard)
     */
    public function myCourses()
    {
        $courses = Course::with(['teacher', 'modules.lessons'])
            ->whereHas('enrollments', function($query) {
                $query->where('student_id', auth()->id());
            })
            ->get()
            ->map(function($course) {
                $course->progress = $course->getProgressForStudent(auth()->id());
                $course->completed_lessons = $course->getCompletedLessonsCount(auth()->id());
                $course->total_lessons = $course->getTotalLessonsAttribute();
                return $course;
            });

        return view('student.courses.my-courses', compact('courses'));
    }

    /**
     * แสดงหน้าเรียนบทเรียน
     */
    public function learnLesson(Course $course, Lesson $lesson)
    {
        // ตรวจสอบว่านักเรียนลงทะเบียนคอร์สนี้หรือไม่
        if (!$course->isEnrolledByStudent(auth()->id())) {
            return redirect()->route('student.courses.index')
                ->with('error', 'คุณยังไม่ได้ลงทะเบียนคอร์สนี้');
        }

        // ตรวจสอบว่าบทเรียนนี้อยู่ในคอร์สนี้จริงหรือไม่
        if ($lesson->module->course_id !== $course->id) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', 'ไม่พบบทเรียนนี้ในคอร์ส');
        }

        // ตรวจสอบว่าเรียนเสร็จแล้วหรือไม่
        $isCompleted = $lesson->isCompletedByStudent(auth()->id());

        // ดึงบทเรียนถัดไปและก่อนหน้า
        $allLessons = $course->lessons()->orderBy('order')->get();
        $currentLessonIndex = $allLessons->search(function($l) use ($lesson) {
            return $l->id === $lesson->id;
        });

        $previousLesson = $currentLessonIndex > 0 ? $allLessons[$currentLessonIndex - 1] : null;
        $nextLesson = $currentLessonIndex < $allLessons->count() - 1 ? $allLessons[$currentLessonIndex + 1] : null;

        return view('student.lessons.learn', compact(
            'course', 'lesson', 'isCompleted', 'previousLesson', 'nextLesson'
        ));
    }

    /**
     * บันทึกการเรียนบทเรียนเสร็จ
     */
    public function completeLesson(Course $course, Lesson $lesson)
    {
        // ตรวจสอบสิทธิ์
        if (!$course->isEnrolledByStudent(auth()->id()) || $lesson->module->course_id !== $course->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // สร้างหรืออัพเดทการเรียนเสร็จ
        $completion = LessonCompletion::firstOrCreate([
            'lesson_id' => $lesson->id,
            'student_id' => auth()->id(),
        ]);

        // คำนวณ progress ใหม่
        $progress = $course->getProgressForStudent(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'บทเรียนนี้เรียนเสร็จแล้ว',
            'progress' => $progress
        ]);
    }
}
