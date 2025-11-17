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

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Relationships
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Scopes
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Accessors
     */
    public function getLessonCountAttribute()
    {
        return $this->lessons()->count();
    }

    public function getQuizCountAttribute()
    {
        return $this->quizzes()->count();
    }

    /**
     * Get user progress in this module
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

    /**
     * Check if user completed all lessons in this module
     */
    public function isCompletedBy($userId)
    {
        $totalLessons = $this->lessons()->count();
        $completedLessons = LessonProgress::where('user_id', $userId)
            ->whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->where('completed', true)
            ->count();

        return $totalLessons > 0 && $completedLessons === $totalLessons;
    }
}