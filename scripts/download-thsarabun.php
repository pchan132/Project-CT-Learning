<?php
// Download THSarabun font from reliable sources

$fontsDir = __DIR__ . '/../storage/fonts';

if (!file_exists($fontsDir)) {
    mkdir($fontsDir, 0755, true);
}

echo "Downloading THSarabun fonts...\n\n";

// Try multiple sources
$sources = [
    // Reliable GitHub mirror
    'https://raw.githubusercontent.com/nicespkg/vscode-thsarabun/master/fonts/THSarabunNew.ttf',
    // Alternative
    'https://cdn.jsdelivr.net/gh/nicespkg/vscode-thsarabun@master/fonts/THSarabunNew.ttf',
];

$downloaded = false;
foreach ($sources as $url) {
    echo "Trying: {$url}\n";
    
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);
    
    $content = @file_get_contents($url, false, $ctx);
    if ($content !== false && strlen($content) > 10000) {
        file_put_contents($fontsDir . '/THSarabunNew.ttf', $content);
        echo "Downloaded: " . number_format(strlen($content)) . " bytes\n";
        $downloaded = true;
        break;
    }
    echo "Failed\n";
}

if (!$downloaded) {
    echo "\nCould not download from any source.\n";
    echo "Please manually download THSarabunNew.ttf and place in: {$fontsDir}\n";
    exit(1);
}

echo "\nNow registering font with DomPDF...\n";

require_once __DIR__ . '/../vendor/autoload.php';

$fontPath = $fontsDir . '/THSarabunNew.ttf';

if (!file_exists($fontPath)) {
    echo "Font file not found!\n";
    exit(1);
}

// Initialize DomPDF
$options = new \Dompdf\Options();
$options->setFontDir($fontsDir);
$options->setFontCache($fontsDir);
$options->setDefaultFont('thsarabunnew');

$dompdf = new \Dompdf\Dompdf($options);
$fontMetrics = $dompdf->getFontMetrics();

try {
    // Register THSarabunNew
    $fontMetrics->registerFont(
        ['family' => 'thsarabunnew', 'style' => 'normal', 'weight' => 'normal'],
        $fontPath
    );
    echo "Registered: thsarabunnew normal\n";
    
    // Also as bold, italic
    $fontMetrics->registerFont(
        ['family' => 'thsarabunnew', 'style' => 'normal', 'weight' => 'bold'],
        $fontPath
    );
    $fontMetrics->registerFont(
        ['family' => 'thsarabunnew', 'style' => 'italic', 'weight' => 'normal'],
        $fontPath
    );
    $fontMetrics->registerFont(
        ['family' => 'thsarabunnew', 'style' => 'italic', 'weight' => 'bold'],
        $fontPath
    );
    
    $fontMetrics->saveFontFamilies();
    echo "Font registered successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nDone! Use font-family: 'thsarabunnew' in your CSS.\n";
