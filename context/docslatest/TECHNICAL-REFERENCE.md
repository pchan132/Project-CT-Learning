# 🏗️ CT Learning - Technical Architecture & Database Reference
# เอกสารสถาปัตยกรรมทางเทคนิคและโครงสร้างฐานข้อมูล

---

## 📋 สารบัญ

1. [ภาพรวมสถาปัตยกรรม](#ภาพรวมสถาปัตยกรรม)
2. [Technology Stack](#technology-stack)
3. [โครงสร้างโปรเจค](#โครงสร้างโปรเจค)
4. [Database Schema](#database-schema)
5. [Models & Relationships](#models--relationships)
6. [Controllers & Routes](#controllers--routes)
7. [Middleware System](#middleware-system)
8. [File Storage System](#file-storage-system)
9. [Authentication Flow](#authentication-flow)
10. [Security Implementation](#security-implementation)

---

## ภาพรวมสถาปัตยกรรม

### 🎯 Architecture Overview

CT Learning ใช้สถาปัตยกรรมแบบ **MVC (Model-View-Controller)** ตาม Laravel Framework โดยมีการแบ่งส่วนการทำงานตาม Role-based Access Control

```
┌─────────────────────────────────────────────────────────────┐
│                     CLIENT LAYER                            │
│         (Web Browser - Chrome, Firefox, Safari)             │
└─────────────────────┬───────────────────────────────────────┘
                      │ HTTP/HTTPS
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                   PRESENTATION LAYER                        │
│  ┌─────────────┐ ┌──────────────┐ ┌───────────────────┐   │
│  │Blade Views  │ │Tailwind CSS  │ │Alpine.js/Vanilla │   │
│  │(Templates)  │ │(Styling)     │ │JavaScript        │   │
│  └─────────────┘ └──────────────┘ └───────────────────┘   │
└─────────────────────┬───────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                    ROUTING LAYER                            │
│                   (routes/web.php)                          │
│  ┌─────────────┐ ┌──────────────┐ ┌───────────────────┐   │
│  │Public Routes│ │Auth Routes   │ │Role Routes        │   │
│  │(welcome)    │ │(login,logout)│ │(admin,teacher,    │   │
│  │             │ │              │ │student)           │   │
│  └─────────────┘ └──────────────┘ └───────────────────┘   │
└─────────────────────┬───────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                   MIDDLEWARE LAYER                          │
│  ┌─────────────┐ ┌──────────────┐ ┌───────────────────┐   │
│  │Auth         │ │Admin         │ │Teacher/Student    │   │
│  │Middleware   │ │Middleware    │ │Middleware         │   │
│  └─────────────┘ └──────────────┘ └───────────────────┘   │
└─────────────────────┬───────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                  CONTROLLER LAYER                           │
│  ┌──────────────────────────────────────────────────────┐  │
│  │                Admin Controllers                      │  │
│  │  AdminController, CertificateTemplateController      │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │               Teacher Controllers                     │  │
│  │  CourseController, ModuleController, LessonController│  │
│  │  QuizController, QuestionController, SignatureCtrl   │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │               Student Controllers                     │  │
│  │  CourseController, QuizController, CertificateCtrl   │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────┬───────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                    MODEL LAYER                              │
│                  (Eloquent ORM)                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐     │
│  │   User   │ │  Course  │ │  Module  │ │  Lesson  │     │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘     │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐     │
│  │   Quiz   │ │ Question │ │  Answer  │ │Enrollment│     │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘     │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────────┐   │
│  │QuizAttempt   │ │ Certificate  │ │LessonCompletion  │   │
│  └──────────────┘ └──────────────┘ └──────────────────┘   │
└─────────────────────┬───────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                   DATABASE LAYER                            │
│                   (MySQL 8.0)                               │
│                                                             │
│  14 Tables | Relationships | Indexes | Foreign Keys        │
└─────────────────────────────────────────────────────────────┘
```

---

## Technology Stack

### 🛠️ Backend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| **PHP** | 8.1+ | Server-side Language |
| **Laravel** | 10.x | PHP Framework |
| **Eloquent ORM** | 10.x | Database ORM |
| **Laravel Breeze** | 1.x | Authentication |
| **DomPDF** | 2.x | PDF Generation |
| **Laravel Storage** | Built-in | File Management |

### 🎨 Frontend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| **Blade** | Built-in | Template Engine |
| **Tailwind CSS** | 3.x | CSS Framework |
| **Alpine.js** | 3.x | JavaScript Framework |
| **Vite** | 4.x | Build Tool |
| **Quill.js** | 1.x | Rich Text Editor |
| **SortableJS** | 1.x | Drag & Drop |

### 🗃️ Database & Storage

| Technology | Version | Purpose |
|------------|---------|---------|
| **MySQL** | 8.0+ | Primary Database |
| **MariaDB** | 10.3+ | Alternative Database |
| **Local Storage** | - | File Storage |

---

## โครงสร้างโปรเจค

### 📁 Directory Structure

```
ct-learning/
│
├── app/                                 # Application Core
│   ├── Console/
│   │   └── Kernel.php                   # Console Commands Configuration
│   │
│   ├── Exceptions/
│   │   └── Handler.php                  # Exception Handling
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminController.php          # Admin Dashboard & User Management
│   │   │   │   └── CertificateTemplateController.php  # Certificate Templates
│   │   │   │
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── ConfirmablePasswordController.php
│   │   │   │   ├── EmailVerificationController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── PasswordController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── RegisteredUserController.php  # Modified for multi-role
│   │   │   │   └── VerifyEmailController.php
│   │   │   │
│   │   │   ├── Student/
│   │   │   │   ├── CertificateController.php    # Student Certificates
│   │   │   │   ├── CourseController.php         # Course Browsing & Learning
│   │   │   │   └── QuizController.php           # Quiz Taking
│   │   │   │
│   │   │   ├── Teacher/
│   │   │   │   ├── CourseController.php         # Course CRUD
│   │   │   │   ├── LessonController.php         # Lesson Management
│   │   │   │   ├── ModuleController.php         # Module Management
│   │   │   │   ├── QuestionController.php       # Question Management
│   │   │   │   ├── QuizController.php           # Quiz Management
│   │   │   │   └── SignatureController.php      # Signature & Background
│   │   │   │
│   │   │   ├── Controller.php                   # Base Controller
│   │   │   ├── ProfileController.php            # User Profile
│   │   │   └── TeacherProfileController.php     # Teacher Public Profile
│   │   │
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php              # Admin Role Check
│   │   │   ├── StudentMiddleware.php            # Student Role Check
│   │   │   └── TeacherMiddleware.php            # Teacher Role Check
│   │   │
│   │   └── Requests/                            # Form Request Validation
│   │
│   ├── Models/
│   │   ├── Answer.php                   # Quiz Answer Model
│   │   ├── Certificate.php              # Certificate Model
│   │   ├── CertificateTemplate.php      # Certificate Template Model
│   │   ├── Course.php                   # Course Model
│   │   ├── Enrollment.php               # Enrollment Model
│   │   ├── Lesson.php                   # Lesson Model
│   │   ├── LessonCompletion.php         # Lesson Completion Model
│   │   ├── Module.php                   # Module Model
│   │   ├── Question.php                 # Quiz Question Model
│   │   ├── Quiz.php                     # Quiz Model
│   │   ├── QuizAttempt.php              # Quiz Attempt Model
│   │   └── User.php                     # User Model (Multi-role)
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── BroadcastServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   │
│   └── View/
│       └── Components/                  # Blade Components
│
├── bootstrap/
│   ├── app.php
│   └── cache/                           # Framework Cache
│
├── config/                              # Configuration Files
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── filesystems.php
│   ├── mail.php
│   └── ...
│
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   │
│   ├── migrations/                      # Database Migrations (23 files)
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── 2025_11_21_123634_create_courses_table.php
│   │   ├── 2025_11_21_123830_create_enrollments_table.php
│   │   ├── 2025_11_23_021024_create_modules_table.php
│   │   ├── 2025_11_23_021029_create_lessons_table.php
│   │   ├── 2025_11_23_021033_create_lesson_completions_table.php
│   │   ├── 2025_11_24_190419_create_quizzes_table.php
│   │   ├── 2025_11_24_190426_create_questions_table.php
│   │   ├── 2025_11_24_190445_create_answers_table.php
│   │   ├── 2025_11_24_190451_create_quiz_attempts_table.php
│   │   ├── 2025_11_24_191338_create_certificates_table.php
│   │   └── ...
│   │
│   └── seeders/                         # Database Seeders
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── CourseSeeder.php
│       └── ...
│
├── public/
│   ├── index.php                        # Application Entry Point
│   ├── storage -> ../storage/app/public # Storage Symlink
│   └── build/                           # Compiled Assets
│
├── resources/
│   ├── css/
│   │   └── app.css                      # Main CSS (Tailwind)
│   │
│   ├── js/
│   │   └── app.js                       # Main JavaScript
│   │
│   └── views/
│       ├── admin/                       # Admin Views
│       │   ├── dashboard.blade.php
│       │   ├── users/
│       │   ├── courses/
│       │   └── certificate-templates/
│       │
│       ├── auth/                        # Authentication Views
│       │   ├── login.blade.php
│       │   ├── register-student.blade.php
│       │   ├── register-teacher.blade.php
│       │   └── ...
│       │
│       ├── components/                  # Reusable Components
│       │   ├── certificate-preview.blade.php
│       │   ├── progress-bar.blade.php
│       │   └── ...
│       │
│       ├── layouts/
│       │   ├── app.blade.php            # Main Layout
│       │   ├── guest.blade.php          # Guest Layout
│       │   └── navigation.blade.php     # Navigation Component
│       │
│       ├── student/                     # Student Views
│       │   ├── dashboard.blade.php
│       │   ├── courses/
│       │   ├── certificates/
│       │   └── quiz/
│       │
│       ├── teacher/                     # Teacher Views
│       │   ├── dashboard.blade.php
│       │   ├── courses/
│       │   ├── modules/
│       │   ├── lessons/
│       │   ├── quizzes/
│       │   └── signature/
│       │
│       └── welcome.blade.php            # Landing Page
│
├── routes/
│   ├── web.php                          # Main Web Routes (227 lines)
│   ├── api.php                          # API Routes
│   ├── auth.php                         # Authentication Routes
│   ├── channels.php                     # Broadcast Channels
│   └── console.php                      # Console Commands
│
├── storage/
│   ├── app/
│   │   └── public/                      # Public Storage
│   │       ├── course-covers/           # Course Images
│   │       ├── lessons/                 # Lesson Files
│   │       ├── profile-images/          # User Avatars
│   │       ├── signatures/              # Teacher Signatures
│   │       └── certificate-backgrounds/ # Certificate Backgrounds
│   │
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   │
│   └── logs/
│       └── laravel.log                  # Application Logs
│
├── tests/                               # PHPUnit Tests
│   ├── Feature/
│   └── Unit/
│
├── context/docs/                        # Documentation
│
├── .env                                 # Environment Variables
├── .env.example                         # Environment Template
├── composer.json                        # PHP Dependencies
├── package.json                         # Node Dependencies
├── tailwind.config.js                   # Tailwind Configuration
├── vite.config.js                       # Vite Configuration
└── phpunit.xml                          # PHPUnit Configuration
```

---

## Database Schema

### 📐 Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         DATABASE SCHEMA                                  │
│                           CT Learning                                    │
└─────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐         ┌─────────────────┐
│     users       │         │    courses      │
├─────────────────┤         ├─────────────────┤
│ id (PK)         │◄───────┤│ teacher_id (FK) │
│ name            │    1:N  │ id (PK)         │
│ email           │         │ title           │
│ password        │         │ description     │
│ role            │         │ cover_image_url │
│ profile_image   │         │ created_at      │
│ position        │         │ updated_at      │
│ bio             │         └────────┬────────┘
│ signature_image │                  │
│ certificate_bg  │                  │ 1:N
│ created_at      │                  │
│ updated_at      │                  ▼
└────────┬────────┘         ┌─────────────────┐
         │                  │    modules      │
         │                  ├─────────────────┤
         │           ┌─────▶│ course_id (FK)  │
         │           │      │ id (PK)         │
         │           │      │ title           │
         │           │      │ description     │
         │      1:N  │      │ order           │
         │           │      │ created_at      │
         ▼           │      │ updated_at      │
┌─────────────────┐  │      └────────┬────────┘
│  enrollments    │  │               │
├─────────────────┤  │               │ 1:N
│ id (PK)         │  │               │
│ student_id (FK) │──┘               ▼
│ course_id (FK)  │─────────┐ ┌─────────────────┐
│ enrolled_at     │         │ │    lessons      │
│ created_at      │         │ ├─────────────────┤
│ updated_at      │         │ │ module_id (FK)  │
└─────────────────┘         │ │ id (PK)         │
                            │ │ title           │
         ┌──────────────────┘ │ content_type    │
         │                    │ content_url     │
         │                    │ content_text    │
         │                    │ order           │
         │                    │ required_duration│
         ▼                    │ created_at      │
┌─────────────────┐           │ updated_at      │
│lesson_completions│          └────────┬────────┘
├─────────────────┤                   │
│ id (PK)         │                   │
│ student_id (FK) │◄──────────────────┘
│ lesson_id (FK)  │
│ completed_at    │
│ created_at      │
│ updated_at      │
└─────────────────┘

┌─────────────────┐         ┌─────────────────┐
│    quizzes      │         │   questions     │
├─────────────────┤         ├─────────────────┤
│ module_id (FK)  │◄───────┤│ quiz_id (FK)    │
│ id (PK)         │    1:N  │ id (PK)         │
│ title           │         │ question_text   │
│ description     │         │ order           │
│ time_limit      │         │ created_at      │
│ passing_score   │         │ updated_at      │
│ created_at      │         └────────┬────────┘
│ updated_at      │                  │
└────────┬────────┘                  │ 1:N
         │                           │
         │                           ▼
         │                  ┌─────────────────┐
         │                  │    answers      │
         │                  ├─────────────────┤
         │                  │ question_id (FK)│
         │                  │ id (PK)         │
         │                  │ answer_text     │
         │                  │ is_correct      │
         │                  │ order           │
         │                  │ created_at      │
         │                  │ updated_at      │
         │                  └─────────────────┘
         │
         │ 1:N
         ▼
┌─────────────────┐
│  quiz_attempts  │
├─────────────────┤
│ id (PK)         │
│ user_id (FK)    │◄─────── users
│ quiz_id (FK)    │
│ score           │
│ total_questions │
│ correct_answers │
│ passed          │
│ answers (JSON)  │
│ started_at      │
│ completed_at    │
│ created_at      │
│ updated_at      │
└─────────────────┘

┌─────────────────┐         ┌─────────────────────┐
│  certificates   │         │certificate_templates│
├─────────────────┤         ├─────────────────────┤
│ id (PK)         │         │ id (PK)             │
│ user_id (FK)    │◄───┐    │ name                │
│ course_id (FK)  │    │    │ description         │
│ certificate_no  │    │    │ background_image    │
│ issued_at       │    │    │ is_active           │
│ theme           │    │    │ signature_width     │
│ created_at      │    │    │ signature_height    │
│ updated_at      │    │    │ created_at          │
└─────────────────┘    │    │ updated_at          │
                       │    └─────────────────────┘
                       │
                       └─────── users (student)
```

### 📋 Table Definitions

#### 1. users

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') DEFAULT 'student',
    profile_image VARCHAR(255) NULL,
    position VARCHAR(255) NULL,
    bio TEXT NULL,
    signature_image VARCHAR(255) NULL,
    certificate_background VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### 2. courses

```sql
CREATE TABLE courses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    cover_image_url VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 3. modules

```sql
CREATE TABLE modules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);
```

#### 4. lessons

```sql
CREATE TABLE lessons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content_type ENUM('PDF', 'VIDEO', 'TEXT', 'PPT', 'GDRIVE', 'CANVA') NOT NULL,
    content_url VARCHAR(500) NULL,
    content_text LONGTEXT NULL,
    order INT UNSIGNED DEFAULT 0,
    required_duration_minutes INT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);
```

#### 5. enrollments

```sql
CREATE TABLE enrollments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    enrolled_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);
```

#### 6. lesson_completions

```sql
CREATE TABLE lesson_completions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    lesson_id BIGINT UNSIGNED NOT NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    UNIQUE KEY unique_completion (student_id, lesson_id)
);
```

#### 7. quizzes

```sql
CREATE TABLE quizzes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    time_limit INT UNSIGNED NULL COMMENT 'Time limit in minutes',
    passing_score INT UNSIGNED DEFAULT 60 COMMENT 'Percentage required to pass',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);
```

#### 8. questions

```sql
CREATE TABLE questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quiz_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);
```

#### 9. answers

```sql
CREATE TABLE answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id BIGINT UNSIGNED NOT NULL,
    answer_text VARCHAR(500) NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);
```

#### 10. quiz_attempts

```sql
CREATE TABLE quiz_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    quiz_id BIGINT UNSIGNED NOT NULL,
    score DECIMAL(5,2) DEFAULT 0,
    total_questions INT UNSIGNED DEFAULT 0,
    correct_answers INT UNSIGNED DEFAULT 0,
    passed BOOLEAN DEFAULT FALSE,
    answers JSON NULL COMMENT 'Stored answers for review',
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);
```

#### 11. certificates

```sql
CREATE TABLE certificates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    certificate_number VARCHAR(50) NOT NULL UNIQUE,
    issued_at TIMESTAMP NULL,
    theme VARCHAR(50) DEFAULT 'default',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_certificate (user_id, course_id)
);
```

#### 12. certificate_templates

```sql
CREATE TABLE certificate_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    background_image VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT FALSE,
    signature_width INT UNSIGNED DEFAULT 150,
    signature_height INT UNSIGNED DEFAULT 80,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

## Models & Relationships

### User Model

```php
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role',
        'profile_image', 'position', 'bio',
        'signature_image', 'certificate_background',
    ];

    // Role Checks
    public function isStudent(): bool;
    public function isTeacher(): bool;
    public function isAdmin(): bool;

    // Relationships
    public function teachingCourses();      // hasMany Course (for teachers)
    public function enrollments();           // hasMany Enrollment (for students)
    public function enrolledCourses();       // belongsToMany Course
    public function lessonCompletions();     // hasMany LessonCompletion
    public function quizAttempts();          // hasMany QuizAttempt
    public function certificates();          // hasMany Certificate
}
```

### Course Model

```php
class Course extends Model
{
    protected $fillable = ['teacher_id', 'title', 'description', 'cover_image_url'];

    // Relationships
    public function teacher();               // belongsTo User
    public function modules();               // hasMany Module (ordered)
    public function lessons();               // hasManyThrough Lesson
    public function enrollments();           // hasMany Enrollment
    public function enrolledStudents();      // belongsToMany User

    // Computed Properties
    public function getTotalModulesAttribute();
    public function getTotalLessonsAttribute();
    public function getCompletedLessonsCount($studentId);
    public function getProgressForStudent($studentId);
    public function isEnrolledByStudent($studentId);
}
```

### Module Model

```php
class Module extends Model
{
    protected $fillable = ['course_id', 'title', 'description', 'order'];

    // Relationships
    public function course();                // belongsTo Course
    public function lessons();               // hasMany Lesson (ordered)
    public function quizzes();               // hasMany Quiz
}
```

### Lesson Model

```php
class Lesson extends Model
{
    protected $fillable = [
        'module_id', 'title', 'content_type',
        'content_url', 'content_text', 'order',
        'required_duration_minutes'
    ];

    // Content Types: PDF, VIDEO, TEXT, PPT, GDRIVE, CANVA

    // Relationships
    public function module();                // belongsTo Module
    public function completions();           // hasMany LessonCompletion

    // Methods
    public function isCompletedBy($studentId);
    public function getContentDisplayUrlAttribute();
    public function getGoogleDriveEmbedUrl();
    public function getCanvaEmbedUrl();
}
```

### Quiz Model

```php
class Quiz extends Model
{
    protected $fillable = [
        'module_id', 'title', 'description',
        'time_limit', 'passing_score'
    ];

    // Relationships
    public function module();                // belongsTo Module
    public function questions();             // hasMany Question (ordered)
    public function attempts();              // hasMany QuizAttempt

    // Methods
    public function hasPassingAttemptBy($userId);
}
```

### Certificate Model

```php
class Certificate extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'certificate_number',
        'issued_at', 'theme'
    ];

    // Relationships
    public function user();                  // belongsTo User
    public function course();                // belongsTo Course

    // Methods
    public static function generateCertificateNumber();
}
```

---

## Controllers & Routes

### Route Structure Overview

```php
// Public Routes
Route::get('/', [WelcomeController::class, 'index']);

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/register/student', ...);
    Route::get('/register/teacher', ...);
    Route::get('/login', ...);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', ...);  // Redirects by role
    Route::get('/profile', ...);
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::resource('/users', ...);
    Route::resource('/courses', ...);
    Route::resource('/certificate-templates', ...);
    Route::get('/statistics', ...);
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('courses', TeacherCourseController::class);
    Route::get('courses/{course}/students', ...);
    Route::resource('courses.modules', ModuleController::class);
    Route::resource('courses.modules.lessons', LessonController::class);
    Route::resource('courses.modules.quizzes', QuizController::class);
    Route::get('signature', ...);
    Route::get('certificate-preview', ...);
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses', [StudentCourseController::class, 'index']);
    Route::get('/courses/my-courses', ...);
    Route::post('/courses/{course}/enroll', ...);
    Route::get('/courses/{course}/lessons/{lesson}', ...);
    Route::post('/courses/{course}/lessons/{lesson}/complete', ...);
    Route::get('/courses/{course}/modules/{module}/quizzes/{quiz}', ...);
    Route::post('/quizzes/{quiz}/start', ...);
    Route::post('/attempts/{attempt}/submit', ...);
    Route::get('/certificates', ...);
    Route::post('/courses/{course}/certificates/generate', ...);
});
```

### Controller Methods Summary

#### AdminController
| Method | Route | Description |
|--------|-------|-------------|
| `index()` | GET /admin/dashboard | Admin dashboard |
| `users()` | GET /admin/users | List all users |
| `createUser()` | GET /admin/users/create | Create user form |
| `storeUser()` | POST /admin/users | Store new user |
| `editUser()` | GET /admin/users/{id}/edit | Edit user form |
| `updateUser()` | PUT /admin/users/{id} | Update user |
| `destroyUser()` | DELETE /admin/users/{id} | Delete user |

#### Teacher\CourseController
| Method | Route | Description |
|--------|-------|-------------|
| `index()` | GET /teacher/courses | List teacher's courses |
| `create()` | GET /teacher/courses/create | Create course form |
| `store()` | POST /teacher/courses | Store new course |
| `show()` | GET /teacher/courses/{id} | Show course details |
| `edit()` | GET /teacher/courses/{id}/edit | Edit course form |
| `update()` | PUT /teacher/courses/{id} | Update course |
| `destroy()` | DELETE /teacher/courses/{id} | Delete course |
| `students()` | GET /teacher/courses/{id}/students | List enrolled students |

#### Student\CourseController
| Method | Route | Description |
|--------|-------|-------------|
| `index()` | GET /student/courses | Browse all courses |
| `myCourses()` | GET /student/courses/my-courses | Enrolled courses |
| `show()` | GET /student/courses/{id} | Course details |
| `enroll()` | POST /student/courses/{id}/enroll | Enroll in course |
| `unenroll()` | DELETE /student/courses/{id}/unenroll | Unenroll from course |
| `learnLesson()` | GET /student/courses/{id}/lessons/{id} | Learn lesson |
| `completeLesson()` | POST /student/courses/{id}/lessons/{id}/complete | Mark as complete |

---

## Middleware System

### Custom Middleware

```php
// AdminMiddleware.php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403, 'Access denied. Admin only.');
    }
    return $next($request);
}

// TeacherMiddleware.php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->isTeacher()) {
        abort(403, 'Access denied. Teacher only.');
    }
    return $next($request);
}

