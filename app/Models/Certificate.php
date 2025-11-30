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
        'template_id',
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
     * Certificate uses Template
     */
    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
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
