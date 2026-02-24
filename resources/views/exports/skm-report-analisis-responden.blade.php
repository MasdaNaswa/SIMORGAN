<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Responden - SKM</title>
    <style>
        body {
            font-family: 'Arial';
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            text-align: justify;
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
            margin: 20px 0 15px;
            line-height: 1.5;
        }
        
        .justify-text {
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.5;
            margin-bottom: 15pt;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 20px 0;
            font-size: 10pt;
            line-height: 1.4;
        }
        
        table th, table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
            line-height: 1.4;
        }
        
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-italic {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="bab-title">BAB II<br>ANALISIS DATA SKM</div>
    
    <div class="sub-bab-title">2.1 Analisis Responden</div>
    <div class="justify-text" style="margin-bottom: 15px;">
        Berdasarkan hasil pengumpulan data, jumlah responden penerima layanan yang diperoleh yaitu {{ number_format($skm->jumlah_sampel, 0, ',', '.') }} orang responden, dengan rincian sebagai berikut :
    </div>
    
    @if(count($analisis_responden) > 0)
    <table>
        <thead>
            <tr>
                <th width="5%" style="text-align: center;">No</th>
                <th width="20%" style="text-align: center;">KARAKTERISTIK</th>
                <th width="30%" style="text-align: center;">INDIKATOR</th>
                <th width="15%" style="text-align: center;">JUMLAH</th>
                <th width="15%" style="text-align: center;">PERSENTASE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($analisis_responden as $item)
            <tr>
                <td class="text-center">{{ $item['no'] ?? '' }}</td>
                <td>{{ $item['karakteristik'] ?? '' }}</td>
                <td>{{ $item['indikator'] ?? '' }}</td>
                <td class="text-center">{{ $item['jumlah'] ?? '' }}</td>
                <td class="text-center">{{ $item['persentase'] ?? '' }}@if(!empty($item['persentase']))%@endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-center text-italic" style="margin-top: 50px;">Data analisis responden tidak tersedia</p>
    @endif
</body>
</html>