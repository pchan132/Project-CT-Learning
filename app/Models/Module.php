<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
    ];

    /**
     * ความสัมพันธ์กับตาราง Course
     * คือ Module 1 ตัว เป็นของ Course 1 คอร์ส
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * ความสัมพันธ์กับตาราง Lesson
     * คือ Module 1 ตัว มีหลาย Lessons
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * นับจำนวน lessons ทั้งหมดใน module
     */
    public function getTotalLessonsAttribute()
    {
        return $this->lessons()->count();
    }

    /**
     * ดึง lessons ที่ student นั้นเรียนเสร็จแล้วใน module นี้
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
     * คำนวณ progress ของ student ใน module นี้
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
     * Module has many Quizzes
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Query scope เพื่อเรียงลำดับตาม order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}