<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan SKM - Tabel Jenis Layanan</title>
    <style>
        /* Page setup - landscape */
        @page {
            size: A4 landscape;
            margin: 2.54cm;
        }
        
        body {
            font-family: 'Arial';
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            text-align: justify;
            text-justify: inter-word;
        }
        
        /* Tabel styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 20px 0;
            font-size: 9pt;
            line-height: 1.4;
        }
        
        table th, table td {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
            line-height: 1.4;
        }
        
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .text-center { 
            text-align: center; 
        }
        
        .text-bold { 
            font-weight: bold; 
        }
        
        .text-left { 
            text-align: left; 
        }
        
        /* Warna untuk baris khusus */
        .bg-light-gray {
            background-color: #f8f9fa;
        }
        
        .bg-light-blue {
            background-color: #e8f4fd;
        }
        
        /* Sub-bab title styling */
        .sub-bab-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 5px 0 15px;
            line-height: 1.5;
            text-align: left;
        }
        
        /* Judul tabel */
        .table-title {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            margin: 10px 0 20px 0;
        }
        
        /* Content wrapper */
        .content-wrapper {
            padding-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="sub-bab-title">2.2 Indeks Kepuasan Masyarakat Per Jenis Layanan</div>
        
        @if(count($jenis_layanan) > 0)
        <table>
            <thead>
                <tr>
                    <th rowspan="2" width="4%">No.</th>
                    <th rowspan="2" width="18%">Jenis Layanan</th>
                    <th rowspan="2" width="8%">Jumlah<br>Responden</th>
                    <th colspan="9" width="58%">Nilai Unsur</th>
                    <th rowspan="2" width="12%">IKM Per Jenis Layanan</th>
                </tr>
                <tr>
                    <th width="6.5%">Persyaratan</th>
                    <th width="6.5%">Prosedur</th>
                    <th width="6.5%">Waktu</th>
                    <th width="6.5%">Biaya</th>
                    <th width="6.5%">Produk</th>
                    <th width="6.5%">Kompetensi</th>
                    <th width="6.5%">Perilaku</th>
                    <th width="6.5%">Aduan</th>
                    <th width="6.5%">Sarpras</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jenis_layanan as $layanan)
                <tr>
                    <td>{{ $layanan['no'] ?? $loop->iteration }}.</td>
                    <td class="text-left">{{ $layanan['jenis_layanan'] }}</td>
                    <td>{{ $layanan['jumlah_responden'] }}</td>
                    @for($i = 0; $i < 9; $i++)
                    <td>{{ $layanan['nilai'][$i] ?? 0 }}</td>
                    @endfor
                    <td>{{ number_format($layanan['ikm_per_jenis'], 2) }}</td>
                </tr>
                @endforeach
                
                <!-- Baris kosong untuk spacing -->
                @for($i = 0; $i < max(0, 4 - count($jenis_layanan)); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    @for($j = 0; $j < 9; $j++)
                    <td>&nbsp;</td>
                    @endfor
                    <td>&nbsp;</td>
                </tr>
                @endfor
                
                <!-- Baris Rerata IKM Per Unsur -->
                <tr class="bg-light-gray">
                    <td colspan="2" class="text-bold">Rerata IKM Per Unsur</td>
                    <td></td>
                    @foreach($rerata_ikm as $rerata)
                    <td class="text-bold">{{ number_format($rerata, 1) }}</td>
                    @endforeach
                    <td class="text-bold">{{ number_format($skm->ikm_unit_layanan, 2) }}</td>
                </tr>
                
                <!-- Baris IKM Unit Layanan -->
                <tr class="bg-light-blue">
                    <td colspan="2" class="text-bold">IKM Unit Layanan</td>
                    <td colspan="10" class="text-bold text-left" style="padding-left: 15px;">
                        {{ number_format($skm->ikm_unit_layanan, 2) }}
                    </td>
                </tr>
                
                <!-- Baris Mutu Unit Layanan -->
                <tr class="bg-light-blue">
                    <td colspan="2" class="text-bold">Mutu Unit Layanan</td>
                    <td colspan="10" class="text-bold text-left" style="padding-left: 15px;">
                        {{ $skm->mutu_unit_layanan }}
                    </td>
                </tr>
            </tbody>
        </table>
        @else
        <p class="text-center" style="font-style: italic; margin-top: 50px;">Data jenis layanan tidak tersedia</p>
        @endif
    </div>
</body>
</html>