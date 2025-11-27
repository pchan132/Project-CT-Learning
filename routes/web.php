<?php
// -------------------------- ของ Controller ----------------------------
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use Illuminate\Support\Facades\Route;

// ---------------------------- Register ----------------------------------
use App\Http\Controllers\Auth\RegisteredUserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ----หน้าแรกของเว็บไซต์  **********
Route::get('/', function () {
    return view('welcome');
});

// ------ Dashboard Route - Redirect based on role -----------**
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isTeacher()) {
        return redirect()->route('teacher.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// ------ โปรไฟล์ผู้ใช้ -----------**
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------------------------------------------------
// เพิ่มเส้นทาง Auth แยก Register เป็น นักเรียน กับ ครู -----------
// Register as Student *********
Route::get(uri: '/register/student', action: [RegisteredUserController::class, 'createStudent'])
    ->middleware(middleware: 'guest')
    ->name(name: 'register.student');

// Register as Student (POST) **********
Route::post(uri: '/register/student', action: [RegisteredUserController::class, 'storeStudent'])
    ->middleware(middleware: 'guest')
    ->name(name: 'register.student.store');

// --------------------------------------------------------------
// Register as Teacher **************
Route::get(uri: '/register/teacher', action: [RegisteredUserController::class, 'createTeacher'])
    ->middleware(middleware: 'guest')
    ->name(name: 'register.teacher');

Route::post(uri: '/register/teacher', action: [RegisteredUserController::class, 'storeTeacher'])
    ->middleware(middleware: 'guest')
    ->name(name: 'register.teacher.store');

//******** */ Dashboard Routes for Student and Teacher
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard')->middleware('student');
    
    // ส่งไปที่ TeacherCourseController 
    // และไปที่ฟังก์ชัน index 
    Route::get('/teacher/dashboard', [TeacherCourseController::class, 'index'])
        ->name('teacher.dashboard')
        ->middleware('teacher');
});

require __DIR__.'/auth.php';


