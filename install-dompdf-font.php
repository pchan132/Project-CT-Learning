<?php
/**
 * Script to properly install fonts for DomPDF
 */

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$fontDir = __DIR__ . '/storage/fonts';

// Install font using DomPDF's font metrics
$options = new Options();
$options->set('fontDir', $fontDir);
$options->set('fontCache', $fontDir);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Get font metrics
$fontMetrics = $dompdf->getFontMetrics();

// Install NotoSansThai font
$fontFamily = 'notosansthai';

echo "Installing $fontFamily...\n";

// Normal weight
$normalFont = $fontDir . '/NotoSansThai-Regular.ttf';
$boldFont = $fontDir . '/NotoSansThai-Bold.ttf';

if (file_exists($normalFont)) {
    echo "Registering normal: $normalFont\n";
    $fontMetrics->registerFont(['family' => $fontFamily, 'style' => 'normal', 'weight' => 'normal'], $normalFont);
} else {
    echo "ERROR: $normalFont not found!\n";
}

if (file_exists($boldFont)) {
    echo "Registering bold: $boldFont\n";
    $fontMetrics->registerFont(['family' => $fontFamily, 'style' => 'normal', 'weight' => 'bold'], $boldFont);
} else {
    echo "ERROR: $boldFont not found!\n";
}

// Also register italic variants (using regular as fallback)
if (file_exists($normalFont)) {
    $fontMetrics->registerFont(['family' => $fontFamily, 'style' => 'italic', 'weight' => 'normal'], $normalFont);
}
if (file_exists($boldFont)) {
    $fontMetrics->registerFont(['family' => $fontFamily, 'style' => 'italic', 'weight' => 'bold'], $boldFont);
}

echo "\nDone! Checking installed fonts...\n";

// List files in font directory
$files = glob($fontDir . '/*.ufm.json');
echo "\nGenerated font metrics files:\n";
foreach ($files as $file) {
    echo "  - " . basename($file) . "\n";
}

$installedFonts = $fontDir . '/installed-fonts.json';
if (file_exists($installedFonts)) {
    echo "\ninstalled-fonts.json:\n";
    echo file_get_contents($installedFonts) . "\n";
}
