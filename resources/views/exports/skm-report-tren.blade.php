<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tren SKM - {{ $opd->nama_opd }}</title>
    <style>
        @page {
            margin: 2.54cm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            text-align: justify;
        }
        
        .sub-bab-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 10px 0 15px 0;
            line-height: 1.2;
            text-align: left;
        }
        
        .chart-container {
            margin: 15px 0 20px 0;
            text-align: center;
            page-break-inside: avoid;
        }
        
        .chart-wrapper {
            display: inline-block;
            max-width: 100%;
        }
        
        .chart-image {
            max-width: 100%;
            max-height: 300px;
            height: auto;
            display: block;
            margin: 0 auto;
            border: 1px solid #ddd;
        }
        
        .page-number:after {
            content: counter(page);
        }
        
        .content-wrapper {
            padding-bottom: 30px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .fallback-chart {
            border: 2px dashed #ccc;
            padding: 20px;
            margin: 20px auto;
            text-align: center;
            max-width: 600px;
            background-color: #f9f9f9;
        }
        
        .trend-analysis {
            text-align: justify;
            line-height: 1.5;
            margin: 15px 0;
            font-size: 11pt;
            padding: 0 10px;
        }
    </style>
</head>
<body>
    
    <div class="content-wrapper">
        <div class="sub-bab-title">2.4 Tren Nilai SKM</div>

        <div style="text-align: justify; line-height: 1.5; margin-bottom: 15px; font-size: 11pt;">
            Tren tingkat kepuasan penerima layanan {{ $opd->nama_opd }} Kabupaten Karimun dapat dilihat melalui grafik
            berikut :
        </div>

        @if(count($tren_skm) > 0)
            @php
                $years = array_column($tren_skm, 'tahun');
                $ikmValues = array_column($tren_skm, 'ikm');
                $pointCount = count($years);
                
                // Hitung statistik
                $rataRataTren = number_format(array_sum($ikmValues) / count($ikmValues), 2);
                $minTahun = min($years);
                $maxTahun = max($years);
                $minIkm = number_format(min($ikmValues), 1);
                $maxIkm = number_format(max($ikmValues), 1);
            @endphp

            <div class="sub-bab-title text-center">Gambar 2. Grafik Tren Nilai SKM ({{ count($tren_skm) }} Tahun Terakhir)</div>

            <!-- GRAFIK TREN -->
            <div class="chart-container">
                <div class="chart-wrapper">
                    @if(isset($tren_chart_path) && $tren_chart_path)
                        @php
                            $trenChartFullPath = public_path($tren_chart_path);
                        @endphp
                        
                        @if(file_exists($trenChartFullPath))
                            <img src="{{ $trenChartFullPath }}" 
                                 alt="Grafik Tren SKM {{ $opd->nama_opd }}" 
                                 class="chart-image">
                        @else
                            <!-- Fallback ke diagram default -->
                            <div class="fallback-chart">
                                <p><strong>[GRAFIK TREN SKM {{ $opd->nama_opd }}]</strong></p>
                                <p>Periode: {{ $minTahun }} - {{ $maxTahun }}</p>
                                <p>Rata-rata: {{ $rataRataTren }}</p>
                            </div>
                        @endif
                    @else
                        <!-- Jika tidak ada grafik tren yang digenerate -->
                        <div class="fallback-chart">
                            <p><strong>GRAFIK TREN NILAI SKM {{ $opd->nama_opd }}</strong></p>
                            <p>Tahun: {{ $minTahun }} - {{ $maxTahun }}</p>
                            <p>Rata-rata IKM: {{ $rataRataTren }}</p>
                            <p>Status Tren: 
                                @if(end($ikmValues) > reset($ikmValues))
                                    <span style="color: green;">↑ Naik</span>
                                @elseif(end($ikmValues) < reset($ikmValues))
                                    <span style="color: red;">↓ Turun</span>
                                @else
                                    <span style="color: blue;">→ Stabil</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Analisis tren (sederhana) -->
            <div class="trend-analysis">
                @if ($pointCount > 0)
                    @php
                        if (end($ikmValues) > reset($ikmValues)) {
                            $selisih = number_format(end($ikmValues) - reset($ikmValues), 2);
                            $persentase = number_format(((end($ikmValues) - reset($ikmValues)) / reset($ikmValues) * 100), 1);
                            $trendText = "peningkatan sebesar {$selisih} poin (peningkatan {$persentase}%).";
                        } elseif (end($ikmValues) < reset($ikmValues)) {
                            $selisih = number_format(reset($ikmValues) - end($ikmValues), 2);
                            $persentase = number_format(((reset($ikmValues) - end($ikmValues)) / reset($ikmValues) * 100), 1);
                            $trendText = "penurunan sebesar {$selisih} poin (penurunan {$persentase}%).";
                        } else {
                            $trendText = "stabil tanpa perubahan signifikan.";
                        }
                    @endphp
                    
                    Berdasarkan grafik di atas, tren nilai SKM {{ $opd->nama_opd }} selama {{ count($tren_skm) }}
                    tahun terakhir ({{ $minTahun }}-{{ $maxTahun }}) menunjukkan pola <strong>{{ $trendText }}</strong>
                    Rata-rata nilai IKM selama periode ini adalah <strong>{{ $rataRataTren }}</strong>.
                @else
                    <div style="font-style: italic; color: #666; text-align: center;">
                        Tidak dapat menganalisis tren karena data tidak mencukupi.
                    </div>
                @endif
            </div>
        @else
            <div style="text-align: center; font-style: italic; color: #666; margin: 30px 0;">
                Data tren SKM tidak tersedia untuk periode ini.
            </div>
        @endif
    </div>
</body>
</html>