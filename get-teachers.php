<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$teachers = App\Models\User::where('role', 'teacher')->get();

echo "จำนวนอาจารย์: " . $teachers->count() . "\n\n";

foreach ($teachers as $teacher) {
    echo "ชื่อ: " . $teacher->name . "\n";
    echo "อีเมล: " . $teacher->email . "\n";
    echo "รูปโปรไฟล์: " . ($teacher->profile_image ?? 'ไม่มี') . "\n";
    echo "---\n";
}
