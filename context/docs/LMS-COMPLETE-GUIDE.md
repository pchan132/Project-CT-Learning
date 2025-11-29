# 📚 CT Learning - LMS Complete Guide

## 🎯 คู่มือระบบ LMS ครบถ้วน

เอกสารนี้คือคู่มือการใช้งานระบบ Learning Management System (LMS) ของ CT Learning อย่างละเอียด ครอบคลุมทุกฟีเจอร์และวิธีการใช้งานสำหรับทุกบทบาทผู้ใช้

---

## 📋 สารบัญ

1. [ภาพรวมระบบ](#ภาพรวมระบบ)
2. [ระบบ Authentication + Roles](#ระบบ-authentication--roles)
3. [ระบบการจัดการคอร์สเรียน](#ระบบการจัดการคอร์สเรียน)
4. [ระบบการจัดการเนื้อหา](#ระบบการจัดการเนื้อหา)
5. [ระบบการประเมินผล](#ระบบการประเมินผล)
6. [ระบบใบประกาศนียบัตร](#ระบบใบประกาศนียบัตร)
7. [ระบบติดตามความคืบหน้า](#ระบบติดตามความคืบหน้า)
8. [ระบบผู้ดูแลระบบ](#ระบบผู้ดูแลระบบ)
9. [การใช้งานแบบ Step-by-Step](#การใช้งานแบบ-step-by-step)
10. [การแก้ไขปัญหา](#การแก้ไขปัญหา)

---

## 🎯 ภาพรวมระบบ

CT Learning เป็นระบบ Learning Management System (LMS) ที่ออกแบบมาให้ครบวงจรสำหรับการจัดการการเรียนการสอนออนไลน์ มีผู้ใช้ 3 บทบาทหลักคือ Student, Teacher และ Admin

### 🏗️ สถาปัตยกรรมระบบ
```
┌─────────────────────────────────────────────────────────────┐
│                    User Layer                            │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐      │
│  │   Student   │ │   Teacher   │ │    Admin    │      │
│  └─────────────┘ └─────────────┘ └─────────────┘      │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                   Application Layer                        │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐      │
│  │   Courses   │ │   Lessons   │ │    Quiz     │      │
│  │ Management  │ │ Management  │ │  System     │      │
│  └─────────────┘ └─────────────┘ └─────────────┘      │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│                    Data Layer                             │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐      │
│  │    Users    │ │   Courses   │ │  Lessons    │      │
│  │    Roles    │ │  Modules    │ │  Quizzes    │      │
│  └─────────────┘ └─────────────┘ └─────────────┘      │
└─────────────────────────────────────────────────────────────┘
```

### 🔄 Workflow การทำงาน
```
Student:   Register → Enroll → Learn → Test → Certificate
Teacher:   Login → Create → Manage → Monitor → Grade
Admin:      Login → Manage → Configure → Report → Maintain
```

---

## 🔐 ระบบ Authentication + Roles

### 📋 ภาพรวม
ระบบยืนยันตัวตนและการจัดการสิทธิ์ผู้ใช้แบบ Multi-role โดยใช้ Laravel Breeze

### 👥 บทบาทผู้ใช้ (User Roles)

#### 1. Student (นักเรียน)
- **สิทธิ์**: ลงทะเบียนเรียน, เข้าเรียนบทเรียน, ทำแบบทดสอบ, ขอใบประกาศนียบัตร
- **หน้าหลัก**: `/student/dashboard`
- **การลงทะเบียน**: `/register/student`

#### 2. Teacher (ครูผู้สอน)
- **สิทธิ์**: สร้าง/จัดการคอร์สเรียน, สร้างบทเรียน, สร้างแบบทดสอบ, ดูผลนักเรียน
- **หน้าหลัก**: `/teacher/dashboard`
- **การลงทะเบียน**: `/register/teacher`

#### 3. Admin (ผู้ดูแลระบบ)
- **สิทธิ์**: จัดการผู้ใช้ทั้งหมด, จัดการคอร์สเรียน, ดูสถิติ, ตั้งค่าระบบ
- **หน้าหลัก**: `/admin/dashboard`
- **การลงทะเบียน**: สร้างโดย Admin เท่านั้น

### 🔧 การทำงานของระบบ

#### Registration Process
```
1. User เลือกประเภท (Student/Teacher)
2. กรอกข้อมูลส่วนตัว (Name, Email, Password)
3. ระบบตรวจสอบความถูกต้องของข้อมูล
4. สร้าง User record พร้อม role ที่เลือก
5. Redirect ไปยัง Dashboard ตาม role
```

#### Authentication Flow
```
1. User กรอก Email และ Password
2. ระบบตรวจสอบข้อมูลกับ Database
3. ถูกต้อง → สร้าง Session และ Redirect ไป Dashboard
4. ไม่ถูกต้อง → แสดง Error message
5. Middleware ตรวจสอบสิทธิ์ในทุก request
```

#### Authorization Middleware
```php
// StudentMiddleware - ตรวจสอบว่าเป็น Student
if (auth()->user()->role !== 'student') {
    abort(403, 'Access denied. Student role required.');
}

// TeacherMiddleware - ตรวจสอบว่าเป็น Teacher
if (auth()->user()->role !== 'teacher') {
    abort(403, 'Access denied. Teacher role required.');
}

// AdminMiddleware - ตรวจสอบว่าเป็น Admin
if (auth()->user()->role !== 'admin') {
    abort(403, 'Access denied. Admin role required.');
}
```

### 📍 ไฟล์ที่เกี่ยวข้อง
```
app/Models/User.php                          # User Model พร้อม role functions
app/Http/Middleware/StudentMiddleware.php     # Middleware สำหรับ Student
app/Http/Middleware/TeacherMiddleware.php     # Middleware สำหรับ Teacher
app/Http/Middleware/AdminMiddleware.php       # Middleware สำหรับ Admin
app/Http/Controllers/Auth/RegisteredUserController.php # Registration Controller
routes/auth.php                             # Authentication routes
routes/web.php                              # Dashboard redirect routes
```

### 🛠️ การปรับแต่ง

#### เพิ่ม Role ใหม่
1. แก้ไข migration `create_users_table.php`
2. สร้าง Middleware ใหม่
3. ลงทะเบียนใน `app/Http/Kernel.php`
4. เพิ่มฟังก์ชันใน `User.php`

#### เปลี่ยน Default Role
แก้ใน migration:
```php
$table->string('role')->default('student');
```

---

## 📚 ระบบการจัดการคอร์สเรียน

### 📋 ภาพรวม
ระบบจัดการคอร์สเรียนสำหรับครูผู้สอน รองรับการสร้าง แก้ไข ลบ คอร์สเรียน พร้อมรูปภาพปก

### 🎯 ฟีเจอร์หลัก
- ✅ **CRUD Operations**: สร้าง อ่าน แก้ไข ลบ คอร์สเรียน
- ✅ **Cover Image Upload**: อัพโหลดรูปปกคอร์สเรียน
- ✅ **Course Enrollment**: ระบบลงทะเบียนเรียน
- ✅ **Course Catalog**: แสดงรายการคอร์สทั้งหมด
- ✅ **Ownership Control**: ครูเห็นเฉพาะคอร์สของตัวเอง

### 🔄 การทำงานของระบบ

#### สำหรับครูผู้สอน
```
1. Login → Teacher Dashboard
2. คลิก "Create Course"
3. กรอกข้อมูลคอร์ส:
   - Title (ชื่อคอร์ส)
   - Description (คำอธิบาย)
   - Cover Image (รูปปก)
4. บันทึกคอร์ส → แสดงใน Course List
5. สามารถแก้ไข/ลบ คอร์สได้
```

#### สำหรับนักเรียน
```
1. Login → Student Dashboard
2. ดู Course Catalog → เลือกคอร์สที่สนใจ
3. อ่านรายละเอียดคอร์ส
4. คลิก "Enroll" → ลงทะเบียนเรียน
5. คอร์สปรากฏใน "My Courses"
```

### 📊 โครงสร้างข้อมูล

#### Courses Table
```sql
CREATE TABLE courses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    cover_image_url VARCHAR(500) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Enrollments Table
```sql
CREATE TABLE enrollments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    enrolled_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, course_id)
);
```

### 📍 ไฟล์ที่เกี่ยวข้อง
```
app/Models/Course.php                        # Course Model
app/Models/Enrollment.php                    # Enrollment Model
app/Http/Controllers/Teacher/CourseController.php # Teacher CRUD
app/Http/Controllers/Student/CourseController.php # Student enroll/view
resources/views/teacher/courses/           # Teacher Views
resources/views/student/courses/           # Student Views
```

### 🛠️ การปรับแต่ง

#### เพิ่มฟิลด์ใหม่ใน Course
1. สร้าง migration:
```bash
php artisan make:migration add_new_fields_to_courses_table
```

2. เพิ่มฟิลด์:
```php
$table->string('category')->nullable();
$table->decimal('price', 8, 2)->nullable();
$table->boolean('is_active')->default(true);
```

3. อัพเดท Model และ Views

#### จำกัดจำนวนนักเรียนต่อคอร์ส
แก้ใน `Student/CourseController.php`:
```php
public function enroll(Course $course)
{
    $enrolledCount = $course->enrollments()->count();
    
    if ($enrolledCount >= $course->max_students) {
        return back()->with('error', 'คอร์สเต็มแล้ว');
    }
    
    // enrollment logic
}
```

---

## 📖 ระบบการจัดการเนื้อหา

### 📋 ภาพรวม
ระบบจัดการเนื้อหาการสอนแบบ Nested Structure (Course → Modules → Lessons) รองรับหลายรูปแบบเนื้อหา

### 🏗️ โครงสร้างเนื้อหา
```
Course
├── Module 1
│   ├── Lesson 1.1
│   ├── Lesson 1.2
│   └── Lesson 1.3
├── Module 2
│   ├── Lesson 2.1
│   └── Lesson 2.2
└── Module 3
    ├── Lesson 3.1
    └── Lesson 3.2
```

### 🎯 ประเภทเนื้อหาที่รองรับ

#### 1. PDF Documents 📄
- **รูปแบบที่รองรับ**: PDF, DOC, DOCX, PPT, PPTX
- **ขนาดสูงสุด**: 10MB
- **การจัดเก็บ**: `storage/app/public/lessons/pdf/`
- **การแสดงผล**: `<embed>` tag ใน browser

#### 2. Video Content 🎥
- **แหล่งที่มา**: YouTube เท่านั้น
- **รูปแบบ URL**: `youtube.com/watch?v=ID` หรือ `youtu.be/ID`
- **การแปลง**: Auto-convert เป็น embed URL
- **การแสดงผล**: `<iframe>` YouTube embed

#### 3. Text Articles 📝
- **รูปแบบ**: Plain text พร้อม line breaks
- **การจัดรูปแบบ**: ใช้ `nl2br()` สำหรับขึ้นบรรทัดใหม่
- **ความยาว**: ไม่จำกัด
- **การแสดงผล**: Text ใน `<div>` พร้อม styling

#### 4. Google Drive Content 🌐
- **ประเภท**: Google Docs, Sheets, Slides, Files
- **รูปแบบ URL**: Google Drive share links
- **การแปลง**: Auto-convert เป็น embed/preview URL
- **การแสดงผล**: `<iframe>` Google embed

#### 5. Canva Content 🎨
- **แหล่งที่มา**: Canva designs
- **รูปแบบ URL**: Canva design URLs
- **การแปลง**: Auto-convert เป็น embed URL
- **การแสดงผล**: `<iframe>` Canva embed

### 🔄 การทำงานของระบบ

#### สำหรับครูผู้สอน
```
1. เข้า Course → คลิก "Manage Modules"
2. สร้าง Module:
   - กรอก Title, Description
   - กำหนด Order (ลำดับ)
3. เข้า Module → คลิก "Add Lesson"
4. สร้าง Lesson:
   - เลือก Content Type
   - กรอก Title
   - เพิ่ม Content (ตามประเภท)
5. บันทึก → แสดงใน Lesson List
```

#### สำหรับนักเรียน
```
1. เข้า Course → ดูรายการ Modules
2. เลือก Module → ดูรายการ Lessons
3. คลิก "Start Learning" → เข้าเรียน Lesson
4. ระบบแสดง Content ตามประเภท:
   - PDF: Document viewer
   - Video: YouTube player
   - Text: Article view
   - Google Drive: Google embed
   - Canva: Canva embed
5. คลิก "Mark as Complete" → บันทึกความคืบหน้า
```

### 📊 โครงสร้างข้อมูล

#### Modules Table
```sql
CREATE TABLE modules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    order INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);
```

#### Lessons Table
```sql
CREATE TABLE lessons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content_type ENUM('PDF', 'VIDEO', 'TEXT', 'GDRIVE', 'CANVA') NOT NULL,
    content_url VARCHAR(500) NULL,
    content_text TEXT NULL,
    order INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);
```

#### Lesson Completions Table
```sql
CREATE TABLE lesson_completions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_completion (lesson_id, user_id)
);
```

### 📍 ไฟล์ที่เกี่ยวข้อง
```
app/Models/Module.php                      # Module Model
app/Models/Lesson.php                      # Lesson Model
app/Models/LessonCompletion.php            # Lesson Completion Model
app/Http/Controllers/Teacher/ModuleController.php # Module CRUD
app/Http/Controllers/Teacher/LessonController.php # Lesson CRUD
app/Http/Controllers/Student/CourseController.php # Learning logic
resources/views/teacher/modules/           # Module Views
resources/views/teacher/lessons/           # Lesson Views
resources/views/student/lessons/           # Learning Views
```

### 🛠️ การปรับแต่ง

#### เพิ่มประเภท Content ใหม่
1. แก้ไข migration `lessons` table
2. อัพเดท `Lesson.php` Model
3. แก้ไข Form ใน Views
4. อัพเดท Controller logic

#### จำกัดขนาดไฟล์
แก้ใน `LessonController.php`:
```php
'file' => 'required|file|max:10240|mimes:pdf,doc,docx,ppt,pptx'
// max:10240 KB = 10MB
```

#### เพิ่ม Rich Text Editor
ติดตั้งและใช้ Quill.js หรือ TinyMCE สำหรับ Text content

---

## 📝 ระบบการประเมินผล

### 📋 ภาพรวม
ระบบแบบทดสอบ (Quiz) สำหรับประเมินความเข้าใจของนักเรียน รองรับ Multiple Choice พร้อม Auto-grading

### 🎯 ฟีเจอร์หลัก
- ✅ **Quiz Creation**: สร้างแบบทดสอบในแต่ละ Module
- ✅ **Multiple Choice**: คำถามพร้อมตัวเลือกคำตอบ
- ✅ **Timer Support**: กำหนดเวลาในการทำแบบทดสอบ
- ✅ **Auto-grading**: ตรวจและคำนวณคะแนนอัตโนมัติ
- ✅ **Passing Score**: กำหนดคะแนนผ่านต่อแบบทดสอบ
- ✅ **Attempt Tracking**: บันทึกประวัติการทำแบบทดสอบ
- ✅ **Results Display**: แสดงผลลัพธ์พร้อมสถิติ

### 🔄 การทำงานของระบบ

#### สำหรับครูผู้สอน
```
1. เข้า Module → คลิก "Create Quiz"
2. สร้าง Quiz:
   - Title (ชื่อแบบทดสอบ)
   - Description (คำอธิบาย)
   - Time Limit (เวลาจำกัด นาที)
   - Passing Score (คะแนนผ่าน %)
3. เพิ่มคำถาม:
   - Question Text (โจทย์)
   - Multiple Choice Options (ตัวเลือก)
   - Mark Correct Answer (กำหนดคำตอบถูก)
4. จัดลำดับคำถาม
5. บันทึก Quiz → พร้อมให้นักเรียนทำ
```

#### สำหรับนักเรียน
```
1. เข้า Module → คลิก Quiz
2. อ่านรายละเอียด Quiz
3. คลิก "Start Quiz" → เริ่มจับเวลา
4. ตอบคำถาม:
   - เลือกคำตอบสำหรับแต่ละข้อ
   - สามารถกลับมาแก้ไขได้
5. คลิก "Submit Quiz" → ส่งคำตอบ
6. ระบบตรวจคำตอบและคำนวณคะแนน
7. แสดงผลลัพธ์:
   - คะแนนที่ได้
   - เปอร์เซ็นต์
   - ผลการผ่าน/ไม่ผ่าน
   - คำตอบที่ถูกต้อง
```

### 📊 โครงสร้างข้อมูล

#### Quizzes Table
```sql
CREATE TABLE quizzes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    passing_score DECIMAL(5,2) DEFAULT 70.00,
    time_limit INT NULL, -- minutes
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);
```

#### Questions Table
```sql
CREATE TABLE questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quiz_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    order INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);
```

#### Answers Table
```sql
CREATE TABLE answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id BIGINT UNSIGNED NOT NULL,
    answer_text TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    order INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);
```

#### Quiz Attempts Table
```sql
CREATE TABLE quiz_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quiz_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    passed BOOLEAN DEFAULT FALSE,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 📍 ไฟล์ที่เกี่ยวข้อง
```
app/Models/Quiz.php                         # Quiz Model
app/Models/Question.php                     # Question Model
app/Models/Answer.php                       # Answer Model
app/Models/QuizAttempt.php                  # Quiz Attempt Model
app/Http/Controllers/Teacher/QuizController.php # Quiz CRUD
app/Http/Controllers/Student/QuizController.php # Quiz Taking
resources/views/teacher/quizzes/            # Quiz Management Views
resources/views/student/quizzes/            # Quiz Taking Views
```

### 🛠️ การปรับแต่ง

#### เพิ่มประเภทคำถาม (True/False, Essay)
1. เพิ่มฟิลด์ `question_type` ใน `questions` table
2. แก้ไข Question Model
3. อัพเดท Views สำหรับแต่ละประเภท
4. แก้ไข grading logic

#### จำกัดจำนวนครั้งการทำ Quiz
แก้ใน `Student/QuizController.php`:
```php
public function start(Quiz $quiz)
{
    $attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
        ->where('student_id', auth()->id())
        ->count();
        
    if ($attemptCount >= 3) {
        return back()->with('error', 'ทำแบบทดสอบได้สูงสุด 3 ครั้ง');
    }
    
    // start quiz logic
}
```

#### เพิ่ม Random Question Order
แก้ใน Quiz taking logic:
```php
$questions = $quiz->questions()->with('answers')
    ->inRandomOrder()
    ->get();
```

---

## 🎓 ระบบใบประกาศนียบัตร

### 📋 ภาพรวม
ระบบออกใบประกาศนียบัตร PDF อัตโนมัติเมื่อนักเรียนเรียนครบคอร์สและผ่านแบบทดสอบทั้งหมด

### 🎯 ฟีเจอร์หลัก
- ✅ **Automatic Generation**: สร้าง PDF อัตโนมัติ
- ✅ **Unique Certificate Numbers**: เลขที่อ้างอิงไม่ซ้ำกัน
- ✅ **Professional Templates**: รูปแบบเอกสารสวยงาม
- ✅ **Download & Share**: ดาวน์โหลดและแชร์ได้
- ✅ **Verification System**: ตรวจสอบความถูกต้องได้
- ✅ **PDF Storage**: จัดเก็บ PDF ในระบบ

### 🔄 การทำงานของระบบ

#### เงื่อนไขการได้รับ Certificate
```
1. เรียนครบทุกบทเรียนในคอร์ส
   - ตรวจสอบจาก lesson_completions table
   - คำนวณ progress = 100%

2. ผ่านแบบทดสอบทุกบทในคอร์ส
   - ตรวจสอบจาก quiz_attempts table
   - score >= passing_score ทุก quiz

3. ยังไม่เคยได้รับ Certificate นี้มาก่อน
   - ตรวจสอบจาก certificates table
```

#### การสร้าง Certificate
```
1. นักเรียนคลิก "Get Certificate"
2. ระบบตรวจสอบเงื่อนไข
3. ถ้าผ่าน → สร้าง Certificate:
   - สร้าง Certificate Number (unique)
   - บันทึกลงฐานข้อมูล
   - Generate PDF จาก template
   - บันทึก PDF ลง storage
4. แสดงปุ่ม Download
5. ส่ง Email notification (ถ้ามี)
```

### 📊 โครงสร้างข้อมูล

#### Certificates Table
```sql
CREATE TABLE certificates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    certificate_number VARCHAR(50) UNIQUE NOT NULL,
    issued_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    pdf_path VARCHAR(500) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);
```

### 🎨 Certificate Template
ใช้ Blade template สำหรับสร้าง PDF:

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ใบประกาศนียบัตร</title>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .certificate {
            background: white;
            border: 10px solid #gold;
            padding: 50px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            margin-bottom: 30px;
        }
        .title {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 40px;
        }
        .recipient {
            font-size: 28px;
            color: #34495e;
            margin: 30px 0;
            font-weight: bold;
        }
        .course-info {
            font-size: 20px;
            color: #2c3e50;
            margin: 20px 0;
        }
        .certificate-number {
            font-size: 16px;
            color: #7f8c8d;
            margin-top: 40px;
        }
        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-block {
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 10px auto;
        }
        .signature-title {
            font-size: 14px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div class="title">ใบประกาศนียบัตร</div>
            <div class="subtitle">Certificate of Completion</div>
        </div>
        
        <div class="recipient">
            มอบให้แก่ {{ $student->name }}
        </div>
        
        <div class="course-info">
            ได้เข้าร่วมอบรมคอร์สเรียน<br>
            <strong>{{ $course->title }}</strong><br>
            และผ่านการประเมินผลการเรียนเรียบร้อย
        </div>
        
        <div class="certificate-number">
            เลขที่: {{ $certificate->certificate_number }}<br>
            ออกให้วันที่: {{ $certificate->issued_at->format('d/m/Y') }}
        </div>
        
        <div class="signature">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-title">ครูผู้สอน</div>
                <div>{{ $course->teacher->name }}</div>
            </div>
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-title">ผู้อำนวยการ</div>
                <div>ผู้อำนวยการแผนกเทคโนโลยีคอมพิวเตอร์</div>
            </div>
        </div>
    </div>
</body>
</html>
```

### 📍 ไฟล์ที่เกี่ยวข้อง
```
app/Models/Certificate.php                 # Certificate Model
app/Http/Controllers/Student/CertificateController.php # Certificate Controller
resources/views/certificates/template.blade.php # PDF Template
config/dompdf.php                          # DomPDF Configuration
```

### 🛠️ การปรับแต่ง

#### เปลี่ยนรูปแบบ Certificate Number
แก้ใน `Certificate.php`:
```php
public static function generateCertificateNumber()
{
    $year = date('Y');
    $random = strtoupper(substr(md5(uniqid()), 0, 6));
    return "CT-{$year}-{$random}";
}
```

#### เพิ่ม QR Code สำหรับ Verification
ติดตั้ง package และเพิ่มใน template:
```bash
composer require simplesoftwareio/simple-qrcode
```

#### เพิ่ม Digital Signature
เพิ่มรูปลายเซ็นใน template และจัดตำแหน่ง

---

## 📊 ระบบติดตามความคืบหน้า

### 📋 ภาพรวม
ระบบติดตามความคืบหน้าการเรียนแบบ Real-time พร้อมการแสดงผลเป็นภาพ (Progress bars, charts, statistics)

### 🎯 ฟีเจอร์หลัก
- ✅ **Real-time Progress**: อัพเดทความคืบหน้าแบบทันที
- ✅ **Progress Visualization**: Progress bars และ completion badges
- ✅ **Lesson Completion Tracking**: บันทึกการเรียนเสร็จแต่ละบทเรียน
- ✅ **Course Completion**: ติดตามการเรียนจบคอร์ส
- ✅ **Statistics Dashboard**: สถิติการเรียนสำหรับครูและผู้ดูแล
- ✅ **Performance Analytics**: วิเคราะห์ผลการเรียน

### 🔄 การทำงานของระบบ

#### Progress Calculation Formula
```
Course Progress (%) = (Completed Lessons / Total Lessons) × 100

Module Progress (%) = (Completed Lessons in Module / Total Lessons in Module) × 100

Overall Progress (%) = Average of all enrolled courses progress
```

#### Lesson Completion Tracking
```
1. นักเรียนคลิก "Mark as Complete"
2. AJAX request ส่งไปยัง server
3. ตรวจสอบว่ายังไม่เคย complete
4. บันทึกลง lesson_completions table
5. Return JSON response
6. Update UI แบบ real-time (ไม่ reload หน้า)
7. อัพเดท Progress bars
```

#### Dashboard Statistics
```
Student Dashboard:
- จำนวนคอร์สที่ลงทะเบียน
- Progress ของแต่ละคอร์ส
- บทเรียนล่าสุดที่เรียน
- ใบประกาศนียบัตรที่ได้รับ

Teacher Dashboard:
- จำนวนคอร์สที่สอน
- จำนวนนักเรียนทั้งหมด
- จำนวนนักเรียนต่อคอร์ส
- สถิติการเรียนเสร็จของนักเรียน

Admin Dashboard:
- จำนวนผู้ใช้ทั้งหมดแบ่งตาม role
- จำนวนคอร์สทั้งหมด
- สถิติการใช้งานระบบ
- กราฟความคืบหน้ารวม
```

### 📊 โครงสร้างข้อมูลสำหรับ Progress

#### Lesson Completions
```sql
CREATE TABLE lesson_completions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_completion (lesson_id, user_id)
);
```

#### Progress Views (Database Views)
```sql
-- Course Progress View
CREATE VIEW course_progress AS
SELECT 
    c.id as course_id,
    c.title as course_title,
    u.id as student_id,
    u.name as student_name,
    COUNT(l.id) as total_lessons,
    COUNT(lc.id) as completed_lessons,
    ROUND((COUNT(lc.id) / COUNT(l.id)) * 100, 2) as progress_percentage
FROM courses c
JOIN users u ON 1=1  -- All students
JOIN modules m ON m.course_id = c.id
JOIN lessons l ON l.module_id = m.id
LEFT JOIN lesson_completions lc ON lc.lesson_id = l.id AND lc.user_id = u.id
LEFT JOIN enrollments e ON e.course_id = c.id AND e.student_id = u.id
WHERE e.id IS NOT NULL  -- Only enrolled students
GROUP BY c.id, u.id;
```

### 📍 ไฟล์ที่เกี่ยวข้อง
```
app/Models/User.php                          # Progress calculation methods
app/Models/Course.php                        # Progress calculation methods
app/Models/LessonCompletion.php              # Lesson completion tracking
app/Http/Controllers/Student/CourseController.php # Progress logic
app/Http/Controllers/Teacher/CourseController.php # Teacher statistics
app/Http/Controllers/Admin/AdminController.php # Admin statistics
resources/views/student/dashboard.blade.php   # Student progress UI
resources/views/teacher/dashboard.blade.php   # Teacher statistics UI
resources/views/admin/dashboard.blade.php     # Admin statistics UI
```

### 🛠️ การปรับแต่ง

#### เพิ่ม Progress Tracking แบบ Weighted
แก้ Progress calculation:
```php
public function getWeightedProgressForStudent($studentId)
{
    $totalWeight = 0;
    $completedWeight = 0;
    
    foreach ($this->lessons as $lesson) {
        $weight = $lesson->order * 10; // Weight by order
        $totalWeight += $weight;
        
        if ($lesson->isCompletedByStudent($studentId)) {
            $completedWeight += $weight;
        }
    }
    
    return $totalWeight > 0 ? round(($completedWeight / $totalWeight) * 100, 2) : 0;
}
```

#### เพิ่ม Real-time Notifications
ใช้ WebSocket หรือ Server-Sent Events สำหรับ real-time updates

#### เพิ่ม Progress Analytics Charts
ติดตั้ง Chart.js หรือ D3.js สำหรับการแสดงผลข้อมูล

---

## 👥 ระบบผู้ดูแลระบบ

### 📋 ภาพรวม
ระบบจัดการระดับผู้ดูแล (Admin) สำหรับจัดการผู้ใช้ คอร์สเรียน และสถิติระบบทั้งหมด

### 🎯 ฟีเจอร์หลัก
- ✅ **User Management**: สร้าง แก้ไข ลบ ผู้ใช้ทั้งหมด
- ✅ **Role Assignment**: กำหนดและเปลี่ยนบทบาทผู้ใช้
- ✅ **Course Management**: จัดการคอร์สเรียนทั้งหมดในระบบ
- ✅ **System Statistics**: ดูสถิติการใช้งานระบบ
- ✅ **Activity Logs**: ติดตามกิจกรรมในระบบ
- ✅ **System Configuration**: ตั้งค่าระบบต่างๆ

### 🔄 การทำงานของระบบ

#### User Management
```
1. Admin Login → Admin Dashboard
2. เมนู "Users" → ดูรายชื่อผู้ใช้ทั้งหมด
3. สามารถ:
   - สร้างผู้ใช้ใหม่
   - แก้ไขข้อมูลผู้ใช้
   - เปลี่ยน role ของผู้ใช้
   - ลบผู้ใช้
   - ค้นหาและกรองผู้ใช้
4. แสดงสถิติ: จำนวนตาม role, วันที่สร้าง, การใช้งานล่าสุด
```

#### Course Management
```
1. Admin Login → Admin Dashboard
2. เมนู "Courses" → ดูรายการคอร์สทั้งหมด
3. สามารถ:
   - ดูรายละเอียดคอร์สทั้งหมด
   - แก้ไขคอร์สใดๆ
   - ลบคอร์ส
   - ดูสถิติคอร์ส (จำนวนนักเรียน, completion rate)
4. แสดงคอร์สตามครูผู้สอน
```

#### System Statistics
```
1. Admin Dashboard → แสดงสถิติภาพรวม:
   - จำนวนผู้ใช้ทั้งหมดแบ่งตาม role
   - จำนวนคอร์สทั้งหมด
   - จำนวนการลงทะเบียนในเดือนนี้
   - จำนวนใบประกาศนียบัตรที่ออก
   - กราฟความคืบหน้าโดยรวม
2. สามารถดูรายละเอียดและ export ข้อมูลได้
```

### 📊 สถิติระบบที่สำคัญ

#### User Statistics
- จำนวนผู้ใช้ทั้งหมด
- จำนวนผู้ใช้แบ่งตาม role (Student/Teacher/Admin)
- ผู้ใช้ใหม่ในแต่ละเดือน
- การใช้งานล่าสุด
- บัญชีที่ไม่ได้ใช้งานนาน

#### Course Statistics
- จำนวนคอร์สทั้งหมด
- คอร์สที่สร้างใหม่ในแต่ละเดือน
- จำนวนนักเรียนต่อคอร์ส (เฉลี่ย)
- คอร์สยอดนิยม (จำนวนนักเรียนสูงสุด)
- Completion rate ของแต่ละคอร์ส

#### Learning Statistics
- จำนวนการลงทะเบียนในแต่ละวัน
- Progress rate เฉลี่ยของนักเรียน
- จำนวนใบประกาศนียบัตรที่ออกในแต่ละเดือน
- Quiz performance statistics
- Learning hours analytics

### 📍 ไฟล์ที่เกี่ยวข้อง
```
app/Http/Controllers/Admin/AdminController.php # Admin Controller
app/Http/Middleware/AdminMiddleware.php    # Admin Middleware
resources/views/admin/dashboard.blade.php   # Admin Dashboard
resources/views/admin/users/             # User Management Views
resources/views/admin/courses/            # Course Management Views
resources/views/admin/statistics.blade.php # Statistics View
```

### 🛠️ การปรับแต่ง

#### เพิ่ม Activity Logging
สร้าง Activity Log model:
```php
class Activity extends Model
{
    protected $fillable = [
        'user_id', 'action', 'subject_type', 'subject_id', 'description'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

#### เพิ่ม System Configuration
สร้าง Settings table:
```sql
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key VARCHAR(100) UNIQUE NOT NULL,
    value TEXT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### เพิ่ม Backup System
สร้าง backup commands และ scheduling

---

## 📝 การใช้งานแบบ Step-by-Step

### 👨‍🎓 สำหรับนักเรียน (Student)

#### 1. การสมัครและเข้าสู่ระบบ
```
1. เข้าเว็บไซต์ → คลิก "Register as Student"
2. กรอกข้อมูล:
   - Name (ชื่อ-นามสกุล)
   - Email (อีเมล)
   - Password (รหัสผ่าน)
   - Confirm Password (ยืนยันรหัสผ่าน)
3. คลิก "Register" → สร้างบัญชีสำเร็จ
4. ใช้ Email และ Password ในการ Login
5. ระบบจะ Redirect ไปยัง Student Dashboard
```

#### 2. การลงทะเบียนเรียนคอร์ส
```
1. จาก Dashboard → คลิก "Browse Courses"
2. ดูรายการคอร์สที่เปิดให้ลงทะเบียน
3. คลิกที่คอร์สที่สนใจ → ดูรายละเอียด
4. อ่านรายละเอียดคอร์ส:
   - คำอธิบาย
   - จำนวน Modules/Lessons
   - รายละเอียดครูผู้สอน
5. คลิก "Enroll" → ลงทะเบียนเรียน
6. คอร์สจะปรากฏใน "My Courses"
```

#### 3. การเรียนบทเรียน
```
1. จาก Dashboard → เลือกคอร์สที่ลงทะเบียน
2. ดูรายการ Modules ในคอร์ส
3. คลิกที่ Module ที่ต้องการเรียน
4. ดูรายการ Lessons ใน Module
5. คลิก "Start Learning" ที่ Lesson ที่ต้องการ
6. ระบบแสดงเนื้อหาตามประเภท:
   - PDF: Document viewer
   - Video: YouTube player
   - Text: Article view
   - Google Drive: Google embed
   - Canva: Canva embed
7. เมื่ออ่าน/ดูเสร็จ → คลิก "Mark as Complete"
8. ระบบบันทึกความคืบหน้าและอัพเดท Progress
```

#### 4. การทำแบบทดสอบ
```
1. เข้า Module → คลิกที่ Quiz
2. อ่านรายละเอียด Quiz (เวลา, คะแนนผ่าน)
3. คลิก "Start Quiz" → เริ่มจับเวลา
4. ตอบคำถามทีละข้อ:
   - เลือกคำตอบที่ถูกต้อง
   - สามารถกลับมาแก้ไขได้ก่อน Submit
5. เมื่อตอบครบ → คลิก "Submit Quiz"
6. ระบบตรวจคำตอบและคำนวณคะแนน
7. แสดงผลลัพธ์:
   - คะแนนที่ได้
   - เปอร์เซ็นต์
   - ผลการผ่าน/ไม่ผ่าน
   - คำตอบที่ถูกต้อง
```

#### 5. การขอใบประกาศนียบัตร
```
1. เรียนครบทุก Lessons ในคอร์ส
2. ผ่านทุก Quizzes ในคอร์ส
3. จาก Course page → คลิก "Get Certificate"
4. ระบบตรวจสอบเงื่อนไข
5. ถ้าผ่าน → สร้าง Certificate PDF
6. คลิก "Download Certificate" → ดาวน์โหลด PDF
7. สามารถดู Certificate ใน "My Certificates"
```

### 👨‍🏫 สำหรับครูผู้สอน (Teacher)

#### 1. การสมัครและเข้าสู่ระบบ
```
1. เข้าเว็บไซต์ → คลิก "Register as Teacher"
2. กรอกข้อมูลส่วนตัว
3. คลิก "Register" → สร้างบัญชีสำเร็จ
4. Login ด้วย Email และ Password
5. ระบบ Redirect ไปยัง Teacher Dashboard
```

#### 2. การสร้างคอร์สเรียน
```
1. จาก Dashboard → คลิก "Create Course"
2. กรอกข้อมูลคอร์ส:
   - Title (ชื่อคอร์ส)
   - Description (คำอธิบาย)
   - Cover Image (อัพโหลดรูปปก)
3. คลิก "Create Course" → สร้างคอร์สสำเร็จ
4. คอร์สจะปรากฏใน Course List
```

#### 3. การจัดการ Modules
```
1. เข้า Course → คลิก "Manage Modules"
2. สร้าง Module:
   - คลิก "Add Module"
   - กรอก Title, Description
   - กำหนด Order (ลำดับ)
   - คลิก "Save"
3. แก้ไข Module:
   - คลิก "Edit" ที่ Module ที่ต้องการ
   - แก้ไขข้อมูล
   - คลิก "Update"
4. ลบ Module:
   - คลิก "Delete" ที่ Module ที่ต้องการ
   - ยืนยันการลบ
```

#### 4. การสร้างบทเรียน (Lessons)
```
1. เข้า Module → คลิก "Add Lesson"
2. กรอกข้อมูล Lesson:
   - Title (ชื่อบทเรียน)
   - Content Type (เลือกประเภท)
3. เพิ่ม Content ตามประเภท:
   - PDF: อัพโหลดไฟล์ (max 10MB)
   - Video: ใส่ YouTube URL
   - Text: เขียนบทความ
   - Google Drive: ใส่ Google Drive URL
   - Canva: ใส่ Canva URL
4. กำหนด Order (ลำดับ)
5. คลิก "Create Lesson" → สร้างสำเร็จ
```

#### 5. การสร้างแบบทดสอบ
```
1. เข้า Module → คลิก "Create Quiz"
2. กรอกข้อมูล Quiz:
   - Title (ชื่อแบบทดสอบ)
   - Description (คำอธิบาย)
   - Time Limit (เวลาจำกัด นาที)
   - Passing Score (คะแนนผ่าน %)
3. คลิก "Create Quiz" → สร้าง Quiz สำเร็จ
4. เพิ่มคำถาม:
   - คลิก "Add Question"
   - กรอก Question Text
   - เพิ่มตัวเลือกคำตอบ (4 ตัวเลือก)
   - กำหนดคำตอบที่ถูกต้อง
   - คลิก "Save Question"
5. ทำซ้ำสำหรับคำถามอื่นๆ
6. จัดลำดับคำถาม (drag & drop)
```

#### 6. การดูสถิตินักเรียน
```
1. เข้า Course → คลิก "View Students"
2. ดูรายชื่อนักเรียนที่ลงทะเบียน
3. ดูข้อมูลแต่ละคน:
   - วันที่ลงทะเบียน
   - Progress การเรียน
   - ผลการทำแบบทดสอบ
   - สถานะการเรียน (Active/Completed)
4. สามารถคลิกเพื่อดูรายละเอียดเพิ่มเติม
```

### 🔧 สำหรับผู้ดูแลระบบ (Admin)

#### 1. การจัดการผู้ใช้
```
1. Login → Admin Dashboard
2. เมนู "Users" → ดูรายชื่อผู้ใช้ทั้งหมด
3. สร้างผู้ใช้ใหม่:
   - คลิก "Create User"
   - กรอกข้อมูล (Name, Email, Password, Role)
   - คลิก "Save"
4. แก้ไขผู้ใช้:
   - คลิก "Edit" ที่ผู้ใช้ที่ต้องการ
   - แก้ไขข้อมูล
   - คลิก "Update"
5. เปลี่ยน Role:
   - แก้ไขฟิลด์ Role
   - เลือก Role ใหม่
   - คลิก "Update"
6. ลบผู้ใช้:
   - คลิก "Delete"
   - ยืนยันการลบ
```

#### 2. การจัดการคอร์สเรียน
```
1. เมนู "Courses" → ดูรายการคอร์สทั้งหมด
2. ดูรายละเอียดคอร์ส:
   - คลิก "View" ที่คอร์สที่ต้องการ
   - ดูข้อมูลครูผู้สอน
   - ดูจำนวนนักเรียน
   - ดู Progress rate
3. แก้ไขคอร์ส:
   - คลิก "Edit"
   - แก้ไขข้อมูลคอร์ส
   - คลิก "Update"
4. ลบคอร์ส:
   - คลิก "Delete"
   - ยืนยันการลบ
```

#### 3. การดูสถิติระบบ
```
1. Admin Dashboard → ดูสถิติภาพรวม:
   - จำนวนผู้ใช้แบ่งตาม role
   - จำนวนคอร์สทั้งหมด
   - จำนวนการลงทะเบียนในเดือนนี้
   - จำนวนใบประกาศนียบัตรที่ออก
2. เมนู "Statistics" → ดูสถิติละเอียด:
   - กราฟการเติบโตของผู้ใช้
   - กราฟการลงทะเบียน
   - กราฟความคืบหน้าโดยรวม
   - ตารางสถิติต่างๆ
3. Export ข้อมูล:
   - เลือกรูปแบบที่ต้องการ
   - คลิก "Export"
   - ดาวน์โหลดไฟล์ (CSV/Excel)
```

---

## 🆘 การแก้ไขปัญหา

### 🔧 ปัญหาที่พบบ่อยและวิธีแก้ไข

#### 1. ปัญหา Login ไม่ได้
```
อาการ: กรอก Email และ Password ถูกต้องแต่ Login ไม่ได้
สาเหตุ: อาจจะเป็นเพราะ Password ไม่ถูกต้อง หรือ Account ถูกปิดใช้งาน
วิธีแก้ไข:
1. ตรวจสอบว่ากรอกข้อมูลถูกต้องหรือไม่
2. ใช้ "Forgot Password" เพื่อรีเซ็ตรหัสผ่าน
3. ติดต่อ Admin เพื่อตรวจสอบสถานะบัญชี
```

#### 2. ปัญหาไม่เห็นคอร์สใน Dashboard
```
อาการ: Login ได้แต่ไม่เห็นคอร์สที่สร้างไว้
สาเหตุ: อาจจะเป็นเพราะ Cache หรือ Permission ไม่ถูกต้อง
วิธีแก้ไข:
1. ลอง Refresh หน้าเว็บ (Ctrl+F5)
2. ลอง Logout แล้ว Login ใหม่
3. ตรวจสอบสิทธิ์การเข้าถึง (Role-based access)
4. ล้าง Cache: ไปที่ /dashboard?clear=1
```

#### 3. ปัญหาอัพโหลดไฟล์ไม่ได้
```
อาการ: อัพโหลดรูปปกคอร์สหรือไฟล์ PDF ไม่ได้
สาเหตุ: อาจจะเป็นเพราะขนาดไฟล์เกินขีดจำกัด หรือ Permission
วิธีแก้ไข:
1. ตรวจสอบขนาดไฟล์ (ไม่เกิน 10MB)
2. ตรวจสอบประเภทไฟล์ที่รองรับ
3. ตรวจสอบว่าสร้าง symbolic link แล้ว:
   php artisan storage:link
4. ตรวจสอบ permission ของ storage folder
```

#### 4. ปัญหา Progress ไม่อัพเดท
```
อาการ: เรียนบทเรียนเสร็จแต่ Progress ไม่เปลี่ยน
สาเหตุ: อาจจะเป็นเพราะ JavaScript error หรือ AJAX ไม่ทำงาน
วิธีแก้ไข:
1. ตรวจสอบ Console ของ Browser (F12)
2. ตรวจสอบว่ามี JavaScript error หรือไม่
3. ลอง Refresh หน้าแล้วทำใหม่
4. ตรวจสอบ Internet connection
```

#### 5. ปัญหา Quiz ไม่ส่งคำตอบได้
```
อาการ: ทำแบบทดสอบเสร็จแต่ Submit ไม่ได้
สาเหตุ: อาจจะหมดเวลา หรือมีข้อผิดพลาดใน validation
วิธีแก้ไข:
1. ตรวจสอบว่าตอบคำถามครบทุกข้อหรือไม่
2. ตรวจสอบว่ายังไม่หมดเวลา
3. ลอง Refresh หน้าแล้วทำใหม่
4. ติดต่อครูผู้สอนเพื่อตรวจสอบ
```

### 📞 ช่องทางการขอความช่วยเหลือ

#### ช่องทางติดต่อ
- **Email**: support@ct.ac.th
- **Phone**: 02-xxx-xxxx (แผนกเทคโนโลยีคอมพิวเตอร์)
- **Line**: @ct-learning
- **Facebook**: CT Learning Official

#### ข้อมูลที่ต้องระบุเมื่อแจ้งปัญหา
1. **ชื่อ-นามสกุล** และ **Email** ของผู้แจ้ง
2. **บทบาท** (Student/Teacher/Admin)
3. **ปัญหา** ที่พบอย่างละเอียด
4. **ขั้นตอนการเกิดปัญหา** (Step-by-step)
5. **หน้าจอภาพ** หรือ **ข้อความ Error** (ถ้ามี)
6. **Browser** และ **อุปกรณ์** ที่ใช้งาน

#### การรายงานปัญหาแบบมีประสิทธิภาพ
```
เรียน: นายสมชัย ใจดี (student1@ct.ac.th)
ปัญหา: ไม่สามารถอัพโหลดไฟล์ PDF ในบทเรียน "การเขียนโปรแกรม"
ขั้นตอน:
1. เข้าสู่ระบบได้ปกติ
2. เข้าคอร์ส "การเขียนโปรแกรม" ได้
3. คลิก Module 1 → บทเรียน "PDF Tutorial"
4. คลิก "Start Learning" → หน้าแสดงเนื้อหาว่าง
5. พยายามอัพโหลดไฟล์ PDF ขนาด 2MB
6. ปรากฏ error message: "File upload failed. Please try again."
Browser: Chrome 120.0, Windows 11
```

---

## 📞 ข้อมูลการติดต่อ

### 🏫 ทีมพัฒนา CT Learning
- **Project Lead**: [Pchan132](https://github.com/pchan132)
- **Email**: dev@ct.ac.th
- **GitHub**: https://github.com/pchan132/Project-CT-Learning

### 📚 เอกสารเพิ่มเติม
- [Architecture Documentation](./ARCHITECTURE.md) - สถาปัตยกรรมระบบ
- [Routes Reference](./ROUTES-REFERENCE.md) - รายการ Routes ทั้งหมด
- [Quick Reference](./QUICK-REFERENCE.md) - คู่มือใช้งานด่วน
- [Documentation Index](./INDEX.md) - ดูเอกสารทั้งหมด

---

**เอกสารนี้สร้างเมื่อ**: 28 พฤศจิกายน 2025  
**เวอร์ชัน**: v2.0  
**ผู้เขียน**: CT Learning Team  
**สถานะ**: ✅ Complete & Updated  

---

<p align="center">
  <strong>📚 CT Learning - Complete LMS Guide</strong><br>
  <em>Comprehensive guide for complete learning management</em>
</p>