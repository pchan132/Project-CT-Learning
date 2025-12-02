<?php
// Quick test script for PDF generation with mPDF and Thai font

require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;

$html = '<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: "sarabun", "garuda", sans-serif; }
        h1 { color: #d4af37; font-size: 32pt; text-align: center; }
        p { font-size: 16pt; text-align: center; }
        .border { border: 4px solid #d4af37; padding: 30px; margin: 20px; }
    </style>
</head>
<body>
    <div class="border">
        <h1>ใบประกาศนียบัตร</h1>
        <p>Certificate of Completion</p>
        <p>ขอมอบใบประกาศนียบัตรนี้ให้แก่</p>
        <p><strong>นายทดสอบ ภาษาไทย</strong></p>
        <p>สำเร็จการศึกษาจากคอร์ส</p>
        <p><strong>หลักสูตร Laravel PHP</strong></p>
    </div>
</body>
</html>';

echo "Generating PDF with mPDF...\n";

try {
    $mpdf = new Mpdf([
        "mode" => "utf-8",
        "format" => "A4-L",
        "margin_left" => 10,
        "margin_right" => 10,
        "margin_top" => 10,
        "margin_bottom" => 10,
        "default_font" => "sarabun",
        "tempDir" => __DIR__ . "/storage/app/mpdf-temp",
    ]);
    
    $mpdf->WriteHTML($html);
    $mpdf->Output(__DIR__ . "/storage/app/test-mpdf-thai.pdf", "F");
    
    echo "PDF saved to: " . __DIR__ . "/storage/app/test-mpdf-thai.pdf\n";
    echo "SUCCESS!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
