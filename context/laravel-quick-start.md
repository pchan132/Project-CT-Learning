# Laravel Quick Start Guide สำหรับ LMS Project

## 🚀 เริ่มต้นอย่างรวดเร็วสำหรับผู้ไม่เคยใช้ Laravel

### สิ่งที่ต้องเตรียมก่อนเริ่ม
1. **PHP 8.1+** ติดตั้งแล้ว
2. **Composer** ติดตั้งแล้ว
3. **MySQL** หรือฐานข้อมูลที่รองรับ
4. **Node.js & NPM** สำหรับ Frontend
5. **VS Code** แนะนำ Extensions: Laravel Blade Snippets, PHP Intelephense

---

## 📋 คำสั่งพื้นฐานที่ต้องรู้

### 1. คำสั่ง Artisan ที่ใช้บ่อย
```bash
# สร้าง Controller
php artisan make:controller StudentController

# สร้าง Model พร้อม Migration
php artisan make:model Course -m

# สร้าง Migration อย่างเดียว
php artisan make:migration create_lessons_table

# รัน Migration
php artisan migrate

# สร้าง Seeder
php artisan make:database\Seeder UserSeeder

# รัน Seeder
php artisan db:seed

# ล้างและรัน Migration ใหม่
php artisan migrate:fresh --seed

# ดู Routes ทั้งหมด
php artisan route:list

# เปิด Development Server
php artisan serve

# เคลียร์ Cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 2. โครงสร้างไฟล์หลักที่ต้องรู้
```
app/Http/Controllers/     # Logic การทำงาน
app/Models/              # ติดต่อฐานข้อมูล
database/migrations/     # สร้างตารางฐานข้อมูล
routes/web.php          # กำหนด URL
resources/views/        # HTML Templates (Blade)
```

---

## 🎯 วิธีการสร้าง Feature ใหม่ (Step by Step)

### ตัวอย่าง: สร้างระบบจัดการวิชา (Course Management)

#### Step 1: สร้าง Migration
```bash
php artisan make:migration create_courses_table
```

#### Step 2: เขียน Migration (database/migrations/xxx_create_courses_table.php)
```php
Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->foreignId('teacher_id')->constrained('users');
    $table->boolean('is_published')->default(false);
    $table->timestamps();
});
```

#### Step 3: สร้าง Model
```bash
php artisan make:model Course
```

#### Step 4: เขียน Model (app/Models/Course.php)
```php
class Course extends Model
{
    protected $fillable = ['title', 'description', 'teacher_id', 'is_published'];
    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
```

#### Step 5: สร้าง Controller
```bash
php artisan make:controller CourseController --resource
```

#### Step 6: เขียน Controller (app/Http/Controllers/CourseController.php)
```php
class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('teacher')->get();
        return view('courses.index', compact('courses'));
    }
    
    public function create()
    {
        return view('courses.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        
        Course::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'teacher_id' => auth()->id(),
        ]);
        
        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully!');
    }
}
```

#### Step 7: กำหนด Routes (routes/web.php)
```php
Route::resource('courses', CourseController::class);
```

#### Step 8: สร้าง Views
```bash
# resources/views/courses/index.blade.php
# resources/views/courses/create.blade.php
# resources/views/courses/show.blade.php
```

---

## 🔥 Tips & Tricks สำหรับ LMS Project

### 1. การใช้ Eloquent Relationships
```php
// User Model
public function courses()
{
    return $this->hasMany(Course::class, 'teacher_id');
}

public function enrolledCourses()
{
    return $this->belongsToMany(Course::class, 'course_user');
}

// การใช้งาน
$user = User::find(1);
$courses = $user->courses; // วิชาที่สร้าง
$enrolled = $user->enrolledCourses; // วิชาที่ลงทะเบียน
```

### 2. การตรวจสอบสิทธิ์ (Authorization)
```php
// ใน Controller
public function edit(Course $course)
{
    if (auth()->user()->id !== $course->teacher_id) {
        abort(403);
    }
    
    return view('courses.edit', compact('course'));
}

// หรือใช้ Policy
php artisan make:policy CoursePolicy --model=Course
```

### 3. การ Upload ไฟล์
```php
public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:pdf,ppt,pptx|max:10240',
    ]);
    
    $path = $request->file('file')->store('lessons', 'public');
    
    return response()->json(['path' => $path]);
}
```

### 4. การใช้ Blade Template
```php
// resources/views/layouts/app.blade.php
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'LMS System')</title>
</head>
<body>
    @include('components.navbar')
    
    <main class="container">
        @yield('content')
    </main>
    
    @include('components.footer')
</body>
</html>

// resources/views/courses/index.blade.php
@extends('layouts.app')

@section('title', 'All Courses')

@section('content')
<div class="row">
    @foreach($courses as $course)
        <div class="col-md-4">
            <div class="card">
                <h3>{{ $course->title }}</h3>
                <p>{{ $course->description }}</p>
                <small>By: {{ $course->teacher->name }}</small>
            </div>
        </div>
    @endforeach
</div>
@endsection
```

---

## 🚨 Common Problems & Solutions

### 1. ปัญหา Migration Error
```bash
# ถ้าเจอ error ให้ลอง
php artisan migrate:rollback
php artisan migrate
```

### 2. ปัญหา Permission
```bash
# ให้สิทธิ์ storage และ bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 3. ปัญหา Route not found
```bash
# ล้าง route cache
php artisan route:clear
php artisan route:cache
```

### 4. ปัญหา View not found
- ตรวจสอบชื่อไฟล์ .blade.php
- ตรวจสอบ path ใน controller

---

## 📚 Resources ที่แนะนำ

### สำหรับเรียนรู้ Laravel (ภาษาอังกฤษ)
- [Laravel Documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com/)
- [Laravel Bootcamp](https://bootcamp.laravel.com/)

### สำหรับค้นหาโค้ด
- [Laravel Daily](https://laraveldaily.com/)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)

### สำหรับ UI Components
- [Tailwind UI](https://tailwindui.com/)
- [Heroicons](https://heroicons.com/)
- [Blade UI Kit](https://blade-ui-kit.com/)

---

## 🎯 สิ่งที่ต้องทำก่อนเริ่มวันแรก

1. **อ่าน Quick Start นี้ให้เข้าใจ**
2. **ติดตั้ง Environment ให้พร้อม**
3. **ทดลองสร้าง CRUD ง่ายๆ 1-2 ตัว**
4. **เข้าใจ MVC Pattern ของ Laravel**
5. **เตรียม Database ให้พร้อม**

**⏰ เวลาที่แนะนำสำหรับการเรียนรู้: 2-3 ชั่วโมง**

ถ้าทำตามนี้คุณจะพร้อมเริ่มทำ LMS ในวันแรกได้เลย!