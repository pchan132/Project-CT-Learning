<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'choice',
        'is_correct',
        'order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relationships
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Scopes
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Accessors
     */
    public function getIsCorrectLabelAttribute()
    {
        return $this->is_correct ? 'ถูกต้อง' : 'ผิด';
    }

    public function getLetterAttribute()
    {
        return chr(65 + $this->order - 1); // A, B, C, D...
    }

    /**
     * Check if this choice was selected by user in attempt
     */
    public function isSelectedByUser($userId, $attemptId)
    {
        return $this->answers()
            ->whereHas('quizAttempt', function ($query) use ($userId, $attemptId) {
                $query->where('user_id', $userId)->where('id', $attemptId);
            })
            ->exists();
    }

    /**
     * Get user's selection for this choice in attempt
     */
    public function getUserSelection($userId, $attemptId)
    {
        return $this->answers()
            ->whereHas('quizAttempt', function ($query) use ($userId, $attemptId) {
                $query->where('user_id', $userId)->where('id', $attemptId);
            })
            ->first();
    }

    /**
     * Mark this choice as correct
     */
    public function markAsCorrect()
    {
        // First, mark all choices for this question as incorrect
        $this->question->choices()->update(['is_correct' => false]);
        
        // Then mark this choice as correct
        $this->update(['is_correct' => true]);
        
        return $this;
    }

    /**
     * Get choice label for display
     */
    public function getDisplayLabelAttribute()
    {
        return "{$this->letter}. {$this->choice}";
    }

    /**
     * Check if this is the correct answer for true/false questions
     */
    public function isTrueFalseAnswer($answer)
    {
        $question = $this->question;
        if ($question->is_true_false) {
            return ($answer === 'true' && $this->choice === 'ถูก') ||
                   ($answer === 'false' && $this->choice === 'ผิด');
        }
        return false;
    }
}