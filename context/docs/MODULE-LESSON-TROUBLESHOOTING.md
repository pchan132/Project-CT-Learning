# คู่มือแก้ปัญหา Module & Lesson System

## 📋 สารบัญ
1. [ปัญหาที่พบบ่อยและวิธีแก้](#ปัญหาที่พบบ่อยและวิธีแก้)
2. [การตั้งค่าเบื้องต้น](#การตั้งค่าเบื้องต้น)
3. [สถาปัตยกรรมระบบ](#สถาปัตยกรรมระบบ)
4. [การใช้งาน Teacher Module & Lesson](#การใช้งาน-teacher-module--lesson)
5. [Routes ที่ใช้งานได้](#routes-ที่ใช้งานได้)

---

## ปัญหาที่พบบ่อยและวิธีแก้

### ❌ Problem 1: Error 403 "Unauthorized" เมื่อเข้า `/teacher/courses/1/modules`

**สาเหตุ:**
- Controller ใช้ `$this->authorize('manage-course', $course)` แต่ยังไม่ได้สร้าง Gate/Policy

**วิธีแก้:**
```php
// แทนที่ใน ModuleController และ LessonController
// เปลี่ยนจาก:
$this->authorize('manage-course', $course);

// เป็น:
if (auth()->id() !== $course->teacher_id) {
    abort(403);
}
```

**ไฟล์ที่ต้องแก้:**
- `app/Http/Controllers/Teacher/ModuleController.php`
- `app/Http/Controllers/Teacher/LessonController.php`

---

### ❌ Problem 2: Undefined variable `$course` ใน View

**สาเหตุ:**
- View พยายามใช้ `$course` แต่ Controller ไม่ได้ส่งตัวแปรนี้มา

**วิธีแก้ที่ 1: แก้ไข Controller ให้ส่ง `$course`**
```php
// ใน LessonController@index
public function index(Course $course, Module $module)
{
    if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
        abort(403);
    }

    $lessons = $module->lessons()->ordered()->get();

    return view('teacher.lessons.index', compact('course', 'module', 'lessons'));
}
```

**วิธีแก้ที่ 2: ใช้ relationship ใน View**
```blade
{{-- แทนที่ --}}
{{ $course->title }}

{{-- เป็น --}}
{{ $module->course->title }}
```

---

### ❌ Problem 3: Undefined variable `$slot` ใน Layout

**สาเหตุ:**
- Views ใช้ `@extends('layouts.app')` (Traditional Blade)
- แต่ Layout ใช้ `{{ $slot }}` (Blade Component)

**วิธีแก้: เปลี่ยน Views ทั้งหมดเป็น Blade Component**

**ก่อนแก้:**
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <!-- content -->
</div>
@endsection
```

**หลังแก้:**
```blade
<x-app-layout>
<div class="container">
    <!-- content -->
</div>
</x-app-layout>
```

**ไฟล์ที่ต้องแก้ทั้งหมด:**
- `resources/views/teacher/modules/create.blade.php`
- `resources/views/teacher/modules/edit.blade.php`
- `resources/views/teacher/modules/index.blade.php`
- `resources/views/teacher/modules/show.blade.php`
- `resources/views/teacher/lessons/create.blade.php`
- `resources/views/teacher/lessons/edit.blade.php`
- `resources/views/teacher/lessons/index.blade.php`
- `resources/views/teacher/lessons/show.blade.php`
- `resources/views/teacher/courses/show.blade.php`

---

### ❌ Problem 4: Route not found หรือ Missing required parameter

**สาเหตุ:**
- Route ต้องการ parameters ที่ครบถ้วน

**วิธีแก้: ตรวจสอบ Route Parameters**

**ถูกต้อง:**
```blade
{{-- สำหรับ Modules Index --}}
<a href="{{ route('teacher.courses.modules.index', $course) }}">

{{-- สำหรับ Modules Create --}}
<a href="{{ route('teacher.courses.modules.create', $course) }}">

{{-- สำหรับ Lessons Index --}}
<a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}">

{{-- สำหรับ Lessons Create --}}
<a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}">

{{-- สำหรับ Lessons Edit --}}
<a href="{{ route('teacher.courses.modules.lessons.edit', [$course, $module, $lesson]) }}">
```

**ผิด:**
```blade
{{-- ❌ ขาด parameter --}}
<a href="{{ route('teacher.courses.modules.index') }}">

{{-- ❌ ใช้ id แทน model --}}
<a href="{{ route('teacher.courses.modules.index', $course->id) }}">
```

---

## การตั้งค่าเบื้องต้น

### 1. ติดตั้ง Dependencies
```bash
composer install
npm install
```

### 2. สร้าง Storage Link
```bash
php artisan storage:link
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Clear Cache (เมื่อแก้ไข Views)
```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### 5. Compile Assets
```bash
npm run dev
# หรือ
npm run build
```

---

## สถาปัตยกรรมระบบ

### Database Schema

```
courses
├── id
├── teacher_id (FK → users.id)
├── title
├── description
├── cover_image_url
└── timestamps

modules
├── id
├── course_id (FK → courses.id)
├── title
├── description
├── order
└── timestamps

lessons
├── id
├── module_id (FK → modules.id)
├── title
├── content_type (enum: 'PDF', 'VIDEO', 'TEXT')
├── content_url (for PDF files or YouTube URLs)
├── content_text (for article content)
├── order
└── timestamps

lesson_completions
├── id
├── lesson_id (FK → lessons.id)
├── user_id (FK → users.id)
└── timestamps
```

### Relationships

**Course Model:**
```php
public function teacher() {
    return $this->belongsTo(User::class, 'teacher_id');
}

public function modules() {
    return $this->hasMany(Module::class);
}

public function enrollments() {
    return $this->hasMany(Enrollment::class);
}
```

**Module Model:**
```php
public function course() {
    return $this->belongsTo(Course::class);
}

public function lessons() {
    return $this->hasMany(Lesson::class);
}
```

**Lesson Model:**
```php
public function module() {
    return $this->belongsTo(Module::class);
}

public function completions() {
    return $this->hasMany(LessonCompletion::class);
}

public function isCompletedBy($userId) {
    return $this->completions()->where('user_id', $userId)->exists();
}
```

---

## การใช้งาน Teacher Module & Lesson

### Flow การทำงาน

```
1. Teacher สร้าง Course
   ↓
2. เข้าจัดการ Modules (คลิก "📚 Modules")
   ↓
3. สร้าง Module (+ Add Module)
   - Title
   - Description (optional)
   - Order
   ↓
4. เข้าจัดการ Lessons (คลิก "📝 Lessons")
   ↓
5. สร้าง Lesson (+ Add Lesson)
   - Title
   - Content Type (PDF/Video/Article)
   - Content (file/URL/text)
   - Order
```

### Content Types

#### 1. PDF Content
- **Field:** `content_url`
- **Upload:** ไฟล์ PDF, DOC, DOCX, PPT, PPTX
- **Storage:** `storage/app/public/lessons/pdf/`
- **Max Size:** 10MB

#### 2. Video Content
- **Field:** `content_url`
- **Format:** YouTube URL
- **Examples:**
  - `https://www.youtube.com/watch?v=VIDEO_ID`
  - `https://youtu.be/VIDEO_ID`

#### 3. Article Content
- **Field:** `content_text`
- **Format:** Plain text
- **Display:** แสดงด้วย `nl2br(e($content))`

---

## Routes ที่ใช้งานได้

### Teacher Routes

#### Courses Routes
```php
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('courses', CourseController::class);
});
```

| Method | URL | Route Name | Controller Method |
|--------|-----|------------|------------------|
| GET | `/teacher/courses` | `teacher.courses.index` | `index()` |
| GET | `/teacher/courses/create` | `teacher.courses.create` | `create()` |
| POST | `/teacher/courses` | `teacher.courses.store` | `store()` |
| GET | `/teacher/courses/{course}` | `teacher.courses.show` | `show()` |
| GET | `/teacher/courses/{course}/edit` | `teacher.courses.edit` | `edit()` |
| PUT | `/teacher/courses/{course}` | `teacher.courses.update` | `update()` |
| DELETE | `/teacher/courses/{course}` | `teacher.courses.destroy` | `destroy()` |

#### Modules Routes
```php
Route::prefix('courses/{course}/modules')->name('courses.modules.')->group(function () {
    Route::get('/', [ModuleController::class, 'index'])->name('index');
    Route::get('/create', [ModuleController::class, 'create'])->name('create');
    Route::post('/', [ModuleController::class, 'store'])->name('store');
    Route::get('/{module}', [ModuleController::class, 'show'])->name('show');
    Route::get('/{module}/edit', [ModuleController::class, 'edit'])->name('edit');
    Route::put('/{module}', [ModuleController::class, 'update'])->name('update');
    Route::delete('/{module}', [ModuleController::class, 'destroy'])->name('destroy');
});
```

| Method | URL | Route Name | Parameters |
|--------|-----|------------|-----------|
| GET | `/teacher/courses/{course}/modules` | `teacher.courses.modules.index` | `$course` |
| GET | `/teacher/courses/{course}/modules/create` | `teacher.courses.modules.create` | `$course` |
| POST | `/teacher/courses/{course}/modules` | `teacher.courses.modules.store` | `$course` |
| GET | `/teacher/courses/{course}/modules/{module}` | `teacher.courses.modules.show` | `$course, $module` |
| GET | `/teacher/courses/{course}/modules/{module}/edit` | `teacher.courses.modules.edit` | `$course, $module` |
| PUT | `/teacher/courses/{course}/modules/{module}` | `teacher.courses.modules.update` | `$course, $module` |
| DELETE | `/teacher/courses/{course}/modules/{module}` | `teacher.courses.modules.destroy` | `$course, $module` |

#### Lessons Routes
```php
Route::prefix('/{module}/lessons')->name('lessons.')->group(function () {
    Route::get('/', [LessonController::class, 'index'])->name('index');
    Route::get('/create', [LessonController::class, 'create'])->name('create');
    Route::post('/', [LessonController::class, 'store'])->name('store');
    Route::get('/{lesson}', [LessonController::class, 'show'])->name('show');
    Route::get('/{lesson}/edit', [LessonController::class, 'edit'])->name('edit');
    Route::put('/{lesson}', [LessonController::class, 'update'])->name('update');
    Route::delete('/{lesson}', [LessonController::class, 'destroy'])->name('destroy');
});
```

| Method | URL | Route Name | Parameters |
|--------|-----|------------|-----------|
| GET | `/teacher/courses/{course}/modules/{module}/lessons` | `teacher.courses.modules.lessons.index` | `$course, $module` |
| GET | `/teacher/courses/{course}/modules/{module}/lessons/create` | `teacher.courses.modules.lessons.create` | `$course, $module` |
| POST | `/teacher/courses/{course}/modules/{module}/lessons` | `teacher.courses.modules.lessons.store` | `$course, $module` |
| GET | `/teacher/courses/{course}/modules/{module}/lessons/{lesson}` | `teacher.courses.modules.lessons.show` | `$course, $module, $lesson` |
| GET | `/teacher/courses/{course}/modules/{module}/lessons/{lesson}/edit` | `teacher.courses.modules.lessons.edit` | `$course, $module, $lesson` |
| PUT | `/teacher/courses/{course}/modules/{module}/lessons/{lesson}` | `teacher.courses.modules.lessons.update` | `$course, $module, $lesson` |
| DELETE | `/teacher/courses/{course}/modules/{module}/lessons/{lesson}` | `teacher.courses.modules.lessons.destroy` | `$course, $module, $lesson` |

### Student Routes

#### Learning Routes
```php
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses/{course}/learn', [LearningController::class, 'showCourse'])
        ->name('courses.learn');
    
    Route::get('/courses/{course}/modules/{module}', [LearningController::class, 'showModule'])
        ->name('modules.show');
    
    Route::get('/courses/{course}/modules/{module}/lessons/{lesson}', 
        [LearningController::class, 'showLesson'])
        ->name('lessons.show');
    
    Route::post('/courses/{course}/modules/{module}/lessons/{lesson}/complete', 
        [LearningController::class, 'markLessonComplete'])
        ->name('lessons.complete');
});
```

| Method | URL | Route Name | Description |
|--------|-----|------------|-------------|
| GET | `/student/courses/{course}/learn` | `student.courses.learn` | แสดงโมดูลทั้งหมด |
| GET | `/student/courses/{course}/modules/{module}` | `student.modules.show` | แสดงบทเรียนในโมดูล |
| GET | `/student/courses/{course}/modules/{module}/lessons/{lesson}` | `student.lessons.show` | แสดงเนื้อหาบทเรียน |
| POST | `/student/courses/{course}/modules/{module}/lessons/{lesson}/complete` | `student.lessons.complete` | บันทึกการเรียนจบ |

#### AJAX Route
```php
Route::middleware(['auth'])->group(function () {
    Route::post('/lessons/{lesson}/complete', [LearningController::class, 'complete'])
        ->name('lessons.ajax-complete');
});
```

---

## Authorization & Security

### Teacher Authorization
```php
// ตรวจสอบว่าเป็นเจ้าของคอร์ส
if (auth()->id() !== $course->teacher_id) {
    abort(403);
}

// ตรวจสอบว่า Module เป็นของ Course นี้
if ($module->course_id !== $course->id) {
    abort(404);
}

// ตรวจสอบว่า Lesson เป็นของ Module นี้
if ($lesson->module_id !== $module->id) {
    abort(404);
}
```

### Student Authorization
```php
// ตรวจสอบว่าลงทะเบียนคอร์สนี้หรือไม่
$enrollment = auth()->user()->enrollments()
    ->where('course_id', $course->id)
    ->first();

if (!$enrollment) {
    abort(403, 'You are not enrolled in this course.');
}
```

---

## Validation Rules

### Module Validation
```php
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'description' => 'nullable|string',
    'order' => 'required|integer|min:1',
]);
```

### Lesson Validation
```php
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'content_type' => 'required|in:PDF,VIDEO,TEXT',
    'content_url' => 'nullable|required_if:content_type,VIDEO|string|max:500',
    'content_text' => 'nullable|required_if:content_type,TEXT|string',
    'file' => 'nullable|required_if:content_type,PDF|file|mimes:pdf,ppt,pptx|max:10240',
    'order' => 'required|integer|min:1',
]);
```

---

## Testing Routes

### ทดสอบด้วย Artisan
```bash
# ดูรายการ routes ทั้งหมด
php artisan route:list

# กรองเฉพาะ teacher routes
php artisan route:list --name=teacher

# กรองเฉพาะ modules routes
php artisan route:list --name=modules
```

### ตัวอย่างการใช้งาน
```php
// ใน Controller
return redirect()->route('teacher.courses.modules.index', $course);

// ใน Blade
<a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}">
    Create Lesson
</a>

// Form Action
<form action="{{ route('teacher.courses.modules.store', $course) }}" method="POST">
    @csrf
    <!-- form fields -->
</form>
```

---

## Troubleshooting Commands

### เมื่อมีปัญหา
```bash
# 1. Clear cache ทั้งหมด
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 2. Regenerate autoload
composer dump-autoload

# 3. Recompile assets
npm run dev

# 4. Check routes
php artisan route:list --name=teacher

# 5. Check database
php artisan migrate:status
```

---

## Best Practices

### 1. Order Management
```php
// หา order ถัดไปสำหรับ Module/Lesson ใหม่
$nextOrder = $module->lessons()->max('order') + 1;
```

### 2. File Upload
```php
// จัดการ file upload อย่างปลอดภัย
if ($request->hasFile('file')) {
    $file = $request->file('file');
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('lessons/pdf', $filename, 'public');
    $data['content_url'] = $path;
}
```

### 3. Cascade Delete
```php
// ลบ Module จะลบ Lessons ที่เกี่ยวข้องด้วย
$module->delete(); // ต้อง set cascade ใน migration
```

### 4. Eager Loading
```php
// โหลด relationships เพื่อลด N+1 queries
$modules = $course->modules()->with(['lessons'])->orderBy('order')->get();
```

---

## สรุป

ระบบ Module & Lesson มีโครงสร้างที่ชัดเจน:
- ✅ Course → Modules → Lessons (nested structure)
- ✅ Authorization ที่รัดกุม (teacher ownership, student enrollment)
- ✅ Support 3 content types (PDF, Video, Article)
- ✅ Progress tracking สำหรับนักเรียน
- ✅ RESTful routes ที่สมบูรณ์

**หากพบปัญหา ให้:**
1. Clear cache ก่อนเสมอ
2. ตรวจสอบ parameters ใน routes
3. ตรวจสอบ authorization logic
4. ตรวจสอบว่าใช้ Blade component ถูกต้อง
