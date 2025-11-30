# 🛣️ CT Learning - Routes Reference

## 📋 คู่มืออ้างอิง Routes ทั้งหมด

เอกสารนี้คือรายการ Routes ทั้งหมดในระบบ CT Learning พร้อมรายละเอียดการใช้งาน และข้อมูลสำคัญสำหรับนักพัฒนา

---

## 📋 สารบัญ

1. [Authentication Routes](#authentication-routes)
2. [Dashboard Routes](#dashboard-routes)
3. [Student Routes](#student-routes)
4. [Teacher Routes](#teacher-routes)
5. [Admin Routes](#admin-routes)
6. [API Routes](#api-routes)
7. [Middleware Reference](#middleware-reference)
8. [Route Parameters](#route-parameters)

---

## 🔐 Authentication Routes

### Registration Routes
```php
// Register as Student
Route::get('/register/student', [RegisteredUserController::class, 'createStudent'])
    ->middleware('guest')
    ->name('register.student');

Route::post('/register/student', [RegisteredUserController::class, 'storeStudent'])
    ->middleware('guest')
    ->name('register.student.store');

// Register as Teacher
Route::get('/register/teacher', [RegisteredUserController::class, 'createTeacher'])
    ->middleware('guest')
    ->name('register.teacher');

Route::post('/register/teacher', [RegisteredUserController::class, 'storeTeacher'])
    ->middleware('guest')
    ->name('register.teacher.store');
```

### Login/Logout Routes
```php
// Login
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Password Reset
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');
```

### Email Verification Routes
```php
Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
```

---

## 🏠 Dashboard Routes

### Main Dashboard
```php
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
```

### Role-specific Dashboards
```php
// Student Dashboard
Route::get('/student/dashboard', function () {
    return view('student.dashboard');
})->name('student.dashboard')->middleware('student');

// Teacher Dashboard
Route::get('/teacher/dashboard', [TeacherCourseController::class, 'index'])
    ->name('teacher.dashboard')
    ->middleware('teacher');

// Admin Dashboard
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('admin');
```

---

## 👨‍🎓 Student Routes

### Course Management
```php
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    // Course List
    Route::get('/courses', [StudentCourseController::class, 'index'])
        ->name('courses.index');
    
    // My Courses
    Route::get('/courses/my-courses', [StudentCourseController::class, 'myCourses'])
        ->name('courses.my-courses');
    
    // Course Preview
    Route::get('/courses/{course}/preview', [StudentCourseController::class, 'preview'])
        ->name('courses.preview');
    
    // Course Details
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])
        ->name('courses.show');
    
    // Enrollment
    Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])
        ->name('courses.enroll');
    
    Route::delete('/courses/{course}/unenroll', [StudentCourseController::class, 'unenroll'])
        ->name('courses.unenroll');
});
```

### Learning Routes
```php
// Lesson Learning
Route::get('/courses/{course}/lessons/{lesson}', [StudentCourseController::class, 'learnLesson'])
    ->name('student.courses.learn-lesson')
    ->middleware(['auth', 'student']);

// Mark Lesson Complete (AJAX)
Route::post('/courses/{course}/lessons/{lesson}/complete', [StudentCourseController::class, 'completeLesson'])
    ->name('student.courses.complete-lesson')
    ->middleware(['auth', 'student']);
```

### Quiz Routes
```php
// Quiz Details
Route::get('/courses/{course}/modules/{module}/quizzes/{quiz}', [StudentQuizController::class, 'show'])
    ->name('student.courses.modules.quizzes.show')
    ->middleware(['auth', 'student']);

// Start Quiz
Route::post('/student/quizzes/{quiz}/start', [StudentQuizController::class, 'start'])
    ->name('student.quizzes.start')
    ->middleware(['auth', 'student']);

// Take Quiz
Route::get('/student/attempts/{attempt}/take', [StudentQuizController::class, 'take'])
    ->name('student.attempts.take')
    ->middleware(['auth', 'student']);

// Submit Quiz
Route::post('/student/attempts/{attempt}/submit', [StudentQuizController::class, 'submit'])
    ->name('student.attempts.submit')
    ->middleware(['auth', 'student']);

// Quiz Results
Route::get('/student/attempts/{attempt}/result', [StudentQuizController::class, 'result'])
    ->name('student.attempts.result')
    ->middleware(['auth', 'student']);
```

### Certificate Routes
```php
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    // Certificate List
    Route::get('/certificates', [CertificateController::class, 'index'])
        ->name('certificates.index');
    
    // Generate Certificate
    Route::post('/courses/{course}/certificates/generate', [CertificateController::class, 'generate'])
        ->name('certificates.generate');
    
    // View Certificate
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])
        ->name('certificates.show');
    
    // Download Certificate
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])
        ->name('certificates.download');
});
```

---

## 👨‍🏫 Teacher Routes

### Course Management (Resource)
```php
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('courses', TeacherCourseController::class);
    
    // Course Students
    Route::get('courses/{course}/students', [TeacherCourseController::class, 'students'])
        ->name('courses.students');
});
```

### Module Management (Nested Resource)
```php
Route::prefix('courses/{course}/modules')->name('courses.modules.')->group(function () {
    // Module CRUD
    Route::get('/', [ModuleController::class, 'index'])
        ->name('index');
    
    Route::get('/create', [ModuleController::class, 'create'])
        ->name('create');
    
    Route::post('/', [ModuleController::class, 'store'])
        ->name('store');
    
    Route::post('/reorder', [ModuleController::class, 'reorder'])
        ->name('reorder');
    
    Route::get('/{module}', [ModuleController::class, 'show'])
        ->name('show');
    
    Route::get('/{module}/edit', [ModuleController::class, 'edit'])
        ->name('edit');
    
    Route::put('/{module}', [ModuleController::class, 'update'])
        ->name('update');
    
    Route::delete('/{module}', [ModuleController::class, 'destroy'])
        ->name('destroy');
});
```

### Lesson Management (Double Nested Resource)
```php
Route::prefix('/{module}/lessons')->name('lessons.')->group(function () {
    // Lesson CRUD
    Route::get('/', [LessonController::class, 'index'])
        ->name('index');
    
    Route::get('/create', [LessonController::class, 'create'])
        ->name('create');
    
    Route::post('/', [LessonController::class, 'store'])
        ->name('store');
    
    Route::post('/reorder', [LessonController::class, 'reorder'])
        ->name('reorder');
    
    Route::get('/{lesson}', [LessonController::class, 'show'])
        ->name('show');
    
    Route::get('/{lesson}/edit', [LessonController::class, 'edit'])
        ->name('edit');
    
    Route::put('/{lesson}', [LessonController::class, 'update'])
        ->name('update');
    
    Route::delete('/{lesson}', [LessonController::class, 'destroy'])
        ->name('destroy');
});
```

### Quiz Management (Nested Resource)
```php
Route::prefix('/{module}/quizzes')->name('quizzes.')->group(function () {
    // Quiz CRUD
    Route::get('/', [QuizController::class, 'index'])
        ->name('index');
    
    Route::get('/create', [QuizController::class, 'create'])
        ->name('create');
    
    Route::post('/', [QuizController::class, 'store'])
        ->name('store');
    
    Route::get('/{quiz}', [QuizController::class, 'show'])
        ->name('show');
    
    Route::get('/{quiz}/edit', [QuizController::class, 'edit'])
        ->name('edit');
    
    Route::put('/{quiz}', [QuizController::class, 'update'])
        ->name('update');
    
    Route::delete('/{quiz}', [QuizController::class, 'destroy'])
        ->name('destroy');
    
    // Question Management
    Route::post('/{quiz}/questions', [QuizController::class, 'storeQuestion'])
        ->name('questions.store');
    
    Route::put('/{quiz}/questions/{question}', [QuizController::class, 'updateQuestion'])
        ->name('questions.update');
    
    Route::delete('/{quiz}/questions/{question}', [QuizController::class, 'destroyQuestion'])
        ->name('questions.destroy');
    
    Route::post('/{quiz}/questions/reorder', [QuizController::class, 'reorderQuestions'])
        ->name('questions.reorder');
});
```

---

## 🔧 Admin Routes

### User Management
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // User List
    Route::get('/users', [AdminController::class, 'users'])
        ->name('users');
    
    // Create User
    Route::get('/users/create', [AdminController::class, 'createUser'])
        ->name('users.create');
    
    // Store User
    Route::post('/users', [AdminController::class, 'storeUser'])
        ->name('users.store');
    
    // Edit User
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])
        ->name('users.edit');
    
    // Update User
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])
        ->name('users.update');
    
    // Delete User
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])
        ->name('users.destroy');
});
```

### Course Management
```php
// Course List
Route::get('/courses', [AdminController::class, 'courses'])
    ->name('courses');

