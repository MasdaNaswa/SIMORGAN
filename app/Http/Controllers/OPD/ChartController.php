<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ChartController extends Controller
{
    public function generateBarChart(Request $request)
    {
        // Validasi dengan pesan error yang jelas
        $validator = Validator::make($request->all(), [
            'values' => 'required|array|min:1',
            'values.*' => 'required|numeric|min:0|max:100',
            'labels' => 'required|array|min:1',
            'labels.*' => 'required|string|max:50',
            'title' => 'nullable|string|max:100',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'width' => 'nullable|integer|min:400|max:2000',
            'height' => 'nullable|integer|min:200|max:1200',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        
        // Default values dengan type casting
        $values = $data['values'];
        $labels = $data['labels'];
        $color = $data['color'] ?? '#4A90E2';
        $width = (int) ($data['width'] ?? 800);
        $height = (int) ($data['height'] ?? 400);
        $title = $data['title'] ?? 'Grafik Batang';
        
        // Pastikan jumlah values dan labels sama
        if (count($values) !== count($labels)) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah values dan labels harus sama'
            ], 422);
        }
        
        // Parse color dengan validasi
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            $color = '#4A90E2'; // Fallback ke warna default
        }
        
        $r = hexdec(substr($color, 1, 2));
        $g = hexdec(substr($color, 3, 2));
        $b = hexdec(substr($color, 5, 2));

        // Create image dengan error handling
        try {
            $image = imagecreatetruecolor($width, $height);
            if (!$image) {
                throw new \Exception('Gagal membuat image');
            }
            
            // Colors - tambahkan lebih banyak warna untuk variasi
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $gray = imagecolorallocate($image, 200, 200, 200);
            $lightGray = imagecolorallocate($image, 240, 240, 240);
            $darkGray = imagecolorallocate($image, 100, 100, 100);
            $barColor = imagecolorallocate($image, $r, $g, $b);
            $barBorderColor = imagecolorallocate($image, 
                max(0, $r - 30), 
                max(0, $g - 30), 
                max(0, $b - 30)
            );
            $gridColor = imagecolorallocate($image, 230, 230, 230);
            
            // Fill background
            imagefilledrectangle($image, 0, 0, $width, $height, $white);
            
            // Chart area dimensions
            $marginTop = 50;    // Lebih besar untuk title
            $marginBottom = 80; // Lebih besar untuk label x-axis
            $marginLeft = 70;   // Lebih besar untuk label y-axis
            $marginRight = 40;
            $chartWidth = $width - $marginLeft - $marginRight;
            $chartHeight = $height - $marginTop - $marginBottom;
            
            // Draw chart background area
            imagefilledrectangle($image, 
                $marginLeft, $marginTop, 
                $width - $marginRight, $height - $marginBottom, 
                $lightGray
            );
            
            // Draw grid lines horizontal
            $gridLines = 5;
            for ($i = 0; $i <= $gridLines; $i++) {
                $y = $marginTop + ($i * ($chartHeight / $gridLines));
                imageline($image, $marginLeft, $y, $width - $marginRight, $y, $gridColor);
                
                // Y-axis labels (persentase)
                $value = 100 - ($i * 20);
                $labelText = $value . '%';
                $textWidth = imagefontwidth(2) * strlen($labelText);
                imagestring($image, 2, $marginLeft - $textWidth - 5, $y - 5, $labelText, $darkGray);
            }
            
            // Draw axes
            imageline($image, $marginLeft, $marginTop, $marginLeft, $height - $marginBottom, $black); // Y-axis
            imageline($image, $marginLeft, $height - $marginBottom, $width - $marginRight, $height - $marginBottom, $black); // X-axis
            
            // Calculate bar dimensions
            $barCount = count($values);
            $barWidth = ($chartWidth / $barCount) * 0.6;
            $barSpacing = ($chartWidth / $barCount) * 0.4;
            
            // Gunakan fixed max value 100 (karena data sudah 0-100)
            $maxValue = 100;
            
            // Draw bars
            foreach ($values as $index => $value) {
                // Normalize height (0-100%)
                $barHeight = ($value / $maxValue) * $chartHeight;
                $x1 = $marginLeft + ($index * ($barWidth + $barSpacing)) + ($barSpacing / 2);
                $y1 = $height - $marginBottom - $barHeight;
                $x2 = $x1 + $barWidth;
                $y2 = $height - $marginBottom;
                
                // Draw bar dengan efek 3D sederhana
                imagefilledrectangle($image, $x1, $y1, $x2, $y2, $barColor);
                imagerectangle($image, $x1, $y1, $x2, $y2, $barBorderColor);
                
                // Value label on top (dengan background)
                $valueText = number_format($value, 1);
                $textWidth = imagefontwidth(2) * strlen($valueText);
                $textX = $x1 + (($barWidth - $textWidth) / 2);
                $textY = $y1 - 18;
                
                // Background putih untuk nilai
                imagefilledrectangle($image, 
                    $textX - 3, $textY - 2,
                    $textX + $textWidth + 3, $textY + 10,
                    $white
                );
                imagerectangle($image, 
                    $textX - 3, $textY - 2,
                    $textX + $textWidth + 3, $textY + 10,
                    $darkGray
                );
                
                // Nilai di atas batang
                imagestring($image, 2, $textX, $textY, $valueText, $black);
                
                // X-axis label (rotated 45 derajat)
                $label = $labels[$index];
                $this->drawRotatedText($image, $x1 + ($barWidth/2), $height - $marginBottom + 20, $label, -45, $darkGray);
            }
            
            // Title
            if (!empty($title)) {
                $titleWidth = imagefontwidth(4) * strlen($title);
                $titleX = ($width - $titleWidth) / 2;
                imagestring($image, 4, $titleX, 15, $title, $black);
            }
            
            // Draw legend box
            $legendX = $width - 180;
            $legendY = $marginTop + 20;
            $legendWidth = 160;
            $legendHeight = 70;
            
            // Legend background
            imagefilledrectangle($image, $legendX, $legendY, 
                               $legendX + $legendWidth, $legendY + $legendHeight, 
                               $white);
            imagerectangle($image, $legendX, $legendY, 
                          $legendX + $legendWidth, $legendY + $legendHeight, 
                          $darkGray);
            
            // Legend title
            imagestring($image, 3, $legendX + 10, $legendY + 10, "Keterangan:", $black);
            
            // Legend color box
            $boxSize = 12;
            imagefilledrectangle($image, 
                $legendX + 10, $legendY + 30,
                $legendX + 10 + $boxSize, $legendY + 30 + $boxSize,
                $barColor
            );
            imagerectangle($image, 
                $legendX + 10, $legendY + 30,
                $legendX + 10 + $boxSize, $legendY + 30 + $boxSize,
                $barBorderColor
            );
            
            // Legend text
            imagestring($image, 2, $legendX + 30, $legendY + 30, "Nilai IKM", $black);
            
            // Info tambahan di legend
            $average = array_sum($values) / count($values);
            imagestring($image, 2, $legendX + 10, $legendY + 50, "Rata-rata: " . number_format($average, 1), $black);
            
            // Output as base64
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);
            
            return response()->json([
                'success' => true,
                'image' => 'data:image/png;base64,' . base64_encode($imageData),
                'info' => [
                    'width' => $width,
                    'height' => $height,
                    'bars' => $barCount,
                    'color' => $color,
                    'average' => number_format($average, 1)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Chart generation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghasilkan grafik: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate Tren Chart Image (LINE CHART untuk data tren tahunan)
     */
    public function generateTrenChartImage($trenSkm, $warnaGrafik, $opdName)
    {
        try {
            if (empty($trenSkm)) {
                return null;
            }

            // Ambil data dari array tren
            $years = array_column($trenSkm, 'tahun');
            $ikmValues = array_column($trenSkm, 'ikm');
            $mutuValues = array_column($trenSkm, 'mutu');
            
            // Dimensi gambar
            $width = 800;
            $height = 450;

            // Create image
            $image = imagecreatetruecolor($width, $height);

            // Parse warna
            $color = $warnaGrafik;
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
                $color = '#4A90E2';
            }

            $r = hexdec(substr($color, 1, 2));
            $g = hexdec(substr($color, 3, 2));
            $b = hexdec(substr($color, 5, 2));

            // Define colors
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $gray = imagecolorallocate($image, 200, 200, 200);
            $lightGray = imagecolorallocate($image, 245, 245, 245);
            $darkGray = imagecolorallocate($image, 100, 100, 100);
            $lineColor = imagecolorallocate($image, $r, $g, $b);
            $pointColor = imagecolorallocate($image, 
                min(255, $r + 30), 
                min(255, $g + 30), 
                min(255, $b + 30)
            );
            $gridColor = imagecolorallocate($image, 230, 230, 230);
            
            // Fill background
            imagefilledrectangle($image, 0, 0, $width, $height, $white);
            
            // Chart area dimensions
            $marginTop = 60;    // Space untuk title dan legenda
            $marginBottom = 80; // Space untuk label tahun
            $marginLeft = 80;   // Space untuk label nilai
            $marginRight = 180; // Space untuk legenda
            
            $chartWidth = $width - $marginLeft - $marginRight;
            $chartHeight = $height - $marginTop - $marginBottom;
            
            // Draw chart background
            imagefilledrectangle($image, 
                $marginLeft, $marginTop, 
                $marginLeft + $chartWidth, $marginTop + $chartHeight, 
                $lightGray
            );
            
            // Draw grid lines horizontal
            $gridLines = 5;
            for ($i = 0; $i <= $gridLines; $i++) {
                $y = $marginTop + ($i * ($chartHeight / $gridLines));
                imageline($image, $marginLeft, $y, $marginLeft + $chartWidth, $y, $gridColor);
                
                // Y-axis labels (nilai IKM)
                $value = 100 - ($i * 20);
                $labelText = $value;
                $textWidth = imagefontwidth(2) * strlen($labelText);
                imagestring($image, 2, $marginLeft - $textWidth - 10, $y - 5, $labelText, $darkGray);
            }
            
            // Draw axes
            imageline($image, $marginLeft, $marginTop, $marginLeft, $marginTop + $chartHeight, $black); // Y-axis
            imageline($image, $marginLeft, $marginTop + $chartHeight, $marginLeft + $chartWidth, $marginTop + $chartHeight, $black); // X-axis
            
            // Plot data points and lines
            $pointCount = count($years);
            
            // Untuk menentukan skala Y yang baik
            $maxValue = max($ikmValues);
            $minValue = min($ikmValues);
            
            // Tambahkan sedikit ruang di atas dan bawah
            $yRange = $maxValue - $minValue;
            $yPadding = $yRange * 0.1; // 10% padding
            
            $yMax = min(100, $maxValue + $yPadding);
            $yMin = max(0, $minValue - $yPadding);
            
            $previousX = null;
            $previousY = null;
            
            // Draw data points and connecting lines
            for ($i = 0; $i < $pointCount; $i++) {
                // Calculate X position
                $x = $marginLeft + ($i * ($chartWidth / ($pointCount - 1)));
                
                // Calculate Y position (dibalik karena origin di kiri atas)
                $value = $ikmValues[$i];
                $y = $marginTop + $chartHeight - (($value - $yMin) / ($yMax - $yMin)) * $chartHeight;
                
                // Draw connecting line
                if ($previousX !== null && $previousY !== null) {
                    imageline($image, $previousX, $previousY, $x, $y, $lineColor);
                    imageline($image, $previousX, $previousY + 1, $x, $y + 1, $lineColor); // Tebalkan sedikit
                }
                
                // Draw data point (lingkaran)
                imagefilledellipse($image, $x, $y, 12, 12, $pointColor);
                imageellipse($image, $x, $y, 12, 12, $black);
                
                // Draw value label
                $valueText = number_format($value, 1);
                $textWidth = imagefontwidth(2) * strlen($valueText);
                $textX = $x - ($textWidth / 2);
                $textY = $y - 20;
                
                // Background untuk nilai
                imagefilledrectangle($image, 
                    $textX - 3, $textY - 2,
                    $textX + $textWidth + 3, $textY + 10,
                    $white
                );
                imagerectangle($image, 
                    $textX - 3, $textY - 2,
                    $textX + $textWidth + 3, $textY + 10,
                    $darkGray
                );
                
                // Nilai
                imagestring($image, 2, $textX, $textY, $valueText, $black);
                
                // Draw year label
                $yearLabel = $years[$i];
                $labelWidth = imagefontwidth(2) * strlen($yearLabel);
                $labelX = $x - ($labelWidth / 2);
                imagestring($image, 2, $labelX, $marginTop + $chartHeight + 10, $yearLabel, $black);
                
                $previousX = $x;
                $previousY = $y;
            }
            
            // Title
            $title = "Tren Nilai IKM " . $opdName . " (" . min($years) . " - " . max($years) . ")";
            $titleWidth = imagefontwidth(4) * strlen($title);
            $titleX = ($width - $titleWidth) / 2;
            imagestring($image, 4, $titleX, 15, $title, $black);
            
            // Draw legend box
            $legendX = $marginLeft + $chartWidth + 20;
            $legendY = $marginTop;
            $legendWidth = 150;
            $legendHeight = 120;
            
            // Legend background
            imagefilledrectangle($image, $legendX, $legendY, 
                               $legendX + $legendWidth, $legendY + $legendHeight, 
                               $white);
            imagerectangle($image, $legendX, $legendY, 
                          $legendX + $legendWidth, $legendY + $legendHeight, 
                          $darkGray);
            
            // Legend title
            imagestring($image, 3, $legendX + 10, $legendY + 10, "KETERANGAN:", $black);
            
            // Line sample
            imageline($image, $legendX + 10, $legendY + 35, $legendX + 40, $legendY + 35, $lineColor);
            
            // Point sample
            imagefilledellipse($image, $legendX + 25, $legendY + 35, 10, 10, $pointColor);
            imageellipse($image, $legendX + 25, $legendY + 35, 10, 10, $black);
            
            // Legend text
            imagestring($image, 2, $legendX + 45, $legendY + 30, "Tren IKM", $black);
            
            // Additional info
            $average = array_sum($ikmValues) / count($ikmValues);
            $trendDirection = end($ikmValues) > reset($ikmValues) ? "Naik" : 
                            (end($ikmValues) < reset($ikmValues) ? "Turun" : "Stabil");
            
            imagestring($image, 2, $legendX + 10, $legendY + 50, "Rata-rata: " . number_format($average, 1), $black);
            imagestring($image, 2, $legendX + 10, $legendY + 65, "Tren: " . $trendDirection, $black);
            imagestring($image, 2, $legendX + 10, $legendY + 80, "Periode: " . min($years) . "-" . max($years), $black);
            imagestring($image, 2, $legendX + 10, $legendY + 95, "Data: " . $pointCount . " tahun", $black);
            
            // Save image
            $fileName = 'tren_chart_' . time() . '_' . uniqid() . '.png';
            $folderPath = 'images/charts/';
            
            // Create directory if not exists
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0777, true);
            }
            
            $filePath = public_path($folderPath . $fileName);
            imagepng($image, $filePath);
            imagedestroy($image);
            
            Log::info('Tren chart generated: ' . $filePath);
            
            return $folderPath . $fileName;

        } catch (\Exception $e) {
            Log::error('Error generating trend chart: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * API Endpoint untuk generate tren chart
     */
    public function generateTrenChart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tren_skm' => 'required|array|min:2',
            'tren_skm.*.tahun' => 'required|integer',
            'tren_skm.*.ikm' => 'required|numeric|min:0|max:100',
            'tren_skm.*.mutu' => 'nullable|string|max:1',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'title' => 'nullable|string|max:150',
            'width' => 'nullable|integer|min:400|max:2000',
            'height' => 'nullable|integer|min:200|max:1200',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $data = $validator->validated();
        
        try {
            // Generate chart
            $chartPath = $this->generateTrenChartImage(
                $data['tren_skm'],
                $data['color'] ?? '#4A90E2',
                $data['title'] ?? 'Tren SKM'
            );
            
            if ($chartPath && file_exists(public_path($chartPath))) {
                // Baca gambar sebagai base64 untuk response
                $imageData = file_get_contents(public_path($chartPath));
                $base64Image = 'data:image/png;base64,' . base64_encode($imageData);
                
                return response()->json([
                    'success' => true,
                    'image' => $base64Image,
                    'image_url' => asset($chartPath),
                    'image_path' => $chartPath,
                    'info' => [
                        'width' => 800,
                        'height' => 450,
                        'years' => count($data['tren_skm']),
                        'color' => $data['color'] ?? '#4A90E2'
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat atau menyimpan grafik tren'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Error in generateTrenChart API: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghasilkan grafik: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Fungsi yang lebih baik untuk menggambar teks miring
     */
    private function drawRotatedText($image, $x, $y, $text, $angle, $color)
    {
        // Gunakan TrueType Font jika tersedia, fallback ke GD font
        $fontFile = public_path('fonts/arial.ttf');
        
        if (function_exists('imagettftext') && file_exists($fontFile)) {
            // Gunakan TrueType Font untuk hasil yang lebih baik
            imagettftext($image, 8, $angle, $x, $y, $color, $fontFile, $text);
        } else {
            // Fallback ke metode character-by-character
            $chars = str_split($text);
            $angleRad = deg2rad($angle);
            $charSpacing = 7;
            
            foreach ($chars as $char) {
                imagestring($image, 2, $x, $y, $char, $color);
                $x += $charSpacing * cos($angleRad);
                $y += $charSpacing * sin($angleRad);
            }
        }
    }
    
    /**
     * Endpoint alternatif untuk menyimpan gambar ke file
     */
    public function generateAndSaveChart(Request $request)
    {
        $response = $this->generateBarChart($request);
        $data = json_decode($response->getContent(), true);
        
        if ($data['success']) {
            // Simpan ke file
            $base64Data = str_replace('data:image/png;base64,', '', $data['image']);
            $imageData = base64_decode($base64Data);
            
            $filename = 'chart_' . time() . '_' . uniqid() . '.png';
            $path = 'charts/' . $filename;
            
            Storage::disk('public')->put($path, $imageData);
            
            return response()->json([
                'success' => true,
                'url' => Storage::url($path),
                'path' => $path,
                'filename' => $filename
            ]);
        }
        
        return $response;
    }
    
    /**
     * Cleanup old chart files (optional)
     */
    public function cleanupOldCharts()
    {
        try {
            $folderPath = public_path('images/charts/');
            
            if (file_exists($folderPath)) {
                $files = glob($folderPath . '*.png');
                $now = time();
                $daysToKeep = 7; // Hapus file yang lebih dari 7 hari
                
                foreach ($files as $file) {
                    if (is_file($file)) {
                        if ($now - filemtime($file) >= 60 * 60 * 24 * $daysToKeep) {
                            unlink($file);
                        }
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Cleanup completed'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Folder not found'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Cleanup error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Cleanup failed'
            ], 500);
        }
    }
}