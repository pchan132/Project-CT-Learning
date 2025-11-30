# 🎓 CT Learning - Certificate System Guide

## 📋 คู่มือระบบใบประกาศนียบัตร

เอกสารนี้คือคู่มือระบบออกใบประกาศนียบัตร PDF อัตโนมัติสำหรับระบบ CT Learning อย่างละเอียดครบถ้วน

---

## 🎯 ภาพรวมระบบ

ระบบใบประกาศนียบัตรของ CT Learning ทำงานอัตโนมัติเมื่อนักเรียนเรียนครบคอร์สและผ่านแบบทดสอบทั้งหมด โดยมีฟีเจอร์หลักดังนี้:

### ✨ ฟีเจอร์หลัก
- ✅ **Automatic Generation**: สร้าง PDF อัตโนมัติเมื่อผ่านเงื่อนไข
- ✅ **Unique Certificate Numbers**: เลขที่อ้างอิงไม่ซ้ำกัน
- ✅ **Professional Templates**: รูปแบบเอกสารสวยงาม รองรับภาษาไทย
- ✅ **Download & Share**: ดาวน์โหลดและแชร์ได้
- ✅ **Verification System**: ตรวจสอบความถูกต้องของใบประกาศนียบัตร
- ✅ **PDF Storage**: จัดเก็บ PDF ในระบบอย่างปลอดภัย

### 🔄 Workflow การทำงาน
```
Student Learning Progress
├── Complete All Lessons (100%)
├── Pass All Quizzes (>= Passing Score)
├── Click "Get Certificate"
├── System Validates Conditions
├── Generate Certificate PDF
├── Save to Database & Storage
├── Display Download Options
└── Email Notification (Optional)
```

---

## 🗂️ โครงสร้างฐานข้อมูล

### Table: `certificates`
```sql
CREATE TABLE certificates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    certificate_number VARCHAR(50) UNIQUE NOT NULL,
    pdf_path VARCHAR(500) NOT NULL,
    issued_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    
    INDEX idx_student_course (student_id, course_id),
    INDEX idx_certificate_number (certificate_number)
);
```

### ความสัมพันธ์กับตารางอื่น
```sql
-- certificates 1:N กับ users
-- certificates 1:N กับ courses
-- student สามารถมีหลาย certificates
-- course สามารถมีหลาย certificates
```

---

## 📋 Certificate Model

### Location: `app/Models/Certificate.php`

### Relationships
```php
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
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    /**
     * ความสัมพันธ์กับ User (นักเรียน)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * ความสัมพันธ์กับ Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
```

### Methods

#### 1. generateCertificateNumber()
สร้างเลขที่ใบประกาศแบบ unique
```php
public static function generateCertificateNumber()
{
    $year = now()->year;           // 2025
    $month = now()->format('m');   // 11
    $random = strtoupper(substr(md5(uniqid()), 0, 8));  // A1B2C3D4
    
    return "CERT-{$year}{$month}-{$random}";
    // Result: CERT-202511-A1B2C3D4
}
```

#### 2. getPdfUrlAttribute()
ดึง URL เต็มของไฟล์ PDF
```php
public function getPdfUrlAttribute()
{
    return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
}
```

#### 3. scopeForStudent()
Query scope สำหรับกรองตามนักเรียน
```php
public function scopeForStudent($query, $studentId)
{
    return $query->where('student_id', $studentId);
}
```

#### 4. scopeForCourse()
Query scope สำหรับกรองตามคอร์ส
```php
public function scopeForCourse($query, $courseId)
{
    return $query->where('course_id', $courseId);
}
```

---

## 🎯 Certificate Controller

### Location: `app/Http/Controllers/Student/CertificateController.php`

### 1. index()
แสดงรายการใบประกาศทั้งหมดของ Student
```php
public function index()
{
    $certificates = Certificate::where('student_id', auth()->id())
        ->with('course')
        ->latest('issued_at')
        ->paginate(12);

    return view('student.certificates.index', compact('certificates'));
}
```

