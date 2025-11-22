<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------------------------------------------------
// เพิ่มเส้นทาง Auth แยก Register เป็น นักเรียน กับ ครู
// Register as Student
Route::get(uri: '/register/student', action: [RegisteredUserController::class, 'createStudent'])
    ->middleware(middleware: 'guest')
    ->name(name: 'register.student');

Route::post(uri: '/register/student', action: [RegisteredUserController::class, 'storeStudent'])
    ->middleware(middleware: 'guest')
    ->name(name: 'register.student.store');

// --------------------------------------------------------------
// Register as Teacher
Route::get(uri: '/register/teacher', action: [RegisteredUserController::class, 'createTeacher'])
    ->middleware(middleware: 'guest')
    ->name(name: 'register.teacher');

Route::post(uri: '/register/teacher', action: [RegisteredUserController::class, 'storeTeacher'])
    ->middleware(middleware: 'guest')
    ->name(name: 'register.teacher.store');

// Dashboard Routes for Student and Teacher
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
// กำหนดเส้นทางสำหรับ สิทธิ์การเข้าถึงของนักเรียน และ ครู ในการจัดการคอร์สเรียน

// ของนักเรียน
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');

    // เส้นทางสำหรับการลงทะเบียนเรียนในคอร์ส ของนักเรียน
    Route::post('/courses/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
});

// ของครู
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('courses', TeacherCourseController::class);
});