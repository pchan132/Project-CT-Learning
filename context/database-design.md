# Database Design for LMS System

## รูปแบบฐานข้อมูล (Database Schema)

### 1. Users Table (ตารางผู้ใช้)
```sql
- id (PK)
- name (string)
- email (string, unique)
- password (string)
- role (enum: student, teacher, admin)
- avatar (string, nullable)
- email_verified_at (timestamp, nullable)
- remember_token (string)
- created_at (timestamp)
- updated_at (timestamp)
```

### 2. Courses Table (ตารางรายวิชา)
```sql
- id (PK)
- title (string)
- description (text)
- cover_image (string, nullable)
- teacher_id (FK -> users.id)
- is_published (boolean, default: false)
- passing_score (integer, default: 80)
- created_at (timestamp)
- updated_at (timestamp)
```

### 3. CourseUser Table (ตารางการลงทะเบียนวิชา)
```sql
- id (PK)
- course_id (FK -> courses.id)
- user_id (FK -> users.id)
- enrolled_at (timestamp)
- completed_at (timestamp, nullable)
- certificate_generated (boolean, default: false)
```

### 4. Modules Table (ตารางบทเรียน)
```sql
- id (PK)
- course_id (FK -> courses.id)
- title (string)
- description (text, nullable)
- order (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### 5. Lessons Table (ตารางเนื้อหาในบท)
```sql
- id (PK)
- module_id (FK -> modules.id)
- title (string)
- content_type (enum: text, pdf, video, link)
- content (text) // HTML content หรือ URL
- file_path (string, nullable) // สำหรับ PDF/PPT
- order (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### 6. LessonProgress Table (ตารางความก้าวหน้าการเรียน)
```sql
- id (PK)
- user_id (FK -> users.id)
- lesson_id (FK -> lessons.id)
- completed (boolean, default: false)
- completed_at (timestamp, nullable)
```

### 7. Quizzes Table (ตารางแบบทดสอบ)
```sql
- id (PK)
- module_id (FK -> modules.id)
- title (string)
- description (text, nullable)
- time_limit (integer, nullable) // นาที
- passing_score (integer, default: 80)
- created_at (timestamp)
- updated_at (timestamp)
```

### 8. Questions Table (ตารางคำถาม)
```sql
- id (PK)
- quiz_id (FK -> quizzes.id)
- question (text)
- question_type (enum: multiple_choice, true_false)
- order (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### 9. Choices Table (ตารางตัวเลือกคำตอบ)
```sql
- id (PK)
- question_id (FK -> questions.id)
- choice (text)
- is_correct (boolean)
- order (integer)
```

### 10. QuizAttempts Table (ตารางการทำแบบทดสอบ)
```sql
- id (PK)
- quiz_id (FK -> quizzes.id)
- user_id (FK -> users.id)
- score (integer)
- total_questions (integer)
- passed (boolean)
- started_at (timestamp)
- completed_at (timestamp)
```

### 11. QuizAnswers Table (ตารางคำตอบที่ผู้ใช้เลือก)
```sql
- id (PK)
- quiz_attempt_id (FK -> quiz_attempts.id)
- question_id (FK -> questions.id)
- choice_id (FK -> choices.id, nullable)
- answer_text (text, nullable) // สำหรับคำตอบอื่นๆ
```

## ความสัมพันธ์ระหว่างตาราง (Relationships)

### User Model
- hasMany(Course::class, 'teacher_id') // อาจารย์สร้างวิชา
- belongsToMany(Course::class, 'course_user') // นักศึกษาลงทะเบียนวิชา
- hasMany(LessonProgress::class)
- hasMany(QuizAttempt::class)

### Course Model
- belongsTo(User::class, 'teacher_id')
- hasMany(Module::class)
- belongsToMany(User::class, 'course_user')

### Module Model
- belongsTo(Course::class)
- hasMany(Lesson::class)
- hasMany(Quiz::class)

### Lesson Model
- belongsTo(Module::class)
- hasMany(LessonProgress::class)

### Quiz Model
- belongsTo(Module::class)
- hasMany(Question::class)
- hasMany(QuizAttempt::class)

### Question Model
- belongsTo(Quiz::class)
- hasMany(Choice::class)
- hasMany(QuizAnswer::class)

## การปรับปรุง User Model
เราต้องเพิ่มฟิลด์ `role` ในตาราง users เพื่อแยกประเภทผู้ใช้