**Route:** `GET /student/certificates`  
**View:** `resources/views/student/certificates/index.blade.php`

### 2. generate(Course $course)
สร้างใบประกาศสำหรับคอร์ส
```php
public function generate(Course $course)
{
    $student = auth()->user();

    // 1. ตรวจสอบว่าลงทะเบียนหรือไม่
    if (!$course->isEnrolledByStudent($student->id)) {
        return back()->with('error', 'คุณยังไม่ได้ลงทะเบียนเรียนคอร์สนี้');
    }

    // 2. ตรวจสอบเงื่อนไขการได้รับ Certificate
    if (!$this->canGetCertificate($course, $student->id)) {
        return back()->with('error', 'คุณยังไม่สามารถขอใบประกาศนียบัตรได้');
    }

    // 3. ตรวจสอบว่ามีอยู่แล้วหรือไม่
    $existingCert = Certificate::where('student_id', $student->id)
        ->where('course_id', $course->id)
        ->first();
        
    if ($existingCert) {
        return redirect()->route('student.certificates.show', $existingCert->id);
    }

    // 4. สร้าง Certificate
    $certificate = Certificate::create([
        'student_id' => $student->id,
        'course_id' => $course->id,
        'certificate_number' => Certificate::generateCertificateNumber(),
        'issued_at' => now(),
    ]);

    // 5. Generate PDF
    $pdf = $this->generatePdf($certificate, $student, $course);

    // 6. บันทึกไฟล์
    $filename = "certificates/cert-{$certificate->id}.pdf";
    Storage::disk('public')->put($filename, $pdf->output());

    // 7. Update path
    $certificate->update(['pdf_path' => $filename]);

    return redirect()->route('student.certificates.show', $certificate->id)
        ->with('success', 'สร้างใบประกาศนียบัตรเรียบร้อยแล้ว');
}
```

**Route:** `POST /student/courses/{course}/certificates/generate`

### 3. canGetCertificate($course, $studentId)
ตรวจสอบว่า Student สามารถขอใบประกาศได้หรือไม่
```php
private function canGetCertificate($course, $studentId)
{
    // ตรวจสอบว่าเรียนครบทุก lesson
    $totalLessons = $course->getTotalLessonsAttribute();
    $completedLessons = $course->getCompletedLessonsCount($studentId);

    if ($completedLessons < $totalLessons) {
        return false;
    }

    // ตรวจสอบว่าผ่านทุก quiz
    foreach ($course->modules as $module) {
        foreach ($module->quizzes as $quiz) {
            if (!$quiz->hasPassedByStudent($studentId)) {
                return false;
            }
        }
    }

    return true;
}
```

### 4. show(Certificate $certificate)
แสดงใบประกาศ
```php
public function show(Certificate $certificate)
{
    // Security: ตรวจสอบว่าเป็นของ student คนนี้จริง
    if ($certificate->student_id !== auth()->id()) {
        abort(403, 'คุณไม่มีสิทธิ์เข้าถึงใบประกาศนียบัตรนี้');
    }

    $certificate->load(['student', 'course', 'course.teacher']);

    return view('student.certificates.show', compact('certificate'));
}
```

**Route:** `GET /student/certificates/{certificate}`

### 5. download(Certificate $certificate)
ดาวน์โหลดไฟล์ PDF
```php
public function download(Certificate $certificate)
{
    // 1. ตรวจสอบสิทธิ์
    if ($certificate->student_id !== auth()->id()) {
        abort(403);
    }

    // 2. ตรวจสอบว่ามีไฟล์หรือไม่
    if (!Storage::disk('public')->exists($certificate->pdf_path)) {
        return back()->with('error', 'ไม่พบไฟล์ใบประกาศนียบัตร');
    }

    // 3. ดาวน์โหลด
    return Storage::disk('public')->download(
        $certificate->pdf_path, 
        'certificate-' . $certificate->certificate_number . '.pdf'
    );
}
```

