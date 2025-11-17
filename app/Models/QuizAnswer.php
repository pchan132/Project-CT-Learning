<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'choice_id',
        'answer_text',
    ];

    /**
     * Relationships
     */
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }

    /**
     * Scopes
     */
    public function scopeByAttempt($query, $attemptId)
    {
        return $query->where('quiz_attempt_id', $attemptId);
    }

    public function scopeByQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    /**
     * Accessors
     */
    public function getSelectedChoiceAttribute()
    {
        return $this->choice;
    }

    public function getAnswerTextAttribute()
    {
        if ($this->choice) {
            return $this->choice->choice;
        }
        
        return $this->attributes['answer_text'] ?? null;
    }

    public function getIsCorrectAttribute()
    {
        if ($this->choice) {
            return $this->choice->is_correct;
        }
        
        // For text answers or other types
        return false;
    }

    /**
     * Check if answer is correct
     */
    public function isCorrect()
    {
        if ($this->choice) {
            return $this->choice->is_correct;
        }
        
        // Handle true/false questions
        $question = $this->question;
        if ($question->is_true_false && $this->answer_text) {
            $correctChoice = $question->getCorrectChoice();
            if ($correctChoice) {
                return $correctChoice->isTrueFalseAnswer($this->answer_text);
            }
        }
        
        return false;
    }

    /**
     * Get answer label for display
     */
    public function getDisplayAnswerAttribute()
    {
        if ($this->choice) {
            return $this->choice->getDisplayLabelAttribute();
        }
        
        return $this->answer_text;
    }

    /**
     * Create answer for multiple choice question
     */
    public static function createMultipleChoice($attemptId, $questionId, $choiceId)
    {
        return self::create([
            'quiz_attempt_id' => $attemptId,
            'question_id' => $questionId,
            'choice_id' => $choiceId,
        ]);
    }

    /**
     * Create answer for true/false question
     */
    public static function createTrueFalse($attemptId, $questionId, $answer)
    {
        return self::create([
            'quiz_attempt_id' => $attemptId,
            'question_id' => $questionId,
            'answer_text' => $answer,
        ]);
    }

    /**
     * Create answer for text question
     */
    public static function createText($attemptId, $questionId, $text)
    {
        return self::create([
            'quiz_attempt_id' => $attemptId,
            'question_id' => $questionId,
            'answer_text' => $text,
        ]);
    }

    /**
     * Check if user answered this question
     */
    public static function hasUserAnswered($attemptId, $questionId)
    {
        return self::where('quiz_attempt_id', $attemptId)
            ->where('question_id', $questionId)
            ->exists();
    }

    /**
     * Get user's answer for question in attempt
     */
    public static function getUserAnswer($attemptId, $questionId)
    {
        return self::where('quiz_attempt_id', $attemptId)
            ->where('question_id', $questionId)
            ->first();
    }
}