// Create Course
Route::get('/courses/create', [AdminController::class, 'createCourse'])
    ->name('courses.create');

// Store Course
Route::post('/courses', [AdminController::class, 'storeCourse'])
    ->name('courses.store');

// Show Course
Route::get('/courses/{course}', [AdminController::class, 'showCourse'])
    ->name('courses.show');

// Edit Course
Route::get('/courses/{course}/edit', [AdminController::class, 'editCourse'])
    ->name('courses.edit');

// Update Course
Route::put('/courses/{course}', [AdminController::class, 'updateCourse'])
    ->name('courses.update');

// Delete Course
Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])
    ->name('courses.destroy');
```

### Statistics
```php
// Statistics Dashboard
Route::get('/statistics', [AdminController::class, 'statistics'])
    ->name('statistics');
```

---

## 🔌 API Routes

### AJAX Routes (Frontend)
```php
// Mark Lesson Complete (AJAX)
Route::post('/courses/{course}/lessons/{lesson}/complete', [StudentCourseController::class, 'completeLesson'])
    ->middleware(['auth', 'student'])
    ->name('courses.complete-lesson');

// Reorder Modules (AJAX)
Route::post('/teacher/courses/{course}/modules/reorder', [ModuleController::class, 'reorder'])
    ->middleware(['auth', 'teacher'])
    ->name('teacher.courses.modules.reorder');

