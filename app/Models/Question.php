<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'order',
    ];

    /**
     * Question belongs to Quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Question has many Answers
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get correct answer
     */
    public function getCorrectAnswerAttribute()
    {
        return $this->answers()->where('is_correct', true)->first();
    }

    /**
     * Check if answer is correct
     */
    public function isCorrectAnswer($answerId)
    {
        return $this->answers()
            ->where('id', $answerId)
            ->where('is_correct', true)
            ->exists();
    }
}
