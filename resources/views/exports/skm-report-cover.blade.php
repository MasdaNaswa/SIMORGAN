<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan SKM - {{ $opd->nama_opd }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            line-height: 1.2;
            margin-top: 100px;
        }
        
        .header h1 {
            font-size: 16pt;
            margin: 0 0 10px 0;
            font-weight: bold;
            line-height: 1.2;
        }
        
        .header h2 {
            font-size: 14pt;
            margin: 15px 0 5px 0;
            line-height: 1.2;
        }
        
        .header h3 {
            font-size: 12pt;
            margin: 5px 0;
            font-weight: normal;
            line-height: 1.2;
        }
        
        .spacer {
            height: 150px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN</h1>
        <h1>PELAKSANAAN SURVEI KEPUASAN MASYARAKAT (SKM)</h1>
        <div class="spacer"></div>
        <h2>{{ strtoupper($opd->nama_opd) }}</h2>
        <h3>KABUPATEN KARIMUN</h3>
        <h3>TAHUN {{ $skm->tahun }}</h3>
    </div>
</body>
</html>