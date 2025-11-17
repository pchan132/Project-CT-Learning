<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'teacher_id',
        'is_published',
        'passing_score',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'passing_score' => 'integer',
    ];

    /**
     * Relationships
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function enrolledStudents()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot('enrolled_at', 'completed_at', 'certificate_generated')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Module::class);
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Accessors
     */
    public function getStudentCountAttribute()
    {
        return $this->enrolledStudents()->count();
    }

    public function getModuleCountAttribute()
    {
        return $this->modules()->count();
    }

    public function getLessonCountAttribute()
    {
        return $this->lessons()->count();
    }

    /**
     * Check if user is enrolled in this course
     */
    public function isEnrolledBy($userId)
    {
        return $this->enrolledStudents()->where('user_id', $userId)->exists();
    }

    /**
     * Get user progress in this course
     */
    public function getUserProgress($userId)
    {
        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = LessonProgress::where('user_id', $userId)
            ->whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->where('completed', true)
            ->count();

        return ($completedLessons / $totalLessons) * 100;
    }
}