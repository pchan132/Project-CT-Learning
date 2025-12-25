<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'position',
        'bio',
        'signature_image',
        'certificate_background',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

// --------------------------------------------------------------
    // เพิ่มฟังก์ชันเพื่อตรวจสอบ Role ของผู้ใช้
    public function isStudent(): bool{
        return $this->role === 'student';
    }

    public function isTeacher(): bool{
        return $this->role === 'teacher';
    }

    public function isAdmin(): bool{
        return $this->role === 'admin';
    }

    // ความสัมพันธ์กับตาราง LessonCompletion
    // คือ student 1 คน มีการบันทึกการเรียนเสร็จหลายรายการ
    public function lessonCompletions()
    {
        return $this->hasMany(LessonCompletion::class, 'student_id');
    }

    // ความสัมพันธ์กับตาราง Course (สำหรับครู)
    // คือ ครู 1 คน สอนหลายคอร์ส
    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // ความสัมพันธ์กับตาราง Enrollment (สำหรับนักเรียน)
    // คือ นักเรียน 1 คน ลงทะเบียนเรียนได้หลายคอร์ส
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * ดึงรายการคอร์สที่นักเรียนลงทะเบียนเรียน
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id');
    }

    /**
     * นับจำนวนคอร์สที่สอน (สำหรับครู)
     */
    public function getTeachingCoursesCountAttribute()
    {
        return $this->teachingCourses()->count();
    }

    /**
     * นับจำนวนคอร์สที่ลงทะเบียนเรียน (สำหรับนักเรียน)
     */
    public function getEnrolledCoursesCountAttribute()
    {
        return $this->enrollments()->count();
    }

    /**
     * คำนวณ progress รวมของนักเรียนในคอร์สต่างๆ
     */
    public function getOverallProgressAttribute()
    {
        if ($this->isTeacher()) {
            return null;
        }

        $totalLessons = 0;
        $completedLessons = 0;

        foreach ($this->enrolledCourses as $course) {
            $totalLessons += $course->getTotalLessonsAttribute();
            $completedLessons += $course->getCompletedLessonsCount($this->id);
        }

        if ($totalLessons === 0) {
            return 0;
        }

        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    /**
     * ดึง URL รูปโปรไฟล์
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        
        // Default avatar with initials
        return null;
    }

    /**
     * ดึง URL ลายเซ็น
     */
    public function getSignatureImageUrlAttribute()
    {
        if ($this->signature_image) {
            return asset('storage/' . $this->signature_image);
        }
        return null;
    }

    /**
     * ดึงตัวอักษรย่อของชื่อ
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= mb_substr($word, 0, 1);
        }
        
        return mb_strtoupper($initials);
    }

    /**
     * นับจำนวนนักเรียนทั้งหมดในคอร์สของครู
     */
    public function getTotalStudentsAttribute()
    {
        if (!$this->isTeacher()) {
            return 0;
        }

        return $this->teachingCourses()
            ->withCount('enrollments')
            ->get()
            ->sum('enrollments_count');
    }
}
