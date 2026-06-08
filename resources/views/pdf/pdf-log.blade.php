<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Buku Log Latihan Industri</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #000000;
            background: #ffffff;
        }

        .log-page {
            width: 100%;
            page-break-after: always;
        }

        .log-page:last-child {
            page-break-after: avoid;
        }

        .inner {
            margin: 40px 50px;
        }

        /* ===== Header box ===== */
        .header-box {
            border: 1px solid #000;
            padding: 8px 12px;
            margin-bottom: 20px;
            width: 85%;
        }

        .header-row {
            margin-bottom: 6px;
            font-size: 11px;
        }

        .header-row:last-child {
            margin-bottom: 0;
        }

        .h-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 80px;
        }

        .h-line-medium {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 160px;
        }

        .h-line-long {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
        }

        /* ===== Section A-E ===== */
        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .full-line {
            border-bottom: 1px solid #000;
            display: block;
            width: auto;
            margin-bottom: 8px;
            min-height: 16px;
            padding-bottom: 2px;
            font-size: 11px;
        }

        .empty-full-line {
            border-bottom: 1px solid #000;
            display: block;
            width: auto;
            height: 18px;
            margin-bottom: 8px;
        }

        /* ===== Section D — ruangan gambar ===== */

        /*
         * Guna table layout untuk susun gambar 2 sebelah 2.
         * DomPDF tidak support flexbox/grid, tapi table boleh kerja.
         * width: 100% supaya table penuh lebar ruangan.
         */
        .gambar-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        /*
         * Setiap sel table pegang satu gambar.
         * width: 33% = 3 gambar setiap baris.
         * padding-right: 8px untuk bagi ruang antara gambar.
         * vertical-align: top supaya gambar align atas, bukan tengah.
         */
        .gambar-sel {
            width: 33%;
            padding: 0 8px 12px 0;
            vertical-align: top;
            text-align: left;
        }

        /* Gambar dalam setiap sel */
        .gambar-sel img {
            max-width: 100%;
            max-height: 200px;
            display: block;
        }

        /*
         * Label "Gambar 1", "Gambar 2" dll.
         * text-align: left supaya label ikut tepi gambar, bukan tengah halaman.
         */
        .gambar-label {
            text-align: left;
            font-size: 10px;
            color: #555;
            margin-top: 4px;
        }

        /*
         * Kotak amaran — dipapar bila gambar tidak dapat diproses
         * (contoh: gambar PNG tapi PHP GD extension tidak dipasang di server)
         */
        .gambar-warning {
            border: 1px dashed #999;
            padding: 8px 12px;
            font-size: 10px;
            color: #666;
            margin-bottom: 8px;
            text-align: center;
        }

        /* ===== Tandatangan ===== */
        .signature-section {
            margin-top: 24px;
        }

        .sig-row {
            margin-bottom: 8px;
            font-size: 11px;
        }

        .sig-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 130px;
            margin-left: 3px;
        }
    </style>
