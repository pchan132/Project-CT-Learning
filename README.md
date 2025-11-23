# 🎓 CT Learning - Learning Management System

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

ระบบจัดการการเรียนการสอนออนไลน์ (LMS) ที่พัฒนาด้วย Laravel Framework พร้อมฟีเจอร์ครบครันสำหรับครูผู้สอนและนักเรียน

## 📋 สารบัญ

- [คุณสมบัติหลัก](#คุณสมบัติหลัก)
- [สถาปัตยกรรมระบบ](#สถาปัตยกรรมระบบ)
- [การติดตั้ง](#การติดตั้ง)
- [Routes & API](#routes--api)
- [ฟังก์ชันที่ใช้งานได้](#ฟังก์ชันที่ใช้งานได้)
- [Documentation](#documentation)
- [Tech Stack](#tech-stack)

---

## 🎯 คุณสมบัติหลัก

### 👨‍🏫 สำหรับครู (Teacher)
- ✅ **จัดการคอร์ส**: สร้าง แก้ไข ลบคอร์สเรียน พร้อมอัพโหลดรูปปก
- ✅ **จัดการโมดูล**: จัดระเบียบเนื้อหาเป็นโมดูล พร้อมกำหนดลำดับ
- ✅ **จัดการบทเรียน**: สร้างบทเรียนรองรับ 3 รูปแบบ
  - 📄 **PDF**: อัพโหลดเอกสาร PDF, PPT, DOCX
  - 🎥 **Video**: ฝัง YouTube videos
  - 📝 **Article**: เขียนบทความ text-based
- ✅ **ดูรายชื่อนักเรียน**: ตรวจสอบนักเรียนที่ลงทะเบียนเรียน

### 👨‍🎓 สำหรับนักเรียน (Student)
- ✅ **เข้าเรียนคอร์ส**: ดูคอร์สที่ลงทะเบียนพร้อม progress bar
- ✅ **เรียนบทเรียน**: เข้าถึงเนื้อหาทั้ง 3 รูปแบบ (PDF, Video, Article)
- ✅ **ติดตามความคืบหน้า**: 
  - บันทึกบทเรียนที่เรียนจบด้วย AJAX (ไม่ต้อง reload หน้า)
  - แสดง progress percentage ของแต่ละคอร์ส
  - ระบบ completion tracking ที่แม่นยำ
- ✅ **Navigation ที่สะดวก**: Breadcrumb navigation ครบทุกหน้า

---

## 🏗️ สถาปัตยกรรมระบบ

### Database Schema

```
┌─────────────────┐
│     users       │
│─────────────────│
│ id              │
│ name            │
│ email           │
│ password        │
│ role (enum)     │ ← 'teacher', 'student'
└─────────────────┘
         │
         │ 1:N (teacher)
         ▼
┌─────────────────┐
│    courses      │
│─────────────────│
│ id              │
│ teacher_id (FK) │
│ title           │
│ description     │
│ cover_image_url │
└─────────────────┘
         │
         │ 1:N
         ▼
┌─────────────────┐         ┌──────────────────┐
│    modules      │         │   enrollments    │
│─────────────────│         │──────────────────│
│ id              │         │ id               │
│ course_id (FK)  │         │ user_id (FK)     │
│ title           │         │ course_id (FK)   │
│ description     │         │ enrolled_at      │
│ order           │         └──────────────────┘
└─────────────────┘
         │
         │ 1:N
         ▼
┌─────────────────┐
│    lessons      │
│─────────────────│
│ id              │
│ module_id (FK)  │
│ title           │
│ content_type    │ ← 'PDF', 'VIDEO', 'TEXT'
│ content_url     │ ← file path or YouTube URL
│ content_text    │ ← article content
│ order           │
└─────────────────┘
         │
         │ 1:N
         ▼
┌───────────────────────┐
│ lesson_completions    │
│───────────────────────│
│ id                    │
│ lesson_id (FK)        │
│ user_id (FK)          │
│ completed_at          │
└───────────────────────┘
```

### MVC Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Teacher/
│   │   │   ├── CourseController.php       # CRUD คอร์ส
│   │   │   ├── ModuleController.php       # CRUD โมดูล
│   │   │   └── LessonController.php       # CRUD บทเรียน + file upload
│   │   └── Student/
│   │       ├── CourseController.php       # ดูคอร์สที่เรียน
│   │       └── LearningController.php     # เรียนบทเรียน + mark complete
│   └── Middleware/
│       ├── TeacherMiddleware.php          # ตรวจสอบสิทธิ์ครู
│       └── StudentMiddleware.php          # ตรวจสอบสิทธิ์นักเรียน
├── Models/
│   ├── User.php                           # role-based user
│   ├── Course.php                         # + progress calculation
│   ├── Module.php
│   ├── Lesson.php                         # + completion check
│   ├── Enrollment.php
│   └── LessonCompletion.php
└── View/
    └── Components/
        └── AppLayout.php                  # Blade component layout

resources/views/
├── teacher/
│   ├── courses/
│   │   ├── index.blade.php               # รายการคอร์ส + ปุ่ม "📚 Modules"
│   │   ├── create.blade.php              # ฟอร์มสร้างคอร์ส
│   │   ├── edit.blade.php                # ฟอร์มแก้ไขคอร์ส
│   │   └── show.blade.php                # รายละเอียดคอร์ส
│   ├── modules/
│   │   ├── index.blade.php               # รายการโมดูล + ปุ่ม "📝 Lessons"
│   │   ├── create.blade.php              # ฟอร์มสร้างโมดูล
│   │   ├── edit.blade.php                # ฟอร์มแก้ไขโมดูล
│   │   └── show.blade.php                # รายละเอียดโมดูล
│   └── lessons/
│       ├── index.blade.php               # รายการบทเรียน
│       ├── create.blade.php              # ฟอร์มสร้างบทเรียน (toggle content type)
│       ├── edit.blade.php                # ฟอร์มแก้ไขบทเรียน
│       └── show.blade.php                # รายละเอียดบทเรียน
└── student/
    ├── courses/
    │   ├── index.blade.php               # Dashboard + progress bars
    │   └── show.blade.php                # รายการโมดูลในคอร์ส
    ├── modules/
    │   └── show.blade.php                # รายการบทเรียนในโมดูล
    └── lessons/
        └── show.blade.php                # เนื้อหาบทเรียน + AJAX complete button
```

---

## 🚀 การติดตั้ง

### Requirements
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Laravel 10.x

### Installation Steps

```bash
# 1. Clone repository
git clone https://github.com/pchan132/Project-CT-Learning.git
cd ct-learning

# 2. Install PHP dependencies
composer install

# 3. Install JavaScript dependencies
npm install

# 4. สร้างไฟล์ .env
copy .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. ตั้งค่า database ใน .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ct_learning
DB_USERNAME=root
DB_PASSWORD=

# 7. Run migrations
php artisan migrate

# 8. สร้าง symbolic link สำหรับ storage
php artisan storage:link

# 9. Compile assets
npm run dev
# หรือ production
npm run build

# 10. Start development server
php artisan serve
```

### Seeding (Optional)
```bash
php artisan db:seed
```

---

## 🛣️ Routes & API

### Teacher Routes

#### Course Management
```php
GET    /teacher/courses                     # รายการคอร์สทั้งหมด
GET    /teacher/courses/create              # ฟอร์มสร้างคอร์ส
POST   /teacher/courses                     # บันทึกคอร์สใหม่
GET    /teacher/courses/{course}            # รายละเอียดคอร์ส
GET    /teacher/courses/{course}/edit       # ฟอร์มแก้ไขคอร์ส
PUT    /teacher/courses/{course}            # อัพเดทคอร์ส
DELETE /teacher/courses/{course}            # ลบคอร์ส
```

#### Module Management
```php
GET    /teacher/courses/{course}/modules                  # รายการโมดูล
GET    /teacher/courses/{course}/modules/create           # ฟอร์มสร้างโมดูล
POST   /teacher/courses/{course}/modules                  # บันทึกโมดูลใหม่
GET    /teacher/courses/{course}/modules/{module}         # รายละเอียดโมดูล
GET    /teacher/courses/{course}/modules/{module}/edit    # ฟอร์มแก้ไขโมดูล
PUT    /teacher/courses/{course}/modules/{module}         # อัพเดทโมดูล
DELETE /teacher/courses/{course}/modules/{module}         # ลบโมดูล
```

#### Lesson Management
```php
GET    /teacher/courses/{course}/modules/{module}/lessons                 # รายการบทเรียน
GET    /teacher/courses/{course}/modules/{module}/lessons/create          # ฟอร์มสร้างบทเรียน
POST   /teacher/courses/{course}/modules/{module}/lessons                 # บันทึกบทเรียนใหม่
GET    /teacher/courses/{course}/modules/{module}/lessons/{lesson}        # รายละเอียดบทเรียน
GET    /teacher/courses/{course}/modules/{module}/lessons/{lesson}/edit   # ฟอร์มแก้ไขบทเรียน
PUT    /teacher/courses/{course}/modules/{module}/lessons/{lesson}        # อัพเดทบทเรียน
DELETE /teacher/courses/{course}/modules/{module}/lessons/{lesson}        # ลบบทเรียน
```

### Student Routes

```php
GET  /student/courses                                              # Dashboard (คอร์สที่เรียน)
GET  /student/courses/{course}/learn                               # รายการโมดูลในคอร์ส
GET  /student/courses/{course}/modules/{module}                    # รายการบทเรียนในโมดูล
GET  /student/courses/{course}/modules/{module}/lessons/{lesson}   # เนื้อหาบทเรียน
POST /student/courses/{course}/modules/{module}/lessons/{lesson}/complete  # บันทึกการเรียนจบ
```

### AJAX Routes

```php
POST /lessons/{lesson}/complete    # บันทึก completion (AJAX)
```

---

## ⚡ ฟังก์ชันที่ใช้งานได้

### Model Methods

#### Course Model
```php
// คำนวณเปอร์เซ็นต์ความคืบหน้า
$course->getProgressPercentage($userId);  // returns 0-100

// เช็คว่าเรียนจบหรือยัง
$course->isCompletedBy($userId);  // returns true/false

// Relationships
$course->teacher();      // belongsTo User
$course->modules();      // hasMany Module
$course->enrollments();  // hasMany Enrollment
```

#### Module Model
```php
// Relationships
$module->course();   // belongsTo Course
$module->lessons();  // hasMany Lesson

// Scope: เรียงตาม order
Module::ordered()->get();
```

#### Lesson Model
```php
// เช็คว่าเรียนจบหรือยัง
$lesson->isCompletedBy($userId);  // returns true/false

// Relationships
$lesson->module();       // belongsTo Module
$lesson->completions();  // hasMany LessonCompletion

// Scope: เรียงตาม order
Lesson::ordered()->get();
```

#### User Model
```php
// Relationships
$user->enrollments();        // hasMany Enrollment
$user->lessonCompletions();  // hasMany LessonCompletion
$user->courses();            // hasMany Course (ถ้าเป็นครู)

// Helper Methods
$user->isTeacher();  // returns true/false
$user->isStudent();  // returns true/false
```

### Authorization

```php
// Teacher: ตรวจสอบว่าเป็นเจ้าของคอร์ส
if (auth()->id() !== $course->teacher_id) {
    abort(403);
}

// Student: ตรวจสอบว่าลงทะเบียนแล้ว
$enrollment = auth()->user()->enrollments()
    ->where('course_id', $course->id)
    ->first();
    
if (!$enrollment) {
    abort(403, 'You are not enrolled in this course.');
}
```

### File Upload

```php
// ใน LessonController
if ($request->hasFile('file')) {
    $file = $request->file('file');
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('lessons/pdf', $filename, 'public');
    $validated['content_url'] = $path;
}

// Supported types: PDF, DOC, DOCX, PPT, PPTX
// Max size: 10MB
```

### AJAX Completion

```javascript
// ใน student/lessons/show.blade.php
const completeBtn = document.getElementById('complete-lesson-btn');

completeBtn.addEventListener('click', async () => {
    const response = await fetch('/lessons/{{ $lesson->id }}/complete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
    
    if (response.ok) {
        // อัพเดท UI โดยไม่ reload หน้า
        completeBtn.textContent = '✅ Completed';
        completeBtn.disabled = true;
    }
});
```

---

## 📚 Documentation

เอกสารเพิ่มเติมอยู่ในโฟลเดอร์ `context/docs/`:

- **[MODULE-LESSON-TROUBLESHOOTING.md](context/docs/MODULE-LESSON-TROUBLESHOOTING.md)** - คู่มือแก้ปัญหาและวิธีการทำ Module & Lesson ฉบับสมบูรณ์
- **[DAY3-COMPLETE-DOCUMENTATION.md](context/docs/DAY3-COMPLETE-DOCUMENTATION.md)** - เอกสารประกอบ Day 3 Implementation
- **[DAY3-SUMMARY.md](context/docs/DAY3-SUMMARY.md)** - สรุปฟีเจอร์ Day 3
- **[TEACHER-MODULE-LESSON-GUIDE.md](context/docs/TEACHER-MODULE-LESSON-GUIDE.md)** - คู่มือการใช้งานสำหรับครู

---

## 🛠️ Tech Stack

### Backend
- **Laravel 10.x** - PHP Framework
- **Laravel Breeze** - Authentication starter kit
- **Eloquent ORM** - Database ORM
- **Laravel Storage** - File management

### Frontend
- **Blade Templates** - Template engine
- **Blade Components** - Reusable UI components (`<x-app-layout>`)
- **Tailwind CSS** - Utility-first CSS framework
- **Vanilla JavaScript** - For AJAX requests (no jQuery)
- **Vite** - Asset bundler

### Database
- **MySQL/PostgreSQL** - Relational database
- **Migrations** - Version control for database schema
- **Seeders** - Sample data generation

### Development Tools
- **Composer** - PHP dependency manager
- **NPM** - JavaScript package manager
- **Git** - Version control

---

## 🔧 Troubleshooting

### ปัญหาที่พบบ่อย

#### 1. Error 403 Unauthorized
```bash
# แก้ไข: ตรวจสอบ authorization ใน Controller
if (auth()->id() !== $course->teacher_id) {
    abort(403);
}
```

#### 2. Undefined variable $slot
```bash
# แก้ไข: ใช้ Blade Component แทน @extends
# เปลี่ยนจาก
@extends('layouts.app')
@section('content')

# เป็น
<x-app-layout>
<!-- content -->
</x-app-layout>
```

#### 3. File upload ไม่ทำงาน
```bash
# ตรวจสอบว่าสร้าง symbolic link แล้ว
php artisan storage:link

# ตรวจสอบ permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 4. Routes not found
```bash
# Clear cache
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# ดูรายการ routes
php artisan route:list --name=teacher
```

---

## 📈 Roadmap

### Phase 1: Core Features ✅ (Completed)
- [x] Authentication system (Teacher/Student roles)
- [x] Course CRUD
- [x] Module CRUD with ordering
- [x] Lesson CRUD with 3 content types
- [x] File upload (PDF, PPT, DOC)
- [x] YouTube video embedding
- [x] Progress tracking
- [x] AJAX completion

### Phase 2: Enhanced Features (Upcoming)
- [ ] Quiz system
- [ ] Certificate generation
- [ ] Course enrollment approval
- [ ] Discussion forums
- [ ] Assignment submission
- [ ] Grade management

### Phase 3: Advanced Features (Future)
- [ ] Live streaming
- [ ] Video conferencing integration
- [ ] Mobile app (React Native)
- [ ] Analytics dashboard
- [ ] Email notifications
- [ ] Payment integration

---

## 👥 Contributors

- **Pchan132** - Initial work - [GitHub](https://github.com/pchan132)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- **Laravel Framework** - For the excellent PHP framework
- **Tailwind CSS** - For the beautiful utility-first CSS framework
- **Laravel Breeze** - For the authentication scaffolding

---

## 📞 Contact

For questions or support, please open an issue on GitHub or contact:
- **Repository**: [Project-CT-Learning](https://github.com/pchan132/Project-CT-Learning)

---

## ⚠️ Important Notes

### สำหรับ Developer
1. **Blade Components**: ระบบใช้ `<x-app-layout>` **ไม่ใช่** `@extends('layouts.app')`
2. **Authorization**: ใช้ manual checks (`auth()->id() !== $owner`) **ไม่ใช่** Gates/Policies
3. **Nested Routes**: ใส่ parameters ครบ เช่น `route('teacher.courses.modules.index', $course)`
4. **File Upload**: ไฟล์จะถูกเก็บที่ `storage/app/public/lessons/pdf/`
5. **AJAX CSRF**: อย่าลืมใส่ `X-CSRF-TOKEN` ใน header

### สำหรับ User
1. **Teacher**: สร้าง Course → Add Modules → Add Lessons
2. **Student**: ต้องมี Enrollment record ก่อนถึงจะเข้าเรียนได้
3. **Progress**: คำนวณจาก (completed lessons / total lessons) × 100
4. **Content Types**:
   - PDF: รองรับ .pdf, .doc, .docx, .ppt, .pptx (max 10MB)
   - Video: เฉพาะ YouTube URLs
   - Article: Text content with line breaks

---

<p align="center">Made with ❤️ using Laravel</p>
