<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$fontDir = __DIR__ . '/storage/fonts';

$options = new Options();
$options->set('fontDir', $fontDir);
$options->set('fontCache', $fontDir);

$dompdf = new Dompdf($options);

// Load and install Tahoma font
$fontMetrics = $dompdf->getFontMetrics();

// Register Tahoma
$fontMetrics->registerFont(
    ['family' => 'tahoma', 'style' => 'normal', 'weight' => 'normal'],
    $fontDir . '/tahoma.ttf'
);

$fontMetrics->registerFont(
    ['family' => 'tahoma', 'style' => 'normal', 'weight' => 'bold'],
    $fontDir . '/tahomabd.ttf'
);

echo "Tahoma font registered successfully!\n";

// List installed fonts
$installedFonts = $fontDir . '/installed-fonts.json';
if (file_exists($installedFonts)) {
    echo "\nInstalled fonts:\n";
    print_r(json_decode(file_get_contents($installedFonts), true));
}
