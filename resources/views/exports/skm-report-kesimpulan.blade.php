<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesimpulan - SKM</title>
    <style>
        @page {
            size: A4;
            margin: 2.54cm;
        }
        
        body {
            font-family: 'Arial', serif;
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
        
        .justify-text {
            text-align: justify;
            text-justify: inter-word;
            white-space: pre-line;
            line-height: 1.6;
            margin-bottom: 20px;
            text-indent: 1.25cm;
        }
        
        .signature-section {
            margin-top: 100px;
            width: 100%;
        }
        
        .signature-container {
            float: right;
            width: 60%;
        }
        
        .signature-place {
            text-align: left;
            margin-bottom: 40px;
        }
        
        .signature-place .city {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        
        .signature-place .date {
            font-size: 12pt;
            margin-bottom: 50px;
        }
        
        .signature-box {
            text-align: left;
            margin-top: 80px;
        }
        
        .signature-name {
            font-size: 12pt;
            margin-top: 5px;
            margin-bottom: 5px;
            text-decoration: underline;
        }
        
        .signature-nip {
            font-size: 11pt;
            margin-top: 5px;
        }
        
        .clearfix {
            clear: both;
        }
        
        /* Garis untuk tanda tangan */
        .signature-line {
            width: 250px;
            border-top: 1px solid #000;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="bab-title">BAB IV<br>KESIMPULAN</div>
    
    <!-- Kesimpulan -->
    <div class="justify-text">
        {{ $skm->kesimpulan }}
    </div>
    
    <!-- Saran -->
    <div class="justify-text">
        {{ $skm->saran }}
    </div>
    
    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-container">
            <div class="signature-place">
                <div class="city">Kabupaten Karimun, {{ $tanggal_pengesahan_formatted }}</div>
                <div class="date">{{ $skm->jabatan_penandatangan }}</div>
            </div>
            
            <div class="signature-box">
                <!-- Garis untuk tanda tangan -->
                <div class="signature-line"></div>
                
                <!-- Nama Penandatangan -->
                <div class="signature-name">
                    {{ $skm->nama_penandatangan ?? '...................................' }}
                </div>
                
                <!-- NIP Penandatangan -->
                <div class="signature-nip">
                    NIP. {{ $skm->nip_penandatangan ?? '...................................' }}
                </div>
            </div>
        </div>
        
        <div class="clearfix"></div>
    </div>
</body>
</html>