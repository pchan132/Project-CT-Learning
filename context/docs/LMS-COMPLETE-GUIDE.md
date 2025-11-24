# 📚 LMS System - Complete Documentation

## ระบบ LMS สำหรับแผนกเทคโนโลยีคอมพิวเตอร์

เอกสารนี้อธิบายการทำงานและวิธีแก้ไขของทุกระบบใน LMS

---

## 🗂️ สารบัญ

1. [ระบบ Authentication + Roles](#1-ระบบ-authentication--roles)
2. [ระบบ Courses + Enrollments](#2-ระบบ-courses--enrollments)
3. [ระบบ Modules + Lessons + Progress](#3-ระบบ-modules--lessons--progress)
4. [ระบบ Quiz + Scoring](#4-ระบบ-quiz--scoring)
5. [ระบบ Certificate PDF](#5-ระบบ-certificate-pdf)
6. [ระบบ Admin Management](#6-ระบบ-admin-management)
7. [การใช้งานระบบ](#7-การใช้งานระบบ)

---

## 1. ระบบ Authentication + Roles

### ✅ สิ่งที่มี:
- ระบบล็อกอิน/สมัครสมาชิก (Laravel Breeze)
- User Model มี 3 roles: `student`, `teacher`, `admin`
- Middleware: `StudentMiddleware`, `TeacherMiddleware`, `AdminMiddleware`
- Register แยกหน้าสำหรับ Student และ Teacher

### 📍 ไฟล์ที่เกี่ยวข้อง:
```
app/Models/User.php                          # User Model + role functions
app/Http/Middleware/StudentMiddleware.php     # Middleware สำหรับ Student
app/Http/Middleware/TeacherMiddleware.php     # Middleware สำหรับ Teacher  
app/Http/Middleware/AdminMiddleware.php       # Middleware สำหรับ Admin
app/Http/Kernel.php                          # ลงทะเบียน middleware
database/migrations/2014_10_12_000000_create_users_table.php
```

### 🛠️ วิธีแก้ไข:

#### เพิ่ม Role ใหม่:
1. แก้ไข migration `create_users_table.php` เพิ่ม role ใหม่
2. สร้าง Middleware ใหม่: `php artisan make:middleware NewRoleMiddleware`
3. ลงทะเบียนใน `app/Http/Kernel.php`
4. เพิ่มฟังก์ชันใน `User.php`:
```php
public function isNewRole(): bool {
    return $this->role === 'new_role';
}
```

#### เปลี่ยน Default Role:
แก้ใน migration:
```php
$table->string('role')->default('student'); // เปลี่ยนเป็น role ที่ต้องการ
```

---

## 2. ระบบ Courses + Enrollments

### ✅ สิ่งที่มี:
- CRUD Courses โดย Teacher
- Upload รูป Cover Image
- Student ลงทะเบียนเรียน (Enroll/Unenroll)
- หน้า Course Catalog และ My Courses

### 📍 ไฟล์ที่เกี่ยวข้อง:
```
app/Models/Course.php                        # Course Model
app/Models/Enrollment.php                    # Enrollment Model
app/Http/Controllers/Teacher/CourseController.php    # Teacher CRUD
app/Http/Controllers/Student/CourseController.php    # Student enroll/view
database/migrations/2025_11_21_123634_create_courses_table.php
database/migrations/2025_11_21_123830_create_enrollments_table.php
routes/web.php                               # Routes สำหรับ courses
```

### 🛠️ วิธีแก้ไข:

#### เพิ่มฟิลด์ใน Course:
1. สร้าง migration ใหม่:
```bash
php artisan make:migration add_new_field_to_courses_table
```
2. เพิ่มฟิลด์:
```php
$table->string('new_field')->nullable();
```
3. เพิ่มใน `$fillable` ใน `Course.php`
4. แก้ไข Form ใน View และ Controller

#### เปลี่ยนเงื่อนไขการ Enroll:
แก้ใน `app/Http/Controllers/Student/CourseController.php` method `enroll()`:
```php
// เพิ่มเงื่อนไขตรวจสอบ
if ($course->is_premium && !auth()->user()->isPremium()) {
    return back()->with('error', 'คุณต้องเป็นสมาชิก Premium');
}
```

---

## 3. ระบบ Modules + Lessons + Progress

### ✅ สิ่งที่มี:
- Teacher สร้าง/แก้ไข Modules และ Lessons
- Lesson รองรับ 4 ประเภท: TEXT, VIDEO, PDF, PPT
- Upload ไฟล์ PDF/PPT ไปยัง storage
- Student Mark Complete Lesson
- คำนวณ Progress % แบบ Real-time

### 📍 ไฟล์ที่เกี่ยวข้อง:
```
app/Models/Module.php
app/Models/Lesson.php
app/Models/LessonCompletion.php
app/Http/Controllers/Teacher/ModuleController.php
app/Http/Controllers/Teacher/LessonController.php
app/Http/Controllers/Student/CourseController.php  # learnLesson(), completeLesson()
database/migrations/2025_11_23_021024_create_modules_table.php
database/migrations/2025_11_23_021029_create_lessons_table.php
database/migrations/2025_11_23_021033_create_lesson_completions_table.php
```

### 🛠️ วิธีแก้ไข:

#### เพิ่มประเภท Content ใหม่:
1. แก้ใน `Lesson.php`:
```php
public function isNewContent() {
    return $this->content_type === 'NEW_TYPE';
}
```
2. แก้ไข Form ใน View เพิ่มตัวเลือก
3. แก้ไข Validation ใน Controller

#### เปลี่ยนการคำนวณ Progress:
แก้ใน `Course.php` method `getProgressForStudent()`:
```php
// ปรับสูตรคำนวณตามต้องการ
return round(($completedLessons / $totalLessons) * 100, 2);
```

---

## 4. ระบบ Quiz + Scoring

### ✅ สิ่งที่มี:
- Teacher สร้าง Quiz ใน Module
- เพิ่มคำถามพร้อมตัวเลือก (Multiple Choice)
- กำหนดคะแนนผ่าน (passing_score)
- Student ทำ Quiz และดูผลคะแนน
- บันทึก Quiz Attempts

### 📍 ไฟล์ที่เกี่ยวข้อง:
```
app/Models/Quiz.php
app/Models/Question.php
app/Models/Answer.php
app/Models/QuizAttempt.php
app/Http/Controllers/Teacher/QuizController.php
app/Http/Controllers/Teacher/QuestionController.php
app/Http/Controllers/Student/QuizController.php
database/migrations/2025_11_24_190419_create_quizzes_table.php
database/migrations/2025_11_24_190426_create_questions_table.php
database/migrations/2025_11_24_190445_create_answers_table.php
database/migrations/2025_11_24_190451_create_quiz_attempts_table.php
routes/web.php  # Quiz routes
```

### 🛠️ วิธีแก้ไข:

#### เปลี่ยนวิธีคำนวณคะแนน:
แก้ใน `app/Http/Controllers/Student/QuizController.php` method `submit()`:
```php
// ปรับสูตรคำนวณ
$score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;
```

#### เพิ่มประเภทคำถาม (True/False, Essay):
1. เพิ่มฟิลด์ `question_type` ใน `questions` table
2. แก้ไข `Question.php` Model
3. แก้ไข Controller และ View ให้รองรับ

#### จำกัดจำนวนครั้งการทำ Quiz:
แก้ใน `Student/QuizController.php`:
```php
$attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
    ->where('student_id', auth()->id())
    ->count();
    
if ($attemptCount >= 3) {
    return back()->with('error', 'คุณทำแบบทดสอบครบ 3 ครั้งแล้ว');
}
```

---

## 5. ระบบ Certificate PDF

### ✅ สิ่งที่มี:
- สร้าง Certificate เมื่อ Student เรียนครบ + ผ่าน Quiz ทั้งหมด
- Generate PDF ด้วย DomPDF
- บันทึก PDF ไปยัง storage
- เลข Certificate Number unique

### 📍 ไฟล์ที่เกี่ยวข้อง:
```
app/Models/Certificate.php
app/Http/Controllers/Student/CertificateController.php
database/migrations/2025_11_24_191338_create_certificates_table.php
resources/views/certificates/template.blade.php  # Template PDF (ต้องสร้าง)
config/dompdf.php  # Config DomPDF
```

### 🛠️ วิธีแก้ไข:

#### เปลี่ยนเงื่อนไขการได้ Certificate:
แก้ใน `CertificateController.php` method `canGetCertificate()`:
```php
// เพิ่มเงื่อนไขใหม่
if ($course->requires_final_exam && !$student->passedFinalExam($course)) {
    return false;
}
```

#### เปลี่ยนรูปแบบ Certificate Number:
แก้ใน `Certificate.php` method `generateCertificateNumber()`:
```php
return "CT-{$year}-{$random}"; // เปลี่ยนรูปแบบตามต้องการ
```

#### แก้ไข Template PDF:
สร้าง/แก้ไข `resources/views/certificates/template.blade.php`:
```blade
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        .certificate { border: 5px solid gold; padding: 50px; }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>ใบประกาศนียบัตร</h1>
        <p>มอบให้แก่: {{ $student->name }}</p>
        <p>คอร์ส: {{ $course->title }}</p>
        <p>เลขที่: {{ $certificate->certificate_number }}</p>
    </div>
</body>
</html>
```

---

## 6. ระบบ Admin Management

### ✅ สิ่งที่มี:
- Admin Dashboard with Statistics
- จัดการ Users (Create/Edit/Delete)
- เปลี่ยน Role ของ User
- Statistics หน้าสถิติ

### 📍 ไฟล์ที่เกี่ยวข้อง:
```
app/Http/Controllers/Admin/AdminController.php
app/Http/Middleware/AdminMiddleware.php
routes/web.php  # Admin routes
resources/views/admin/  # Admin views (ต้องสร้าง)
```

### 🛠️ วิธีแก้ไข:

#### เพิ่มการจัดการ Courses โดย Admin:
1. เพิ่ม methods ใน `AdminController.php`:
```php
public function courses() {
    $courses = Course::with('teacher')->paginate(20);
    return view('admin.courses.index', compact('courses'));
}

public function deleteCourse(Course $course) {
    $course->delete();
    return back()->with('success', 'ลบคอร์สสำเร็จ');
}
```
2. เพิ่ม Routes ใน `web.php`
3. สร้าง Views

#### เพิ่ม Statistics ใหม่:
แก้ใน `AdminController.php` method `statistics()`:
```php
$stats['quiz_completion_rate'] = QuizAttempt::where('passed', true)->count() 
    / QuizAttempt::count() * 100;
```

---

## 7. การใช้งานระบบ

### 🔐 บัญชีทดสอบ:
```
Admin:    admin@ct.ac.th / password
Teacher1: teacher1@ct.ac.th / password
Teacher2: teacher2@ct.ac.th / password
Student1: student1@ct.ac.th / password
Student2-5: student2@ct.ac.th - student5@ct.ac.th / password
```

### 📖 User Manual สำหรับแต่ละ Role:

#### สำหรับ Admin:
1. Login ที่ `/login`
2. เข้า Dashboard: `/admin/dashboard`
3. จัดการ Users: `/admin/users`
4. ดูสถิติ: `/admin/statistics`

#### สำหรับ Teacher:
1. Login ที่ `/login`
2. เข้า Dashboard: `/teacher/dashboard`
3. สร้างคอร์ส: กด "Create Course"
4. เพิ่ม Module: เข้าคอร์ส > "Add Module"
5. เพิ่ม Lesson: เข้า Module > "Add Lesson"
6. สร้าง Quiz: เข้า Module > "Create Quiz" > "Add Questions"

#### สำหรับ Student:
1. Login ที่ `/login`
2. เข้า Dashboard: `/student/dashboard`
3. ดูคอร์สทั้งหมด: "Browse Courses"
4. ลงทะเบียน: กด "Enroll" ในคอร์สที่ต้องการ
5. เรียน: เข้า "My Courses" > เลือกคอร์ส > เลือก Lesson
6. ทำ Quiz: คลิกที่ Quiz ใน Module
7. ขอ Certificate: เรียนครบทุก Lesson + ผ่าน Quiz ทุกบท > กด "Get Certificate"

---

## 🚀 การติดตั้งและใช้งาน

### Requirements:
- PHP >= 8.1
- MySQL
- Composer
- Node.js & NPM

### ขั้นตอน:
```bash
# 1. Clone & Install
composer install
npm install

# 2. Setup Environment
cp .env.example .env
php artisan key:generate

# 3. Config Database ใน .env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 4. Run Migration + Seed
php artisan migrate:fresh --seed

# 5. Create Storage Link
php artisan storage:link

# 6. Compile Assets
npm run dev

# 7. Run Server
php artisan serve
```

---

## 📝 การ Deploy

### Production Checklist:
- [ ] เปลี่ยน `APP_ENV=production` ใน `.env`
- [ ] เปลี่ยน `APP_DEBUG=false`
- [ ] ตั้งค่า `APP_URL` ให้ถูกต้อง
- [ ] รัน `npm run build` แทน `npm run dev`
- [ ] ตั้งค่า Email สำหรับ Password Reset
- [ ] Backup Database เป็นประจำ
- [ ] ตั้งค่า SSL Certificate (HTTPS)

---

## 🆘 Troubleshooting

### ปัญหา Storage ไม่แสดงรูป:
```bash
php artisan storage:link
```

### ปัญหา Permission Denied:
```bash
chmod -R 775 storage bootstrap/cache
```

### ปัญหา Migration Error:
```bash
php artisan migrate:fresh --seed
```

### ปัญหา PDF ภาษาไทยไม่แสดง:
ติดตั้ง Font ไทยใน DomPDF config

---

## 📞 Support

หากมีปัญหาหรือต้องการความช่วยเหลือ กรุณาติดต่อ:
- Email: support@ct.ac.th
- เอกสารเพิ่มเติม: [ARCHITECTURE.md](./ARCHITECTURE.md)

---

**เอกสารนี้สร้างเมื่อ:** 24 พฤศจิกายน 2025  
**เวอร์ชัน:** 1.0
