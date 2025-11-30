# 🏗️ CT Learning - System Architecture

## 📋 สารบัญ
1. [Overview](#overview)
2. [System Architecture](#system-architecture)
3. [Database Design](#database-design)
4. [Application Flow](#application-flow)
5. [Security & Authorization](#security--authorization)
6. [File Storage](#file-storage)
7. [Performance & Optimization](#performance--optimization)
8. [Development Guidelines](#development-guidelines)

---

## 🎯 Overview

CT Learning เป็น Learning Management System (LMS) ที่พัฒนาด้วย Laravel 10.x ตามสถาปัตยกรรม MVC Pattern รองรับการทำงานแบบ Multi-role และมีโครงสร้างแบบ Nested Resources

### 🏗️ Core Architecture Principles
- **Separation of Concerns**: แยกส่วนตามหน้าที่การทำงาน (MVC)
- **Scalability**: ออกแบบให้รองรับการขยายตัว
- **Maintainability**: โค้ดที่เป็นระเบียบและบำรุงรักษาง่าย
- **Security**: ความปลอดภัยเป็นสำคัญทุกระดับ
- **Performance**: ปรับให้ทำงานได้อย่างมีประสิทธิภาพ

### 🛠️ Technology Stack
- **Backend Framework**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Database**: MySQL 8.0 / PostgreSQL
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage System
- **PDF Generation**: DomPDF
- **Build Tools**: Vite + NPM

### 🎨 Design Patterns ที่ใช้
- **MVC Pattern**: Model-View-Controller
- **Repository Pattern**: สำหรับ Data Access Layer
- **Service Layer**: สำหรับ Business Logic
- **Middleware Pattern**: สำหรับ Request Processing
- **Observer Pattern**: สำหรับ Event Handling

---

## System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        Client Layer                          │
│                      (Web Browser)                           │
└─────────────────────┬───────────────────────────────────────┘
                      │ HTTP/HTTPS
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                    Presentation Layer                        │
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │  Blade Views │  │   Tailwind   │  │  JavaScript  │     │
│  │  Components  │  │     CSS      │  │   (AJAX)     │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                     Routing Layer                            │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              routes/web.php                           │  │
│  │  • Teacher Routes (Nested Resources)                 │  │
│  │  • Student Routes (Learning Paths)                   │  │
│  │  • AJAX Routes (Completion Tracking)                 │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                   Middleware Layer                           │
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │     Auth     │  │   Teacher    │  │   Student    │     │
│  │  Middleware  │  │  Middleware  │  │  Middleware  │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                   Controller Layer                           │
│                                                              │
│  ┌────────────────────────┐  ┌─────────────────────────┐   │
│  │  Teacher Controllers   │  │  Student Controllers    │   │
│  │  • CourseController    │  │  • CourseController     │   │
│  │  • ModuleController    │  │  • LearningController   │   │
│  │  • LessonController    │  │                         │   │
│  └────────────────────────┘  └─────────────────────────┘   │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                     Model Layer                              │
│                   (Business Logic)                           │
│                                                              │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │  Course  │  │  Module  │  │  Lesson  │  │   User   │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
│                                                              │
│  ┌──────────────┐  ┌────────────────────┐                  │
│  │  Enrollment  │  │ LessonCompletion   │                  │
│  └──────────────┘  └────────────────────┘                  │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                   Database Layer                             │
│                   (MySQL/PostgreSQL)                         │
│                                                              │
│  ┌────────────────────────────────────────────────────┐     │
│  │  Tables: users, courses, modules, lessons,         │     │
│  │          enrollments, lesson_completions           │     │
│  └────────────────────────────────────────────────────┘     │
└──────────────────────────────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                    Storage Layer                             │
│                                                              │
│  ┌────────────────┐  ┌────────────────┐                     │
│  │  Local Disk    │  │  Public Disk   │                     │
│  │  (Logs, etc)   │  │  (Uploads)     │                     │
│  └────────────────┘  └────────────────┘                     │
└──────────────────────────────────────────────────────────────┘
```

---

## Database Design

### Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│  ┌─────────────┐                                                │
│  │    users    │                                                │
│  ├─────────────┤                                                │
│  │ id (PK)     │─┐                                              │
│  │ name        │ │                                              │
│  │ email       │ │ 1:N (teacher_id)                            │
│  │ password    │ │                                              │
│  │ role        │ │                                              │
│  │ created_at  │ │                                              │
│  │ updated_at  │ │    ┌──────────────────┐                     │
│  └─────────────┘ │    │     courses      │                     │
│                  └───▶├──────────────────┤                     │
│                       │ id (PK)          │─┐                   │
│                       │ teacher_id (FK)  │ │                   │
│                       │ title            │ │ 1:N               │
│  ┌─────────────┐      │ description      │ │                   │
│  │ enrollments │      │ cover_image_url  │ │                   │
│  ├─────────────┤      │ created_at       │ │                   │
│  │ id (PK)     │      │ updated_at       │ │                   │
│  │ user_id (FK)│──┐   └──────────────────┘ │                   │
│  │ course_id   │  │                        │                   │
│  │ enrolled_at │  │   ┌──────────────────┐ │                   │
│  └─────────────┘  │   │     modules      │ │                   │
│         │         │   ├──────────────────┤ │                   │
│         │         └──▶│ id (PK)          │◀┘                   │
│         │             │ course_id (FK)   │─┐                   │
│         │             │ title            │ │                   │
│         │             │ description      │ │ 1:N               │
│         │             │ order            │ │                   │
│         │             │ created_at       │ │                   │
│         │             │ updated_at       │ │                   │
│         │             └──────────────────┘ │                   │
│         │                                  │                   │
│         │             ┌──────────────────┐ │                   │
│         │             │     lessons      │ │                   │
│         │             ├──────────────────┤ │                   │
│         │         ┌──▶│ id (PK)          │◀┘                   │
│         │         │   │ module_id (FK)   │─┐                   │
│         │         │   │ title            │ │                   │
│         │         │   │ content_type     │ │ 1:N               │
│         │         │   │ content_url      │ │                   │
│         │         │   │ content_text     │ │                   │
│         │         │   │ order            │ │                   │
│         │         │   │ created_at       │ │                   │
│         │         │   │ updated_at       │ │                   │
│         │         │   └──────────────────┘ │                   │
│         │         │                        │                   │
│         │         │   ┌───────────────────────┐                │
│         │         │   │  lesson_completions   │                │
│         │         │   ├───────────────────────┤                │
│         └─────────┼──▶│ id (PK)               │◀───────────────┘
│                   │   │ lesson_id (FK)        │
│                   └───│ user_id (FK)          │
│                       │ completed_at          │
│                       └───────────────────────┘
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### Table Schemas

#### users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('teacher', 'student') NOT NULL DEFAULT 'student',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_role (role)
);
```

#### courses
```sql
CREATE TABLE courses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    cover_image_url VARCHAR(500) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_teacher (teacher_id)
);
```

#### modules
```sql
CREATE TABLE modules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    order INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_course (course_id),
    INDEX idx_order (order)
);
```

#### lessons
```sql
CREATE TABLE lessons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content_type ENUM('PDF', 'VIDEO', 'TEXT') NOT NULL,
    content_url VARCHAR(500) NULL,
    content_text TEXT NULL,
    order INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    INDEX idx_module (module_id),
    INDEX idx_order (order)
);
```

#### enrollments
```sql
CREATE TABLE enrollments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    enrolled_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, course_id),
    INDEX idx_user (user_id),
    INDEX idx_course (course_id)
);
```

#### lesson_completions
```sql
CREATE TABLE lesson_completions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_completion (lesson_id, user_id),
    INDEX idx_lesson (lesson_id),
    INDEX idx_user (user_id)
);
```

---

## Application Flow

### Teacher Workflow

```
┌──────────────────────────────────────────────────────────────┐
│                      Teacher Journey                          │
└──────────────────────────────────────────────────────────────┘

1. Authentication
   │
   ▼
[Login as Teacher] ──▶ TeacherMiddleware validates role
   │
   ▼
2. Course Management
   │
   ├─▶ [View All Courses] ──▶ GET /teacher/courses
   │   │
   │   └─▶ [Create Course] ──▶ GET /teacher/courses/create
   │       │
   │       └─▶ [Submit Form] ──▶ POST /teacher/courses
   │           • Validation
   │           • Upload cover image
   │           • Store in database
   │
   ▼
3. Module Management
   │
   ├─▶ [Click "📚 Modules" button]
   │   │
   │   └─▶ GET /teacher/courses/{course}/modules
   │       │
   │       ├─▶ [View Modules List]
   │       │   • Shows: Title, Order, Lesson Count
   │       │   • Actions: Edit, Delete, View Lessons
   │       │
   │       └─▶ [Add Module] ──▶ GET /teacher/courses/{course}/modules/create
   │           │
   │           └─▶ POST /teacher/courses/{course}/modules
   │               • title (required)
   │               • description (optional)
   │               • order (required)
   │
   ▼
4. Lesson Management
   │
   ├─▶ [Click "📝 Lessons" button]
   │   │
   │   └─▶ GET /teacher/courses/{course}/modules/{module}/lessons
   │       │
   │       ├─▶ [View Lessons List]
   │       │   • Shows: Title, Type, Order
   │       │   • Actions: Edit, Delete, View
   │       │
   │       └─▶ [Add Lesson] ──▶ GET .../create
   │           │
   │           └─▶ [Select Content Type]
   │               │
   │               ├─▶ [PDF] ──▶ Upload file (.pdf, .ppt, .doc)
   │               │   • Max 10MB
   │               │   • Store in storage/app/public/lessons/pdf/
   │               │
   │               ├─▶ [Video] ──▶ Enter YouTube URL
   │               │   • Format: youtube.com/watch?v=ID
   │               │   • Auto-convert to embed format
   │               │
   │               └─▶ [Article] ──▶ Write text content
   │                   • Plain text with line breaks
   │                   • Display with nl2br()
   │
   ▼
5. View Students
   │
   └─▶ [View Enrolled Students] ──▶ GET /teacher/courses/{course}
       • List all enrolled students
       • Show enrollment date
```

### Student Workflow

```
┌──────────────────────────────────────────────────────────────┐
│                      Student Journey                          │
└──────────────────────────────────────────────────────────────┘

1. Authentication
   │
   ▼
[Login as Student] ──▶ StudentMiddleware validates role
   │
   ▼
2. Course Dashboard
   │
   └─▶ GET /student/courses
       │
       └─▶ [View Enrolled Courses]
           • Course card with cover image
           • Progress bar (0-100%)
           • "Completed" badge if 100%
           • "Continue Learning" button
   │
   ▼
3. View Course Modules
   │
   └─▶ [Click "Continue Learning"]
       │
       └─▶ GET /student/courses/{course}/learn
           │
           ├─▶ Check enrollment
           │   • Abort 403 if not enrolled
           │
           └─▶ [Show Modules List]
               • Module cards with:
                 - Order badge
                 - Title & Description
                 - Lesson count
                 - "View Lessons" button
   │
   ▼
4. View Module Lessons
   │
   └─▶ [Click "View Lessons"]
       │
       └─▶ GET /student/courses/{course}/modules/{module}
           │
           └─▶ [Show Lessons List]
               • Lesson items with:
                 - Order number
                 - Title
                 - Type icon (📄/🎥/📝)
                 - Completion badge (✅)
                 - "Start Learning" button
   │
   ▼
5. Learn Lesson
   │
   └─▶ [Click "Start Learning"]
       │
       └─▶ GET /student/courses/{course}/modules/{module}/lessons/{lesson}
           │
           ├─▶ [Render Content by Type]
           │   │
           │   ├─▶ [PDF] ──▶ <embed> tag with content_url
           │   │
           │   ├─▶ [Video] ──▶ <iframe> YouTube embed
           │   │   • Convert URL: youtube.com/watch?v=ID
           │   │   • To: youtube.com/embed/ID
           │   │
           │   └─▶ [Article] ──▶ Display text with nl2br()
           │
           └─▶ [Mark Complete Button]
               │
               └─▶ [Click "Mark as Complete"]
                   │
                   └─▶ AJAX POST /lessons/{lesson}/complete
                       │
                       ├─▶ Check if already completed
                       │
                       ├─▶ Create LessonCompletion record
                       │
                       └─▶ Return JSON response
                           │
                           └─▶ Update UI without reload
                               • Change button text
                               • Disable button
                               • Update badge
   │
   ▼
6. Track Progress
   │
   └─▶ [Auto-calculated on dashboard]
       • Formula: (completed / total) × 100
       • Updated after each completion
       • Visual progress bar
       • 100% = "Completed" badge
```

### AJAX Completion Flow

```
┌──────────────────────────────────────────────────────────────┐
│              AJAX Completion Workflow                         │
└──────────────────────────────────────────────────────────────┘

Client Side (JavaScript)
│
├─▶ User clicks "Mark as Complete" button
│
├─▶ JavaScript event listener triggered
│   • Get lesson ID from button
│   • Get CSRF token from meta tag
│
├─▶ Send AJAX POST request
│   • URL: /lessons/{lesson}/complete
│   • Method: POST
│   • Headers:
│     - Content-Type: application/json
│     - X-CSRF-TOKEN: {token}
│   • Body: {} (empty)
│
▼

Server Side (Laravel)
│
├─▶ Route: POST /lessons/{lesson}/complete
│   • Middleware: auth
│   • Controller: LearningController@complete
│
├─▶ LearningController@complete($lesson)
│   │
│   ├─▶ Check if already completed
│   │   • LessonCompletion::where([
│   │       'lesson_id' => $lesson->id,
│   │       'user_id' => auth()->id()
│   │     ])->exists()
│   │
│   ├─▶ If already completed:
│   │   └─▶ return json(['message' => 'Already completed'])
│   │
│   ├─▶ If not completed:
│   │   ├─▶ Create completion record
│   │   │   • LessonCompletion::create([
│   │   │       'lesson_id' => $lesson->id,
│   │   │       'user_id' => auth()->id()
│   │   │     ])
│   │   │
│   │   └─▶ Return success
│   │       • return json(['message' => 'Lesson marked as complete'])
│   │
│   └─▶ HTTP 200 OK
│
▼

Client Side (Response Handling)
│
├─▶ Receive JSON response
│
├─▶ Update UI elements:
│   • Change button text to "✅ Completed"
│   • Disable button
│   • Add green styling
│   • Update progress bar (if on dashboard)
│
└─▶ No page reload required!
```

---

## Security & Authorization

### Authentication Flow

```
┌──────────────────────────────────────────────────────────────┐
│                   Authentication System                       │
└──────────────────────────────────────────────────────────────┘

Laravel Breeze (Built-in)
│
├─▶ Registration
│   • routes/auth.php
│   • Controllers: RegisteredUserController
│   • Default role: 'student'
│
├─▶ Login
│   • routes/auth.php
│   • Controllers: AuthenticatedSessionController
│   • Session-based authentication
│
└─▶ Logout
    • DELETE /logout
    • Clear session
    • Redirect to homepage
```

### Authorization Strategies

#### 1. Role-Based Middleware

```php
// app/Http/Middleware/TeacherMiddleware.php
public function handle(Request $request, Closure $next)
{
    if (auth()->user()->role !== 'teacher') {
        abort(403, 'Access denied. Teacher role required.');
    }
    
    return $next($request);
}

// app/Http/Middleware/StudentMiddleware.php
public function handle(Request $request, Closure $next)
{
    if (auth()->user()->role !== 'student') {
        abort(403, 'Access denied. Student role required.');
    }
    
    return $next($request);
}
```

#### 2. Ownership-Based Authorization (Teacher)

```php
// ใน Teacher Controllers
public function edit(Course $course, Module $module)
{
    // Check 1: เป็น Teacher ของคอร์สนี้หรือไม่
    if (auth()->id() !== $course->teacher_id) {
        abort(403, 'You do not have permission to edit this module.');
    }
    
    // Check 2: Module เป็นของคอร์สนี้จริงหรือไม่
    if ($module->course_id !== $course->id) {
        abort(404, 'Module not found in this course.');
    }
    
    return view('teacher.modules.edit', compact('course', 'module'));
}
```

#### 3. Enrollment-Based Authorization (Student)

```php
// ใน Student Controllers
public function showLesson(Course $course, Module $module, Lesson $lesson)
{
    // Check 1: ลงทะเบียนคอร์สนี้หรือไม่
    $enrollment = auth()->user()->enrollments()
        ->where('course_id', $course->id)
        ->first();
        
    if (!$enrollment) {
        abort(403, 'You are not enrolled in this course.');
    }
    
    // Check 2: Module เป็นของคอร์สนี้
    if ($module->course_id !== $course->id) {
        abort(404);
    }
    
    // Check 3: Lesson เป็นของ Module นี้
    if ($lesson->module_id !== $module->id) {
        abort(404);
    }
    
    return view('student.lessons.show', compact('course', 'module', 'lesson'));
}
```

### CSRF Protection

```html
<!-- ทุก Form ต้องมี CSRF Token -->
<form method="POST" action="...">
    @csrf
    <!-- form fields -->
</form>

<!-- AJAX Requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
});
</script>
```

---

## File Storage

### Storage Structure

```
storage/
├── app/
│   ├── public/              # Publicly accessible files
│   │   └── lessons/
│   │       └── pdf/         # Uploaded lesson files
│   │           ├── 1637234567_presentation.pdf
│   │           ├── 1637234890_document.docx
│   │           └── 1637235123_slides.pptx
│   │
│   └── private/             # Private files (logs, etc.)
│
├── framework/               # Laravel framework cache
│   ├── cache/
│   ├── sessions/
│   └── views/
│
└── logs/                    # Application logs
    └── laravel.log

public/
└── storage ──▶ symlink to storage/app/public
```

### File Upload Flow

```
┌──────────────────────────────────────────────────────────────┐
│                    File Upload Process                        │
└──────────────────────────────────────────────────────────────┘

1. User submits form with file
   │
   ▼
2. Laravel Request validates file
   • Type: pdf, doc, docx, ppt, pptx
   • Max size: 10MB (10240 KB)
   │
   ▼
3. LessonController@store
   │
   ├─▶ Check if file exists
   │   • $request->hasFile('file')
   │
   ├─▶ Get file object
   │   • $file = $request->file('file')
   │
   ├─▶ Generate unique filename
   │   • $filename = time() . '_' . $file->getClientOriginalName()
   │   • Example: 1637234567_presentation.pdf
   │
   ├─▶ Store file
   │   • $path = $file->storeAs('lessons/pdf', $filename, 'public')
   │   • Stored at: storage/app/public/lessons/pdf/{filename}
   │
   ├─▶ Save path to database
   │   • $lesson->content_url = $path
   │   • Value: "lessons/pdf/1637234567_presentation.pdf"
   │
   └─▶ Return success
       • redirect()->back()->with('success', 'Lesson created')
   │
   ▼
4. Student views lesson
   │
   ├─▶ Blade template renders
   │   • {{ Storage::url($lesson->content_url) }}
   │   • Converts to: /storage/lessons/pdf/{filename}
   │
   └─▶ Browser loads file
       • Public URL: http://localhost/storage/lessons/pdf/{filename}
       • Actual file: storage/app/public/lessons/pdf/{filename}
```

### Symbolic Link

```bash
# สร้าง symbolic link ครั้งเดียวหลัง clone project
php artisan storage:link

# สิ่งที่เกิดขึ้น:
# สร้าง symlink จาก public/storage ไปยัง storage/app/public
# ทำให้ไฟล์ใน storage/app/public เข้าถึงได้ผ่าน web

# ตรวจสอบ
ls -la public/storage
# lrwxrwxrwx 1 user user 28 Nov 23 10:00 public/storage -> ../storage/app/public
```

---

## Performance Considerations

### N+1 Query Prevention

```php
// ❌ Bad: N+1 queries
$courses = Course::all();
foreach ($courses as $course) {
    echo $course->modules->count(); // Query for each course
}

// ✅ Good: Eager loading
$courses = Course::with('modules')->get();
foreach ($courses as $course) {
    echo $course->modules->count(); // No additional queries
}

// ✅ Better: Eager load with counts
$courses = Course::withCount('modules')->get();
foreach ($courses as $course) {
    echo $course->modules_count; // Calculated in single query
}
```

### Query Optimization Examples

```php
// Student Dashboard
$courses = auth()->user()->enrollments()
    ->with([
        'course.modules.lessons.completions' => function($query) {
            $query->where('user_id', auth()->id());
        }
    ])
    ->get();

// Teacher Course Management
$course = Course::with(['modules' => function($query) {
        $query->orderBy('order')->with(['lessons' => function($q) {
            $q->orderBy('order');
        }]);
    }])
    ->findOrFail($id);
```

### Caching Strategy

```php
// Cache course progress for 5 minutes
$progress = Cache::remember(
    "course.{$courseId}.user.{$userId}.progress",
    300,
    function () use ($course, $userId) {
        return $course->getProgressPercentage($userId);
    }
);

// Clear cache on completion
Cache::forget("course.{$courseId}.user.{$userId}.progress");
```

---

## Deployment Checklist

### Production Setup

```bash
# 1. Environment
cp .env.example .env
# แก้ไข .env:
# APP_ENV=production
# APP_DEBUG=false
# APP_URL=https://yourdomain.com

# 2. Dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 3. Database
php artisan migrate --force

# 4. Storage
php artisan storage:link
chmod -R 775 storage bootstrap/cache

# 5. Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Security
php artisan key:generate
# Set proper file permissions
# Configure firewall
# Setup SSL certificate
```

---

## Monitoring & Logging

### Log Files

```
storage/logs/
└── laravel.log          # All application logs

# Log levels:
# - emergency: System is unusable
# - alert: Action must be taken immediately
# - critical: Critical conditions
# - error: Error conditions
# - warning: Warning conditions
# - notice: Normal but significant
# - info: Informational messages
# - debug: Debug-level messages
```

### Custom Logging

```php
// ใน Controller
use Illuminate\Support\Facades\Log;

// Log lesson completion
Log::info('Lesson completed', [
    'user_id' => auth()->id(),
    'lesson_id' => $lesson->id,
    'course_id' => $course->id,
    'timestamp' => now()
]);

// Log errors
try {
    // risky operation
} catch (\Exception $e) {
    Log::error('File upload failed', [
        'error' => $e->getMessage(),
        'file' => $request->file('file')->getClientOriginalName()
    ]);
}
```

---

## Summary

ระบบ CT Learning มี Architecture ที่:

✅ **ชัดเจน**: MVC pattern ที่เข้าใจง่าย  
✅ **ปลอดภัย**: Multi-layer authorization  
✅ **ยืดหยุ่น**: Support หลายรูปแบบ content  
✅ **Scalable**: Database design รองรับการขยาย  
✅ **Maintainable**: Code structure เป็นระเบียบ  

สำหรับข้อมูลเพิ่มเติม ดูได้ที่:
- [README.md](../../README.md) - คู่มือการใช้งาน
- [MODULE-LESSON-TROUBLESHOOTING.md](MODULE-LESSON-TROUBLESHOOTING.md) - การแก้ปัญหา
