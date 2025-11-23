# 🛣️ CT Learning - Complete Routes Reference

## สารบัญ
1. [Overview](#overview)
2. [Authentication Routes](#authentication-routes)
3. [Teacher Routes](#teacher-routes)
4. [Student Routes](#student-routes)
5. [AJAX Routes](#ajax-routes)
6. [Route Parameters](#route-parameters)
7. [Usage Examples](#usage-examples)

---

## Overview

ระบบใช้ **Nested Resource Routes** สำหรับ Teacher และ **Named Routes** สำหรับ Student

### Route Structure

```
/teacher/courses/{course}/modules/{module}/lessons/{lesson}
    ↑         ↑          ↑          ↑          ↑
    │         │          │          │          └─ Lesson ID
    │         │          │          └─ Module ID
    │         │          └─ Course ID
    │         └─ Resource type
    └─ Role prefix
```

### Middleware Applied

| Route Group | Middleware | Description |
|------------|-----------|-------------|
| `/teacher/*` | `auth`, `teacher` | ต้อง login และเป็น teacher |
| `/student/*` | `auth`, `student` | ต้อง login และเป็น student |
| `/lessons/*/complete` | `auth` | ต้อง login (any role) |

---

## Authentication Routes

จัดการโดย Laravel Breeze ใน `routes/auth.php`

### Registration

| Method | URL | Route Name | Description |
|--------|-----|------------|-------------|
| GET | `/register` | `register` | แสดงฟอร์มลงทะเบียน |
| POST | `/register` | - | บันทึกข้อมูลผู้ใช้ใหม่ |

**Example:**
```php
// View
<a href="{{ route('register') }}">Register</a>

// Redirect after registration
return redirect()->route('dashboard');
```

### Login

| Method | URL | Route Name | Description |
|--------|-----|------------|-------------|
| GET | `/login` | `login` | แสดงฟอร์ม login |
| POST | `/login` | - | ตรวจสอบ credentials |

**Example:**
```php
// View
<a href="{{ route('login') }}">Login</a>

// Redirect after login
return redirect()->intended('dashboard');
```

### Logout

| Method | URL | Route Name | Description |
|--------|-----|------------|-------------|
| POST | `/logout` | `logout` | ออกจากระบบ |

**Example:**
```blade
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
```

### Password Reset

| Method | URL | Route Name | Description |
|--------|-----|------------|-------------|
| GET | `/forgot-password` | `password.request` | ฟอร์มขอรีเซ็ต |
| POST | `/forgot-password` | `password.email` | ส่งอีเมลรีเซ็ต |
| GET | `/reset-password/{token}` | `password.reset` | ฟอร์มรีเซ็ตรหัส |
| POST | `/reset-password` | `password.update` | บันทึกรหัสใหม่ |

---

## Teacher Routes

Defined in `routes/web.php` with prefix `teacher` and middleware `['auth', 'teacher']`

### Course Routes (Resource)

```php
Route::resource('courses', CourseController::class);
```

| Method | URL | Route Name | Controller Method | Description |
|--------|-----|------------|------------------|-------------|
| GET | `/teacher/courses` | `teacher.courses.index` | `index()` | รายการคอร์สทั้งหมด |
| GET | `/teacher/courses/create` | `teacher.courses.create` | `create()` | ฟอร์มสร้างคอร์ส |
| POST | `/teacher/courses` | `teacher.courses.store` | `store()` | บันทึกคอร์สใหม่ |
| GET | `/teacher/courses/{course}` | `teacher.courses.show` | `show()` | รายละเอียดคอร์ส |
| GET | `/teacher/courses/{course}/edit` | `teacher.courses.edit` | `edit()` | ฟอร์มแก้ไขคอร์ส |
| PUT/PATCH | `/teacher/courses/{course}` | `teacher.courses.update` | `update()` | อัพเดทคอร์ส |
| DELETE | `/teacher/courses/{course}` | `teacher.courses.destroy` | `destroy()` | ลบคอร์ส |

**Controller:** `App\Http\Controllers\Teacher\CourseController`

**Example Usage:**
```php
// รายการคอร์ส
<a href="{{ route('teacher.courses.index') }}">My Courses</a>

// สร้างคอร์สใหม่
<a href="{{ route('teacher.courses.create') }}">Create Course</a>

// ดูรายละเอียด
<a href="{{ route('teacher.courses.show', $course) }}">View Course</a>

// แก้ไข
<a href="{{ route('teacher.courses.edit', $course) }}">Edit Course</a>

// ลบ
<form action="{{ route('teacher.courses.destroy', $course) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>
```

---

### Module Routes (Nested Resource)

```php
Route::prefix('courses/{course}/modules')
    ->name('courses.modules.')
    ->group(function () {
        Route::get('/', [ModuleController::class, 'index'])->name('index');
        Route::get('/create', [ModuleController::class, 'create'])->name('create');
        Route::post('/', [ModuleController::class, 'store'])->name('store');
        Route::get('/{module}', [ModuleController::class, 'show'])->name('show');
        Route::get('/{module}/edit', [ModuleController::class, 'edit'])->name('edit');
        Route::put('/{module}', [ModuleController::class, 'update'])->name('update');
        Route::delete('/{module}', [ModuleController::class, 'destroy'])->name('destroy');
    });
```

| Method | URL | Route Name | Parameters | Description |
|--------|-----|------------|-----------|-------------|
| GET | `/teacher/courses/{course}/modules` | `teacher.courses.modules.index` | `$course` | รายการโมดูลในคอร์ส |
| GET | `/teacher/courses/{course}/modules/create` | `teacher.courses.modules.create` | `$course` | ฟอร์มสร้างโมดูล |
| POST | `/teacher/courses/{course}/modules` | `teacher.courses.modules.store` | `$course` | บันทึกโมดูลใหม่ |
| GET | `/teacher/courses/{course}/modules/{module}` | `teacher.courses.modules.show` | `$course, $module` | รายละเอียดโมดูล |
| GET | `/teacher/courses/{course}/modules/{module}/edit` | `teacher.courses.modules.edit` | `$course, $module` | ฟอร์มแก้ไขโมดูล |
| PUT | `/teacher/courses/{course}/modules/{module}` | `teacher.courses.modules.update` | `$course, $module` | อัพเดทโมดูล |
| DELETE | `/teacher/courses/{course}/modules/{module}` | `teacher.courses.modules.destroy` | `$course, $module` | ลบโมดูล |

**Controller:** `App\Http\Controllers\Teacher\ModuleController`

**Example Usage:**
```php
// รายการโมดูล
<a href="{{ route('teacher.courses.modules.index', $course) }}">
    📚 Modules
</a>

// สร้างโมดูลใหม่
<a href="{{ route('teacher.courses.modules.create', $course) }}">
    + Add Module
</a>

// ดูรายละเอียด
<a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}">
    View Module
</a>

// แก้ไข
<a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}">
    Edit
</a>

// ฟอร์มบันทึก
<form action="{{ route('teacher.courses.modules.store', $course) }}" method="POST">
    @csrf
    <input type="text" name="title" required>
    <textarea name="description"></textarea>
    <input type="number" name="order" required>
    <button type="submit">Create Module</button>
</form>

// ฟอร์มอัพเดท
<form action="{{ route('teacher.courses.modules.update', [$course, $module]) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="title" value="{{ $module->title }}" required>
    <button type="submit">Update</button>
</form>

// ลบ
<form action="{{ route('teacher.courses.modules.destroy', [$course, $module]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>
```

---

### Lesson Routes (Double Nested Resource)

```php
Route::prefix('/{module}/lessons')
    ->name('lessons.')
    ->group(function () {
        Route::get('/', [LessonController::class, 'index'])->name('index');
        Route::get('/create', [LessonController::class, 'create'])->name('create');
        Route::post('/', [LessonController::class, 'store'])->name('store');
        Route::get('/{lesson}', [LessonController::class, 'show'])->name('show');
        Route::get('/{lesson}/edit', [LessonController::class, 'edit'])->name('edit');
        Route::put('/{lesson}', [LessonController::class, 'update'])->name('update');
        Route::delete('/{lesson}', [LessonController::class, 'destroy'])->name('destroy');
    });
```

| Method | URL | Route Name | Parameters | Description |
|--------|-----|------------|-----------|-------------|
| GET | `/teacher/courses/{course}/modules/{module}/lessons` | `teacher.courses.modules.lessons.index` | `$course, $module` | รายการบทเรียน |
| GET | `.../lessons/create` | `teacher.courses.modules.lessons.create` | `$course, $module` | ฟอร์มสร้างบทเรียน |
| POST | `.../lessons` | `teacher.courses.modules.lessons.store` | `$course, $module` | บันทึกบทเรียนใหม่ |
| GET | `.../lessons/{lesson}` | `teacher.courses.modules.lessons.show` | `$course, $module, $lesson` | รายละเอียดบทเรียน |
| GET | `.../lessons/{lesson}/edit` | `teacher.courses.modules.lessons.edit` | `$course, $module, $lesson` | ฟอร์มแก้ไข |
| PUT | `.../lessons/{lesson}` | `teacher.courses.modules.lessons.update` | `$course, $module, $lesson` | อัพเดทบทเรียน |
| DELETE | `.../lessons/{lesson}` | `teacher.courses.modules.lessons.destroy` | `$course, $module, $lesson` | ลบบทเรียน |

**Controller:** `App\Http\Controllers\Teacher\LessonController`

**Example Usage:**
```php
// รายการบทเรียน
<a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}">
    📝 Lessons
</a>

// สร้างบทเรียนใหม่
<a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}">
    + Add Lesson
</a>

// ดูรายละเอียด
<a href="{{ route('teacher.courses.modules.lessons.show', [$course, $module, $lesson]) }}">
    View Lesson
</a>

// แก้ไข
<a href="{{ route('teacher.courses.modules.lessons.edit', [$course, $module, $lesson]) }}">
    Edit
</a>

// ฟอร์มบันทึก (PDF)
<form action="{{ route('teacher.courses.modules.lessons.store', [$course, $module]) }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    <input type="text" name="title" required>
    <select name="content_type" required>
        <option value="PDF">PDF Document</option>
        <option value="VIDEO">YouTube Video</option>
        <option value="TEXT">Article</option>
    </select>
    <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx">
    <input type="number" name="order" required>
    <button type="submit">Create Lesson</button>
</form>

// ฟอร์มอัพเดท
<form action="{{ route('teacher.courses.modules.lessons.update', [$course, $module, $lesson]) }}" 
      method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="title" value="{{ $lesson->title }}" required>
    <button type="submit">Update</button>
</form>

// ลบ
<form action="{{ route('teacher.courses.modules.lessons.destroy', [$course, $module, $lesson]) }}" 
      method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>
```

---

## Student Routes

Defined in `routes/web.php` with prefix `student` and middleware `['auth', 'student']`

### Course Dashboard

| Method | URL | Route Name | Controller Method | Description |
|--------|-----|------------|------------------|-------------|
| GET | `/student/courses` | `student.courses.index` | `index()` | Dashboard (คอร์สที่เรียน) |

**Controller:** `App\Http\Controllers\Student\CourseController`

**Example:**
```php
// View
<a href="{{ route('student.courses.index') }}">My Courses</a>

// Controller
public function index()
{
    $enrollments = auth()->user()->enrollments()
        ->with('course.modules.lessons')
        ->get();
    
    $courses = $enrollments->map(function ($enrollment) {
        $course = $enrollment->course;
        $course->progress = $course->getProgressPercentage(auth()->id());
        return $course;
    });
    
    return view('student.courses.index', compact('courses'));
}
```

---

### Learning Routes

| Method | URL | Route Name | Parameters | Description |
|--------|-----|------------|-----------|-------------|
| GET | `/student/courses/{course}/learn` | `student.courses.learn` | `$course` | รายการโมดูล |
| GET | `/student/courses/{course}/modules/{module}` | `student.modules.show` | `$course, $module` | รายการบทเรียน |
| GET | `/student/courses/{course}/modules/{module}/lessons/{lesson}` | `student.lessons.show` | `$course, $module, $lesson` | เนื้อหาบทเรียน |

**Controller:** `App\Http\Controllers\Student\LearningController`

**Example Usage:**
```php
// รายการโมดูล
<a href="{{ route('student.courses.learn', $course) }}">
    Continue Learning
</a>

// รายการบทเรียนในโมดูล
<a href="{{ route('student.modules.show', [$course, $module]) }}">
    View Lessons
</a>

// เนื้อหาบทเรียน
<a href="{{ route('student.lessons.show', [$course, $module, $lesson]) }}">
    Start Learning
</a>
```

**Controller Methods:**
```php
// LearningController@showCourse
public function showCourse(Course $course)
{
    // Check enrollment
    $enrollment = auth()->user()->enrollments()
        ->where('course_id', $course->id)
        ->first();
        
    if (!$enrollment) {
        abort(403, 'You are not enrolled in this course.');
    }
    
    $modules = $course->modules()->with('lessons')->orderBy('order')->get();
    
    return view('student.courses.show', compact('course', 'modules'));
}

// LearningController@showModule
public function showModule(Course $course, Module $module)
{
    // Check enrollment
    $enrollment = auth()->user()->enrollments()
        ->where('course_id', $course->id)
        ->first();
        
    if (!$enrollment) {
        abort(403);
    }
    
    if ($module->course_id !== $course->id) {
        abort(404);
    }
    
    $lessons = $module->lessons()->orderBy('order')->get();
    
    return view('student.modules.show', compact('course', 'module', 'lessons'));
}

// LearningController@showLesson
public function showLesson(Course $course, Module $module, Lesson $lesson)
{
    // Validations...
    
    $isCompleted = $lesson->isCompletedBy(auth()->id());
    
    return view('student.lessons.show', compact('course', 'module', 'lesson', 'isCompleted'));
}
```

---

## AJAX Routes

### Lesson Completion

| Method | URL | Route Name | Controller Method | Description |
|--------|-----|------------|------------------|-------------|
| POST | `/lessons/{lesson}/complete` | `lessons.ajax-complete` | `complete()` | บันทึกการเรียนจบ (AJAX) |

**Controller:** `App\Http\Controllers\Student\LearningController`

**JavaScript Example:**
```javascript
// ใน student/lessons/show.blade.php
const completeBtn = document.getElementById('complete-lesson-btn');

completeBtn.addEventListener('click', async function() {
    try {
        const response = await fetch('/lessons/{{ $lesson->id }}/complete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // อัพเดท UI
            completeBtn.textContent = '✅ Completed';
            completeBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
            completeBtn.classList.add('bg-green-500', 'cursor-not-allowed');
            completeBtn.disabled = true;
            
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to mark lesson as complete');
    }
});
```

**Controller Method:**
```php
public function complete(Lesson $lesson)
{
    // Check if already completed
    $exists = LessonCompletion::where('lesson_id', $lesson->id)
        ->where('user_id', auth()->id())
        ->exists();
    
    if ($exists) {
        return response()->json([
            'message' => 'You have already completed this lesson'
        ]);
    }
    
    // Create completion record
    LessonCompletion::create([
        'lesson_id' => $lesson->id,
        'user_id' => auth()->id()
    ]);
    
    return response()->json([
        'message' => 'Lesson marked as complete',
        'completed' => true
    ]);
}
```

---

## Route Parameters

### URL Pattern vs Route Parameters

```php
// URL Pattern
/teacher/courses/{course}/modules/{module}/lessons/{lesson}

// Route Parameters (in Controller)
public function edit(Course $course, Module $module, Lesson $lesson)
{
    // Laravel automatically binds models by ID
    // $course = Course::findOrFail($courseId);
    // $module = Module::findOrFail($moduleId);
    // $lesson = Lesson::findOrFail($lessonId);
}
```

### Route Model Binding

Laravel ใช้ **Implicit Route Model Binding** โดยอัตโนมัติ:

```php
// routes/web.php
Route::get('/courses/{course}', [CourseController::class, 'show']);

// CourseController
public function show(Course $course)
{
    // $course ถูก bind จาก Course::findOrFail($id) โดยอัตโนมัติ
    return view('courses.show', compact('course'));
}
```

### Custom Key for Binding

```php
// ใช้ slug แทน id
Route::get('/courses/{course:slug}', [CourseController::class, 'show']);

// CourseController
public function show(Course $course)
{
    // $course = Course::where('slug', $slug)->firstOrFail();
}
```

---

## Usage Examples

### 1. Teacher Creates Module

```php
// View: teacher/courses/index.blade.php
@foreach($courses as $course)
    <a href="{{ route('teacher.courses.modules.index', $course) }}" 
       class="btn btn-primary">
        📚 Modules
    </a>
@endforeach

// View: teacher/modules/index.blade.php
<a href="{{ route('teacher.courses.modules.create', $course) }}" 
   class="btn btn-success">
    + Add Module
</a>

// View: teacher/modules/create.blade.php
<form action="{{ route('teacher.courses.modules.store', $course) }}" 
      method="POST">
    @csrf
    <input type="text" name="title" required>
    <textarea name="description"></textarea>
    <input type="number" name="order" value="1" required>
    <button type="submit">Create Module</button>
</form>

// Controller: ModuleController@store
public function store(Request $request, Course $course)
{
    // Validate
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'order' => 'required|integer|min:1'
    ]);
    
    // Check authorization
    if (auth()->id() !== $course->teacher_id) {
        abort(403);
    }
    
    // Create module
    $module = $course->modules()->create($validated);
    
    // Redirect
    return redirect()
        ->route('teacher.courses.modules.index', $course)
        ->with('success', 'Module created successfully');
}
```

### 2. Teacher Creates Lesson with PDF

```php
// View: teacher/lessons/create.blade.php
<form action="{{ route('teacher.courses.modules.lessons.store', [$course, $module]) }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    
    <input type="text" name="title" placeholder="Lesson Title" required>
    
    <select name="content_type" id="content_type" required>
        <option value="">Select Content Type</option>
        <option value="PDF">PDF Document</option>
        <option value="VIDEO">YouTube Video</option>
        <option value="TEXT">Article</option>
    </select>
    
    <!-- PDF Upload Field -->
    <div id="pdf-field" style="display: none;">
        <input type="file" 
               name="file" 
               accept=".pdf,.doc,.docx,.ppt,.pptx">
        <small>Max 10MB</small>
    </div>
    
    <!-- Video URL Field -->
    <div id="video-field" style="display: none;">
        <input type="url" 
               name="content_url" 
               placeholder="https://youtube.com/watch?v=...">
    </div>
    
    <!-- Article Text Field -->
    <div id="text-field" style="display: none;">
        <textarea name="content_text" 
                  rows="10" 
                  placeholder="Write article content..."></textarea>
    </div>
    
    <input type="number" name="order" value="1" required>
    
    <button type="submit">Create Lesson</button>
</form>

<script>
document.getElementById('content_type').addEventListener('change', function() {
    // Hide all fields
    document.getElementById('pdf-field').style.display = 'none';
    document.getElementById('video-field').style.display = 'none';
    document.getElementById('text-field').style.display = 'none';
    
    // Show relevant field
    const type = this.value;
    if (type === 'PDF') {
        document.getElementById('pdf-field').style.display = 'block';
    } else if (type === 'VIDEO') {
        document.getElementById('video-field').style.display = 'block';
    } else if (type === 'TEXT') {
        document.getElementById('text-field').style.display = 'block';
    }
});
</script>

// Controller: LessonController@store
public function store(Request $request, Course $course, Module $module)
{
    // Validate
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content_type' => 'required|in:PDF,VIDEO,TEXT',
        'content_url' => 'nullable|required_if:content_type,VIDEO|string|max:500',
        'content_text' => 'nullable|required_if:content_type,TEXT|string',
        'file' => 'nullable|required_if:content_type,PDF|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        'order' => 'required|integer|min:1'
    ]);
    
    // Check authorization
    if (auth()->id() !== $course->teacher_id || $module->course_id !== $course->id) {
        abort(403);
    }
    
    // Handle file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('lessons/pdf', $filename, 'public');
        $validated['content_url'] = $path;
    }
    
    // Create lesson
    $lesson = $module->lessons()->create($validated);
    
    // Redirect
    return redirect()
        ->route('teacher.courses.modules.lessons.index', [$course, $module])
        ->with('success', 'Lesson created successfully');
}
```

### 3. Student Marks Lesson Complete

```blade
{{-- View: student/lessons/show.blade.php --}}
<x-app-layout>
    <div class="container mx-auto py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-4">
            <a href="{{ route('student.courses.index') }}">My Courses</a> /
            <a href="{{ route('student.courses.learn', $course) }}">{{ $course->title }}</a> /
            <a href="{{ route('student.modules.show', [$course, $module]) }}">{{ $module->title }}</a> /
            <span>{{ $lesson->title }}</span>
        </nav>
        
        {{-- Lesson Content --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-4">{{ $lesson->title }}</h1>
            
            @if($lesson->content_type === 'PDF')
                <embed src="{{ Storage::url($lesson->content_url) }}" 
                       type="application/pdf" 
                       width="100%" 
                       height="600px">
                       
            @elseif($lesson->content_type === 'VIDEO')
                @php
                    $videoId = null;
                    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $lesson->content_url, $matches)) {
                        $videoId = $matches[1];
                    } elseif (preg_match('/youtu\.be\/([^?]+)/', $lesson->content_url, $matches)) {
                        $videoId = $matches[1];
                    }
                @endphp
                
                @if($videoId)
                    <iframe width="100%" 
                            height="500" 
                            src="https://www.youtube.com/embed/{{ $videoId }}" 
                            frameborder="0" 
                            allowfullscreen>
                    </iframe>
                @endif
                
            @elseif($lesson->content_type === 'TEXT')
                <div class="prose max-w-none">
                    {!! nl2br(e($lesson->content_text)) !!}
                </div>
            @endif
            
            {{-- Mark Complete Button --}}
            <div class="mt-6">
                @if($isCompleted)
                    <button disabled 
                            class="px-6 py-3 bg-green-500 text-white rounded cursor-not-allowed">
                        ✅ Completed
                    </button>
                @else
                    <button id="complete-lesson-btn" 
                            class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded">
                        Mark as Complete
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    <script>
    @if(!$isCompleted)
    const completeBtn = document.getElementById('complete-lesson-btn');
    
    completeBtn.addEventListener('click', async function() {
        try {
            const response = await fetch('/lessons/{{ $lesson->id }}/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                completeBtn.textContent = '✅ Completed';
                completeBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                completeBtn.classList.add('bg-green-500', 'cursor-not-allowed');
                completeBtn.disabled = true;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
    @endif
    </script>
</x-app-layout>
```

---

## Testing Routes

### Using Artisan Commands

```bash
# ดูรายการ routes ทั้งหมด
php artisan route:list

# กรอง routes ของ teacher
php artisan route:list --name=teacher

# กรอง routes ของ student
php artisan route:list --name=student

# ดูรายละเอียด route เฉพาะ
php artisan route:list --path=courses

# แสดงเฉพาะ POST methods
php artisan route:list --method=POST
```

### Testing with Browser

```
# Teacher Routes
http://localhost:8000/teacher/courses
http://localhost:8000/teacher/courses/1/modules
http://localhost:8000/teacher/courses/1/modules/1/lessons

# Student Routes
http://localhost:8000/student/courses
http://localhost:8000/student/courses/1/learn
http://localhost:8000/student/courses/1/modules/1
http://localhost:8000/student/courses/1/modules/1/lessons/1
```

---

## Common Issues & Solutions

### Issue 1: Route not found

```
Error: Route [teacher.courses.modules.index] not defined
```

**Solution:**
```php
// ✅ Correct
route('teacher.courses.modules.index', $course)

// ❌ Wrong
route('teacher.modules.index', $course)  // Missing 'courses.' prefix
```

### Issue 2: Missing required parameter

```
Error: Missing required parameter for [Route: teacher.courses.modules.lessons.create]
```

**Solution:**
```php
// ✅ Correct - ใส่ parameters ครบ
route('teacher.courses.modules.lessons.create', [$course, $module])

// ❌ Wrong - ขาด $module
route('teacher.courses.modules.lessons.create', $course)
```

### Issue 3: Too few/many arguments

```
Error: Too few arguments to function, 1 passed and exactly 2 expected
```

**Solution:**
```php
// ✅ Correct - ใช้ array สำหรับหลาย parameters
route('teacher.courses.modules.edit', [$course, $module])

// ❌ Wrong - ส่ง parameters แยก
route('teacher.courses.modules.edit', $course, $module)
```

---

## Summary

### Route Naming Convention

```
{role}.{resource}.{nested}.{action}

Examples:
- teacher.courses.index
- teacher.courses.modules.store
- teacher.courses.modules.lessons.edit
- student.courses.learn
- student.lessons.show
```

### Parameter Passing

```php
// Single parameter
route('name', $model)

// Multiple parameters
route('name', [$model1, $model2, $model3])

// Named parameters
route('name', ['course' => $course, 'module' => $module])
```

---

สำหรับข้อมูลเพิ่มเติม:
- [README.md](../../README.md)
- [ARCHITECTURE.md](ARCHITECTURE.md)
- [MODULE-LESSON-TROUBLESHOOTING.md](MODULE-LESSON-TROUBLESHOOTING.md)