// Reorder Lessons (AJAX)
Route::post('/teacher/courses/{course}/modules/{module}/lessons/reorder', [LessonController::class, 'reorder'])
    ->middleware(['auth', 'teacher'])
    ->name('teacher.courses.modules.lessons.reorder');

// Reorder Quiz Questions (AJAX)
Route::post('/teacher/courses/{course}/modules/{module}/quizzes/{quiz}/questions/reorder', [QuizController::class, 'reorderQuestions'])
    ->middleware(['auth', 'teacher'])
    ->name('teacher.courses.modules.quizzes.questions.reorder');
```

---

## 🛡️ Middleware Reference

### Authentication Middleware
```php
// Standard authentication
->middleware('auth')

// Guest only (not authenticated)
->middleware('guest')

// Email verification required
->middleware(['auth', 'verified'])

// Rate limiting
->middleware('throttle:6,1') // 6 requests per minute
```

### Role-based Middleware
```php
// Student only
->middleware('student')

// Teacher only
->middleware('teacher')

// Admin only
->middleware('admin')

// Multiple roles
->middleware(['teacher', 'admin'])
```

### Custom Middleware
```php
// Signed URL verification
->middleware('signed')

// CORS handling
->middleware('cors')

// API throttling
->middleware('throttle:60,1') // 60 requests per minute
```

---

## 📝 Route Parameters

### Model Binding
```php
// Implicit Model Binding
Route::get('/courses/{course}', [CourseController::class, 'show']);
// $course parameter will be automatically resolved from database

// Explicit Model Binding
Route::get('/users/{user}', [UserController::class, 'show']);
// $user parameter will be automatically resolved from database
```

### Parameter Constraints
```php
// Numeric constraint
Route::get('/courses/{course}', [CourseController::class, 'show'])
    ->where('course', '[0-9]+');

// String constraint
Route::get('/users/{user}', [UserController::class, 'show'])
    ->where('user', '[a-zA-Z]+');

// Multiple constraints
Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])
    ->where(['course' => '[0-9]+', 'lesson' => '[0-9]+']);
```

### Optional Parameters
```php
// Optional parameter with default value
Route::get('/courses/{course?}', [CourseController::class, 'index']);

// Multiple optional parameters
Route::get('/courses/{course}/modules/{module?}', [ModuleController::class, 'index']);
```

---

## 🔍 Route List Commands

### Show All Routes
```bash
# Show all routes
php artisan route:list

# Show routes in a table format
php artisan route:list --columns=method,uri,name

# Show routes with middleware
php artisan route:list --middleware

# Show routes by name
php artisan route:list --name=student

# Show routes by method
php artisan route:list --method=GET

# Show routes by domain
php artisan route:list --domain=yourdomain.com

# Show routes in JSON format
php artisan route:list --json
```

### Filter Routes
```bash
# Filter by path
php artisan route:list --path=/student

# Filter by method
php artisan route:list --method=POST

# Filter by middleware
php artisan route:list --middleware=auth

# Filter by name pattern
php artisan route:list --name=*courses*
```

---

## 📊 Route Statistics

### Route Count by Group
```bash
# Total routes count
php artisan route:list | wc -l

# Student routes
php artisan route:list --name=student* | wc -l

