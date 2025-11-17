<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'question_type',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Relationships
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class)->orderBy('order');
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Scopes
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeWithChoices($query)
    {
        return $query->with('choices');
    }

    /**
     * Accessors
     */
    public function getTypeLabelAttribute()
    {
        return match($this->question_type) {
            'multiple_choice' => 'ปรนัย',
            'true_false' => 'ถูก/ผิด',
            default => 'ไม่ทราบ',
        };
    }

    public function getIsMultipleChoiceAttribute()
    {
        return $this->question_type === 'multiple_choice';
    }

    public function getIsTrueFalseAttribute()
    {
        return $this->question_type === 'true_false';
    }

    /**
     * Get correct choice
     */
    public function getCorrectChoice()
    {
        return $this->choices()->where('is_correct', true)->first();
    }

    /**
     * Check if user answered correctly
     */
    public function isAnsweredByUser($userId, $attemptId)
    {
        return $this->answers()
            ->whereHas('quizAttempt', function ($query) use ($userId, $attemptId) {
                $query->where('user_id', $userId)->where('id', $attemptId);
            })
            ->exists();
    }

    /**
     * Get user's answer for specific attempt
     */
    public function getUserAnswer($userId, $attemptId)
    {
        return $this->answers()
            ->whereHas('quizAttempt', function ($query) use ($userId, $attemptId) {
                $query->where('user_id', $userId)->where('id', $attemptId);
            })
            ->first();
    }

    /**
     * Create true/false choices automatically
     */
    public function createTrueFalseChoices()
    {
        $this->choices()->createMany([
            ['choice' => 'ถูก', 'is_correct' => false, 'order' => 1],
            ['choice' => 'ผิด', 'is_correct' => false, 'order' => 2],
        ]);
    }

    /**
     * Validate if question has correct answer
     */
    public function hasCorrectAnswer()
    {
        return $this->choices()->where('is_correct', true)->exists();
    }

    /**
     * Get total points for this question (always 1 for now)
     */
    public function getPointsAttribute()
    {
        return 1;
    }
}