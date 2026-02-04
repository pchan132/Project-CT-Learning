<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$courses = App\Models\Course::latest()->take(3)->get(['id', 'title', 'image']);

echo "จำนวนรายวิชา: " . $courses->count() . "\n\n";

foreach ($courses as $course) {
    echo "ID: " . $course->id . "\n";
    echo "ชื่อ: " . $course->title . "\n";
    echo "รูปภาพ: " . ($course->image ?? 'ไม่มีรูป') . "\n";
    
    if ($course->image) {
        $imagePath = storage_path('app/public/' . $course->image);
        echo "ตรวจสอบไฟล์: " . (file_exists($imagePath) ? '✓ มีไฟล์' : '✗ ไม่มีไฟล์') . "\n";
    }
    echo "---\n";
}
