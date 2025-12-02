<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallThaiFont extends Command
{
    protected $signature = 'font:install-thai';
    protected $description = 'Install Thai font (Sarabun) for DomPDF from Google Fonts';

    public function handle()
    {
        $this->info('Installing Thai font (Sarabun) for DomPDF...');
        
        // Use DomPDF's fonts directory
        $fontsDir = base_path('vendor/dompdf/dompdf/lib/fonts');
        
        // Alternative: Use storage fonts directory
        $storageFontsDir = storage_path('fonts');
        if (!file_exists($storageFontsDir)) {
            mkdir($storageFontsDir, 0755, true);
        }
        
        // Sarabun from Google Fonts (supports Thai)
        $fonts = [
            'sarabun' => [
                'normal' => 'https://fonts.gstatic.com/s/sarabun/v15/DtVjJx26TKEr37c9YL5rilwm.ttf',
                'bold' => 'https://fonts.gstatic.com/s/sarabun/v15/DtVmJx26TKEr37c9YNpoulwm6gDX.ttf',
            ]
        ];
        
        $downloadedFiles = [];
        
        foreach ($fonts as $fontFamily => $styles) {
            foreach ($styles as $style => $url) {
                $filename = "{$fontFamily}_{$style}.ttf";
                $filepath = "{$storageFontsDir}/{$filename}";
                
                $this->info("Downloading {$fontFamily} {$style}...");
                
                // Use curl for better compatibility
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                $content = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode !== 200 || $content === false) {
                    $this->error("Failed to download {$fontFamily} {$style}");
                    continue;
                }
                
                file_put_contents($filepath, $content);
                $this->info("Saved to {$filepath}");
                $downloadedFiles[$style] = $filepath;
            }
        }
        
        if (empty($downloadedFiles)) {
            $this->error('No fonts were downloaded.');
            return 1;
        }
        
        // Now we need to register fonts with DomPDF
        $this->info('');
        $this->info('Registering fonts with DomPDF...');
        
        // Use DomPDF's load_font.php script approach
        try {
            $dompdf = new \Dompdf\Dompdf();
            $fontMetrics = $dompdf->getFontMetrics();
            
            if (isset($downloadedFiles['normal'])) {
                $fontMetrics->registerFont(
                    ['family' => 'sarabun', 'style' => 'normal', 'weight' => 'normal'],
                    $downloadedFiles['normal']
                );
                $this->info('Registered sarabun normal');
            }
            
            if (isset($downloadedFiles['bold'])) {
                $fontMetrics->registerFont(
                    ['family' => 'sarabun', 'style' => 'normal', 'weight' => 'bold'],
                    $downloadedFiles['bold']
                );
                $this->info('Registered sarabun bold');
            }
            
            // Save font cache
            $fontMetrics->saveFontFamilies();
            
        } catch (\Exception $e) {
            $this->error('Failed to register fonts: ' . $e->getMessage());
            $this->info('');
            $this->warn('Manual registration required:');
            $this->info('1. Copy TTF files to: ' . $fontsDir);
            $this->info('2. Run: php vendor/dompdf/dompdf/load_font.php sarabun ' . $downloadedFiles['normal']);
            return 1;
        }
        
        $this->info('');
        $this->info('========================================');
        $this->info('Font installation complete!');
        $this->info('========================================');
        $this->info('');
        $this->info('Use font-family: "sarabun" in your PDF CSS.');
        $this->info('');
        $this->info('Example:');
        $this->info('  body { font-family: "sarabun", sans-serif; }');
        
        return 0;
    }
}