// StudentMiddleware.php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->isStudent()) {
        abort(403, 'Access denied. Student only.');
    }
    return $next($request);
}
```

### Middleware Registration (Kernel.php)

```php
protected $middlewareAliases = [
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
    'student' => \App\Http\Middleware\StudentMiddleware::class,
];
```

---

## File Storage System

### Storage Structure

```
storage/app/public/
│
├── course-covers/              # Course cover images
│   └── {course_id}_*.{jpg|png}
│
├── lessons/                    # Lesson files
│   ├── pdfs/
│   │   └── {lesson_id}_*.pdf
│   ├── videos/
│   │   └── {lesson_id}_*.mp4
│   └── ppts/
│       └── {lesson_id}_*.{ppt|pptx}
│
├── profile-images/             # User profile images
│   └── {user_id}_*.{jpg|png}
│
├── signatures/                 # Teacher signatures
│   └── {user_id}_signature.png
│
├── certificate-backgrounds/    # Certificate backgrounds
│   ├── teachers/
│   │   └── {user_id}_*.{jpg|png}
│   └── templates/
│       └── {template_id}_*.{jpg|png}
│
└── quill-images/               # Rich text editor images
    └── *.{jpg|png}
```

### File Upload Configuration

```php
// config/filesystems.php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],

// Max file sizes (configurable in php.ini)
// PDF/PPT: 10MB
// Video: 100MB
// Images: 5MB
```

---

## Authentication Flow

### Registration Flow

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   User       │────▶│  Select Role │────▶│  Fill Form   │
│   Visits     │     │  (Student/   │     │  (Name,Email │
│              │     │   Teacher)   │     │   Password)  │
└──────────────┘     └──────────────┘     └──────────────┘
                                                 │
                                                 ▼
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   Redirect   │◀────│  Login       │◀────│  Validate &  │
│   to         │     │  User        │     │  Create User │
│   Dashboard  │     │              │     │  with Role   │
└──────────────┘     └──────────────┘     └──────────────┘
```

