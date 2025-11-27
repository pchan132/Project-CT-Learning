<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        "teacher_id",
        "title",
        "description",
        "cover_image_url"
    ];

    // ความสัมพันธ์กับตาราง User (ครูผู้สอน)
    // คือ ครูผู้สอน 1 คน สอนหลายคอร์ส
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // ความสัมพันธ์กับตาราง Enrollment (การลงทะเบียนเรียน)
    // คือ คอร์ส 1 คอร์ส มีการลงทะเบียนเรียนหลายรายการ
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // ความสัมพันธ์กับตาราง Module
    // คือ คอร์ส 1 คอร์ส มีหลาย modules
    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    // ความสัมพันธ์กับตาราง Lesson (ผ่าน Module)
    // คือ คอร์ส 1 คอร์ส มีหลาย lessons (ทุก lessons ในทุก modules)
    public function lessons()
    {
        return $this->hasManyThrough(
            Lesson::class,
            Module::class,
            'course_id', // Foreign key on modules table
            'module_id', // Foreign key on lessons table
            'id',        // Local key on courses table
            'id'         // Local key on modules table
        )->orderBy('modules.order')->orderBy('lessons.order');
    }

    /**
     * นับจำนวน modules ทั้งหมดในคอร์ส
     */
    public function getTotalModulesAttribute()
    {
        return $this->modules()->count();
    }

    /**
     * นับจำนวน lessons ทั้งหมดในคอร์ส
     */
    public function getTotalLessonsAttribute()
    {
        return $this->lessons()->count();
    }

    /**
     * นับจำนวน lessons ที่ student นั้นเรียนเสร็จแล้วในคอร์สนี้
     */
    public function getCompletedLessonsCount($studentId)
    {
        $lessonIds = $this->lessons()->pluck('lessons.id');
        return LessonCompletion::where('student_id', $studentId)
            ->whereIn('lesson_id', $lessonIds)
            ->count();
    }

    /**
     * คำนวณ progress ของ student ในคอร์สนี้
     */
    public function getProgressForStudent($studentId)
    {
        $totalLessons = $this->getTotalLessonsAttribute();
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->getCompletedLessonsCount($studentId);
        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    /**
     * ตรวจสอบว่า student นั้นลงทะเบียนเรียนคอร์สนี้หรือไม่
     */
    public function isEnrolledByStudent($studentId)
    {
        return $this->enrollments()
            ->where('student_id', $studentId)
            ->exists();
    }

    /**
     * Get enrolled students
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
            ->withTimestamps();
    }

    /**
     * Get quizzes through modules
     */
    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Module::class);
    }

    /**
     * Get certificates for this course
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Check if student has passed all quizzes
     */
    public function hasPassedAllQuizzes($studentId)
    {
        foreach ($this->modules as $module) {
            foreach ($module->quizzes as $quiz) {
                if (!$quiz->hasPassedByStudent($studentId)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Check if student can get certificate
     */
    public function canGetCertificate($studentId)
    {
        // ตรวจสอบว่าเรียนครบทุก lesson
        $totalLessons = $this->getTotalLessonsAttribute();
        if ($totalLessons === 0) {
            return false;
        }
        
        $completedLessons = $this->getCompletedLessonsCount($studentId);
        if ($completedLessons < $totalLessons) {
            return false;
        }
        
        // ตรวจสอบว่าผ่านทุก quiz
        return $this->hasPassedAllQuizzes($studentId);
    }

    /**
     * Check if student already has certificate
     */
    public function hasCertificate($studentId)
    {
        return $this->certificates()
            ->where('student_id', $studentId)
            ->exists();
    }

    /**
     * Get student's certificate
     */
    public function getCertificateForStudent($studentId)
    {
        return $this->certificates()
            ->where('student_id', $studentId)
            ->first();
    }

}
