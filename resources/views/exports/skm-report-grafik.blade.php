<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik SKM - {{ $opd->nama_opd }}</title>
    <style>
        @page {
            margin: 2.54cm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            text-align: justify;
        }
        
        .sub-bab-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 0 0 20px 0;
            line-height: 1.5;
            text-align: center;
        }
        
        .chart-container {
            margin: 20px 0 30px 0;
            position: relative;
            text-align: center;
        }
        
        .chart-wrapper {
            border: 1px solid #ddd;
            padding: 15px;
            background: #f9f9f9;
            display: inline-block;
            max-width: 100%;
        }
        
        .chart-image {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    
        
        .page-number:after {
            content: counter(page);
        }
        
        .content-wrapper {
            padding-bottom: 40px;
        }
        
        img {
            max-width: 100%;
            height: auto;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    
    <div class="content-wrapper">
        <div class="sub-bab-title text-center">Gambar 1. Grafik Nilai SKM Per Unsur</div>

        <div class="chart-container">
            <div class="chart-wrapper">
                @if(isset($chart_image_path) && file_exists(public_path($chart_image_path)))
                    <img src="{{ public_path($chart_image_path) }}" 
                         alt="Grafik SKM Per Unsur" 
                         class="chart-image">
                @else
                    <!-- Fallback jika gambar tidak ada -->
                    <div style="text-align: center; padding: 50px; color: #666; border: 1px dashed #ccc;">
                        <p><strong>[GRAFIK TIDAK DAPAT DITAMPILKAN]</strong></p>
                        <p>Grafik akan menampilkan diagram batang nilai IKM per unsur</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>