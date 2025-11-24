# Student Learning System Documentation

## ภาพรวมระบบ

Student Learning System เป็นระบบการเรียนออนไลน์ที่พัฒนาด้วย Laravel และ Tailwind CSS โดยมุ่งเน้นการให้นักเรียนสามารถลงทะเบียนเรียนคอร์ส ติดตามความคืบหน้า และเรียนบทเรียนต่างๆ ได้อย่างมีประสิทธิภาพ

## ฟีเจอร์หลัก

### 1. Course Enrollment System (ระบบลงทะเบียนวิชา)
- **การลงทะเบียนคอร์ส**: นักเรียนสามารถเลือกลงทะเบียนคอร์สที่เปิดสอนได้
- **การถอนการลงทะเบียน**: สามารถถอนการลงทะเบียนคอร์สที่ไม่ต้องการเรียนได้
- **การแสดงคอร์ส**: แยกแสดงระหว่างคอร์สที่ลงทะเบียนแล้วกับคอร์สที่ยังไม่ได้ลงทะเบียน

### 2. Progress Tracking Foundation (ระบบติดตามความคืบหน้า)
- **คำนวณความคืบหน้า**: คำนวณเป็นเปอร์เซ็นต์จากจำนวนบทเรียนที่เรียนเสร็จ
- **แสดงสถิติ**: แสดงจำนวนโมดูล บทเรียนทั้งหมด และบทเรียนที่เรียนเสร็จ
- **Progress Bar**: แสดงความคืบหน้าแบบวิชวล์ในแต่ละคอร์ส

### 3. Lesson Completion Tracking Structure (โครงสร้างการติดตามการเรียนบทเรียน)
- **บันทึกการเรียนเสร็จ**: บันทึกการเรียนแต่ละบทเรียนของนักเรียน
- **สถานะบทเรียน**: แสดงว่าเรียนเสร็จแล้วหรือยังไม่เสร็จ
- **การนำทางบทเรียน**: ลิงก์ไปยังบทเรียนถัดไป/ก่อนหน้าโดยอัตโนมัติ

## โครงสร้างไฟล์หลัก

### Controllers
- `app/Http/Controllers/Student/CourseController.php`
  - `index()` - แสดงรายการคอร์สทั้งหมด
  - `show()` - แสดงรายละเอียดคอร์สและบทเรียน
  - `enroll()` - ลงทะเบียนคอร์ส
  - `unenroll()` - ถอนการลงทะเบียนคอร์ส
  - `learnLesson()` - แสดงหน้าเรียนบทเรียน
  - `completeLesson()` - บันทึกการเรียนเสร็จ

### Views
- `resources/views/student/courses/index.blade.php` - หน้ารายการคอร์ส
- `resources/views/student/courses/show.blade.php` - หน้ารายละเอียดคอร์ส
- `resources/views/student/lessons/learn.blade.php` - หน้าเรียนบทเรียน
- `resources/views/student/dashboard.blade.php` - หน้าแดชบอร์ดนักเรียน

### Models
- `User` - มีความสัมพันธ์กับ enrollments และ lessonCompletions
- `Course` - มีฟังก์ชันคำนวณ progress และตรวจสอบการลงทะเบียน
- `Enrollment` - จัดการการลงทะเบียนเรียน
- `Lesson` - มีฟังก์ชันตรวจสอบการเรียนเสร็จและจัดการประเภทเนื้อหา
- `LessonCompletion` - บันทึกการเรียนเสร็จของแต่ละบทเรียน

## Routes

```php
// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    // Course routes
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/my-courses', [StudentCourseController::class, 'myCourses'])->name('courses.my-courses');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    
    // Enrollment routes
    Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/unenroll', [StudentCourseController::class, 'unenroll'])->name('courses.unenroll');
    
    // Lesson learning routes
    Route::get('/courses/{course}/lessons/{lesson}', [StudentCourseController::class, 'learnLesson'])->name('courses.learn-lesson');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [StudentCourseController::class, 'completeLesson'])->name('courses.complete-lesson');
});
```

