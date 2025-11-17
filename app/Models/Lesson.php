<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content_type',
        'content',
        'file_path',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Relationships
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function course()
    {
        return $this->hasOneThrough(Course::class, Module::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Scopes
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('content_type', $type);
    }

    /**
     * Accessors
     */
    public function getTypeLabelAttribute()
    {
        return match($this->content_type) {
            'text' => 'ข้อความ',
            'pdf' => 'PDF',
            'video' => 'วิดีโอ',
            'link' => 'ลิงก์',
            default => 'ไม่ทราบ',
        };
    }

    public function getIsVideoAttribute()
    {
        return $this->content_type === 'video';
    }

    public function getIsPdfAttribute()
    {
        return $this->content_type === 'pdf';
    }

    public function getIsTextAttribute()
    {
        return $this->content_type === 'text';
    }

    public function getIsLinkAttribute()
    {
        return $this->content_type === 'link';
    }

    /**
     * Get file URL for PDF/PPT files
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Get video embed URL
     */
    public function getVideoEmbedUrlAttribute()
    {
        if ($this->content_type === 'video' && $this->content) {
            // YouTube
            if (str_contains($this->content, 'youtube.com') || str_contains($this->content, 'youtu.be')) {
                $videoId = $this->extractYoutubeId($this->content);
                return $videoId ? "https://www.youtube.com/embed/{$videoId}" : $this->content;
            }
            
            // Vimeo
            if (str_contains($this->content, 'vimeo.com')) {
                $videoId = $this->extractVimeoId($this->content);
                return $videoId ? "https://player.vimeo.com/video/{$videoId}" : $this->content;
            }
            
            return $this->content;
        }
        return null;
    }

    /**
     * Extract YouTube video ID
     */
    private function extractYoutubeId($url)
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract Vimeo video ID
     */
    private function extractVimeoId($url)
    {
        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Check if user completed this lesson
     */
    public function isCompletedBy($userId)
    {
        return $this->progress()
            ->where('user_id', $userId)
            ->where('completed', true)
            ->exists();
    }

    /**
     * Mark lesson as completed for user
     */
    public function markAsCompleted($userId)
    {
        return $this->progress()->updateOrCreate(
            ['user_id' => $userId],
            ['completed' => true, 'completed_at' => now()]
        );
    }
}