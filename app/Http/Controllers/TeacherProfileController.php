<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherProfileController extends Controller
{
    /**
     * แสดงรายชื่อ Teacher ทั้งหมด (สำหรับทุก role)
     */
    public function index()
    {
        $teachers = User::where('role', 'teacher')
            ->withCount('teachingCourses')
            ->orderBy('name')
            ->get();

        return view('teachers.index', compact('teachers'));
    }

    /**
     * แสดงโปรไฟล์ Teacher และ Courses ของ Teacher นั้น
     */
    public function show(User $teacher)
    {
        // ตรวจสอบว่าเป็น teacher จริง
        if (!$teacher->isTeacher()) {
            abort(404);
        }

        $courses = $teacher->teachingCourses()
            ->withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teachers.show', compact('teacher', 'courses'));
    }

    /**
     * แสดงหน้าแก้ไขโปรไฟล์ของ Teacher (สำหรับ Teacher เอง)
     */
    public function editProfile()
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->isTeacher()) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return view('teacher.profile.edit', compact('teacher'));
    }

    /**
     * อัปเดตโปรไฟล์ Teacher
     */
    public function updateProfile(Request $request)
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->isTeacher()) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $validated = $request->validate([
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // อัปโหลดรูปโปรไฟล์
        if ($request->hasFile('profile_image')) {
            // ลบรูปเก่า
            if ($teacher->profile_image) {
                Storage::disk('public')->delete($teacher->profile_image);
            }

            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;
        }

        // อัปโหลดลายเซ็น
        if ($request->hasFile('signature_image')) {
            // ลบลายเซ็นเก่า
            if ($teacher->signature_image) {
                Storage::disk('public')->delete($teacher->signature_image);
            }

            $path = $request->file('signature_image')->store('signatures', 'public');
            $validated['signature_image'] = $path;
        }

        $teacher->update($validated);

        return redirect()->route('teacher.profile.edit')
            ->with('success', 'อัปเดตโปรไฟล์เรียบร้อยแล้ว');
    }

    /**
     * แสดงหน้าตัวอย่าง Certificate พร้อมลายเซ็นของ Teacher
     */
    public function certificatePreview()
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->isTeacher()) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // ดึงคอร์สที่ Teacher สอน
        $courses = $teacher->teachingCourses()->get();

        return view('teacher.certificate-preview', compact('teacher', 'courses'));
    }

    /**
     * ลบรูปโปรไฟล์
     */
    public function deleteProfileImage()
    {
        /** @var User $teacher */
        $teacher = Auth::user();

        if (!$teacher->isTeacher()) {
            abort(403);
        }

        if ($teacher->profile_image) {
            Storage::disk('public')->delete($teacher->profile_image);
            $teacher->update(['profile_image' => null]);
        }

        return redirect()->route('teacher.profile.edit')
            ->with('success', 'ลบรูปโปรไฟล์เรียบร้อยแล้ว');
    }

    /**
     * แสดง Preview คอร์ส (สำหรับทุก role ที่ login)
     */
    public function coursePreview(Course $course)
    {
        $user = Auth::user();

        // ถ้าเป็น student และลงทะเบียนแล้ว redirect ไปหน้า show
        if ($user->isStudent() && $course->isEnrolledByStudent($user->id)) {
            return redirect()->route('student.courses.show', $course);
        }

        // ถ้าเป็น teacher เจ้าของคอร์ส redirect ไปหน้าจัดการ
        if ($user->isTeacher() && $course->teacher_id === $user->id) {
            return redirect()->route('teacher.courses.show', $course);
        }

        // โหลดข้อมูลคอร์สพร้อม modules และ teacher
        $course->load(['modules.lessons', 'teacher']);
        $course->loadCount('enrollments');

        // นับจำนวน Quiz
        $course->quizCount = \App\Models\Quiz::whereIn('module_id', $course->modules->pluck('id'))->count();

        return view('courses.preview', compact('course'));
    }
}
