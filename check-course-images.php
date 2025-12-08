<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$courses = App\Models\Course::select('id', 'title', 'cover_image_url')->get();

echo "=== Course Images Debug ===\n\n";

foreach($courses as $course) {
    echo "ID: {$course->id}\n";
    echo "Title: {$course->title}\n";
    echo "Cover Image URL: " . ($course->cover_image_url ?? 'NULL') . "\n";
    echo "------------------------\n";
}