# Teacher routes
php artisan route:list --name=teacher* | wc -l

# Admin routes
php artisan route:list --name=admin* | wc -l

# AJAX routes (POST only)
php artisan route:list --method=POST | wc -l
```

### Route Analysis
```bash
# Show routes with most middleware
php artisan route:list | grep -o "middleware: [^,]*" | sort | uniq -c | sort -nr

# Show longest routes
php artisan route:list | awk '{print length($2), $2}' | sort -n | tail -10

# Show routes without names
php artisan route:list | grep "│" | grep -v "│\s*[a-zA-Z0-9._-]\+│"
```

---

## 🎨 Route Naming Conventions

### Naming Pattern
```php
// Pattern: group.resource.action
student.courses.index      // List student's courses
student.courses.show       // Show specific course
student.courses.enroll     // Enroll in course
student.courses.unenroll   // Unenroll from course

teacher.courses.create     // Create new course
teacher.courses.store      // Store new course
teacher.courses.edit      // Edit existing course
teacher.courses.update     // Update existing course
teacher.courses.destroy   // Delete course

admin.users.index         // List all users
admin.users.create        // Create new user
admin.users.store         // Store new user
admin.users.edit          // Edit existing user
admin.users.update        // Update existing user
admin.users.destroy      // Delete existing user
```

### Nested Resource Naming
```php
// Pattern: group.parent.child.action
teacher.courses.modules.index      // List modules in course
teacher.courses.modules.create     // Create module in course
teacher.courses.modules.store      // Store module in course

teacher.courses.modules.lessons.index      // List lessons in module
teacher.courses.modules.lessons.create     // Create lesson in module
teacher.courses.modules.lessons.store      // Store lesson in module

teacher.courses.modules.quizzes.questions.store  // Store question in quiz
```

---

## 🔗 Route Helpers

### Generating URLs
```php
// Basic route URL
$url = route('student.courses.index');

// Route with parameter
$url = route('student.courses.show', ['course' => $course]);

// Route with multiple parameters
$url = route('student.courses.learn-lesson', [
    'course' => $course,
    'lesson' => $lesson
]);

// Route with query parameters
$url = route('student.courses.index', ['page' => 1, 'search' => 'php']);
// Result: /student/courses?page=1&search=php
```

### Generating Links
```php
// HTML link
$link = link_to_route('student.courses.show', 'View Course', ['course' => $course]);

// Link with attributes
$link = link_to_route('student.courses.destroy', 'Delete', ['course' => $course], [
    'method' => 'DELETE',
    'class' => 'btn btn-danger',
    'onclick' => 'return confirm("Are you sure?")'
]);
```

### Redirecting to Routes
```php
// Basic redirect
return redirect()->route('student.dashboard');

// Redirect with parameter
return redirect()->route('student.courses.show', ['course' => $course]);

// Redirect with flash data
return redirect()->route('student.courses.index')
    ->with('success', 'Course enrolled successfully!');

// Redirect back with input
return redirect()->route('teacher.courses.create')
    ->withInput()
    ->withErrors($validator);
```

---

## 🧪 Testing Routes

### HTTP Tests
```php
// Test route accessibility
public function test_student_can_access_dashboard()
{
    $student = User::factory()->create(['role' => 'student']);
    
    $response = $this->actingAs($student)
        ->get('/student/dashboard');
    
    $response->assertStatus(200);
}

// Test route protection
public function test_guest_cannot_access_student_dashboard()
{
    $response = $this->get('/student/dashboard');
    
    $response->assertRedirect('/login');
}

// Test route with parameter
public function test_student_can_view_course()
{
    $student = User::factory()->create(['role' => 'student']);
    $course = Course::factory()->create();
    
    $response = $this->actingAs($student)
        ->get("/student/courses/{$course->id}");
    
    $response->assertStatus(200);
    $response->assertSee($course->title);
}
```

### Route Model Binding Tests
```php
// Test implicit model binding
public function test_course_is_bound_correctly()
{
    $course = Course::factory()->create();
    
    $response = $this->get("/student/courses/{$course->id}");
    
    $response->assertStatus(200);
    $this->assertEquals($course->id, $response->viewData('course')->id);
}
```

---

## 🚨 Common Route Issues

### 404 Not Found
```php
// Problem: Route not found
// Solution: Check route list
php artisan route:list | grep "route-name"

// Problem: Parameter constraint not matching
// Solution: Check parameter constraints
Route::get('/courses/{course}', [CourseController::class, 'show'])
    ->where('course', '[0-9]+'); // Must be numeric
