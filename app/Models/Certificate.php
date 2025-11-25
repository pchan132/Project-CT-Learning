<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'certificate_number',
        'pdf_path',
        'issued_date',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    /**
     * Certificate belongs to Student
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Certificate belongs to Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Generate certificate number
     */
    public static function generateCertificateNumber()
    {
        $year = now()->year;
        $month = now()->format('m');
        $random = strtoupper(substr(md5(uniqid()), 0, 8));
        
        return "CERT-{$year}{$month}-{$random}";
    }

    /**
     * Get full PDF URL
     */
    public function getPdfUrlAttribute()
    {
        return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
    }
}
