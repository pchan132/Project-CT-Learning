# 🎓 CT Learning - Learning Management System

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)
![Status](https://img.shields.io/badge/status-Active%20Development-orange.svg)

ระบบจัดการการเรียนการสอนออนไลน์ (LMS) ที่พัฒนาด้วย Laravel Framework 10.x พร้อมฟีเจอร์ครบครันสำหรับครูผู้สอนและนักเรียน รองรับการสอนแบบ Multi-media และการติดตามความคืบหน้าแบบ Real-time

## 📋 สารบัญ

- [คุณสมบัติหลัก](#คุณสมบัติหลัก)
- [สถาปัตยกรรมระบบ](#สถาปัตยกรรมระบบ)
- [ฟีเจอร์ที่พัฒนาแล้ว](#ฟีเจอร์ที่พัฒนาแล้ว)
- [การติดตั้ง](#การติดตั้ง)
- [Routes & API](#routes--api)
- [ฟังก์ชันที่ใช้งานได้](#ฟังก์ชันที่ใช้งานได้)
- [Documentation](#documentation)
- [Tech Stack](#tech-stack)
- [Screenshots & UI](#screenshots--ui)

---

## 🎯 คุณสมบัติหลัก

### 👨‍🏫 สำหรับครู (Teacher)
- ✅ **ระบบสมัครสมาชิกแบบแยกประเภท**: Register แยกระหว่าง Teacher และ Student
- ✅ **Dashboard สำหรับครู**: แสดงสถิติคอร์ส จำนวนนักเรียน และการจัดการคอร์ส
- ✅ **จัดการคอร์สเรียน**: สร้าง แก้ไข ลบคอร์สเรียน พร้อมอัพโหลดรูปปก
- ✅ **จัดการโมดูล**: จัดระเบียบเนื้อหาเป็นโมดูล พร้อมกำหนดลำดับ
- ✅ **จัดการบทเรียน**: สร้างบทเรียนรองรับ 3 รูปแบบ
  - 📄 **PDF**: อัพโหลดเอกสาร PDF, PPT, DOCX (ขนาดสูงสุด 10MB)
  - 🎥 **Video**: ฝัง YouTube videos พร้อมการแปลง URL อัตโนมัติ
  - 📝 **Article**: เขียนบทความแบบ text-based พร้อมการจัดรูปแบบ
- ✅ **ระบบสิทธิ์**: ตรวจสอบสิทธิ์ครูผู้สอน (Owner-based authorization)
- ✅ **ดูรายชื่อนักเรียน**: ตรวจสอบนักเรียนที่ลงทะเบียนเรียนในแต่ละคอร์ส

### 👨‍🎓 สำหรับนักเรียน (Student)
- ✅ **ระบบสมัครสมาชิก**: สมัครแยกประเภท Student
- ✅ **Dashboard สำหรับนักเรียน**: แสดงคอร์สที่ลงทะเบียนพร้อม Progress Bar
- ✅ **ระบบลงทะเบียนเรียน**: ลงทะเบียนคอร์สเรียนได้ด้วยตนเอง
- ✅ **เข้าเรียนคอร์ส**: ดูคอร์สที่ลงทะเบียนพร้อม progress bar และ completion status
- ✅ **เรียนบทเรียน**: เข้าถึงเนื้อหาทั้ง 3 รูปแบบ (PDF, Video, Article)
- ✅ **ติดตามความคืบหน้า**: 
  - บันทึกบทเรียนที่เรียนจบด้วย AJAX (ไม่ต้อง reload หน้า)
  - แสดง progress percentage ของแต่ละคอร์ส
  - ระบบ completion tracking ที่แม่นยำ
- ✅ **Navigation ที่สะดวก**: Breadcrumb navigation ครบทุกหน้า

### 🎨 UI/UX Features
- ✅ **Dark Mode Toggle**: รองรับโหมดมืด/สว่าง
- ✅ **Responsive Design**: รองรับทุกขนาดหน้าจอ (Mobile, Tablet, Desktop)
- ✅ **Modern UI**: ใช้ Tailwind CSS พร้อม Glass Morphism design
- ✅ **Interactive Components**: Hover effects, transitions, และ micro-interactions
- ✅ **Icon Integration**: ใช้ Font Awesome icons

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
│ created_at      │
│ updated_at      │
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
│ created_at      │
│ updated_at      │
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
│ created_at      │
│ updated_at      │
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
│ created_at      │
│ updated_at      │
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
│   │   │   ├── CourseController.php       # CRUD คอร์ส + Dashboard
│   │   │   ├── ModuleController.php       # CRUD โมดูล
│   │   │   └── LessonController.php       # CRUD บทเรียน + file upload
│   │   ├── Student/
│   │   │   └── CourseController.php       # ดูคอร์ส + ลงทะเบียน
│   │   └── Auth/
│   │       └── RegisteredUserController.php # Register แยกประเภท
│   └── Middleware/
│       ├── TeacherMiddleware.php          # ตรวจสอบสิทธิ์ครู
│       └── StudentMiddleware.php          # ตรวจสอบสิทธิ์นักเรียน
├── Models/
│   ├── User.php                           # role-based user + relationships
│   ├── Course.php                         # progress calculation + relationships
│   ├── Module.php                         # ordering + relationships
│   ├── Lesson.php                         # completion check + relationships
│   ├── Enrollment.php                     # student-course relationship
│   └── LessonCompletion.php               # tracking completion
└── View/
    └── Components/
        ├── AppLayout.php                  # Main layout component
        ├── teacher-components/
        │   ├── teacher-courses-grid.blade.php    # Course grid display
        │   └── statistics-teacher-courses.blade.php # Dashboard stats
        └── [other blade components]

resources/views/
├── teacher/
│   ├── dashboard.blade.php               # Teacher dashboard
│   ├── courses/
│   │   ├── index.blade.php               # รายการคอร์ส
│   │   ├── create.blade.php              # ฟอร์มสร้างคอร์ส
│   │   ├── edit.blade.php                # ฟอร์มแก้ไขคอร์ส
│   │   └── show.blade.php                # รายละเอียดคอร์ส
│   ├── modules/
│   │   ├── index.blade.php               # รายการโมดูล
│   │   ├── create.blade.php              # ฟอร์มสร้างโมดูล
│   │   ├── edit.blade.php                # ฟอร์มแก้ไขโมดูล
│   │   └── show.blade.php                # รายละเอียดโมดูล
│   └── lessons/
│       ├── index.blade.php               # รายการบทเรียน
│       ├── create.blade.php              # ฟอร์มสร้างบทเรียน
│       ├── edit.blade.php                # ฟอร์มแก้ไขบทเรียน
│       └── show.blade.php                # รายละเอียดบทเรียน
├── student/
│   ├── dashboard.blade.php               # Student dashboard
│   └── courses/
│       └── index.blade.php               # รายการคอร์สที่ลงทะเบียน
└── auth/
    ├── register.blade.php                # Registration form (dynamic role)
    ├── login.blade.php                   # Login form
    └── [other auth views]
```

---

## 🚀 ฟีเจอร์ที่พัฒนาแล้ว (Current Status)

### ✅ Phase 1: Core Foundation (Completed)
- **Authentication System**
  - Laravel Breeze integration
  - Role-based registration (Teacher/Student)
  - Separate registration routes: `/register/teacher` และ `/register/student`
  - Automatic dashboard redirection based on role

- **User Management**
  - User model with role-based methods (`isTeacher()`, `isStudent()`)
  - Role-based middleware (`TeacherMiddleware`, `StudentMiddleware`)
  - User relationships with courses and enrollments

- **Course Management (Teacher)**
  - Full CRUD operations for courses
  - Cover image upload with storage management
  - Owner-based authorization (ครูเห็นเฉพาะคอร์สของตัวเอง)
  - Course listing with grid layout

- **Dashboard Systems**
  - **Teacher Dashboard**: 
    - Statistics cards (จำนวนคอร์ส, จำนวนนักเรียน)
    - Course grid with cover images
    - Modern Glass Morphism UI design
    - Hover effects and transitions
  - **Student Dashboard**: 
    - Basic dashboard structure (ready for course listing)

- **Database Design**
  - Complete migration system
  - Proper foreign key constraints
  - Optimized table structure with indexes

- **UI/UX Foundation**
  - Tailwind CSS integration
  - Dark mode support
  - Responsive design
  - Component-based architecture
  - Font Awesome icons

### ✅ Phase 2: Content Management (In Progress)
- **Module Management**
  - Nested resource routes (`/courses/{course}/modules`)
  - Module CRUD with ordering system
  - Module-Lesson relationship

- **Lesson Management**
  - Support for 3 content types: PDF, Video, Article
  - File upload system for PDF/PPT/DOCX
  - YouTube URL integration with auto-embed conversion
  - Text-based article content

- **Student Learning System**
  - Course enrollment system
  - Progress tracking foundation
  - Lesson completion tracking structure

### 🔄 Phase 3: Learning Experience (Planned)
- AJAX completion system
- Real-time progress updates
- Interactive lesson viewer
- Breadcrumb navigation

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

### Authentication Routes
```php
// Registration (Separated by role)
GET    /register/student                   # Student registration form
POST   /register/student                   # Student registration submit
GET    /register/teacher                   # Teacher registration form
POST   /register/teacher                   # Teacher registration submit

// Standard Auth
POST   /login                              # Login
POST   /logout                             # Logout
```

### Teacher Routes

#### Dashboard
```php
GET    /teacher/dashboard                  # Teacher dashboard with statistics
```

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

#### Module Management (Nested)
```php
GET    /teacher/courses/{course}/modules                  # รายการโมดูล
GET    /teacher/courses/{course}/modules/create           # ฟอร์มสร้างโมดูล
POST   /teacher/courses/{course}/modules                  # บันทึกโมดูลใหม่
GET    /teacher/courses/{course}/modules/{module}         # รายละเอียดโมดูล
GET    /teacher/courses/{course}/modules/{module}/edit    # ฟอร์มแก้ไขโมดูล
PUT    /teacher/courses/{course}/modules/{module}         # อัพเดทโมดูล
DELETE /teacher/courses/{course}/modules/{module}         # ลบโมดูล
```

#### Lesson Management (Double Nested)
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
GET  /student/dashboard                                     # Student dashboard
GET  /student/courses                                        # Dashboard (คอร์สที่เรียน)
POST /student/courses/enroll                                 # ลงทะเบียนเรียนคอร์ส
```

### AJAX Routes (Planned)
```php
POST /lessons/{lesson}/complete    # บันทึก completion (AJAX)
```

---

## ⚡ ฟังก์ชันที่ใช้งานได้

### Model Methods

#### User Model
```php
// Role checking
$user->isTeacher();     // returns true/false
$user->isStudent();     // returns true/false

// Relationships
$user->teachingCourses();        // hasMany Course (ถ้าเป็นครู)
$user->enrollments();           // hasMany Enrollment (ถ้าเป็นนักเรียน)
$user->lessonCompletions();     // hasMany LessonCompletion
$user->enrolledCourses();        // belongsToMany Course

// Statistics
$user->teaching_courses_count;      // จำนวนคอร์สที่สอน
$user->enrolled_courses_count;      // จำนวนคอร์สที่ลงทะเบียน
$user->overall_progress;            // Progress รวมทั้งหมด
```

#### Course Model
```php
// Progress calculation
$course->getProgressForStudent($userId);  // returns 0-100
$course->getCompletedLessonsCount($userId); // จำนวนบทเรียนที่เรียนจบ

// Relationships
$course->teacher();      // belongsTo User
$course->modules();      // hasMany Module (ordered)
$course->lessons();      // hasManyThrough Lesson
$course->enrollments();  // hasMany Enrollment

// Statistics
$course->total_modules;      // จำนวนโมดูลทั้งหมด
$course->total_lessons;      // จำนวนบทเรียนทั้งหมด

// Helper
$course->isEnrolledByStudent($studentId); // ตรวจสอบการลงทะเบียน
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
// เช็คว่าเรียนจบหรือยัง (planned)
$lesson->isCompletedBy($userId);  // returns true/false

// Relationships
$lesson->module();       // belongsTo Module
$lesson->completions();  // hasMany LessonCompletion

// Scope: เรียงตาม order
Lesson::ordered()->get();
```

### Authorization

```php
// Teacher: ตรวจสอบว่าเป็นเจ้าของคอร์ส
if (auth()->id() !== $course->teacher_id) {
    abort(403, 'Unauthorized action.');
}

// Student: ตรวจสอบว่าลงทะเบียนแล้ว (planned)
$enrollment = auth()->user()->enrollments()
    ->where('course_id', $course->id)
    ->first();
    
if (!$enrollment) {
    abort(403, 'You are not enrolled in this course.');
}
```

### File Upload

```php
// ใน CourseController (Cover Image)
if ($request->hasFile('cover_image_url')) {
    $data['cover_image_url'] = $request->file('cover_image_url')->store('cover_images', 'public');
}

// ใน LessonController (PDF Files)
if ($request->hasFile('file')) {
    $file = $request->file('file');
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('lessons/pdf', $filename, 'public');
    $validated['content_url'] = $path;
}

// Supported types: PDF, DOC, DOCX, PPT, PPTX
// Max size: 10MB
```

### YouTube URL Processing

```php
// ใน LessonController (Video type)
if ($request->content_type === 'VIDEO') {
    $url = $request->youtube_url;
    // Convert youtube.com/watch?v=ID to youtube.com/embed/ID
    $embedUrl = preg_replace(
        '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/',
        'https://www.youtube.com/embed/$1',
        $url
    );
    $validated['content_url'] = $embedUrl;
}
```

---

## 📚 Documentation

เอกสารเพิ่มเติมอยู่ในโฟลเดอร์ `context/docs/`:

- **[ARCHITECTURE.md](context/docs/ARCHITECTURE.md)** - เอกสารสถาปัตยกรรมระบบอย่างละเอียด
- **[authentication.md](context/docs/authentication.md)** - เอกสารระบบ authentication
- **[teacher-course-crud.md](context/docs/teacher-course-crud.md)** - เอกสารการทำ CRUD คอร์สเรียน
- **[routes-fix.md](context/docs/routes-fix.md)** - บันทึกการแก้ไขปัญหา routes
- **[dark-mode-toggle.md](context/docs/dark-mode-toggle.md)** - เอกสารการทำ Dark Mode
- **[ROUTES-REFERENCE.md](context/docs/ROUTES-REFERENCE.md)** - อ้างอิง routes ทั้งหมด

---

## 🛠️ Tech Stack

### Backend
- **Laravel 10.x** - PHP Framework
- **Laravel Breeze** - Authentication starter kit
- **Eloquent ORM** - Database ORM
- **Laravel Storage** - File management
- **MySQL/PostgreSQL** - Database

### Frontend
- **Blade Templates** - Template engine
- **Blade Components** - Reusable UI components (`<x-app-layout>`)
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Font Awesome** - Icon library
- **Vite** - Asset bundler

### Development Tools
- **Composer** - PHP dependency manager
- **NPM** - JavaScript package manager
- **Git** - Version control

---

## 🖼️ Screenshots & UI

### Teacher Dashboard
- **Statistics Cards**: แสดงจำนวนคอร์ส จำนวนนักเรียน ด้วย Glass Morphism design
- **Course Grid**: แสดงคอร์สเรียนในรูปแบบ grid พร้อมรูปปก ปุ่มจัดการ
- **Responsive Design**: รองรับทุกขนาดหน้าจอ
- **Dark Mode**: รองรับโหมดมืด/สว่าง

### Registration System
- **Separated Registration**: แยกฟอร์มสมัครสำหรับ Teacher และ Student
- **Role-based Redirection**: นำทางไปยัง Dashboard ที่ถูกต้องหลังสมัคร

### Course Management
- **Cover Image Upload**: รองรับการอัพโหลดรูปปกคอร์ส
- **CRUD Operations**: สร้าง อ่าน แก้ไข ลบ คอร์สเรียน
- **Authorization**: ครูเห็นเฉพาะคอร์สของตัวเอง

---

## 🔧 Troubleshooting

### ปัญหาที่พบบ่อย

#### 1. Error 403 Unauthorized
```bash
# แก้ไข: ตรวจสอบ authorization ใน Controller
if (auth()->id() !== $course->teacher_id) {
    abort(403, 'Unauthorized action.');
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

#### 5. Dark mode ไม่ทำงาน
```bash
# ตรวจสอบว่ามี Alpine.js ใน layout
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

---

## 📈 Development Roadmap

### ✅ Phase 1: Core Foundation (Completed)
- [x] Authentication system (Teacher/Student roles)
- [x] Role-based registration
- [x] User management system
- [x] Course CRUD
- [x] Teacher dashboard with statistics
- [x] File upload system (cover images)
- [x] Responsive UI with Tailwind CSS
- [x] Dark mode support

### 🔄 Phase 2: Content Management (In Progress)
- [x] Module CRUD with ordering
- [x] Lesson CRUD with 3 content types
- [x] File upload (PDF, PPT, DOC)
- [x] YouTube video embedding
- [x] Student enrollment system
- [ ] Progress tracking foundation
- [ ] AJAX completion system

### 📋 Phase 3: Learning Experience (Planned)
- [ ] Interactive lesson viewer
- [ ] Real-time progress updates
- [ ] Breadcrumb navigation
- [ ] Student course dashboard
- [ ] Lesson completion tracking

### 🚀 Phase 4: Advanced Features (Future)
- [ ] Quiz system
- [ ] Certificate generation
- [ ] Discussion forums
- [ ] Assignment submission
- [ ] Grade management
- [ ] Email notifications
- [ ] Analytics dashboard

---

## 👥 Contributors

- **Pchan132** - Initial work & ongoing development - [GitHub](https://github.com/pchan132)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- **Laravel Framework** - For the excellent PHP framework
- **Tailwind CSS** - For the beautiful utility-first CSS framework
- **Laravel Breeze** - For the authentication scaffolding
- **Font Awesome** - For the amazing icon library
- **Alpine.js** - For the lightweight JavaScript framework

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
4. **File Upload**: ไฟล์จะถูกเก็บที่ `storage/app/public/cover_images/` และ `storage/app/public/lessons/pdf/`
5. **AJAX CSRF**: อย่าลืมใส่ `X-CSRF-TOKEN` ใน header
6. **Dark Mode**: ใช้ Alpine.js สำหรับจัดการ state

### สำหรับ User
1. **Teacher**: Register → Login → Create Course → Add Modules → Add Lessons
2. **Student**: Register → Login → Browse Courses → Enroll → Learn
3. **Progress**: คำนวณจาก (completed lessons / total lessons) × 100
4. **Content Types**:
   - PDF: รองรับ .pdf, .doc, .docx, .ppt, .pptx (max 10MB)
   - Video: เฉพาะ YouTube URLs (auto-convert to embed)
   - Article: Text content with line breaks

### Current Limitations
- Student dashboard ยังไม่แสดงคอร์สที่ลงทะเบียน (อยู่ระหว่างพัฒนา)
- Progress tracking ยังไม่สมบูรณ์
- ยังไม่มี AJAX completion system
- ยังไม่มี interactive lesson viewer

---

<p align="center">
  <strong>🚀 CT Learning - Building the Future of Online Education 🚀</strong><br>
  Made with ❤️ using Laravel & Tailwind CSS
</p>