</head>
<body>

    @foreach($logs as $log)
    <div class="log-page">
        <div class="inner">

            {{-- ===== HEADER BOX ===== --}}
            <div class="header-box">

                <div class="header-row">
                    Hari Ke : <span class="h-line">{{ $log->log_day }}</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    Tarikh : <span class="h-line-medium">{{ $log->log_date->format('d/m/Y') }}</span>
                </div>

                <div class="header-row">
                    Lokasi/Tempat : <span class="h-line-long">{{ trim(($log->log_location ?? '') . ($log->log_location && $log->log_place ? ', ' : '') . ($log->log_place ?? '')) }}</span>
                </div>

                <div class="header-row">
                    Jabatan/Unit/Bahagian : <span class="h-line-long">{{ $user->defaults->default_department ?? '' }}</span>
                </div>

            </div>

            {{-- ===== A) RINGKASAN AKTIVITI HARIAN ===== --}}
            <div class="section">
                <div class="section-title">A) Ringkasan Aktiviti Harian</div>
                @foreach(explode("\n", wordwrap(trim($log->log_summary ?? ''), 105, "\n", true)) as $baris)
                    <div class="full-line">{{ $baris }}</div>
                @endforeach
                <div class="empty-full-line"></div>
                <div class="empty-full-line"></div>
            </div>

            {{-- ===== B) PENGETAHUAN/KEMAHIRAN ===== --}}
            <div class="section">
                <div class="section-title">B) Pengetahuan/Kemahiran yang diperolehi</div>
                @if($log->log_knowledge)
                    @foreach(explode("\n", wordwrap(trim($log->log_knowledge), 105, "\n", true)) as $baris)
                        <div class="full-line">{{ $baris }}</div>
                    @endforeach
                @endif
                <div class="empty-full-line"></div>
                <div class="empty-full-line"></div>
            </div>

            {{-- ===== C) PERALATAN/TEKNOLOGI ===== --}}
            <div class="section">
                <div class="section-title">C) Peralatan/Teknologi yang digunakan</div>
                @if($log->log_tools)
                    @foreach(explode("\n", wordwrap(trim($log->log_tools), 105, "\n", true)) as $baris)
                        <div class="full-line">{{ $baris }}</div>
                    @endforeach
                @endif
                <div class="empty-full-line"></div>
                <div class="empty-full-line"></div>
            </div>

            {{-- ===== D) GAMBARAJAH/CARTA ALIR ===== --}}
            <div class="section">
                <div class="section-title">D) Gambarajah/Carta Alir jika berkaitan/Isu/Cabaran</div>

                @if($log->references->isNotEmpty())

                    {{--
                        Susun gambar dalam table, 3 gambar setiap baris.
                        Kita chunk collection kepada kumpulan 3.
                        Contoh: [G1, G2, G3] [G4, G5, G6]
                    --}}
                    <table class="gambar-table">
                        @foreach($log->references->chunk(3) as $baris)
                        <tr>
                            @foreach($baris as $index => $ref)
                            <td class="gambar-sel">

                                {{-- KES 1: Gambar berjaya diproses — papar gambar --}}
                                @if($ref->image_base64)
                                    <img src="{{ $ref->image_base64 }}" alt="Gambar {{ $loop->parent->index * 3 + $loop->index + 1 }}">
                                    <div class="gambar-label">Gambar {{ $loop->parent->index * 3 + $loop->index + 1 }}</div>

                                {{-- KES 2: Gambar tidak dapat diproses (PNG tanpa GD) --}}
                                @else
                                    <div class="gambar-warning">
                                        [Gambar {{ $loop->parent->index * 3 + $loop->index + 1 }} tidak dapat dipaparkan.
                                        Sila enable PHP GD extension atau upload semula dalam format JPEG.]
                                    </div>
                                @endif

                            </td>
                            @endforeach

                            {{--
                                Kalau baris terakhir ada gambar kurang dari 3,
                                tambah sel kosong supaya table tidak nampak pelik.
                                Contoh: 4 gambar = baris kedua ada 1 gambar + 2 sel kosong.
                            --}}
                            @for($kosong = $baris->count(); $kosong < 3; $kosong++)
                            <td class="gambar-sel"></td>
                            @endfor
                        </tr>
                        @endforeach
                    </table>

                @else
                    {{-- Tiada gambar disubmit — tunjuk garisan kosong --}}
                    <div class="empty-full-line"></div>
                    <div class="empty-full-line"></div>
                @endif

            </div>

            {{-- ===== E) CATATAN/REFLEKSI ===== --}}
            <div class="section">
                <div class="section-title">E) Catatan/Refleksi/Kaedah Penyelesaian</div>
                @if($log->log_note)
                    @foreach(explode("\n", wordwrap(trim($log->log_note), 105, "\n", true)) as $baris)
                        <div class="full-line">{{ $baris }}</div>
                    @endforeach
                @endif
                <div class="empty-full-line"></div>
                <div class="empty-full-line"></div>
            </div>

            {{-- ===== TANDATANGAN ===== --}}
            <div class="signature-section">
                <div class="sig-row">
                    Tandatangan : <span class="sig-line">&nbsp;</span>
                </div>
                <div class="sig-row">
                    Nama : <span class="sig-line">{{ $user->user_name ?? '' }}</span>
                </div>
            </div>

        </div>{{-- end .inner --}}
    </div>
    @endforeach

</body>
</html>