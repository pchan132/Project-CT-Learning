<?php
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

try {
    echo "Getting certificate...\n";
    
    // Get first certificate
    $certificate = Certificate::with(['course.teacher', 'student', 'template'])->first();
    
    if (!$certificate) {
        echo "No certificate found!\n";
        exit;
    }
    
    echo "Certificate ID: " . $certificate->id . "\n";
    echo "Student: " . ($certificate->student->name ?? 'N/A') . "\n";
    echo "Course: " . ($certificate->course->title ?? 'N/A') . "\n";
    
    $template = $certificate->template ?? CertificateTemplate::getActiveTemplate();
    echo "Template: " . ($template->name ?? 'Default') . "\n";
    
    // Debug signatures
    echo "\n=== Signature Debug ===\n";
    $course = $certificate->course;
    echo "Teacher: " . ($course->teacher ? $course->teacher->name : 'NULL') . "\n";
    $teacherSigPath = ($course->teacher && $course->teacher->signature_image) ? public_path('storage/' . $course->teacher->signature_image) : null;
    echo "Teacher sig exists: " . ($teacherSigPath && file_exists($teacherSigPath) ? 'YES' : 'NO') . "\n";
    
    $adminSigPath = $template->admin_signature ? public_path('storage/' . $template->admin_signature) : null;
    echo "Admin sig exists: " . ($adminSigPath && file_exists($adminSigPath) ? 'YES' : 'NO') . "\n";
    echo "========================\n";
    
    echo "\nCreating PDF with DomPDF...\n";
    
    $pdf = Pdf::loadView('certificates.template-dompdf', [
        'template' => $template,
        'certificate' => $certificate,
        'student' => $certificate->student,
        'course' => $certificate->course,
    ]);
    
    // Set paper to A4 Landscape
    $pdf->setPaper('a4', 'landscape');
    
    // Save PDF
    $outputPath = storage_path('app/public/test-dompdf-cert.pdf');
    $pdf->save($outputPath);
    
    echo "\nSUCCESS! PDF created at: " . $outputPath . "\n";
    
} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
