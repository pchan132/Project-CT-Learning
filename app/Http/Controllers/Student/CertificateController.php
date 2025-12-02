<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class CertificateController extends Controller
{
    /**
     * Generate PDF using mPDF with Thai font support
     */
    private function generatePdf($template, $certificate, $student, $course)
    {
        // Create mPDF instance - A4 Landscape with Thai font
        $tempDir = storage_path('app/mpdf-temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'tempDir' => $tempDir,
        ]);

        // Render the Blade template to HTML
        $html = View::make('certificates.template-mpdf', [
            'template' => $template,
            'certificate' => $certificate,
            'student' => $student,
            'course' => $course,
        ])->render();

        $mpdf->WriteHTML($html);

        return $mpdf;
    }

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
        
        // ดึง Template ที่ใช้งานอยู่ (จะสร้าง default ถ้าไม่มี)
        $template = CertificateTemplate::getActiveTemplate();
        
        // สร้าง certificate
        $certificate = Certificate::create([
            'student_id' => $studentId,
            'course_id' => $course->id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'issued_date' => now(),
            'template_id' => $template->id,
        ]);
        
        // สร้าง PDF ด้วย mPDF
        $mpdf = $this->generatePdf($template, $certificate, auth()->user(), $course);
        
        // บันทึก PDF
        $filename = 'certificates/cert-' . $certificate->id . '.pdf';
        $fullPath = storage_path('app/public/' . $filename);
        
        // สร้าง directory ถ้ายังไม่มี
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        
        $mpdf->Output($fullPath, 'F');
        
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
        
        $certificate->load(['course.teacher', 'student', 'template']);
        
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
        
        // สร้าง PDF ใหม่ทุกครั้งเพื่อให้ตรงกับ template ล่าสุด
        $certificate->load(['course.teacher', 'student']);
        $template = $certificate->template ?? CertificateTemplate::getActiveTemplate();
        
        $mpdf = $this->generatePdf($template, $certificate, $certificate->student, $certificate->course);
        
        // บันทึก PDF ใหม่
        $filename = 'certificates/cert-' . $certificate->id . '.pdf';
        $fullPath = storage_path('app/public/' . $filename);
        
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        
        $mpdf->Output($fullPath, 'F');
        $certificate->update(['pdf_path' => $filename, 'template_id' => $template->id]);
        
        // Download
        return response()->download($fullPath, 'certificate-' . $certificate->certificate_number . '.pdf');
    }

    /**
     * Regenerate certificate PDF with latest template
     */
    public function regenerate(Certificate $certificate)
    {
        // Make sure this is student's certificate
        if ($certificate->student_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงใบประกาศนียบัตรนี้');
        }
        
        $certificate->load(['course.teacher', 'student']);
        $template = CertificateTemplate::getActiveTemplate();
        
        if ($template) {
            $mpdf = $this->generatePdf($template, $certificate, $certificate->student, $certificate->course);
            
            // บันทึก PDF ใหม่
            $filename = 'certificates/cert-' . $certificate->id . '.pdf';
            $fullPath = storage_path('app/public/' . $filename);
            
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }
            
            $mpdf->Output($fullPath, 'F');
            $certificate->update(['pdf_path' => $filename, 'template_id' => $template->id]);
            
            return back()->with('success', 'สร้างใบประกาศนียบัตรใหม่ตาม Template ล่าสุดเรียบร้อย!');
        }
        
        return back()->with('error', 'ไม่พบ Template ที่ใช้งาน');
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
