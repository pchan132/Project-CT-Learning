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
        return $this->hasManyThrough(Lesson::class, Module::class)
            ->orderBy('modules.order')
            ->orderBy('lessons.order');
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
        return $this->lessons()
            ->whereHas('completions', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
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

}
