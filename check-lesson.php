<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$lesson = App\Models\Lesson::find(5);
if ($lesson) {
    echo "Lesson ID: " . $lesson->id . PHP_EOL;
    echo "Title: " . $lesson->title . PHP_EOL;
    echo "Type: " . $lesson->content_type . PHP_EOL;
    echo "URL: " . $lesson->content_url . PHP_EOL;
    
    // Test regex
    $videoUrl = $lesson->content_url;
    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches)) {
        echo "YouTube ID: " . $matches[1] . PHP_EOL;
        echo "isYouTube: true" . PHP_EOL;
    } else {
        echo "isYouTube: false" . PHP_EOL;
    }
} else {
    echo "Lesson not found" . PHP_EOL;
}
