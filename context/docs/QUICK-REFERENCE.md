# 📋 CT Learning - Quick Reference

## 🚀 คู่มือใช้งานด่วน

เอกสารนี้คือคู่มือใช้งานด่วนสำหรับระบบ CT Learning รวบรวมคำสั่งที่ใช้บ่อย ปัญหาที่พบบ่อย และวิธีแก้ไขแบบรวดเร็ว

---

## 📋 สารบัญ

1. [คำสั่ง Artisan ที่ใช้บ่อย](#คำสั่ง-artisan-ที่ใช้บ่อย)
2. [Routes หลัก](#routes-หลัก)
3. [Database Commands](#database-commands)
4. [File Management](#file-management)
5. [Troubleshooting Quick Fixes](#troubleshooting-quick-fixes)
6. [User Management](#user-management)
7. [Common Issues & Solutions](#common-issues--solutions)

---

## 🛠️ คำสั่ง Artisan ที่ใช้บ่อย

### 🏗️ การติดตั้งและตั้งค่า
```bash
# สร้าง application key
php artisan key:generate

# สร้าง symbolic link สำหรับ storage
php artisan storage:link

# รัน migration พร้อม seeder
php artisan migrate:fresh --seed

# ล้าง cache ทั้งหมด
php artisan optimize:clear
```

### 🗃️ Database Management
```bash
# สร้าง migration ใหม่
php artisan make:migration create_table_name

# รัน migration
php artisan migrate

# รัน migration พร้อม seed
php artisan migrate --seed

# รีเซ็ต database
php artisan migrate:fresh

# ย้อนกลับ migration
php artisan migrate:rollback

# แสดงสถานะ migration
php artisan migrate:status
```

### 📦 Package Management
```bash
# ติดตั้ง package ใหม่
composer require package-name

# ติดตั้ง dev package
composer require --dev package-name

# อัพเดท package
composer update

# ลบ package
composer remove package-name

# ติดตั้ง Node package
npm install package-name

# อัพเดท Node package
npm update
```

### 🔧 Development Commands
```bash
# เริ่ม development server
php artisan serve

# คอมไพล์ assets สำหรับ development
npm run dev

# คอมไพล์ assets สำหรับ production
npm run build

# คอมไพล์ assets แบบ watch
npm run watch

# แสดง routes ทั้งหมด
php artisan route:list

# แสดง routes ตามชื่อ
php artisan route:list --name=teacher

# แสดง middleware ที่ลงทะเบียน
php artisan route:list --middleware
```

### 🗂️ File & Cache Management
```bash
# ล้าง cache
php artisan cache:clear

# ล้าง config cache
php artisan config:clear

# ล้าง route cache
php artisan route:clear

# ล้าง view cache
php artisan view:clear

# สร้าง cache สำหรับ production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ล้าง compiled views
php artisan view:clear
```

### 🔍 Debugging & Testing
```bash
# แสดงข้อมูลสภาพแวดล้อม
php artisan env

# แสดงค่า config
php artisan config cache

# รันทุก tests
php artisan test

# รัน test ตามชื่อ
php artisan test --filter TestName

# รัน test พร้อม coverage
php artisan test --coverage
```

---

## 🛣️ Routes หลัก

### 🔐 Authentication Routes
```php
// Registration
GET  /register/student     -> register.student
POST /register/student     -> register.student.store
GET  /register/teacher     -> register.teacher
POST /register/teacher     -> register.teacher.store

// Login/Logout
POST /login               -> login
POST /logout              -> logout

// Dashboard Redirect
GET  /dashboard           -> dashboard (auto-redirect by role)
```

### 👨‍🎓 Student Routes
```php
// Dashboard
GET /student/dashboard     -> student.dashboard

// Courses
GET /student/courses                  -> student.courses.index
GET /student/courses/my-courses        -> student.courses.my-courses
GET /student/courses/{course}/preview  -> student.courses.preview
GET /student/courses/{course}          -> student.courses.show
POST /student/courses/{course}/enroll -> student.courses.enroll

// Learning
GET /student/courses/{course}/lessons/{lesson}           -> student.courses.learn-lesson
POST /student/courses/{course}/lessons/{lesson}/complete -> student.courses.complete-lesson

// Quizzes
GET /student/courses/{course}/modules/{module}/quizzes/{quiz} -> student.courses.modules.quizzes.show
POST /student/quizzes/{quiz}/start                          -> student.quizzes.start
GET /student/attempts/{attempt}/take                        -> student.attempts.take
POST /student/attempts/{attempt}/submit                      -> student.attempts.submit
GET /student/attempts/{attempt}/result                       -> student.attempts.result

// Certificates
GET /student/certificates                     -> student.certificates.index
POST /student/courses/{course}/certificates/generate -> student.certificates.generate
GET /student/certificates/{certificate}          -> student.certificates.show
GET /student/certificates/{certificate}/download -> student.certificates.download
```

### 👨‍🏫 Teacher Routes
```php
// Dashboard
GET /teacher/dashboard     -> teacher.dashboard

// Courses (Resource)
GET    /teacher/courses              -> teacher.courses.index
GET    /teacher/courses/create       -> teacher.courses.create
POST   /teacher/courses              -> teacher.courses.store
GET    /teacher/courses/{course}    -> teacher.courses.show
GET    /teacher/courses/{course}/edit -> teacher.courses.edit
PUT    /teacher/courses/{course}    -> teacher.courses.update
DELETE /teacher/courses/{course}    -> teacher.courses.destroy

// Course Students
GET /teacher/courses/{course}/students -> teacher.courses.students

// Modules (Nested Resource)
GET    /teacher/courses/{course}/modules                    -> teacher.courses.modules.index
GET    /teacher/courses/{course}/modules/create             -> teacher.courses.modules.create
POST   /teacher/courses/{course}/modules                    -> teacher.courses.modules.store
GET    /teacher/courses/{course}/modules/{module}          -> teacher.courses.modules.show
GET    /teacher/courses/{course}/modules/{module}/edit     -> teacher.courses.modules.edit
PUT    /teacher/courses/{course}/modules/{module}          -> teacher.courses.modules.update
DELETE /teacher/courses/{course}/modules/{module}          -> teacher.courses.modules.destroy

// Lessons (Double Nested Resource)
GET    /teacher/courses/{course}/modules/{module}/lessons                    -> teacher.courses.modules.lessons.index
GET    /teacher/courses/{course}/modules/{module}/lessons/create             -> teacher.courses.modules.lessons.create
POST   /teacher/courses/{course}/modules/{module}/lessons                    -> teacher.courses.modules.lessons.store
GET    /teacher/courses/{course}/modules/{module}/lessons/{lesson}          -> teacher.courses.modules.lessons.show
GET    /teacher/courses/{course}/modules/{module}/lessons/{lesson}/edit     -> teacher.courses.modules.lessons.edit
PUT    /teacher/courses/{course}/modules/{module}/lessons/{lesson}          -> teacher.courses.modules.lessons.update
DELETE /teacher/courses/{course}/modules/{module}/lessons/{lesson}          -> teacher.courses.modules.lessons.destroy

// Quizzes (Nested Resource)
GET    /teacher/courses/{course}/modules/{module}/quizzes                    -> teacher.courses.modules.quizzes.index
GET    /teacher/courses/{course}/modules/{module}/quizzes/create             -> teacher.courses.modules.quizzes.create
POST   /teacher/courses/{course}/modules/{module}/quizzes                    -> teacher.courses.modules.quizzes.store
GET    /teacher/courses/{course}/modules/{module}/quizzes/{quiz}          -> teacher.courses.modules.quizzes.show
GET    /teacher/courses/{course}/modules/{module}/quizzes/{quiz}/edit     -> teacher.courses.modules.quizzes.edit
PUT    /teacher/courses/{course}/modules/{module}/quizzes/{quiz}          -> teacher.courses.modules.quizzes.update
DELETE /teacher/courses/{course}/modules/{module}/quizzes/{quiz}          -> teacher.courses.modules.quizzes.destroy

// Quiz Questions
POST   /teacher/courses/{course}/modules/{module}/quizzes/{quiz}/questions          -> teacher.courses.modules.quizzes.questions.store
PUT    /teacher/courses/{course}/modules/{module}/quizzes/{quiz}/questions/{question} -> teacher.courses.modules.quizzes.questions.update
DELETE /teacher/courses/{course}/modules/{module}/quizzes/{quiz}/questions/{question} -> teacher.courses.modules.quizzes.questions.destroy
```

### 🔧 Admin Routes
```php
// Dashboard
GET /admin/dashboard     -> admin.dashboard

// User Management
GET    /admin/users              -> admin.users
GET    /admin/users/create       -> admin.users.create
POST   /admin/users              -> admin.users.store
GET    /admin/users/{user}/edit  -> admin.users.edit
PUT    /admin/users/{user}       -> admin.users.update
DELETE /admin/users/{user}       -> admin.users.destroy

// Course Management
GET    /admin/courses              -> admin.courses
GET    /admin/courses/create       -> admin.courses.create
POST   /admin/courses              -> admin.courses.store
GET    /admin/courses/{course}     -> admin.courses.show
GET    /admin/courses/{course}/edit -> admin.courses.edit
PUT    /admin/courses/{course}     -> admin.courses.update
DELETE /admin/courses/{course}     -> admin.courses.destroy

// Statistics
GET /admin/statistics      -> admin.statistics
```

---

## 🗃️ Database Commands

### 📊 การดูข้อมูลใน Database
```bash
# เข้า Tinker
php artisan tinker

# ดูข้อมูล User ทั้งหมด
User::all();

# ดูข้อมูลตาม role
User::where('role', 'student')->get();

# นับจำนวนผู้ใช้แบ่งตาม role
User::selectRaw('role, count(*) as count')->groupBy('role')->get();

# ดูคอร์สของครูคนใดคนหนึ่ง
User::find(1)->teachingCourses;

# ดูคอร์สที่นักเรียนลงทะเบียน
User::find(1)->enrolledCourses;

# ดู progress ของนักเรียนในคอร์สหนึ่ง
Course::find(1)->getProgressForStudent(1);

# ดูจำนวน lessons ทั้งหมดในคอร์ส
Course::find(1)->getTotalLessonsAttribute();

# ดูจำนวน lessons ที่เรียนเสร็จ
Course::find(1)->getCompletedLessonsCount(1);
```

### 🔧 การแก้ไขข้อมูล
```bash
# เปลี่ยน role ของผู้ใช้
User::where('email', 'user@example.com')->update(['role' => 'teacher']);

# อัพเดทข้อมูลคอร์ส
Course::find(1)->update(['title' => 'New Course Title']);

# ลบข้อมูล
User::find(1)->delete();

# ลบข้อมูลตามเงื่อนไข
User::where('email', 'user@example.com')->delete();
```

### 🔄 การสร้างข้อมูลตัวอย่าง
```bash
# สร้าง User ใหม่
User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'role' => 'student'
]);

# สร้าง Course ใหม่
Course::create([
    'teacher_id' => 1,
    'title' => 'Test Course',
    'description' => 'Test Description'
]);

# สร้าง Module ใหม่
Module::create([
    'course_id' => 1,
    'title' => 'Test Module',
    'order' => 1
]);
```

---

## 📁 File Management

### 🗂️ Storage Structure
```
storage/
├── app/
│   ├── public/              # Publicly accessible files
│   │   └── lessons/
│   │       └── pdf/         # Uploaded lesson files
│   │           ├── 1637234567_presentation.pdf
│   │           └── 1637234890_document.docx
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
└── storage -> ../storage/app/public  # Symbolic link
```

### 📤 File Upload Commands
```bash
# สร้าง symbolic link (ทำครั้งเดียว)
php artisan storage:link

# ตรวจสอบว่ามี symbolic link หรือไม่
ls -la public/storage

# ตั้งค่า permissions (ถ้าจำเป็น)
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 📝 File Upload Validation Rules
```php
// สำหรับ PDF/Documents
'file' => 'required|file|max:10240|mimes:pdf,doc,docx,ppt,pptx'
// max:10240 KB = 10MB

// สำหรับ Cover Images
'cover_image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
// max:2048 KB = 2MB
```

### 🗑️ การลบไฟล์
```bash
# ลบไฟล์จาก storage
Storage::disk('public')->delete('lessons/pdf/filename.pdf');

# ลบไฟล์เมื่อลบ record
public function destroy(Lesson $lesson)
{
    // ลบไฟล์ถ้ามี
    if ($lesson->content_url) {
        Storage::disk('public')->delete($lesson->content_url);
    }
    
    // ลบ record
    $lesson->delete();
    
    return redirect()->back()->with('success', 'Lesson deleted successfully');
}
```

---

## 🆘 Troubleshooting Quick Fixes

### 🚫 ปัญหา Authentication

#### Problem: Login ไม่ได้แม้กรอกข้อมูลถูกต้อง
```bash
# Solution 1: ล้าง session
php artisan session:clear

# Solution 2: ตรวจสอบ password hash
php artisan tinker
$user = User::where('email', 'test@example.com')->first();
$user->password = bcrypt('newpassword');
$user->save();

# Solution 3: ตรวจสอบ middleware
# ตรวจสอบว่า route มี middleware ที่ถูกต้องหรือไม่
```

#### Problem: ไม่สามารถ Register ได้
```bash
# Solution 1: ตรวจสอบ validation rules
# ใน app/Http/Controllers/Auth/RegisteredUserController.php

# Solution 2: ตรวจสอบ database connection
php artisan tinker
DB::connection()->getPdo();

# Solution 3: ตรวจสอบ email configuration
# ใน .env file
```

### 🖼️ ปัญหา File Upload

#### Problem: ไม่สามารถอัพโหลดไฟล์ได้
```bash
# Solution 1: ตรวจสอบ storage link
php artisan storage:link

# Solution 2: ตรวจสอบ permissions
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Solution 3: ตรวจสอบ php.ini
# upload_max_filesize = 20M
# post_max_size = 20M
# max_execution_time = 300
```

#### Problem: รูปภาพไม่แสดง
```bash
# Solution 1: ตรวจสอบ URL
# ใช้ asset() helper
{{ asset('storage/path/to/image.jpg') }}

# Solution 2: ตรวจสอบว่ามี symbolic link หรือไม่
readlink public/storage

# Solution 3: ล้าง cache
php artisan cache:clear
php artisan view:clear
```

### 🛣️ ปัญหา Routes

#### Problem: 404 Not Found
```bash
# Solution 1: ตรวจสอบ route list
php artisan route:list | grep "route-name"

# Solution 2: ล้าง route cache
php artisan route:clear

# Solution 3: ตรวจสอบ middleware
# ตรวจสอบว่ามี middleware ที่ปิดกั้นหรือไม่

# Solution 4: ตรวจสอบ HTTP method
# ตรวจสอบว่าใช้ GET/POST ถูกต้องหรือไม่
```

#### Problem: 403 Forbidden
```bash
# Solution 1: ตรวจสอบ role-based middleware
# ตรวจสอบว่ามี middleware ที่ถูกต้องหรือไม่

# Solution 2: ตรวจสอบ ownership
# สำหรับ Teacher: ตรวจสอบว่าเป็นเจ้าของ resource หรือไม่
if (auth()->id() !== $course->teacher_id) {
    abort(403, 'Unauthorized');
}

# Solution 3: ตรวจสอบ enrollment
# สำหรับ Student: ตรวจสอบว่าลงทะเบียนแล้วหรือไม่
if (!$course->isEnrolledByStudent(auth()->id())) {
    abort(403, 'Not enrolled');
}
```

### 🎨 ปัญหา Frontend

#### Problem: CSS/JS ไม่โหลด
```bash
# Solution 1: คอมไพล์ assets ใหม่
npm run build
# หรือ
npm run dev

# Solution 2: ล้าง cache
php artisan view:clear
php artisan cache:clear

# Solution 3: ตรวจสอบ manifest.json
# ใน public/build/manifest.json
```

#### Problem: Dark Mode ไม่ทำงาน
```bash
# Solution 1: ตรวจสอบ Alpine.js
# ตรวจสอบว่ามี script tag สำหรับ Alpine.js
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

# Solution 2: ตรวจสอบ JavaScript console
# ตรวจสอบว่ามี error ใน console หรือไม่

# Solution 3: ตรวจสอบ local storage
# ตรวจสอบว่า browser รองรับ local storage หรือไม่
```

---

## 👥 User Management

### 📊 การดูสถิติผู้ใช้
```bash
# จำนวนผู้ใช้ทั้งหมดแบ่งตาม role
User::selectRaw('role, count(*) as count')
    ->groupBy('role')
    ->get();
// Result: [{"role": "student", "count": 50}, {"role": "teacher", "count": 10}, {"role": "admin", "count": 2}]

# ผู้ใช้ที่สร้างในเดือนนี้
User::whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->count();

# ผู้ใช้ที่ login ล่าสุด (7 วัน)
User::where('last_login_at', '>=', now()->subDays(7))
    ->count();
```

### 🔧 การจัดการผู้ใช้
```bash
# เปลี่ยน role ของผู้ใช้
$user = User::find(1);
$user->role = 'teacher';
$user->save();

# รีเซ็ต password
$user = User::find(1);
$user->password = bcrypt('newpassword');
$user->save();

# ปิดใช้งานบัญชี (soft delete)
$user = User::find(1);
$user->delete();

# คืนค่าบัญชีที่ถูกลบ
User::withTrashed()->find(1)->restore();
```

### 📝 การสร้างผู้ใช้จาก Command Line
```bash
# สร้าง User ใหม่ผ่าน Tinker
php artisan tinker
User::create([
    'name' => 'Admin User',
    'email' => 'admin@ct.ac.th',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);

# สร้างหลาย Users
$roles = ['student', 'student', 'teacher'];
foreach ($roles as $role) {
    User::create([
        'name' => ucfirst($role) . ' ' . rand(1, 100),
        'email' => $role . rand(1, 100) . '@example.com',
        'password' => bcrypt('password'),
        'role' => $role
    ]);
}
```

---

## ⚠️ Common Issues & Solutions

### 🔐 Authentication Issues

| Issue | Cause | Solution |
|-------|--------|----------|
| Login fails with correct credentials | Password not hashed properly | Re-hash password: `bcrypt('password')` |
| Cannot register | Email already exists | Check email uniqueness in database |
| Redirect loops after login | Middleware conflict | Check middleware order in Kernel.php |
| Session expires quickly | Session lifetime too short | Set `SESSION_LIFETIME=120` in .env |

### 📁 File Upload Issues

| Issue | Cause | Solution |
|-------|--------|----------|
| File upload fails | File size too large | Increase `upload_max_filesize` in php.ini |
| Images not displaying | Missing storage link | Run `php artisan storage:link` |
| Permission denied | Wrong file permissions | `chmod -R 775 storage` |
| MIME type error | File type not allowed | Check validation rules |

### 🛣️ Route Issues

| Issue | Cause | Solution |
|-------|--------|----------|
| 404 Not Found | Route not defined | Check `php artisan route:list` |
| 403 Forbidden | Missing permission | Check role-based middleware |
| Method Not Allowed | Wrong HTTP method | Check form method attribute |
| CSRF Token Mismatch | Missing CSRF token | Add `@csrf` to forms |

### 🎨 Frontend Issues

| Issue | Cause | Solution |
|-------|--------|----------|
| CSS not loading | Assets not compiled | Run `npm run build` |
| JavaScript errors | Missing dependencies | Run `npm install` |
| Dark mode not working | Alpine.js not loaded | Check script tags |
| Responsive issues | CSS conflicts | Check Tailwind classes |

### 📊 Database Issues

| Issue | Cause | Solution |
|-------|--------|----------|
| Connection refused | Wrong database credentials | Check .env file |
| Table not found | Migration not run | Run `php artisan migrate` |
| SQL syntax error | Wrong query syntax | Check Eloquent syntax |
| N+1 query problem | Missing eager loading | Use `with()` method |

---

## 📞 Emergency Commands

### 🚨 การรีเซ็ตระบบ
```bash
# รีเซ็ตทั้งหมด (ใช้เฉพาะฉุกเฉิว)
php artisan migrate:fresh --seed
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan storage:link
```

### 🔐 การรีเซ็ต Admin Password
```bash
# สร้าง Admin ใหม่
php artisan tinker
User::create([
    'name' => 'Emergency Admin',
    'email' => 'admin@emergency.com',
    'password' => bcrypt('emergency123'),
    'role' => 'admin'
]);
```

### 📊 การตรวจสอบสถานะระบบ
```bash
# ตรวจสอบสถานะระบบทั้งหมด
php artisan about

# ตรวจสอบ environment
php artisan env

# ตรวจสอบ cache status
php artisan cache:status
```

---

## 📋 คำสั่งด่วนสำหรับแต่ละบทบาท

### 👨‍🎓 Student Quick Commands
```bash
# ดูคอร์สที่ลงทะเบียน
User::find(auth()->id())->enrolledCourses;

# ดู progress ทั้งหมด
User::find(auth()->id())->overall_progress;

# ตรวจสอบว่าเรียนจบคอร์สหรือยัง
Course::find(1)->getProgressForStudent(auth()->id()) == 100;
```

### 👨‍🏫 Teacher Quick Commands
```bash
# ดูคอร์สที่สอน
User::find(auth()->id())->teachingCourses;

# ดูจำนวนนักเรียนทั้งหมด
User::find(auth()->id())->teachingCourses()->withCount('enrollments')->get();

# สร้างคอร์สใหม่
Course::create([
    'teacher_id' => auth()->id(),
    'title' => 'New Course',
    'description' => 'Course Description'
]);
```

### 🔧 Admin Quick Commands
```bash
# ดูสถิติผู้ใช้
User::selectRaw('role, count(*) as count')->groupBy('role')->get();

# ดูคอร์สทั้งหมด
Course::with('teacher')->get();

# สร้างผู้ใช้ใหม่
User::create([
    'name' => 'New User',
    'email' => 'user@example.com',
    'password' => bcrypt('password'),
    'role' => 'student'
]);
```

---

## 📱 Mobile Quick Tips

### 📱 การใช้งานบนมือถือ
- **Touch-friendly**: ปุ่มมีขนาดพอเหมาะสำหรับการแตะ
- **Swipe Navigation**: ใช้การสไลด์สำหรับการเปลี่ยนหน้า
- **Offline Mode**: บางเนื้อหาสามารถเข้าถึงได้ offline
- **Push Notifications**: แจ้งเตือนเมื่อมีการอัพเดท

### 🔧 การแก้ไขปัญหาบนมือถือ
- **Clear Cache**: ล้าง cache ของ browser
- **Update App**: อัพเดทเป็นเวอร์ชันล่าสุด
- **Check Connection**: ตรวจสอบ internet connection
- **Restart App**: ปิดและเปิดแอปใหม่

---

## 🔍 Debugging Tips

### 🐛 การใช้ Debug Mode
```php
// เปิด debug mode ใน .env
APP_DEBUG=true

// แสดงข้อมูล debug
dd($variable); // Die and Dump
dump($variable); // Dump without dying

// แสดง SQL queries
DB::enableQueryLog();
// ... run queries
dd(DB::getQueryLog());
```

### 📝 การใช้ Log
```php
// เขียน log
use Illuminate\Support\Facades\Log;

Log::info('User logged in', ['user_id' => auth()->id()]);
Log::error('File upload failed', ['error' => $e->getMessage()]);
Log::debug('Debug information', ['data' => $data]);
```

### 🔍 การตรวจสอบ Performance
```bash
# ตรวจสอบ query performance
php artisan tinker
DB::enableQueryLog();
// ... run queries
$queries = DB::getQueryLog();
$totalTime = collect($queries)->sum('time');
echo "Total query time: {$totalTime}ms";
```

---

## 📞 การขอความช่วยเหลือ

### 🆘 ข้อมูลที่ต้องระบุเมื่อแจ้งปัญหา
1. **ชื่อ-นามสกุล** และ **Email**
2. **บทบาท** (Student/Teacher/Admin)
3. **ปัญหา** ที่พบอย่างละเอียด
4. **ขั้นตอน** การเกิดปัญหา
5. **Error Message** ที่ปรากฏ (ถ้ามี)
6. **Browser** และ **อุปกรณ์** ที่ใช้
7. **Screenshot** ของหน้าจอ (ถ้าจำเป็น)

### 📧 ช่องทางติดต่อ
- **Email**: support@ct.ac.th
- **Phone**: 02-xxx-xxxx
- **Line**: @ct-learning
- **GitHub Issues**: https://github.com/yourusername/ct-learning/issues

---

## 📚 เอกสารอ้างอิง

- [PROJECT-README.md](../PROJECT-README.md) - คู่มือหลัก
- [LMS Complete Guide](./LMS-COMPLETE-GUIDE.md) - คู่มือระบบครบถ้วน
- [Architecture Documentation](./ARCHITECTURE.md) - สถาปัตยกรรมระบบ
- [Routes Reference](./ROUTES-REFERENCE.md) - รายการ Routes ทั้งหมด
- [Documentation Index](./INDEX.md) - ดูเอกสารทั้งหมด

---

**สร้างเมื่อ**: 28 พฤศจิกายน 2025  
**เวอร์ชัน**: v1.0  
**ผู้เขียน**: CT Learning Team  
**สถานะ**: ✅ Complete & Updated  

---

<p align="center">
  <strong>📋 CT Learning - Quick Reference</strong><br>
  <em>Fast answers for busy users</em>
</p>