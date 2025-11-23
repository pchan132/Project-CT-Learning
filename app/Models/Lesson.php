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
            case 'TEXT':
                return null;
            default:
                return null;
        }
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