## ฟังก์ชันการทำงาน

### 1. การลงทะเบียนคอร์ส
```php
public function enroll(Course $course)
{
    Enrollment::firstOrCreate([
        'course_id' => $course->id,
        'student_id' => auth()->id(),
    ]);
    
    return redirect()->route('student.courses.show', $course)
        ->with('success', 'ลงทะเบียนเรียนสำเร็จ');
}
```

### 2. การคำนวณความคืบหน้า
```php
public function getProgressForStudent($studentId)
{
    $totalLessons = $this->getTotalLessonsAttribute();
    if ($totalLessons === 0) {
        return 0;
    }

    $completedLessons = $this->getCompletedLessonsCount($studentId);
    return round(($completedLessons / $totalLessons) * 100, 2);
}
```

### 3. การบันทึกการเรียนเสร็จ
```php
public function completeLesson(Course $course, Lesson $lesson)
{
    $completion = LessonCompletion::firstOrCreate([
        'lesson_id' => $lesson->id,
        'student_id' => auth()->id(),
    ]);

    $progress = $course->getProgressForStudent(auth()->id());

    return response()->json([
        'success' => true,
        'message' => 'บทเรียนนี้เรียนเสร็จแล้ว',
        'progress' => $progress
    ]);
}
```

## ประเภทเนื้อหาบทเรียน

ระบบรองรับหลายประเภทของเนื้อหา:

1. **TEXT** - เนื้อหาข้อความธรรมดา
2. **VIDEO** - วิดีโอ (MP4, WebM)
3. **PDF** - เอกสาร PDF
4. **PPT** - งานนำเสนอ PowerPoint

## การติดตั้งและการใช้งาน

### ข้อกำหนดเบื้องต้น
- PHP 8.0+
- Laravel 9.x
- MySQL 8.0+
- Node.js 16+
- Composer

### การติดตั้ง
```bash
# Clone repository
git clone <repository-url>
cd ct-learning

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate

# Link storage
php artisan storage:link

# Run development server
npm run dev
php artisan serve
```

### การสร้างข้อมูลตัวอย่าง
```bash
php artisan db:seed
```

## การปรับแต่ง

### การเพิ่มประเภทเนื้อหาใหม่
1. แก้ไข `$fillable` ใน `Lesson` model
2. เพิ่ม case ใหม่ใน `learn.blade.php`
3. อัพเดท `getContentTypeLabelAttribute()` method

### การปรับแต่ง UI
- แก้ไข Tailwind CSS classes ในไฟล์ views
- ปรับแต่งสีใน `tailwind.config.js`

## ข้อควรระวัง

1. **Security**: ตรวจสอบสิทธิ์การเข้าถึงของนักเรียนในทุกฟังก์ชัน
2. **Performance**: ใช้ eager loading เพื่อลดจำนวนคำสั่ง SQL
3. **Validation**: ตรวจสอบข้อมูลที่ส่งจากผู้ใช้ทุกครั้ง

## การพัฒนาต่อ

### ฟีเจอร์ที่แนะนำเพิ่มเติม
- ระบบประเมินผลการเรียน
- การสนทนา/ถามตอบในบทเรียน
- ใบประกาศนียบัตร
- ระบบแจ้งเตือน
- การเรียนแบบ Offline
- รายงานความคืบหน้าสำหรับผู้ปกครอง

### การปรับปรุงประสิทธิภาพ
- Implement caching สำหรับข้อมูลที่ใช้บ่อย
- Optimize database queries
- Add pagination สำหรับรายการคอร์สที่มีจำนวนมาก

## License

This project is licensed under the MIT License.

## ผู้พัฒนา

- Development Team: CT Learning Team
- Framework: Laravel 9.x
- Frontend: Tailwind CSS + Alpine.js
- Database: MySQL 8.0