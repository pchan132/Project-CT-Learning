<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
    ];
    
    // ความสัมพันธ์กับตาราง Course
    // คือ คอร์ส 1 คอร์ส มีการลงทะเบียนเรียนหลายรายการ
    public function course()
    {
        return $this->belongsTo(Course::class);
        // การลงทะเบียนนี้ เป็นของคอร์สไหน?
    }
    
    // ความสัมพันธ์กับตาราง User (นักเรียน)
    // คือ นักเรียน 1 คน ลงทะเบียนเรียนได้หลายคอร์ส
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
        // การลงทะเบียนนี้ เป็นของนักเรียนคนไหน?
    }
}