```

### 403 Forbidden
```php
// Problem: Middleware blocking access
// Solution: Check middleware order
Route::get('/teacher/dashboard', [TeacherController::class, 'index'])
    ->middleware(['auth', 'teacher']) // auth must come first

// Problem: Role-based middleware not working
// Solution: Check User role methods
public function isTeacher()
{
    return $this->role === 'teacher';
}
```

### 419 Page Expired
```php
// Problem: CSRF token mismatch
// Solution: Add CSRF token to forms
<form method="POST" action="/route">
    @csrf
    <!-- form fields -->
</form>

// Solution: Add CSRF token to AJAX requests
fetch('/route', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
});
```

---

## 📱 Mobile Routes

### Responsive Route Handling
```php
// Detect mobile and redirect accordingly
Route::get('/courses/{course}', function (Course $course) {
    if (request()->mobile()) {
        return view('mobile.courses.show', compact('course'));
    }
    
    return view('courses.show', compact('course'));
})->middleware('auth');
```

### PWA Routes
```php
// Service worker registration
Route::get('/sw.js', function () {
    return response()->file(public_path('sw.js'))
        ->header('Content-Type', 'application/javascript');
});

// Offline fallback
Route::get('/offline', function () {
    return view('offline');
})->name('offline');
```

---

## 🔍 Debugging Routes

### Route Debugging Commands
```bash
# Show current route information
php artisan route:current

# Show all routes with their middleware
php artisan route:list --middleware=auth

# Clear route cache
php artisan route:clear

# Cache routes for production
php artisan route:cache
```

### Route Debugging in Code
```php
// Debug route information
public function show(Course $course)
{
    dump(request()->route());
    dump(request()->route()->getName());
    dump(request()->route()->parameters());
    
    // Continue with controller logic
    return view('courses.show', compact('course'));
}

// Debug middleware stack
public function show(Course $course)
{
    dump(request()->route()->middleware());
    
    return view('courses.show', compact('course'));
}
```

---

## 📋 Route Checklist

### ✅ Development Checklist
- [ ] All routes have proper names
- [ ] Routes follow naming conventions
- [ ] Proper middleware is applied
- [ ] Route parameters are validated
- [ ] Model binding is working correctly
- [ ] Routes are cached in production
- [ ] Routes are tested

### ✅ Security Checklist
- [ ] Authentication middleware is applied
- [ ] Authorization middleware is applied
- [ ] CSRF protection is enabled
- [ ] Rate limiting is applied where needed
- [ ] Parameter constraints are set
- [ ] Route model binding is secure

### ✅ Performance Checklist
- [ ] Route caching is enabled in production
- [ ] Unnecessary routes are removed
- [ ] Route groups are optimized
- [ ] Middleware stack is minimal
- [ ] Route parameters are efficient

---

## 📞 การขอความช่วยเหลือ

### หากพบปัญหาเกี่ยวกับ Routes
1. **ตรวจสอบ Route List**: `php artisan route:list`
2. **ตรวจสอบ Middleware**: ตรวจสอบว่า middleware ถูกต้องหรือไม่
3. **ตรวจสอบ Parameters**: ตรวจสอบว่า parameters ถูกต้องหรือไม่
4. **ตรวจสอบ Cache**: ล้าง route cache: `php artisan route:clear`

### ช่องทางติดต่อ
- **Email**: dev@ct.ac.th
- **GitHub Issues**: https://github.com/yourusername/ct-learning/issues
- **Documentation**: [Documentation Index](./INDEX.md)

---

## 📚 เอกสารอ้างอิง

- [PROJECT-README.md](../PROJECT-README.md) - คู่มือหลัก
- [LMS Complete Guide](./LMS-COMPLETE-GUIDE.md) - คู่มือระบบครบถ้วน
- [Quick Reference](./QUICK-REFERENCE.md) - คู่มือใช้งานด่วน
- [Architecture Documentation](./ARCHITECTURE.md) - สถาปัตยกรรมระบบ
- [Documentation Index](./INDEX.md) - ดูเอกสารทั้งหมด

---

**สร้างเมื่อ**: 28 พฤศจิกายน 2025  
**เวอร์ชัน**: v1.0  
**ผู้เขียน**: CT Learning Team  
**สถานะ**: ✅ Complete & Updated  

---

<p align="center">
  <strong>🛣️ CT Learning - Routes Reference</strong><br>
  <em>Complete routing documentation for developers</em>
</p>