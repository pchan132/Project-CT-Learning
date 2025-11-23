<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'student_id',
    ];

    /**
     * ความสัมพันธ์กับตาราง Lesson
     * คือ LessonCompletion 1 รายการ เป็นของ Lesson 1 บทเรียน
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * ความสัมพันธ์กับตาราง User (student)
     * คือ LessonCompletion 1 รายการ เป็นของ Student 1 คน
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * ดึงข้อมูล course ที่ lesson completion นี้อยู่
     */
    public function getCourseAttribute()
    {
        return $this->lesson?->module?->course;
    }

    /**
     * ดึงข้อมูล module ที่ lesson completion นี้อยู่
     */
    public function getModuleAttribute()
    {
        return $this->lesson?->module;
    }

    /**
     * Query scope เพื่อกรองตาม student
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Query scope เพื่อกรองตาม lesson
     */
    public function scopeByLesson($query, $lessonId)
    {
        return $query->where('lesson_id', $lessonId);
    }

    /**
     * Query scope เพื่อกรองตาม course
     */
    public function scopeByCourse($query, $courseId)
    {
        return $query->whereHas('lesson.module', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        });
    }

    /**
     * Query scope เพื่อกรองตาม module
     */
    public function scopeByModule($query, $moduleId)
    {
        return $query->whereHas('lesson', function ($query) use ($moduleId) {
            $query->where('module_id', $moduleId);
        });
    }
}