### Login Flow

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   User       │────▶│  Enter       │────▶│  Validate    │
│   Visits     │     │  Email &     │     │  Credentials │
│   /login     │     │  Password    │     │              │
└──────────────┘     └──────────────┘     └──────────────┘
                                                 │
                          ┌──────────────────────┼──────────────────────┐
                          │                      │                      │
                          ▼                      ▼                      ▼
                    ┌──────────┐          ┌──────────┐          ┌──────────┐
                    │  Admin   │          │ Teacher  │          │ Student  │
                    │ Dashboard│          │Dashboard │          │Dashboard │
                    │ /admin/  │          │/teacher/ │          │/student/ │
                    └──────────┘          └──────────┘          └──────────┘
```

---

## Security Implementation

### CSRF Protection

```html
<!-- All forms include CSRF token -->
<form method="POST" action="{{ route('...') }}">
    @csrf
    ...
</form>
```

### Input Validation

```php
// Form Request Validation
public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'file' => 'required|mimes:pdf,pptx|max:10240',
    ];
}
```

### Authorization Checks

```php
// Controller level
public function update(Course $course)
{
    // Ensure teacher owns the course
    if ($course->teacher_id !== auth()->id()) {
        abort(403);
    }
    // ...
}

// Policy level (alternative)
public function update(User $user, Course $course)
{
    return $user->id === $course->teacher_id;
}
```

### Password Hashing

```php
// Automatic hashing via casts
protected $casts = [
    'password' => 'hashed',
];
```

### XSS Prevention

```php
<!-- Blade automatically escapes output -->
{{ $user->name }}  // Escaped
{!! $content !!}   // Unescaped (use carefully)
```

---

<p align="center">
  <strong>🏗️ CT Learning - Technical Architecture</strong><br>
  <em>Version 2.0.0 | December 2025</em>
</p>
