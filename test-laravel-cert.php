<?php
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

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
    echo "Teacher sig_image: " . ($course->teacher && $course->teacher->signature_image ? $course->teacher->signature_image : 'NULL') . "\n";
    $teacherSigPath = ($course->teacher && $course->teacher->signature_image) ? public_path('storage/' . $course->teacher->signature_image) : null;
    echo "Teacher sig path: " . ($teacherSigPath ?? 'NULL') . "\n";
    echo "Teacher sig exists: " . ($teacherSigPath && file_exists($teacherSigPath) ? 'YES' : 'NO') . "\n";
    
    echo "Admin sig_image: " . ($template->admin_signature ?? 'NULL') . "\n";
    $adminSigPath = $template->admin_signature ? public_path('storage/' . $template->admin_signature) : null;
    echo "Admin sig path: " . ($adminSigPath ?? 'NULL') . "\n";
    echo "Admin sig exists: " . ($adminSigPath && file_exists($adminSigPath) ? 'YES' : 'NO') . "\n";
    echo "========================\n";
    
    echo "\nRendering Blade template...\n";
    
    $html = View::make('certificates.template-mpdf', [
        'template' => $template,
        'certificate' => $certificate,
        'student' => $certificate->student,
        'course' => $certificate->course,
    ])->render();
    
    echo "HTML rendered successfully! Length: " . strlen($html) . " chars\n";
    
    // Debug: Save HTML to file for inspection
    file_put_contents(storage_path('app/public/debug-cert.html'), $html);
    echo "Debug HTML saved to: storage/app/public/debug-cert.html\n";
    
    echo "\nCreating mPDF...\n";
    
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
        'autoScriptToLang' => true,
        'autoLangToFont' => true,
        'default_font' => 'garuda',
    ]);
    
    // Set image error handling
    $mpdf->showImageErrors = true;
    
    echo "Writing HTML to mPDF...\n";
    $mpdf->WriteHTML($html);
    
    echo "Saving PDF...\n";
    $mpdf->Output(storage_path('app/public/test-laravel-cert.pdf'), 'F');
    
    echo "\nSUCCESS! PDF created at: storage/app/public/test-laravel-cert.pdf\n";
    
} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
