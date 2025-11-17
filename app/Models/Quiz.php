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
        'time_limit',
        'passing_score',
    ];

    protected $casts = [
        'time_limit' => 'integer',
        'passing_score' => 'integer',
    ];

    /**
     * Relationships
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Scopes
     */
    public function scopeWithQuestions($query)
    {
        return $query->with('questions.choices');
    }

    /**
     * Accessors
     */
    public function getQuestionCountAttribute()
    {
        return $this->questions()->count();
    }

    public function getTimeLimitFormattedAttribute()
    {
        if ($this->time_limit) {
            $hours = floor($this->time_limit / 60);
            $minutes = $this->time_limit % 60;
            
            if ($hours > 0) {
                return "{$hours} ชั่วโมง {$minutes} นาที";
            }
            return "{$minutes} นาที";
        }
        return 'ไม่จำกัดเวลา';
    }

    /**
     * Get user's best attempt
     */
    public function getBestAttemptForUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('passed', true)
            ->orderBy('score', 'desc')
            ->first();
    }

    /**
     * Get user's latest attempt
     */
    public function getLatestAttemptForUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Check if user passed this quiz
     */
    public function isPassedByUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('passed', true)
            ->exists();
    }

    /**
     * Calculate score percentage
     */
    public function calculateScorePercentage($correctAnswers, $totalQuestions)
    {
        if ($totalQuestions === 0) {
            return 0;
        }
        
        return round(($correctAnswers / $totalQuestions) * 100);
    }

    /**
     * Check if attempt passed
     */
    public function didAttemptPass($score, $totalQuestions)
    {
        $percentage = $this->calculateScorePercentage($score, $totalQuestions);
        return $percentage >= $this->passing_score;
    }
}