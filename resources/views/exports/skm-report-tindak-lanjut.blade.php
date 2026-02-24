<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tindak Lanjut - SKM</title>
    <style>
        @page {
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
        }
        
        .bab-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin: 0 0 30px 0;
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
        
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .table-center { margin: 0 auto; }
        
        .text-italic {
            font-style: italic;
        }
    
    </style>
</head>
<body>
    <div class="bab-title">BAB III<br>HASIL TINDAK LANJUT SKM PERIODE SEBELUMNYA</div>
    
    @if(count($hasil_skm_sebelumnya) > 0)
    <div class="justify-text">
        Hasil survei kepuasan masyarakat oleh {{ $opd->nama_opd }} periode sebelumnya menunjukkan angka yang sangat beragam pada berbagai unsur pelayanan seperti dapat terlihat pada tabel di bawah ini:
    </div>
    
    <!-- Tabel Hasil SKM Sebelumnya (TETAP di PORTRAIT) -->
    <div style="text-align: center; margin: 15px 0;">
        <strong>Tabel 4. Hasil SKM Periode Sebelumnya</strong>
    </div>
    
    <table class="table-center" style="width: 80%;">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="60%">Unsur Pelayanan</th>
                <th width="30%">Nilai IKM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasil_skm_sebelumnya as $item)
            <tr>
                <td class="text-center">{{ $item['no'] ?? $loop->iteration }}</td>
                <td>{{ $item['unsur'] ?? '-' }}</td>
                <td class="text-center">{{ isset($item['ikm']) ? number_format($item['ikm'], 2) : '0.00' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="justify-text">
        Berkaca pada data di atas, dapat terlihat beberapa unsur yang memerlukan intervensi lanjutan karena rendahnya angka IKM pada unsur tersebut. {{ $opd->nama_opd }} telah menyusun dan menindaklanjuti rencana tindak lanjut perbaikan pada 3 unsur terendah hasil SKM periode sebelumnya. Berkaitan dengan hal tersebut, maka implementasi yang telah dilaksanakan adalah sebagai berikut:
    </div>
    
    @else
    <p class="text-center text-italic" style="margin-top: 50px;">Data hasil SKM periode sebelumnya tidak tersedia</p>
    @endif
</body>
</html>