**Route:** `GET /student/certificates/{certificate}/download`

### 6. generatePdf($certificate, $student, $course)
สร้างไฟล์ PDF
```php
private function generatePdf($certificate, $student, $course)
{
    $data = [
        'certificate' => $certificate,
        'student' => $student,
        'course' => $course,
        'teacher' => $course->teacher,
    ];

    return Pdf::loadView('certificates.template', $data)
        ->setPaper('a4', 'landscape')
        ->setOption('defaultFont', 'THSarabunNew');
}
```

---

## 🎨 PDF Template

### Location: `resources/views/certificates/template.blade.php`

### ตัวอย่าง Template แบบสมบูรณ์
```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ใบประกาศนียบัตร - {{ $course->title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            font-family: 'THSarabunNew', 'Sarabun', sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f0f0;
        }
        
        .certificate {
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: table;
            position: relative;
            overflow: hidden;
        }
        
        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><text y="50" font-size="50" fill="rgba(255,255,255,0.03)">🎓</text></svg>');
            background-size: 100px 100px;
            opacity: 0.3;
        }
        
        .content {
            display: table-cell;
            vertical-align: middle;
            padding: 50px;
            position: relative;
            z-index: 1;
        }
        
        .border {
            border: 15px solid transparent;
            border-image: linear-gradient(45deg, gold, #FFD700, gold) 1;
            padding: 60px;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            position: relative;
            border-radius: 10px;
        }
        
        .border::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 2px solid gold;
            border-radius: 8px;
            z-index: -1;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .title {
            font-size: 48px;
            color: #2c3e50;
            margin: 0;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .subtitle {
            font-size: 24px;
            color: #7f8c8d;
            margin: 10px 0 0 0;
            font-style: italic;
        }
        
        .recipient {
            text-align: center;
            margin: 40px 0;
        }
        
        .recipient-label {
            font-size: 20px;
            color: #555;
            margin-bottom: 10px;
        }
        
        .student-name {
            font-size: 42px;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .course-info {
            text-align: center;
            margin: 40px 0;
        }
        
        .completion-text {
            font-size: 20px;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .course-title {
            font-size: 32px;
            color: #2c3e50;
            font-weight: bold;
            margin: 20px 0;
            text-transform: capitalize;
        }
        
        .department {
            font-size: 18px;
            color: #666;
            margin-top: 20px;
        }
        
        .certificate-details {
            margin-top: 60px;
            text-align: center;
        }
        
        .certificate-number {
            font-size: 16px;
            color: #888;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
        }
        
        .date {
            font-size: 16px;
            color: #666;
        }
        
        .signature-section {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .signature-block {
            text-align: center;
            min-width: 250px;
        }
        
        .signature-line {
            border-bottom: 2px solid #333;
            width: 200px;
            margin: 10px auto 5px;
        }
        
        .signature-title {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .signature-name {
            font-size: 16px;
            color: #2c3e50;
            font-weight: bold;
        }
        
        .seal {
            position: absolute;
            bottom: 30px;
            right: 30px;
            width: 80px;
            height: 80px;
            border: 3px solid gold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .seal-text {
            font-size: 12px;
            font-weight: bold;
            color: #667eea;
            text-align: center;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 200px;
            color: rgba(0,0,0,0.05);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="watermark">CT</div>
        
        <div class="content">
            <div class="border">
                <div class="header">
                    <h1 class="title">🎓 ใบประกาศนียบัตร</h1>
                    <h2 class="subtitle">Certificate of Completion</h2>
                </div>
                
                <div class="recipient">
                    <p class="recipient-label">มอบให้แก่ / Presented to</p>
                    <div class="student-name">{{ $student->name }}</div>
                </div>
                
                <div class="course-info">
                    <p class="completion-text">
                        ได้ผ่านการอบรมหลักสูตรอย่างสมบูรณ์<br>
                        Has successfully completed the course
                    </p>
                    
                    <div class="course-title">{{ $course->title }}</div>
                    
                    <p class="department">
                        <strong>แผนกเทคโนโลยีคอมพิวเตอร์</strong><br>
                        Department of Computer Technology
                    </p>
                </div>
                
                <div class="certificate-details">
                    <div class="certificate-number">
                        เลขที่ใบประกาศนียบัตร: {{ $certificate->certificate_number }}
                    </div>
                    <div class="date">
                        วันที่ออกใบประกาศ: {{ $certificate->issued_at->format('d/m/Y') }}<br>
                        Issued Date: {{ $certificate->issued_at->format('F d, Y') }}
                    </div>
                </div>
                
                <div class="signature-section">
                    <div class="signature-block">
                        <div class="signature-title">ผู้สอน</div>
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $course->teacher->name }}</div>
                    </div>
                    
                    <div class="signature-block">
                        <div class="signature-title">ผู้อำนวยการ</div>
                        <div class="signature-line"></div>
                        <div class="signature-name">ผู้อำนวยการแผนกเทคโนโลยีคอมพิวเตอร์</div>
                    </div>
                </div>
                
                <div class="seal">
                    <div class="seal-text">CT<br>2025</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

---

## 🛣️ Routes

### Certificate Routes
```php
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    // Certificate List
    Route::get('/certificates', [CertificateController::class, 'index'])
        ->name('certificates.index');
    
    // Generate Certificate
    Route::post('/courses/{course}/certificates/generate', [CertificateController::class, 'generate'])
        ->name('certificates.generate');
    
    // View Certificate
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])
        ->name('certificates.show');
    
    // Download Certificate
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])
        ->name('certificates.download');
});
```

### Public Verification Route (Optional)
```php
// สำหรับการตรวจสอบใบประกาศจากภายนอก
Route::get('/certificates/verify/{certificateNumber}', function($certificateNumber) {
    $certificate = Certificate::where('certificate_number', $certificateNumber)
        ->with(['student', 'course'])
        ->first();
        
    if (!$certificate) {
        return view('certificates.invalid');
    }
    
    return view('certificates.valid', compact('certificate'));
})->name('certificates.verify');
```

---

## 📱 Views Structure

```
resources/views/student/certificates/
├── index.blade.php       # รายการใบประกาศทั้งหมด
└── show.blade.php        # แสดงใบประกาศ + ปุ่มดาวน์โหลด

