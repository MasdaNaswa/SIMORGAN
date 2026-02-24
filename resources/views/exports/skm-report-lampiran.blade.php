<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lampiran - SKM</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        .bab-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin: 0 0 30px 0;
            line-height: 1.5;
        }
        
        .sub-bab-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 25px 0 15px;
            line-height: 1.5;
        }
        
        .image-container {
            text-align: center;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        
        .image-wrapper {
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            max-width: 100%;
        }
        
        .kuesioner-image {
            max-width: 80%;
            max-height: 700px;
            display: block;
            margin: 0 auto;
        }
        
        .dokumentasi-image {
            max-width: 100%;
            max-height: 250px;
            display: block;
            margin: 0 auto;
        }
        
        .dokumentasi-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 15px;
        }
        
        .dokumentasi-item {
            width: 45%;
            min-width: 300px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        @media print {
            .dokumentasi-item {
                width: 48%;
            }
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #888;
            font-style: italic;
        }
        
        .caption {
            font-size: 10pt;
            color: #333;
            margin-top: 8px;
            text-align: center;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="bab-title">LAMPIRAN</div>
    
    <!-- 1. Kuesioner -->
    <div class="sub-bab-title">1. Kuesioner</div>
    
    @if(isset($kuesioner_image) && $kuesioner_image)
        <div class="image-container">
            <div class="image-wrapper">
                <img src="data:image/{{ $kuesioner_image['extension'] }};base64,{{ $kuesioner_image['base64'] }}" 
                     alt="Kuesioner SKM" 
                     class="kuesioner-image">
            </div>
        </div>
    @elseif(!empty($filePaths['kuesioner']))
        <div class="image-container">
            <div class="image-wrapper">
                <p class="caption">Kuesioner terlampir dalam file terpisah.</p>
            </div>
        </div>
    @else
        <div class="no-data">
            Tidak ada kuesioner yang dilampirkan
        </div>
    @endif
    
    <!-- Page break sebelum dokumentasi jika ada kuesioner -->
    @if(isset($kuesioner_image) && $kuesioner_image)
    <div class="page-break"></div>
    @endif
    
    <!-- 2. Dokumentasi -->
    <div class="sub-bab-title">2. Dokumentasi Terkait Pelaksanaan SKM</div>
    
    @if(!empty($dokumentasi_images) && count($dokumentasi_images) > 0)
        <div class="dokumentasi-grid">
            @foreach($dokumentasi_images as $index => $image)
                <div class="dokumentasi-item">
                    <div class="image-wrapper">
                        <img src="data:image/{{ $image['extension'] }};base64,{{ $image['base64'] }}" 
                             alt="Dokumentasi SKM" 
                             class="dokumentasi-image">
                    </div>
                </div>
                
                <!-- Page break setiap 4 foto -->
                @if(($index + 1) % 4 == 0 && $index + 1 < count($dokumentasi_images))
                    <div class="page-break"></div>
                @endif
            @endforeach
        </div>
    @elseif(!empty($filePaths['dokumentasi']) && count($filePaths['dokumentasi']) > 0)
        <div class="image-container">
            <div class="caption">
                Dokumentasi foto tersedia dalam file terpisah.
            </div>
        </div>
    @else
        <div class="no-data">
            Tidak ada dokumentasi foto yang dilampirkan
        </div>
    @endif
</body>
</html>