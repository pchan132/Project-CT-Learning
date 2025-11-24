<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Check if student can get certificate for a course
     */
    private function canGetCertificate($course, $studentId)
    {
        // ตรวจสอบว่าเรียนครบทุก lesson
        $totalLessons = $course->getTotalLessonsAttribute();
        $completedLessons = $course->getCompletedLessonsCount($studentId);
        
        if ($completedLessons < $totalLessons) {
            return false;
        }
        
        // ตรวจสอบว่าผ่านทุก quiz (ถ้ามี)
        foreach ($course->modules as $module) {
            foreach ($module->quizzes as $quiz) {
                if (!$quiz->hasPassedByStudent($studentId)) {
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Generate certificate for course
     */
    public function generate(Course $course)
    {
        $studentId = auth()->id();
        
        // ตรวจสอบว่าลงทะเบียนเรียนหรือไม่
        if (!$course->isEnrolledByStudent($studentId)) {
            return back()->with('error', 'คุณยังไม่ได้ลงทะเบียนเรียนคอร์สนี้');
        }
        
        // ตรวจสอบว่ามีเงื่อนไขครบหรือไม่
        if (!$this->canGetCertificate($course, $studentId)) {
            return back()->with('error', 'คุณยังไม่สามารถขอใบประกาศนียบัตรได้ กรุณาเรียนให้ครบและผ่านแบบทดสอบทุกบทเรียน');
        }
        
        // ตรวจสอบว่ามี certificate อยู่แล้วหรือไม่
        $existingCert = Certificate::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->first();
            
        if ($existingCert) {
            return redirect()->route('student.certificates.show', $existingCert->id);
        }
        
        // สร้าง certificate
        $certificate = Certificate::create([
            'student_id' => $studentId,
            'course_id' => $course->id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'issued_date' => now(),
        ]);
        
        // สร้าง PDF
        $pdf = Pdf::loadView('certificates.template', [
            'certificate' => $certificate,
            'student' => auth()->user(),
            'course' => $course,
        ]);
        
        // บันทึก PDF
        $filename = 'certificates/cert-' . $certificate->id . '.pdf';
        Storage::put('public/' . $filename, $pdf->output());
        
        // อัพเดท path
        $certificate->update(['pdf_path' => $filename]);
        
        return redirect()
            ->route('student.certificates.show', $certificate->id)
            ->with('success', 'สร้างใบประกาศนียบัตรสำเร็จ!');
    }

    /**
     * Show certificate
     */
    public function show(Certificate $certificate)
    {
        // Make sure this is student's certificate
        if ($certificate->student_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงใบประกาศนียบัตรนี้');
        }
        
        $certificate->load('course', 'student');
        
        return view('student.certificates.show', compact('certificate'));
    }

    /**
     * Download certificate PDF
     */
    public function download(Certificate $certificate)
    {
        // Make sure this is student's certificate
        if ($certificate->student_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์ดาวน์โหลดใบประกาศนียบัตรนี้');
        }
        
        if (!$certificate->pdf_path || !Storage::exists('public/' . $certificate->pdf_path)) {
            return back()->with('error', 'ไม่พบไฟล์ใบประกาศนียบัตร');
        }
        
        return Storage::download('public/' . $certificate->pdf_path, 'certificate-' . $certificate->certificate_number . '.pdf');
    }

    /**
     * List all certificates of student
     */
    public function index()
    {
        $certificates = Certificate::where('student_id', auth()->id())
            ->with('course')
            ->latest()
            ->get();
        
        return view('student.certificates.index', compact('certificates'));
    }
}