// --------------------------------------------------------------
// กำหนดเส้นทางสำหรับ สิทธิ์การเข้าถึงของนักเรียน และ ครู ในการจัดการคอร์สเรียน ******************
// ของนักเรียน
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    // Course routes
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/my-courses', [StudentCourseController::class, 'myCourses'])->name('courses.my-courses');
    Route::get('/courses/{course}/preview', [StudentCourseController::class, 'preview'])->name('courses.preview');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    
    // Enrollment routes
    Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/unenroll', [StudentCourseController::class, 'unenroll'])->name('courses.unenroll');
    
    // Lesson learning routes
    Route::get('/courses/{course}/lessons/{lesson}', [StudentCourseController::class, 'learnLesson'])->name('courses.learn-lesson');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [StudentCourseController::class, 'completeLesson'])->name('courses.complete-lesson');
    
    // Quiz routes for students
    Route::get('/courses/{course}/modules/{module}/quizzes/{quiz}', [App\Http\Controllers\Student\QuizController::class, 'show'])->name('courses.modules.quizzes.show');
    Route::post('/quizzes/{quiz}/start', [App\Http\Controllers\Student\QuizController::class, 'start'])->name('quizzes.start');
    Route::get('/attempts/{attempt}/take', [App\Http\Controllers\Student\QuizController::class, 'take'])->name('attempts.take');
    Route::post('/attempts/{attempt}/submit', [App\Http\Controllers\Student\QuizController::class, 'submit'])->name('attempts.submit');
    Route::get('/attempts/{attempt}/result', [App\Http\Controllers\Student\QuizController::class, 'result'])->name('attempts.result');
    
    // Certificate routes for students
    Route::get('/certificates', [App\Http\Controllers\Student\CertificateController::class, 'index'])->name('certificates.index');
    Route::post('/courses/{course}/certificates/generate', [App\Http\Controllers\Student\CertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('/certificates/{certificate}', [App\Http\Controllers\Student\CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [App\Http\Controllers\Student\CertificateController::class, 'download'])->name('certificates.download');
});

// ของครู 
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // ส่ง corse ไปที่ TeacherCourseController
    Route::resource('courses', TeacherCourseController::class);
    
    // Routes สำหรับ Modules
    Route::prefix('courses/{course}/modules')->name('courses.modules.')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\ModuleController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Teacher\ModuleController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Teacher\ModuleController::class, 'store'])->name('store');
        Route::post('/reorder', [App\Http\Controllers\Teacher\ModuleController::class, 'reorder'])->name('reorder');
        Route::get('/{module}', [App\Http\Controllers\Teacher\ModuleController::class, 'show'])->name('show');
        Route::get('/{module}/edit', [App\Http\Controllers\Teacher\ModuleController::class, 'edit'])->name('edit');
        Route::put('/{module}', [App\Http\Controllers\Teacher\ModuleController::class, 'update'])->name('update');
        Route::delete('/{module}', [App\Http\Controllers\Teacher\ModuleController::class, 'destroy'])->name('destroy');
        
        // Routes สำหรับ Lessons ภายใน Module
        Route::prefix('/{module}/lessons')->name('lessons.')->group(function () {
            Route::get('/', [App\Http\Controllers\Teacher\LessonController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Teacher\LessonController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Teacher\LessonController::class, 'store'])->name('store');
            Route::post('/reorder', [App\Http\Controllers\Teacher\LessonController::class, 'reorder'])->name('reorder');
            Route::get('/{lesson}', [App\Http\Controllers\Teacher\LessonController::class, 'show'])->name('show');
            Route::get('/{lesson}/edit', [App\Http\Controllers\Teacher\LessonController::class, 'edit'])->name('edit');
            Route::put('/{lesson}', [App\Http\Controllers\Teacher\LessonController::class, 'update'])->name('update');
            Route::delete('/{lesson}', [App\Http\Controllers\Teacher\LessonController::class, 'destroy'])->name('destroy');
        });
        
        // Routes สำหรับ Quizzes ภายใน Module
        Route::prefix('/{module}/quizzes')->name('quizzes.')->group(function () {
            Route::get('/', [App\Http\Controllers\Teacher\QuizController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Teacher\QuizController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Teacher\QuizController::class, 'store'])->name('store');
            Route::get('/{quiz}', [App\Http\Controllers\Teacher\QuizController::class, 'show'])->name('show');
            Route::get('/{quiz}/edit', [App\Http\Controllers\Teacher\QuizController::class, 'edit'])->name('edit');
            Route::put('/{quiz}', [App\Http\Controllers\Teacher\QuizController::class, 'update'])->name('update');
            Route::delete('/{quiz}', [App\Http\Controllers\Teacher\QuizController::class, 'destroy'])->name('destroy');
            
            // Routes สำหรับ Questions ภายใน Quiz
            Route::post('/{quiz}/questions', [App\Http\Controllers\Teacher\QuizController::class, 'storeQuestion'])->name('questions.store');
            Route::put('/{quiz}/questions/{question}', [App\Http\Controllers\Teacher\QuizController::class, 'updateQuestion'])->name('questions.update');
            Route::delete('/{quiz}/questions/{question}', [App\Http\Controllers\Teacher\QuizController::class, 'destroyQuestion'])->name('questions.destroy');
            Route::post('/{quiz}/questions/reorder', [App\Http\Controllers\Teacher\QuizController::class, 'reorderQuestions'])->name('questions.reorder');
        });
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [App\Http\Controllers\Admin\AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Course Management
    Route::get('/courses', [App\Http\Controllers\Admin\AdminController::class, 'courses'])->name('courses');
    Route::get('/courses/create', [App\Http\Controllers\Admin\AdminController::class, 'createCourse'])->name('courses.create');
    Route::post('/courses', [App\Http\Controllers\Admin\AdminController::class, 'storeCourse'])->name('courses.store');
    Route::get('/courses/{course}', [App\Http\Controllers\Admin\AdminController::class, 'showCourse'])->name('courses.show');
    Route::get('/courses/{course}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editCourse'])->name('courses.edit');
    Route::put('/courses/{course}', [App\Http\Controllers\Admin\AdminController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{course}', [App\Http\Controllers\Admin\AdminController::class, 'destroyCourse'])->name('courses.destroy');
    
    // Statistics
    Route::get('/statistics', [App\Http\Controllers\Admin\AdminController::class, 'statistics'])->name('statistics');
});