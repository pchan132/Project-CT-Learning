<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        "teacher_id",
        "title",
        "description",
        "cover_image_url"
    ];

    // ความสัมพันธ์กับตาราง User (ครูผู้สอน)
    // คือ ครูผู้สอน 1 คน สอนหลายคอร์ส
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // ความสัมพันธ์กับตาราง Enrollment (การลงทะเบียนเรียน)
    // คือ คอร์ส 1 คอร์ส มีการลงทะเบียนเรียนหลายรายการ
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }


}
