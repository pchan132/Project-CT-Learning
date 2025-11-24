# 🎓 Certificate System - Developer Guide

## ระบบใบประกาศนียบัตร (Certificate System)

เอกสารนี้อธิบายการทำงานของระบบออกใบประกาศนียบัตรแบบละเอียด

---

## 🗂️ โครงสร้างฐานข้อมูล

### Table: `certificates`
```sql
- id (PK)
- student_id (FK -> users.id)
- course_id (FK -> courses.id)
- certificate_number (เลขที่ใบประกาศ, unique)
- pdf_path (path ของไฟล์ PDF)
- issued_date (วันที่ออกใบประกาศ)
- timestamps
```

---

## 📋 Certificate Model

### Location: `app/Models/Certificate.php`

### Relationships:
```php
- belongsTo(User, 'student_id')  // เจ้าของใบประกาศ
- belongsTo(Course)              // คอร์สที่เรียนจบ
```

### Methods:

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

**Usage:**
```php
$certificate = Certificate::find(1);
echo $certificate->pdf_url;  
// Output: http://localhost/storage/certificates/cert-1.pdf
```

---

## 🎯 Controller

### Location: `app/Http/Controllers/Student/CertificateController.php`

---

### 1. index()
แสดงรายการใบประกาศทั้งหมดของ Student

**Logic:**
```php
$certificates = Certificate::where('student_id', auth()->id())
    ->with('course')
    ->latest()
    ->get();
```

**Route:** `GET /student/certificates`  
**View:** `resources/views/student/certificates/index.blade.php`

---

### 2. generate(Course $course)
สร้างใบประกาศสำหรับคอร์ส

**เงื่อนไขการได้รับใบประกาศ:**
1. ต้องลงทะเบียนเรียนคอร์สนั้น
2. เรียนครบทุก Lesson (100%)
3. ผ่านทุก Quiz ใน Course

**Logic Flow:**
```php
// 1. ตรวจสอบว่าลงทะเบียนหรือไม่
if (!$course->isEnrolledByStudent(auth()->id())) {
    return back()->with('error', 'คุณยังไม่ได้ลงทะเบียนเรียนคอร์สนี้');
}

// 2. ตรวจสอบเงื่อนไข
if (!$this->canGetCertificate($course, auth()->id())) {
    return back()->with('error', 'คุณยังไม่สามารถขอใบประกาศนียบัตรได้');
}

// 3. ตรวจสอบว่ามีอยู่แล้วหรือไม่
$existingCert = Certificate::where('student_id', auth()->id())
    ->where('course_id', $course->id)
    ->first();
    
if ($existingCert) {
    return redirect()->route('student.certificates.show', $existingCert->id);
}

// 4. สร้าง Certificate
$certificate = Certificate::create([...]);

// 5. Generate PDF
$pdf = Pdf::loadView('certificates.template', [...]);

// 6. บันทึกไฟล์
Storage::put('public/certificates/cert-' . $certificate->id . '.pdf', $pdf->output());

// 7. Update path
$certificate->update(['pdf_path' => 'certificates/cert-' . $certificate->id . '.pdf']);
```

**Route:** `POST /student/courses/{course}/certificates/generate`

---

### 3. canGetCertificate($course, $studentId) - Private Method
ตรวจสอบว่า Student สามารถขอใบประกาศได้หรือไม่

**Logic:**
```php
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
```

---

### 4. show(Certificate $certificate)
แสดงใบประกาศ

**Security:**
```php
// ตรวจสอบว่าเป็นของ student คนนี้จริง
if ($certificate->student_id !== auth()->id()) {
    abort(403, 'คุณไม่มีสิทธิ์เข้าถึงใบประกาศนียบัตรนี้');
}
```

**Route:** `GET /student/certificates/{certificate}`  
**View:** `resources/views/student/certificates/show.blade.php`

---

### 5. download(Certificate $certificate)
ดาวน์โหลดไฟล์ PDF

**Logic:**
```php
// 1. ตรวจสอบสิทธิ์
if ($certificate->student_id !== auth()->id()) {
    abort(403);
}

// 2. ตรวจสอบว่ามีไฟล์หรือไม่
if (!Storage::exists('public/' . $certificate->pdf_path)) {
    return back()->with('error', 'ไม่พบไฟล์ใบประกาศนียบัตร');
}

// 3. ดาวน์โหลด
return Storage::download(
    'public/' . $certificate->pdf_path, 
    'certificate-' . $certificate->certificate_number . '.pdf'
);
```

**Route:** `GET /student/certificates/{certificate}/download`

---

## 🎨 PDF Template

### Location: `resources/views/certificates/template.blade.php`

