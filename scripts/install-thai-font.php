<?php
// Script to download Thai fonts for DomPDF

$fontsDir = __DIR__ . '/../storage/fonts';

// Create fonts directory if not exists
if (!file_exists($fontsDir)) {
    mkdir($fontsDir, 0755, true);
}

echo "Downloading Thai fonts...\n\n";

$fonts = [
    'NotoSansThai-Regular.ttf' => 'https://github.com/googlefonts/noto-fonts/raw/main/hinted/ttf/NotoSansThai/NotoSansThai-Regular.ttf',
    'NotoSansThai-Bold.ttf' => 'https://github.com/googlefonts/noto-fonts/raw/main/hinted/ttf/NotoSansThai/NotoSansThai-Bold.ttf',
];

$downloaded = 0;
foreach ($fonts as $filename => $url) {
    $filepath = $fontsDir . '/' . $filename;
    
    // Skip if already exists
    if (file_exists($filepath)) {
        echo "{$filename} already exists, skipping...\n";
        $downloaded++;
        continue;
    }
    
    echo "Downloading {$filename}... ";
    
    $content = @file_get_contents($url);
    if ($content === false) {
        echo "FAILED\n";
        continue;
    }
    
    file_put_contents($filepath, $content);
    echo "OK (" . number_format(strlen($content)) . " bytes)\n";
    $downloaded++;
}

echo "\n";

if ($downloaded === 0) {
    echo "No fonts downloaded. Please check your internet connection.\n";
    exit(1);
}

echo "Downloaded {$downloaded} font(s) to: {$fontsDir}\n\n";

// Register with DomPDF using registerFont() API
echo "Registering fonts with DomPDF...\n";

require_once __DIR__ . '/../vendor/autoload.php';

$regularPath = $fontsDir . '/NotoSansThai-Regular.ttf';
$boldPath = $fontsDir . '/NotoSansThai-Bold.ttf';

// Initialize DomPDF with options pointing to our fonts directory
$options = new \Dompdf\Options();
$options->setFontDir($fontsDir);
$options->setFontCache($fontsDir);

$dompdf = new \Dompdf\Dompdf($options);

$fontMetrics = $dompdf->getFontMetrics();

try {
    if (file_exists($regularPath)) {
        // Register normal weight
        $fontMetrics->registerFont(
            ['family' => 'notosansthai', 'style' => 'normal', 'weight' => 'normal'],
            $regularPath
        );
        echo "Registered: notosansthai normal\n";
        
        // Also register as italic (since we don't have italic variant)
        $fontMetrics->registerFont(
            ['family' => 'notosansthai', 'style' => 'italic', 'weight' => 'normal'],
            $regularPath
        );
        echo "Registered: notosansthai italic\n";
    }
    
    if (file_exists($boldPath)) {
        // Register bold weight
        $fontMetrics->registerFont(
            ['family' => 'notosansthai', 'style' => 'normal', 'weight' => 'bold'],
            $boldPath
        );
        echo "Registered: notosansthai bold\n";
        
        // Also register as bold_italic
        $fontMetrics->registerFont(
            ['family' => 'notosansthai', 'style' => 'italic', 'weight' => 'bold'],
            $boldPath
        );
        echo "Registered: notosansthai bold_italic\n";
    }
    
    // Save font families
    $fontMetrics->saveFontFamilies();
    echo "\nFont families saved to installed-fonts.json\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n===============================================\n";
echo "Installation complete!\n";
echo "Use font-family: 'notosansthai' in your CSS.\n";
echo "===============================================\n";
