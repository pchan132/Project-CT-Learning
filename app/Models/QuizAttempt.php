<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'total_questions',
        'passed',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'score' => 'integer',
        'total_questions' => 'integer',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Scopes
     */
    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('passed', false);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessors
     */
    public function getScorePercentageAttribute()
    {
        if ($this->total_questions === 0) {
            return 0;
        }
        
        return round(($this->score / $this->total_questions) * 100);
    }

    public function getDurationAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInSeconds($this->completed_at);
        }
        return null;
    }

    public function getDurationFormattedAttribute()
    {
        $duration = $this->duration;
        if (!$duration) {
            return 'N/A';
        }

        $minutes = floor($duration / 60);
        $seconds = $duration % 60;
        
        return "{$minutes} นาที {$seconds} วินาที";
    }

    public function getStatusLabelAttribute()
    {
        if ($this->passed) {
            return '<span class="text-green-600 font-semibold">ผ่าน</span>';
        }
        return '<span class="text-red-600 font-semibold">ไม่ผ่าน</span>';
    }

    /**
     * Calculate final score based on answers
     */
    public function calculateScore()
    {
        $correctAnswers = 0;
        
        foreach ($this->answers as $answer) {
            if ($answer->isCorrect()) {
                $correctAnswers++;
            }
        }
        
        $this->update([
            'score' => $correctAnswers,
            'total_questions' => $this->quiz->questions()->count(),
            'passed' => $this->quiz->didAttemptPass($correctAnswers, $this->quiz->questions()->count()),
        ]);
        
        return $correctAnswers;
    }

    /**
     * Complete the attempt
     */
    public function complete()
    {
        $this->update([
            'completed_at' => now(),
        ]);
        
        return $this->calculateScore();
    }

    /**
     * Check if attempt is completed
     */
    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }

    /**
     * Check if attempt is timed out
     */
    public function isTimedOut()
    {
        if (!$this->quiz->time_limit) {
            return false;
        }
        
        $timeLimit = $this->started_at->addMinutes($this->quiz->time_limit);
        return now()->greaterThan($timeLimit);
    }

    /**
     * Get remaining time in seconds
     */
    public function getRemainingTime()
    {
        if (!$this->quiz->time_limit || $this->isCompleted()) {
            return null;
        }
        
        $timeLimit = $this->started_at->addMinutes($this->quiz->time_limit);
        $remaining = now()->diffInSeconds($timeLimit, false);
        
        return $remaining > 0 ? $remaining : 0;
    }
}