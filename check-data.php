<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CertificateTemplate;
use App\Models\Course;
use App\Models\Certificate;

echo "=== Certificate Template ===\n";
$template = CertificateTemplate::first();
if ($template) {
    echo "ID: " . $template->id . "\n";
    echo "Name: " . $template->name . "\n";
    echo "Logo: " . ($template->logo_image ?: 'NULL') . "\n";
    echo "Admin Sig: " . ($template->admin_signature ?: 'NULL') . "\n";
    echo "Admin Name: " . ($template->admin_name ?: 'NULL') . "\n";
    echo "Admin Position: " . ($template->admin_position ?: 'NULL') . "\n";
    
    // Check if files exist
    if ($template->logo_image) {
        $logoPath = public_path('storage/' . $template->logo_image);
        echo "Logo exists: " . (file_exists($logoPath) ? 'YES' : 'NO - ' . $logoPath) . "\n";
    }
    if ($template->admin_signature) {
        $sigPath = public_path('storage/' . $template->admin_signature);
        echo "Admin Sig exists: " . (file_exists($sigPath) ? 'YES' : 'NO - ' . $sigPath) . "\n";
    }
} else {
    echo "No template found!\n";
}

echo "\n=== Certificate with Course ===\n";
$cert = Certificate::with(['course.teacher', 'student'])->first();
if ($cert) {
    echo "Cert ID: " . $cert->id . "\n";
    echo "Student: " . ($cert->student->name ?? 'NULL') . "\n";
    echo "Course ID: " . $cert->course_id . "\n";
    echo "Course: " . ($cert->course->title ?? 'NULL') . "\n";
    
    if ($cert->course && $cert->course->teacher) {
        echo "Teacher: " . $cert->course->teacher->name . "\n";
        echo "Teacher Sig: " . ($cert->course->teacher->signature_image ?: 'NULL') . "\n";
        if ($cert->course->teacher->signature_image) {
            $teacherSigPath = public_path('storage/' . $cert->course->teacher->signature_image);
            echo "Teacher Sig exists: " . (file_exists($teacherSigPath) ? 'YES' : 'NO') . "\n";
        }
    } else {
        echo "No teacher found for this course\n";
    }
}