resources/views/certificates/
├── template.blade.php    # Template PDF
├── valid.blade.php       # หน้าตรวจสอบ (valid)
└── invalid.blade.php     # หน้าตรวจสอบ (invalid)
```

### View: index.blade.php
```blade
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">ใบประกาศนียบัตรของฉัน</h2>
                <p class="mt-2 text-gray-600">ดาวน์โหลดใบประกาศนียบัตรจากคอร์สที่คุณเรียนจบแล้ว</p>
            </div>
            
            @if($certificates->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีใบประกาศนียบัตร</h3>
                    <p class="mt-1 text-sm text-gray-500">เรียนครบคอร์สและผ่านแบบทดสอบเพื่อรับใบประกาศนียบัตร</p>
                    <div class="mt-6">
                        <a href="{{ route('student.courses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            ดูคอร์สเรียน
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($certificates as $certificate)
                        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $certificate->course->title }}</h3>
                                        <p class="text-sm text-gray-500">คอร์สเรียน</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        เลขที่: {{ $certificate->certificate_number }}
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        ออกเมื่อ: {{ $certificate->issued_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('student.certificates.show', $certificate->id) }}" 
                                       class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        ดูใบประกาศ
                                    </a>
                                    
                                    <a href="{{ route('student.certificates.download', $certificate->id) }}" 
                                       class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        ดาวน์โหลด
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($certificates->hasPages())
                    <div class="mt-8">
                        {{ $certificates->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
```

### View: show.blade.php
```blade
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">ใบประกาศนียบัตร</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ข้อมูลนักเรียน</h3>
                            <dl class="space-y-1">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">ชื่อ:</dt>
                                    <dd class="text-sm text-gray-900">{{ $certificate->student->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">อีเมล:</dt>
                                    <dd class="text-sm text-gray-900">{{ $certificate->student->email }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ข้อมูลคอร์ส</h3>
                            <dl class="space-y-1">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">คอร์ส:</dt>
                                    <dd class="text-sm text-gray-900">{{ $certificate->course->title }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">ครูผู้สอน:</dt>
                                    <dd class="text-sm text-gray-900">{{ $certificate->course->teacher->name }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ข้อมูลใบประกาศ</h3>
                            <dl class="space-y-1">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">เลขที่:</dt>
                                    <dd class="text-sm text-gray-900 font-mono">{{ $certificate->certificate_number }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">วันที่ออก:</dt>
                                    <dd class="text-sm text-gray-900">{{ $certificate->issued_at->format('d/m/Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">การตรวจสอบ</h3>
                            <div class="flex items-center space-x-2">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-green-600">ใบประกาศนียบัตรถูกต้อง</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                สามารถตรวจสอบได้ที่: {{ url('/certificates/verify/' . $certificate->certificate_number) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('student.certificates.download', $certificate->id) }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            ดาวน์โหลด PDF
                        </a>
                        
                        <a href="{{ route('student.courses.show', $certificate->course->id) }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            กลับไปยังคอร์ส
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

---

## 💡 การปรับแต่งระบบ

### 1. เปลี่ยนเงื่อนไขการได้ Certificate

#### ตัวอย่าง: กำหนดคะแนนเฉลี่ยขั้นต่ำ
```php
private function canGetCertificate($course, $studentId)
{
    // ตรวจสอบว่าเรียนครบทุก lesson
    $totalLessons = $course->getTotalLessonsAttribute();
    $completedLessons = $course->getCompletedLessonsCount($studentId);

    if ($completedLessons < $totalLessons) {
        return false;
    }

    // ตรวจสอบคะแนนเฉลี่ยของทุก quiz
    $totalScore = 0;
    $quizCount = 0;

    foreach ($course->modules as $module) {
        foreach ($module->quizzes as $quiz) {
            $bestAttempt = $quiz->getBestAttemptForStudent($studentId);
            if (!$bestAttempt) {
                return false; // ยังไม่ได้ทำ quiz
            }
            $totalScore += $bestAttempt->score;
            $quizCount++;
        }
    }

    $averageScore = $quizCount > 0 ? ($totalScore / $quizCount) : 0;

    // กำหนดคะแนนเฉลี่ยขั้นต่ำ 80%
    if ($averageScore < 80) {
        return false;
    }

    return true;
}
```

### 2. เปลี่ยนรูปแบบเลขที่ใบประกาศ

#### รูปแบบ: CT-2025-001234 (Sequential)
```php
public static function generateCertificateNumber()
{
    $year = now()->year;
    
    // นับจำนวน certificate ในปีนี้
    $lastCert = Certificate::whereYear('created_at', $year)
        ->orderBy('created_at', 'desc')
        ->first();
    
    $nextNumber = $lastCert ? ((int) substr($lastCert->certificate_number, -6) + 1) : 1;
    $number = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    
    return "CT-{$year}-{$number}";
    // Result: CT-2025-000001, CT-2025-000002, ...
}
```

### 3. เพิ่มลายเซ็นดิจิทัล

#### 1. เพิ่มฟิลด์ใน users table
```php
// Migration
Schema::table('users', function (Blueprint $table) {
    $table->string('signature_path')->nullable();
});
```

#### 2. อัพโหลดรูปลายเซ็นของ Teacher
เพิ่มใน Teacher profile management

#### 3. แก้ไข Template
```blade
<div class="signature-block">
    <div class="signature-title">ผู้สอน</div>
    @if($course->teacher->signature_path)
        <img src="{{ asset('storage/' . $course->teacher->signature_path) }}" 
             alt="Signature of {{ $course->teacher->name }}" 
             style="width: 150px; height: 60px; object-fit: contain;">
    @else
        <div class="signature-line"></div>
    @endif
    <div class="signature-name">{{ $course->teacher->name }}</div>
</div>
```

### 4. เพิ่ม QR Code สำหรับการตรวจสอบ

#### 1. ติดตั้ง Package
```bash
composer require simplesoftwareio/simple-qrcode
```

#### 2. สร้าง Verification URL
```php
// ใน CertificateController::generate()
$verifyUrl = route('certificates.verify', $certificate->certificate_number);
```

#### 3. เพิ่มใน Template
```blade
<div style="position: absolute; bottom: 30px; right: 30px;">
    <div style="text-align: center;">
        {!! QrCode::size(80)->generate($verifyUrl) !!}
        <p style="font-size: 10px; margin-top: 5px;">สแกนเพื่อยืนยัน</p>
    </div>
</div>
```

#### 4. สร้าง Verification Route
```php
Route::get('/certificates/verify/{certificateNumber}', function($certificateNumber) {
    $certificate = Certificate::where('certificate_number', $certificateNumber)
        ->with(['student', 'course', 'course.teacher'])
        ->first();
    
    if (!$certificate) {
        return view('certificates.invalid');
    }
    
    return view('certificates.valid', compact('certificate'));
})->name('certificates.verify');
```

### 5. ส่ง Certificate ทาง Email

#### 1. สร้าง Mailable
```bash
php artisan make:mail CertificateIssued
```

#### 2. Config Mailable
```php
// app/Mail/CertificateIssued.php
class CertificateIssued extends Mailable
{
    public $certificate;
    public $student;
    public $course;

    public function __construct($certificate, $student, $course)
    {
        $this->certificate = $certificate;
        $this->student = $student;
        $this->course = $course;
    }
    
    public function build()
    {
        return $this->subject('🎓 ใบประกาศนียบัตร - ' . $this->course->title)
            ->view('emails.certificate-issued')
            ->attach(storage_path('app/public/' . $this->certificate->pdf_path), [
                'as' => 'certificate-' . $this->certificate->certificate_number . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
```

#### 3. ส่ง Email
```php
// ใน CertificateController::generate()
use App\Mail\CertificateIssued;
use Illuminate\Support\Facades\Mail;

// หลังจากสร้าง certificate สำเร็จ
try {
    Mail::to($student->email)->send(new CertificateIssued($certificate, $student, $course));
} catch (\Exception $e) {
    \Log::error('Failed to send certificate email: ' . $e->getMessage());
}
```

---

## 🎨 การจัดการ Font ภาษาไทย

### วิธีเพิ่ม Font ไทยใน DomPDF

#### 1. Download Font THSarabunNew
```bash
# Download from: https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap
# หรือใช้ font ภาษาไทยอื่นๆ
```

#### 2. Copy Font ไปยัง Project
```bash
# สร้าง folder สำหรับ font
mkdir -p public/fonts

# Copy font file
cp THSarabunNew.ttf public/fonts/
```

#### 3. Config ใน Template
```css
@font-face {
    font-family: 'THSarabunNew';
    src: url('{{ public_path('fonts/THSarabunNew.ttf') }}') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'THSarabunNew';
    src: url('{{ public_path('fonts/THSarabunNew-Bold.ttf') }}') format('truetype');
    font-weight: bold;
    font-style: normal;
}

body {
    font-family: 'THSarabunNew', 'Sarabun', sans-serif;
}
```

#### 4. Config DomPDF
```php
// ใน config/dompdf.php
return [
    'font_dir' => public_path('fonts'),
    'font_cache' => storage_path('fonts'),
    // ... other config
];
```

---

## 📊 สถิติและการวิเคราะห์

### ดึงสถิติ Certificate

#### 1. จำนวน Certificate ทั้งหมด
```php
$totalCertificates = Certificate::count();
```

#### 2. Certificate ที่ออกในเดือนนี้
```php
$thisMonthCerts = Certificate::whereMonth('issued_at', now()->month)
    ->whereYear('issued_at', now()->year)
    ->count();
```

#### 3. Top Courses ที่ออก Certificate มากที่สุด
```php
$topCourses = Course::withCount('certificates')
    ->orderByDesc('certificates_count')
    ->take(10)
    ->get();
```

#### 4. Students ที่มี Certificate มากที่สุด
```php
$topStudents = User::where('role', 'student')
    ->withCount('certificates')
    ->orderByDesc('certificates_count')
    ->take(10)
    ->get();
```

#### 5. Certificate Statistics รายเดือน
```php
$monthlyStats = Certificate::selectRaw('
        DATE_FORMAT(issued_at, "%Y-%m") as month,
        COUNT(*) as count
    ')
    ->groupBy('month')
    ->orderBy('month', 'desc')
    ->take(12)
    ->get();
```

### Dashboard สำหรับ Admin
```php
// ใน AdminController
public function certificates()
{
    $stats = [
        'total' => Certificate::count(),
        'this_month' => Certificate::whereMonth('issued_at', now()->month)->count(),
        'this_year' => Certificate::whereYear('issued_at', now()->year)->count(),
        'top_courses' => Course::withCount('certificates')
            ->orderByDesc('certificates_count')
            ->take(5)
            ->get(),
        'monthly_stats' => Certificate::selectRaw('
                DATE_FORMAT(issued_at, "%Y-%m") as month,
                COUNT(*) as count
            ')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get(),
    ];

    return view('admin.certificates', compact('stats'));
}
```

---

## 🔒 Security Best Practices

### 1. Validate Student Progress
```php
// ตรวจสอบว่าเรียนจริงๆ ไม่ใช่แค่บันทึกเท็จ
$completions = LessonCompletion::where('student_id', $studentId)
    ->whereHas('lesson.module', function($q) use ($courseId) {
        $q->where('course_id', $courseId);
    })
    ->get();

// ตรวจสอบว่าจำนวน completions ตรงกับจำนวน lessons จริงหรือไม่
$expectedCount = $course->getTotalLessonsAttribute();
if ($completions->count() !== $expectedCount) {
    return false;
}
```

### 2. Prevent Duplicate Certificates
```php
// ควรตรวจสอบก่อนสร้างใหม่เสมอ
$existing = Certificate::where([
    'student_id' => $studentId,
    'course_id' => $courseId,
])->first();

if ($existing) {
    return redirect()->route('student.certificates.show', $existing->id);
}
```

### 3. File Storage Security
```php
// บันทึกใน storage/app/public/certificates
// ไม่ควรเก็บใน public/ โดยตรง
Storage::disk('public')->put($filename, $pdf->output());

// ตั้งค่า permissions ให้ถูกต้อง
chmod -R 775 storage/app/public/certificates
```

### 4. Access Control
```php
// ตรวจสอบสิทธิ์ในทุก method
public function show(Certificate $certificate)
{
    if ($certificate->student_id !== auth()->id()) {
        abort(403, 'Unauthorized access');
    }
    
    // ... rest of logic
}
```

### 5. Rate Limiting
```php
// ป้องกันการสร้าง certificate หลายๆ ครั้ง
Route::post('/student/courses/{course}/certificates/generate', [
    CertificateController::class, 'generate'
])->middleware(['auth', 'student', 'throttle:3,1']); // 3 ครั้งต่อนาที
```

---

## 🐛 Common Issues & Solutions

### ปัญหา: PDF ภาษาไทยไม่แสดง
**สาเหตุ**: Font ไม่รองรับภาษาไทย  
**วิธีแก้ไข**:
1. ติดตั้ง Font ภาษาไทย (THSarabunNew, Sarabun)
2. Config font ใน CSS template
3. ใช้ `@font-face` อย่างถูกต้อง

### ปัญหา: ไฟล์ PDF ใหญ่เกินไป
**สาเหตุ**: รูปภาพใหญ่ หรือ CSS ซับซ้อน  
**วิธีแก้ไข**:
1. ลดขนาดรูปภาพใน template
2. ใช้ font ที่มีขนาดเล็ก
3. ลดการใช้ CSS ที่ซับซ้อน
4. บีบอัดรูปภาพก่อนแสดง

### ปัญหา: Layout PDF ไม่ตรงกับที่ออกแบบ
**สาเหตุ**: CSS ไม่ทำงานใน PDF  
**วิธีแก้ไข**:
1. ใช้ inline CSS แทน external CSS
2. ทดสอบใน Browser ก่อนสร้าง PDF
3. ใช้ `@page` rule สำหรับกำหนดขนาดกระดาษ
4. ใช้ unit ที่เหมาะสม (px, pt)

### ปัญหา: สร้าง Certificate ไม่ได้
**สาเหตุ**: เงื่อนไขไม่ผ่าน หรือ error ในการสร้าง PDF  
**วิธีแก้ไข**:
1. ตรวจสอบ error log: `tail storage/logs/laravel.log`
2. ตรวจสอบเงื่อนไขการได้ certificate
3. ตรวจสอบว่ามี DomPDF extension ที่จำเป็น
4. ตรวจสอบ permission ของ storage folder

### ปัญหา: ดาวน์โหลด PDF ไม่ได้
**สาเหตุ**: File path ไม่ถูกต้อง หรือ permission  
**วิธีแก้ไข**:
1. ตรวจสอบว่ามี symbolic link: `php artisan storage:link`
2. ตรวจสอบ file path ใน database
3. ตรวจสอบว่าไฟล์มีอยู่จริง
4. ตรวจสอบ permission ของ storage folder

---

## 📞 การขอความช่วยเหลือ

### หากพบปัญหาเกี่ยวกับ Certificate System
1. **ตรวจสอบ Error Log**: `tail -f storage/logs/laravel.log`
2. **ตรวจสอบเงื่อนไข**: ตรวจสอบว่าเรียนครบและผ่าน quiz แล้ว
3. **ตรวจสอบ Storage**: ตรวจสอบว่ามีไฟล์ PDF ใน storage
4. **ตรวจสอบ Permissions**: ตรวจสอบ permission ของ folders

### ช่องทางติดต่อ
- **Email**: support@ct.ac.th
- **GitHub Issues**: https://github.com/yourusername/ct-learning/issues
- **Documentation**: [Documentation Index](./INDEX.md)

---

## 📚 เอกสารอ้างอิง

- [LMS Complete Guide](./LMS-COMPLETE-GUIDE.md) - คู่มือระบบครบถ้วน
- [Quick Reference](./QUICK-REFERENCE.md) - คู่มือใช้งานด่วน
- [Routes Reference](./ROUTES-REFERENCE.md) - รายการ Routes ทั้งหมด
- [DomPDF Documentation](https://github.com/barryvdh/laravel-dompdf) - เอกสาร DomPDF
- [Laravel PDF](https://github.com/barryvdh/laravel-dompdf) - Laravel PDF Integration

---

**สร้างเมื่อ**: 28 พฤศจิกายน 2025  
**เวอร์ชัน**: v2.0  
**ผู้เขียน**: CT Learning Team  
**สถานะ**: ✅ Complete & Updated  

---

<p align="center">
  <strong>🎓 CT Learning - Certificate System Guide</strong><br>
  <em>Complete certificate management system documentation</em>
</p>