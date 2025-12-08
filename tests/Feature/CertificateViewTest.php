<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Testing\WithFaker;

class CertificateViewTest extends TestCase
{
    use WithFaker;

    public function test_certificate_view_renders_with_valid_data()
    {
        // Mock Data
        $student = new User(['name' => 'Test Student']);
        $teacher = new User(['name' => 'Test Teacher', 'signature_image' => 'signatures/teacher.png']);
        
        $course = new Course(['title' => 'Test Course']);
        $course->setRelation('teacher', $teacher);
        
        $template = new CertificateTemplate([
            'name' => 'Default Template',
            'primary_color' => '#000000',
            'border_color' => '#000000',
            'text_color' => '#000000',
        ]);
        
        $certificate = new Certificate([
            'certificate_number' => 'CERT-TEST-001',
            'issued_date' => now(),
        ]);

        // Render View
        $view = View::make('certificates.template-mpdf', [
            'student' => $student,
            'course' => $course,
            'certificate' => $certificate,
            'template' => $template,
        ]);

        $content = $view->render();

        $this->assertStringContainsString('Test Student', $content);
        $this->assertStringContainsString('Test Course', $content);
        $this->assertStringContainsString('Test Teacher', $content);
    }

    public function test_certificate_view_renders_with_missing_teacher()
    {
        // Mock Data with NO teacher
        $student = new User(['name' => 'Test Student']);
        
        $course = new Course(['title' => 'Test Course']);
        // Teacher relation is not set (null)
        
        $template = new CertificateTemplate(['name' => 'Default Template']);
        
        $certificate = new Certificate([
            'certificate_number' => 'CERT-TEST-002',
            'issued_date' => now(),
        ]);

        // Render View
        $view = View::make('certificates.template-mpdf', [
            'student' => $student,
            'course' => $course,
            'certificate' => $certificate,
            'template' => $template,
        ]);

        try {
            $content = $view->render();
            $this->assertStringContainsString('Test Student', $content);
            // Should not crash
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('View rendering failed with missing teacher: ' . $e->getMessage());
        }
    }
}
