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
        'content_url',
        'content_text',
        'order',
        'required_duration_minutes',
    ];

    /**
     * ความสัมพันธ์กับตาราง Module
     * คือ Lesson 1 บทเรียน เป็นของ Module 1 ตัว
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * ความสัมพันธ์กับตาราง LessonCompletion
     * คือ Lesson 1 บทเรียน มีการบันทึกการเรียนเสร็จหลายรายการ
     */
    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    /**
     * ความสัมพันธ์กับตาราง Quiz
     * คือ Lesson 1 บทเรียน มีแบบทดสอบได้หลายข้อ
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Alias for isCompletedByStudent
     */
    public function isCompletedBy($studentId)
    {
        return $this->isCompletedByStudent($studentId);
    }

    /**
     * ตรวจสอบว่า student นั้นเรียน lesson นี้เสร็จแล้วหรือไม่
     */
    public function isCompletedByStudent($studentId)
    {
        return $this->completions()
            ->where('student_id', $studentId)
            ->exists();
    }

    /**
     * ดึงข้อมูล course ที่ lesson นี้อยู่
     */
    public function getCourseAttribute()
    {
        return $this->module?->course;
    }

    /**
     * ดึง URL สำหรับแสดงเนื้อหา
     */
    public function getContentDisplayUrlAttribute()
    {
        switch ($this->content_type) {
            case 'PDF':
            case 'PPT':
                return $this->content_url ? asset('storage/' . $this->content_url) : null;
            case 'VIDEO':
                return $this->content_url;
            case 'GDRIVE':
                return $this->getGoogleDriveEmbedUrl();
            case 'CANVA':
                return $this->getCanvaEmbedUrl();
            case 'TEXT':
                return null;
            default:
                return null;
        }
    }

    /**
     * แปลง Google Drive share link เป็น embed URL
     */
    public function getGoogleDriveEmbedUrl()
    {
        if (!$this->content_url) {
            return null;
        }

        $url = $this->content_url;

        // รูปแบบ: https://drive.google.com/file/d/FILE_ID/view?usp=sharing
        // หรือ: https://drive.google.com/file/d/FILE_ID/view
        // แปลงเป็น: https://drive.google.com/file/d/FILE_ID/preview
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return "https://drive.google.com/file/d/{$matches[1]}/preview";
        }

        // รูปแบบ: https://docs.google.com/document/d/FILE_ID/...
        // รูปแบบ: https://docs.google.com/presentation/d/FILE_ID/...
        // รูปแบบ: https://docs.google.com/spreadsheets/d/FILE_ID/...
        if (preg_match('/docs\.google\.com\/(document|presentation|spreadsheets)\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $type = $matches[1];
            $fileId = $matches[2];
            return "https://docs.google.com/{$type}/d/{$fileId}/preview";
        }

        // ถ้าเป็นรูปแบบอื่นให้ return URL เดิม
        return $url;
    }

    /**
     * ตรวจสอบว่าเป็น Google Drive content หรือไม่
     */
    public function isGoogleDriveContent()
    {
        return $this->content_type === 'GDRIVE';
    }

    /**
     * แปลง Canva URL เป็น embed URL
     */
    public function getCanvaEmbedUrl()
    {
        if (!$this->content_url) {
            return null;
        }

        $url = $this->content_url;

        // ถ้ามี ?embed อยู่แล้วให้ return เลย
        if (str_contains($url, '?embed')) {
            return $url;
        }

        // รูปแบบ: https://www.canva.com/design/DAxxxxx/view
        // หรือ: https://www.canva.com/design/DAxxxxx/yyyyy/view
        // แปลงเป็น: https://www.canva.com/design/DAxxxxx/view?embed
        if (preg_match('/canva\.com\/design\/([a-zA-Z0-9_-]+)(?:\/[a-zA-Z0-9_-]+)?\/view/', $url, $matches)) {
            $designId = $matches[1];
            return "https://www.canva.com/design/{$designId}/view?embed";
        }

        // รูปแบบอื่นๆ ให้เพิ่ม ?embed ต่อท้าย
        if (str_contains($url, '/view')) {
            return $url . '?embed';
        }

        // ถ้าเป็นรูปแบบอื่นให้ return URL เดิม
        return $url;
    }

    /**
     * ตรวจสอบว่าเป็น Canva content หรือไม่
     */
    public function isCanvaContent()
    {
        return $this->content_type === 'CANVA';
    }

    /**
     * ตรวจสอบว่าเป็น content ประเภทไฟล์หรือไม่
     */
    public function isFileContent()
    {
        return in_array($this->content_type, ['PDF', 'PPT']);
    }

    /**
     * ตรวจสอบว่าเป็น video content หรือไม่
     */
    public function isVideoContent()
    {
        return $this->content_type === 'VIDEO';
    }

    /**
     * ตรวจสอบว่าเป็น text content หรือไม่
     */
    public function isTextContent()
    {
        return $this->content_type === 'TEXT';
    }

    /**
     * ดึงชื่อประเภทเนื้อหาเป็นภาษาที่อ่านง่าย
     */
    public function getContentTypeLabelAttribute()
    {
        return match($this->content_type) {
            'PDF' => 'PDF Document',
            'PPT' => 'PowerPoint',
            'VIDEO' => 'Video',
            'TEXT' => 'Text Content',
            'GDRIVE' => 'Google Drive',
            'CANVA' => 'Canva',
            default => 'Unknown',
        };
    }

    /**
     * Query scope เพื่อกรองตามประเภทเนื้อหา
     */
    public function scopeByContentType($query, $contentType)
    {
        return $query->where('content_type', $contentType);
    }

    /**
     * Query scope เพื่อเรียงลำดับตาม order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}