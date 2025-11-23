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

// ------ Dashboard Route -----------**
Route::get('/dashboard', function () {
    return view('dashboard');
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
    
    Route::get('/teacher/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard')->middleware('teacher');
});

require __DIR__.'/auth.php';


// --------------------------------------------------------------
// กำหนดเส้นทางสำหรับ สิทธิ์การเข้าถึงของนักเรียน และ ครู ในการจัดการคอร์สเรียน ******************
// ของนักเรียน
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');

    // เส้นทางสำหรับการลงทะเบียนเรียนในคอร์ส ของนักเรียน
    Route::post('/courses/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
});

// ของครู
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('courses', TeacherCourseController::class);
    
    // Routes สำหรับ Modules
    Route::prefix('courses/{course}/modules')->name('courses.modules.')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\ModuleController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Teacher\ModuleController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Teacher\ModuleController::class, 'store'])->name('store');
        Route::get('/{module}', [App\Http\Controllers\Teacher\ModuleController::class, 'show'])->name('show');
        Route::get('/{module}/edit', [App\Http\Controllers\Teacher\ModuleController::class, 'edit'])->name('edit');
        Route::put('/{module}', [App\Http\Controllers\Teacher\ModuleController::class, 'update'])->name('update');
        Route::delete('/{module}', [App\Http\Controllers\Teacher\ModuleController::class, 'destroy'])->name('destroy');
        
        // Routes สำหรับ Lessons ภายใน Module
        Route::prefix('/{module}/lessons')->name('lessons.')->group(function () {
            Route::get('/', [App\Http\Controllers\Teacher\LessonController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Teacher\LessonController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Teacher\LessonController::class, 'store'])->name('store');
            Route::get('/{lesson}', [App\Http\Controllers\Teacher\LessonController::class, 'show'])->name('show');
            Route::get('/{lesson}/edit', [App\Http\Controllers\Teacher\LessonController::class, 'edit'])->name('edit');
            Route::put('/{lesson}', [App\Http\Controllers\Teacher\LessonController::class, 'update'])->name('update');
            Route::delete('/{lesson}', [App\Http\Controllers\Teacher\LessonController::class, 'destroy'])->name('destroy');
        });
    });
});