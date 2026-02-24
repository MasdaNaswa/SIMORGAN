<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAB I - Pendahuluan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            text-align: justify;
            text-justify: inter-word;
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
        
        .justify-text {
            text-align: justify;
            text-justify: inter-word;
            white-space: pre-line;
            line-height: 1.5;
            margin-bottom: 15pt;
            text-indent: 1cm;
        }
        
        ol {
            margin: 15pt 0 15pt 40pt;
            padding-left: 20pt;
            text-align: justify;
            line-height: 1.5;
        }
        
        li {
            margin-bottom: 8pt;
            line-height: 1.5;
            text-align: justify;
            text-justify: inter-word;
        }
    </style>
</head>
<body>
    <div class="bab-title">BAB I<br>PENDAHULUAN</div>
    
    <div class="sub-bab-title">1.1 Latar Belakang</div>
    <div class="justify-text">
        {{ trim(preg_replace('/\s+/', ' ', $skm->latar_belakang)) }}
    </div>
    
    <div class="sub-bab-title">1.2 Tujuan dan Manfaat</div>
    <div class="justify-text">
        Pelaksanaan SKM bertujuan untuk mengetahui gambaran kepuasan masyarakat terhadap kualitas pelayanan dan menilai kinerja penyelenggaraan pelayanan. Adapun manfaat yang diperoleh melalui SKM, antara lain:
    </div>
    
    <ol>
        <li>Mengidentifikasi kelemahan dalam penyelenggaraan pelayanan;</li>
        <li>Mengetahui kinerja pelayanan yang telah dilaksanakan oleh unit pelayanan publik secara periodik;</li>
        <li>Mengetahui indeks kepuasan masyarakat pada lingkup organisasi penyelenggara pelayanan maupun instansi pemerintah;</li>
        <li>Meningkatkan persaingan positif antar organisasi penyelenggara pelayanan;</li>
        <li>Menjadi dasar penetapan kebijakan maupun perbaikan kualitas pelayanan; dan</li>
        <li>Memberikan gambaran kepada masyarakat mengenai kinerja organisasi penyelenggara pelayanan.</li>
    </ol>
    
    <div class="sub-bab-title">1.3 Metode Pengumpulan Data</div>
    <div class="justify-text">
        {{ trim(preg_replace('/\s+/', ' ', $skm->metode_pengumpulan)) }}
    </div>
    
    <div class="sub-bab-title">1.4 Waktu Pelaksanaan SKM</div>
    <div class="justify-text">
        Survei dilakukan secara periodik dengan jangka waktu (periode) tertentu yaitu {{ $skm->waktu_pelaksanaan_bulan }} bulan.
    </div>
    
    <div class="sub-bab-title">1.5 Penentuan Jumlah Responden</div>
    <div class="justify-text">
        Penentuan jumlah responden dilakukan berdasarkan Peraturan Menteri PANRB No.14 Tahun 2017. Populasi penerima layanan sebanyak {{ number_format($skm->jumlah_populasi, 0, ',', '.') }} orang dan sampel sebanyak {{ number_format($skm->jumlah_sampel, 0, ',', '.') }} responden.
    </div>
</body>
</html>