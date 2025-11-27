<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'total_certificates' => Certificate::count(),
        ];
        
        $recentUsers = User::latest()->take(5)->get();
        $recentCourses = Course::with('teacher')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentCourses'));
    }

    /**
     * User Management - List all users
     */
    public function users(Request $request)
    {
        $query = User::query();
        
        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        
        $users = $query->latest()->paginate(20);
        
        // Stats for tabs
        $stats = [
            'all' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'teacher' => User::where('role', 'teacher')->count(),
            'student' => User::where('role', 'student')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher,admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()
            ->route('admin.users')
            ->with('success', 'สร้างผู้ใช้สำเร็จ!');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:student,teacher,admin',
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users')
            ->with('success', 'อัพเดทผู้ใช้สำเร็จ!');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user)
    {
        // Don't allow deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'คุณไม่สามารถลบตัวเองได้!');
        }

        $user->delete();

        return back()->with('success', 'ลบผู้ใช้สำเร็จ!');
    }

    /**
     * Statistics page
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'students' => User::where('role', 'student')->count(),
            'total_courses' => Course::count(),
            'total_modules' => \App\Models\Module::count(),
            'total_lessons' => \App\Models\Lesson::count(),
            'total_enrollments' => Enrollment::count(),
            'average_completion' => Enrollment::avg('progress') ?? 0,
        ];
        
        // Course stats with relationships
        $courseStats = Course::withCount(['enrollments', 'modules', 'lessons'])
            ->with('teacher')
            ->get()
            ->map(function($course) {
                $course->lesson_completions_count = \App\Models\LessonCompletion::whereHas('lesson.module.course', function($q) use ($course) {
                    $q->where('id', $course->id);
                })->count();
                return $course;
            });
        
        // Top teachers by course count
        $topTeachers = User::where('role', 'teacher')
            ->withCount('courses')
            ->orderByDesc('courses_count')
            ->take(5)
            ->get();
        
        // Top students by enrollment count
        $topStudents = User::where('role', 'student')
            ->withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();
        
        return view('admin.statistics', compact('stats', 'courseStats', 'topTeachers', 'topStudents'));
    }

    // ==================== Course Management ====================

    /**
     * List all courses
     */
    public function courses(Request $request)
    {
        $query = Course::with('teacher')->withCount(['enrollments', 'modules', 'lessons']);
        
        // Search by title or teacher name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        $courses = $query->latest()->paginate(15);
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        return view('admin.courses.index', compact('courses', 'teachers'));
    }

    /**
     * Show create course form
     */
    public function createCourse()
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        return view('admin.courses.create', compact('teachers'));
    }

    /**
     * Store new course
     */
    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'teacher_id' => 'required|exists:users,id',
            'cover_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image_url')) {
            $validated['cover_image_url'] = $request->file('cover_image_url')->store('cover_images', 'public');
        }

        Course::create($validated);

        return redirect()
            ->route('admin.courses')
            ->with('success', 'สร้างคอร์สสำเร็จ!');
    }

    /**
     * Show course details
     */
    public function showCourse(Course $course)
    {
        $course->load(['teacher', 'modules.lessons', 'modules.quizzes', 'enrollments.student']);
        $course->loadCount(['enrollments', 'modules', 'lessons']);
        
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show edit course form
     */
    public function editCourse(Course $course)
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        return view('admin.courses.edit', compact('course', 'teachers'));
    }

    /**
     * Update course
     */
    public function updateCourse(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'teacher_id' => 'required|exists:users,id',
            'cover_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image_url')) {
            // Delete old image if exists
            if ($course->cover_image_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($course->cover_image_url);
            }
            $validated['cover_image_url'] = $request->file('cover_image_url')->store('cover_images', 'public');
        }

        $course->update($validated);

        return redirect()
            ->route('admin.courses')
            ->with('success', 'อัพเดทคอร์สสำเร็จ!');
    }

    /**
     * Delete course
     */
    public function destroyCourse(Course $course)
    {
        // Delete cover image if exists
        if ($course->cover_image_url) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($course->cover_image_url);
        }

        $course->delete();

        return redirect()
            ->route('admin.courses')
            ->with('success', 'ลบคอร์สสำเร็จ!');
    }
}
