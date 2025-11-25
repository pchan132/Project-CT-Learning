<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'passing_score',
        'time_limit',
    ];

    /**
     * Quiz belongs to Module
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Quiz has many Questions
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Quiz has many Attempts
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get course through module
     */
    public function getCourseAttribute()
    {
        return $this->module?->course;
    }

    /**
     * Check if student has passed this quiz
     */
    public function hasPassedByStudent($studentId)
    {
        return $this->attempts()
            ->where('student_id', $studentId)
            ->where('passed', true)
            ->exists();
    }

    /**
     * Get best attempt for student
     */
    public function getBestAttemptForStudent($studentId)
    {
        return $this->attempts()
            ->where('student_id', $studentId)
            ->orderByDesc('score')
            ->first();
    }
}
