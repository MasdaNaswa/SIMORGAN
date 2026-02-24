<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tindak Lanjut - SKM (Landscape)</title>
    <style>
        /* Page setup - landscape */
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }

        body {
            font-family: 'Arial';
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Tabel styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 15px 0;
            font-size: 8pt;
            table-layout: fixed;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 4px 3px;
            vertical-align: top;
            word-wrap: break-word;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            font-size: 8pt;
            line-height: 1.2;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-justify {
            text-align: justify;
        }

        .text-right {
            text-align: right;
        }

        /* Lebar kolom */
        .col-no {
            width: 4%;
        }

        .col-rencana {
            width: 22%;
        }

        .col-status {
            width: 12%;
        }

        .col-deskripsi {
            width: 32%;
        }

        .col-dokumentasi {
            width: 30%;
        }

        .table-title {
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
            margin: 5px 0 10px 0;
        }

        .status-selesai {
            color: #000000;
            font-weight: normal;
            background-color: #e6ffe6;
            padding: 2px 5px;
            border-radius: 3px;
            display: inline-block;
        }

        .status-proses {
            color: #cc6600;
            font-weight: normal;
            background-color: #fff2e6;
            padding: 2px 5px;
            border-radius: 3px;
            display: inline-block;
        }

        .status-belum {
            color: #000000;
            font-weight: normal;
            background-color: #ffe6e6;
            padding: 2px 5px;
            border-radius: 3px;
            display: inline-block;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #666;
            padding: 20px;
            border: 1px dashed #ccc;
            margin: 10px;
            background-color: #f9f9f9;
            font-size: 9pt;
        }

        .content-wrapper {
            padding-bottom: 15px;
        }

        /* Style untuk gambar dokumentasi */
        .dokumentasi-image-container {
            text-align: center;
            margin: 3px 0;
        }

        .dokumentasi-image {
            max-width: 100%;
            max-height: 150px;
            display: block;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 2px;
            background-color: white;
        }

        .image-caption {
            font-size: 7pt;
            color: #555;
            margin-top: 2px;
            font-style: italic;
            text-align: center;
        }

        .no-image {
            font-size: 8pt;
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 10px;
        }

        /* Responsive untuk isi tabel */
        .deskripsi-content {
            max-height: 150px;
            overflow: hidden;
            line-height: 1.2;
        }

        .rencana-content {
            line-height: 1.2;
        }

        /* Page break untuk menjaga gambar tetap dalam satu halaman */
        .keep-together {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <!-- Tabel Tindak Lanjut Sebelumnya -->
        <div class="table-title">
            <strong>Tabel 5. Hasil Tindak Lanjut SKM Periode Sebelumnya</strong>
        </div>

        @if(isset($tindak_lanjut_sebelumnya) && count($tindak_lanjut_sebelumnya) > 0)
            <table>
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-rencana">Rencana Tindak Lanjut</th>
                        <th class="col-status">Status<br>Pelaksanaan</th>
                        <th class="col-deskripsi">Deskripsi Tindak Lanjut</th>
                        <th class="col-dokumentasi">Dokumentasi Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tindak_lanjut_sebelumnya as $index => $tl)
                        <tr class="keep-together">
                            <td class="text-center">{{ $tl['no'] ?? ($index + 1) }}</td>

                            <td class="text-left rencana-content">
                                {{ $tl['rencana'] ?? '-' }}
                            </td>

                            <td class="text-center">
                                @if(isset($tl['status']))
                                    @php
                                        $status = strtolower(trim($tl['status']));
                                        $statusText = 'BELUM';
                                        $statusClass = 'status-belum';

                                        if (str_contains($status, 'sudah') || str_contains($status, 'selesai')) {
                                            $statusText = 'SUDAH';
                                            $statusClass = 'status-selesai';
                                        } elseif (str_contains($status, 'proses') || str_contains($status, 'dalam')) {
                                            $statusText = 'PROSES';
                                            $statusClass = 'status-proses';
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                @else
                                    <span class="status-belum">BELUM</span>
                                @endif
                            </td>

                            <td class="text-justify deskripsi-content">
                                {{ $tl['deskripsi'] ?? 'Tidak ada deskripsi' }}
                                @if(isset($tl['tantangan']) && !empty(trim($tl['tantangan'])))
                                    <br><br><strong>Tantangan:</strong> {{ $tl['tantangan'] }}
                                @endif
                                @if(isset($tl['hambatan']) && !empty(trim($tl['hambatan'])))
                                    <br><br><strong>Hambatan:</strong> {{ $tl['hambatan'] }}
                                @endif
                            </td>

                            <td class="text-center">
                                @if(isset($tl['dokumentasi_image']) && $tl['dokumentasi_image'])
                                    <div class="dokumentasi-image-container">
                                        <img src="data:image/{{ $tl['dokumentasi_image']['extension'] }};base64,{{ $tl['dokumentasi_image']['base64'] }}"
                                            alt="Dokumentasi {{ $index + 1 }}" class="dokumentasi-image">
                                    </div>
                                @elseif(isset($tl['dokumentasi']) && $tl['dokumentasi'] != '-' && $tl['dokumentasi'] != '')
                                    <div class="dokumentasi-image-container">
                                        <div class="no-image">
                                            File: {{ $tl['dokumentasi'] }}
                                        </div>
                                    </div>
                                @else
                                    <div class="no-image">
                                        -
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @else
            <div class="no-data">
                <p><strong>DATA TINDAK LANJUT TIDAK TERSEDIA</strong></p>
                <p>Tidak ada data tindak lanjut yang tercatat untuk periode sebelumnya.</p>
            </div>
        @endif
    </div>
</body>

</html>