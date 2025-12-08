<?php
require 'vendor/autoload.php';

use Mpdf\Mpdf;

try {
    echo "Creating mPDF instance...\n";
    
    $tempDir = __DIR__ . '/storage/app/mpdf-temp';
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
    
    echo "Writing HTML...\n";
    
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: garuda, tahoma, sans-serif; }
            .title { font-size: 36pt; color: #1e3a8a; text-align: center; margin-top: 50px; }
            .name { font-size: 28pt; color: #1e3a8a; text-align: center; margin-top: 30px; }
            .thai { font-size: 18pt; text-align: center; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="title">CERTIFICATE</div>
        <div class="name">John Doe</div>
        <div class="thai">ใบประกาศนียบัตร</div>
        <div class="thai">สวัสดีครับ ทดสอบภาษาไทย</div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);
    
    echo "Saving PDF...\n";
    $mpdf->Output(__DIR__ . '/test-cert-debug.pdf', 'F');
    
    echo "SUCCESS! PDF created at: test-cert-debug.pdf\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