### ตัวอย่าง Template:

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            font-family: 'Sarabun', 'THSarabunNew', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .certificate {
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: table;
            text-align: center;
        }
        
        .content {
            display: table-cell;
            vertical-align: middle;
            padding: 50px;
        }
        
        .border {
            border: 10px solid gold;
            padding: 40px;
            background: white;
        }
        
        h1 {
            font-size: 48px;
            color: #333;
            margin: 20px 0;
        }
        
        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 30px 0;
        }
        
        .course-title {
            font-size: 28px;
            color: #555;
            margin: 20px 0;
        }
        
        .cert-number {
            font-size: 14px;
            color: #888;
            margin-top: 40px;
        }
        
        .date {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="content">
            <div class="border">
                <h1>🎓 ใบประกาศนียบัตร</h1>
                <h2>Certificate of Completion</h2>
                
                <p style="font-size: 20px; margin-top: 30px;">มอบให้แก่ / Presented to</p>
                
                <div class="student-name">{{ $student->name }}</div>
                
                <p style="font-size: 18px;">ได้ผ่านการอบรมหลักสูตร / Has successfully completed the course</p>
                
                <div class="course-title">{{ $course->title }}</div>
                
                <p style="font-size: 16px; margin-top: 30px;">
                    สังกัด: <strong>แผนกเทคโนโลยีคอมพิวเตอร์</strong><br>
                    Department of Computer Technology
                </p>
                
                <div class="cert-number">
                    เลขที่ใบประกาศนียบัตร: {{ $certificate->certificate_number }}
                </div>
                
                <div class="date">
                    วันที่ออกใบประกาศ: {{ $certificate->issued_date->format('d/m/Y') }}<br>
                    Issued Date: {{ $certificate->issued_date->format('F d, Y') }}
                </div>
                
                <div style="margin-top: 50px;">
                    <p>ผู้สอน: {{ $course->teacher->name }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

---

## 🛣️ Routes

```php
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    // Certificate routes
    Route::get('/certificates', 'Student\CertificateController@index')
        ->name('certificates.index');
    
    Route::post('/courses/{course}/certificates/generate', 'Student\CertificateController@generate')
        ->name('certificates.generate');
    
    Route::get('/certificates/{certificate}', 'Student\CertificateController@show')
        ->name('certificates.show');
    
    Route::get('/certificates/{certificate}/download', 'Student\CertificateController@download')
        ->name('certificates.download');
});
```

---

## 📱 Views Structure

```
resources/views/student/certificates/
├── index.blade.php       # รายการใบประกาศทั้งหมด
└── show.blade.php        # แสดงใบประกาศ + ปุ่มดาวน์โหลด

resources/views/certificates/
└── template.blade.php    # Template PDF
```

### ตัวอย่าง View: index.blade.php

```blade
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">ใบประกาศนียบัตรของฉัน</h2>
            
            @if($certificates->isEmpty())
                <p class="text-gray-500">คุณยังไม่มีใบประกาศนียบัตร</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($certificates as $certificate)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="font-bold text-lg mb-2">{{ $certificate->course->title }}</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                เลขที่: {{ $certificate->certificate_number }}
                            </p>
                            <p class="text-sm text-gray-500 mb-4">
                                ออกเมื่อ: {{ $certificate->issued_date->format('d/m/Y') }}
                            </p>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('student.certificates.show', $certificate->id) }}" 
                                   class="btn btn-primary">
                                    ดูใบประกาศ
                                </a>
                                <a href="{{ route('student.certificates.download', $certificate->id) }}" 
                                   class="btn btn-secondary">
                                    ดาวน์โหลด PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
```

---

## 💡 การปรับแต่งระบบ

### 1. เปลี่ยนเงื่อนไขการได้ Certificate

แก้ใน `CertificateController.php` method `canGetCertificate()`:

```php
// ตัวอย่าง: ต้องได้คะแนน Quiz เฉลี่ย >= 80%
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

if ($averageScore < 80) {
    return false; // คะแนนเฉลี่ยต่ำกว่า 80%
}
```

---

### 2. เปลี่ยนรูปแบบเลขที่ใบประกาศ

แก้ใน `Certificate.php` method `generateCertificateNumber()`:

```php
// รูปแบบ: CT-2025-001234
public static function generateCertificateNumber()
{
    $year = now()->year;
    $lastCert = Certificate::whereYear('created_at', $year)->count();
    $number = str_pad($lastCert + 1, 6, '0', STR_PAD_LEFT);
    
    return "CT-{$year}-{$number}";
}
```

---

### 3. เพิ่มลายเซ็นอิเล็กทรอนิกส์

**1. เพิ่มฟิลด์ใน users table:**
```php
$table->string('signature_path')->nullable();
```

**2. อัพโหลดรูปลายเซ็นของ Teacher**

**3. แก้ไข Template:**
```blade
@if($course->teacher->signature_path)
    <img src="{{ asset('storage/' . $course->teacher->signature_path) }}" 
         alt="Signature" 
         style="width: 150px; margin-top: 20px;">
@endif
<p>{{ $course->teacher->name }}</p>
```

---

### 4. เพิ่ม QR Code สำหรับยืนยันความถูกต้อง

**1. ติดตั้ง Package:**
```bash
composer require simplesoftwareio/simple-qrcode
```

**2. สร้าง Verification URL:**
```php
$verifyUrl = route('certificates.verify', $certificate->certificate_number);
```

**3. เพิ่มใน Template:**
```blade
<div style="position: absolute; bottom: 20px; right: 20px;">
    {!! QrCode::size(100)->generate($verifyUrl) !!}
    <p style="font-size: 10px;">สแกนเพื่อยืนยัน</p>
</div>
```

**4. สร้าง Route ยืนยัน:**
```php
Route::get('/certificates/verify/{certificateNumber}', function($certNumber) {
    $cert = Certificate::where('certificate_number', $certNumber)->first();
    
    if (!$cert) {
        return view('certificates.invalid');
    }
    
    return view('certificates.valid', compact('cert'));
})->name('certificates.verify');
```

---

### 5. ส่ง Certificate ทาง Email

**1. สร้าง Mailable:**
```bash
php artisan make:mail CertificateIssued
```

**2. Config Mailable:**
```php
class CertificateIssued extends Mailable
{
    public $certificate;
    
    public function build()
    {
        return $this->subject('ใบประกาศนียบัตรของคุณ')
            ->view('emails.certificate-issued')
            ->attach(storage_path('app/public/' . $this->certificate->pdf_path));
    }
}
```

**3. ส่ง Email:**
```php
// ใน CertificateController::generate()
Mail::to($student->email)->send(new CertificateIssued($certificate));
```

---

## 🎨 Font สำหรับภาษาไทย

### วิธีเพิ่ม Font ไทยใน DomPDF:

**1. Download Font THSarabunNew**

**2. Copy Font ไป:**
```
vendor/dompdf/dompdf/lib/fonts/
```

**3. Config ใน Template:**
```css
@font-face {
    font-family: 'THSarabunNew';
    src: url('{{ public_path('fonts/THSarabunNew.ttf') }}');
}

body {
    font-family: 'THSarabunNew', sans-serif;
}
```

---

## 📊 Statistics & Analytics

### ดึงสถิติ Certificate:

```php
// จำนวน Certificate ทั้งหมด
$totalCertificates = Certificate::count();

// Certificate ที่ออกในเดือนนี้
$thisMonthCerts = Certificate::whereMonth('issued_date', now()->month)
    ->whereYear('issued_date', now()->year)
    ->count();

// Top Courses ที่ออก Certificate มากที่สุด
$topCourses = Course::withCount('certificates')
    ->orderByDesc('certificates_count')
    ->take(10)
    ->get();

// Students ที่มี Certificate มากที่สุด
$topStudents = User::where('role', 'student')
    ->withCount('certificates')
    ->orderByDesc('certificates_count')
    ->take(10)
    ->get();
```

---

## 🔒 Security Best Practices

### 1. Validate Student Progress
```php
// ต้องตรวจสอบว่าเรียนจริงๆ ไม่ใช่แค่บันทึกเท็จ
$completions = LessonCompletion::where('student_id', $studentId)
    ->whereHas('lesson.module', function($q) use ($courseId) {
        $q->where('course_id', $courseId);
    })
    ->get();
```

### 2. Prevent Duplicate Certificates
```php
// ควรตรวจสอบก่อนสร้างใหม่เสมอ
$existing = Certificate::where([
    'student_id' => $studentId,
    'course_id' => $courseId,
])->first();
```

### 3. File Storage Security
```php
// บันทึกใน storage/app/public/certificates
// ไม่ควรเก็บใน public/ โดยตรง
```

---

## 🐛 Common Issues

### ปัญหา: PDF ภาษาไทยไม่แสดง
**แก้ไข:** ติดตั้ง Font ไทยและ config ใน template

### ปัญหา: ไฟล์ PDF ใหญ่เกินไป
**แก้ไข:** 
- ลดขนาดรูปภาพ
- ใช้ font ที่มีขนาดเล็ก
- ไม่ใช้ CSS ที่ซับซ้อนเกินไป

### ปัญหา: Layout PDF ไม่ตรงกับที่ออกแบบ
**แก้ไข:** 
- ใช้ inline CSS
- ทดสอบใน Browser ก่อน
- ใช้ `@page` rule สำหรับกำหนดขนาดกระดาษ

---

## 📞 Support

เอกสารเพิ่มเติม:
- [LMS-COMPLETE-GUIDE.md](./LMS-COMPLETE-GUIDE.md)
- [DomPDF Documentation](https://github.com/barryvdh/laravel-dompdf)

---

**Last Updated:** 24 พฤศจิกายน 2025
