<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Masalah - {{ $opd->nama_opd }}</title>
    <style>
        @page {
            margin: 1.5cm 2.54cm 2.54cm 2.54cm;
        }
        
        body {
            font-family: 'Arial';
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        
        .bab-title {
            font-size: 14pt;
            font-weight: bold;
            margin: 0 0 10px 0;
            padding: 0;
            line-height: 1.5;
            text-align: left;
            display: block;
            width: 100%;
        }
        
        .sub-bab-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 20px 0 15px 0;
            line-height: 1.5;
        }
        
        .content {
            margin: 0 0 20px 0;
            text-align: justify;
        }
        
        .justify-text {
            text-align: justify;
            line-height: 1.6;
        }
        
        .indent-20 {
            padding-left: 20px;
        }
        
        .italic {
            font-style: italic;
        }
        
        .bg-light-blue {
            background-color: #f0f8ff;
        }
        
        .bg-light-gray {
            background-color: #f9f9f9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11pt;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        td {
            text-align: left;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>

    <div class="bab-title">2.3 Analisis Masalah dan Rencana Tindak Lanjut</div>
    
    @if(!empty($skm->analisis_masalah))
    <div class="justify-text" style="margin-bottom: 20px;">
        {{ $skm->analisis_masalah }}
    </div>
    @endif
    
    @if(count($rencana_tindak_lanjut_analisis) > 0)
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Unsur</th>
                <th width="40%">Rencana Tindak Lanjut</th>
                <th width="15%">Waktu</th>
                <th width="25%">Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @php
                $processedMainItems = [];
                $processedSubItems = [];
            @endphp
            
            @foreach($rencana_tindak_lanjut_analisis as $index => $rtl)
                @php
                    $isMainItem = !str_contains($rtl['no'] ?? '', '.');
                    $isSubItem = str_contains($rtl['no'] ?? '', '.');
                    
                    if ($isMainItem) {
                        $processedMainItems[] = $rtl;
                    } else {
                        $processedSubItems[] = $rtl;
                    }
                @endphp
            @endforeach
            
            @foreach($processedMainItems as $mainIndex => $mainItem)
                <tr class="bg-light-blue">
                    <td class="text-center">{{ $mainItem['no'] }}</td>
                    <td>{{ $mainItem['unsur'] ?? $mainItem['rencana'] ?? '' }}</td>
                    <td>{{ $mainItem['rencana'] ?? '' }}</td>
                    <td class="text-center">{{ $mainItem['waktu'] ?? '' }}</td>
                    <td>{{ $mainItem['penanggung_jawab'] ?? '' }}</td>
                </tr>
                
                @foreach($processedSubItems as $subIndex => $subItem)
                    @php
                        $mainNo = $mainItem['no'];
                        $subNoParts = explode('.', $subItem['no'] ?? '');
                        $subMainNo = $subNoParts[0] ?? '';
                        
                        if ($subMainNo == $mainNo) {
                            $subNo = $subNoParts[1] ?? '';
                    @endphp
                            <tr class="bg-light-gray">
                                <td class="text-center">{{ $mainNo }}.{{ $subNo }}</td>
                                <td><div class="indent-20 italic">{{ $subItem['unsur'] ?? '' }}</div></td>
                                <td>{{ $subItem['rencana'] ?? '' }}</td>
                                <td class="text-center">{{ $subItem['waktu'] ?? '' }}</td>
                                <td>{{ $subItem['penanggung_jawab'] ?? '' }}</td>
                            </tr>
                    @php
                        }
                    @endphp
                @endforeach
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>