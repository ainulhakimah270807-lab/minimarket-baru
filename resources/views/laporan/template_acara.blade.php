<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }} - {{ $nama }} ({{ $nim }})</title>
    <style>
        @page {
            margin: 2.5cm 2.5cm 2.5cm 2.5cm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #222222;
        }
        .page-break {
            page-break-after: always;
        }
        /* Cover Page Styling */
        .cover-container {
            text-align: center;
            height: 100%;
            padding-top: 1.5cm;
        }
        .cover-instansi {
            font-size: 13pt;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .cover-subinstansi {
            font-size: 11pt;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .cover-prodi {
            font-size: 10pt;
            color: #64748b;
            margin-bottom: 25px;
        }
        .cover-divider {
            border-bottom: 3px double #1e3a8a;
            margin: 0 auto 35px auto;
            width: 85%;
        }
        .cover-type {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .cover-title {
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .cover-subtitle {
            font-size: 11pt;
            color: #475569;
            font-style: italic;
            margin-bottom: 60px;
        }
        .cover-identity-box {
            margin: 0 auto 70px auto;
            width: 75%;
            text-align: left;
            border: 1px solid #cbd5e1;
            padding: 18px 24px;
            background-color: #f8fafc;
            border-radius: 4px;
        }
        .cover-identity-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cover-identity-table td {
            padding: 4px 6px;
            font-size: 10.5pt;
        }
        .cover-footer {
            font-size: 10pt;
            color: #64748b;
            line-height: 1.5;
            text-transform: uppercase;
        }

        /* Content Document Styling */
        h1.bab-title {
            font-size: 14pt;
            color: #1e3a8a;
            text-align: center;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 8px;
        }
        h2.subbab-title {
            font-size: 11.5pt;
            color: #0f172a;
            margin-top: 16px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        h3.subsubbab-title {
            font-size: 11pt;
            color: #1e293b;
            margin-top: 12px;
            margin-bottom: 6px;
            font-weight: bold;
        }
        p {
            text-align: justify;
            text-indent: 1cm;
            margin-bottom: 10px;
            margin-top: 0;
        }
        p.no-indent {
            text-indent: 0;
        }
        ol, ul {
            margin-top: 0;
            margin-bottom: 10px;
            padding-left: 28px;
        }
        li {
            margin-bottom: 5px;
            text-align: justify;
        }
        code {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f1f5f9;
            padding: 2px 4px;
            font-size: 9.5pt;
            border-radius: 3px;
            color: #0f172a;
        }
        pre {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 9pt;
            line-height: 1.4;
            color: #1e293b;
            margin-top: 6px;
            margin-bottom: 14px;
            border-radius: 4px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 16px;
            font-size: 10pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #94a3b8;
            padding: 6px 10px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            text-align: left;
            font-weight: bold;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .header-running {
            position: fixed;
            top: -1.8cm;
            left: 0;
            right: 0;
            height: 30px;
            border-bottom: 1px solid #cbd5e1;
            font-size: 8.5pt;
            color: #64748b;
            text-align: right;
        }
        .footer-running {
            position: fixed;
            bottom: -1.8cm;
            left: 0;
            right: 0;
            height: 30px;
            border-top: 1px solid #cbd5e1;
            font-size: 8.5pt;
            color: #64748b;
            text-align: center;
            line-height: 25px;
        }
    </style>
</head>
<body>

    <!-- HALAMAN SAMPUL / COVER -->
    <div class="cover-container">
        <div class="cover-instansi">Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi</div>
        <div class="cover-subinstansi">Politeknik Negeri Jember &bull; Jurusan Teknologi Informasi</div>
        <div class="cover-prodi">Program Studi D4 Teknik Informatika &bull; Kampus Jember</div>
        <div class="cover-divider"></div>

        <div class="cover-type">Laporan Praktikum</div>
        <div class="cover-title">{{ $acara_no }}: {{ $acara_judul }}</div>
        <div class="cover-subtitle">Workshop Sistem Informasi Web Framework ({{ $minggu }})</div>

        <div class="cover-identity-box">
            <table class="cover-identity-table">
                <tr>
                    <td style="width: 130px; font-weight: bold;">Nama Mahasiswa</td>
                    <td style="width: 15px;">:</td>
                    <td style="font-weight: bold; color: #1e3a8a;">{{ $nama }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">NIM</td>
                    <td>:</td>
                    <td>{{ $nim }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Golongan / Kelas</td>
                    <td>:</td>
                    <td>{{ $golongan }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Mata Kuliah</td>
                    <td>:</td>
                    <td>Workshop Sistem Informasi Web Framework</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Dosen Pengampu</td>
                    <td>:</td>
                    <td>{{ $dosen }}</td>
                </tr>
            </table>
        </div>

        <div class="cover-footer">
            <strong>Jurusan Teknologi Informasi</strong><br>
            Politeknik Negeri Jember<br>
            Tahun Akademik 2026/2027
        </div>
    </div>

    <div class="page-break"></div>

    <!-- HEADER & FOOTER RUNNING -->
    <div class="header-running">
        {{ $acara_no }}: {{ $acara_judul }} &bull; {{ $nama }} ({{ $nim }})
    </div>
    <div class="footer-running">
        Workshop Sistem Informasi Web Framework &bull; D4 Teknik Informatika Polije 2026
    </div>

    <!-- BAB I: PENDAHULUAN -->
    <h1 class="bab-title">BAB I: PENDAHULUAN</h1>

    <h2 class="subbab-title">1.1 Latar Belakang</h2>
    @foreach($latar_belakang as $p)
        <p>{{ $p }}</p>
    @endforeach

    <h2 class="subbab-title">1.2 Tujuan Praktikum</h2>
    <ol>
        @foreach($tujuan as $t)
            <li>{{ $t }}</li>
        @endforeach
    </ol>

    <h2 class="subbab-title">1.3 Dasar Teori</h2>
    @foreach($dasar_teori as $dt)
        <h3 class="subsubbab-title">{{ $dt['sub'] }}</h3>
        <p>{{ $dt['desc'] }}</p>
        @if(isset($dt['code']))
            <pre>{{ $dt['code'] }}</pre>
        @endif
    @endforeach

    <div class="page-break"></div>

    <!-- BAB II: HASIL DAN PEMBAHASAN -->
    <h1 class="bab-title">BAB II: HASIL DAN PEMBAHASAN</h1>

    <h2 class="subbab-title">2.1 Alat dan Bahan</h2>
    <ul>
        @foreach($alat_bahan as $ab)
            <li>{{ $ab }}</li>
        @endforeach
    </ul>

    <h2 class="subbab-title">2.2 Prosedur dan Langkah Kerja</h2>
    <ol>
        @foreach($langkah_kerja as $lk)
            <li>
                <strong>{{ $lk['judul'] }}</strong><br>
                {{ $lk['deskripsi'] }}
                @if(isset($lk['snippet']))
                    <pre>{{ $lk['snippet'] }}</pre>
                @endif
            </li>
        @endforeach
    </ol>

    <h2 class="subbab-title">2.3 Implementasi Kode Program Utama</h2>
    @foreach($kode_program as $kp)
        <h3 class="subsubbab-title">{{ $kp['file'] }}</h3>
        <p class="no-indent" style="font-size: 9.5pt; color: #475569; margin-bottom: 4px;">{{ $kp['penjelasan'] }}</p>
        <pre>{{ $kp['code'] }}</pre>
    @endforeach

    <h2 class="subbab-title">2.4 Hasil Pengujian dan Evaluasi</h2>
    <p>{{ $evaluasi_text }}</p>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Skenario Pengujian Unit / Fitur</th>
                <th style="width: 90px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($test_cases as $idx => $tc)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td>{{ $tc['case'] }}</td>
                <td style="text-align: center; color: #166534; font-weight: bold;">{{ $tc['status'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- BAB III: KESIMPULAN -->
    <h1 class="bab-title">BAB III: KESIMPULAN</h1>
    <p>Berdasarkan rangkaian praktikum yang telah diselesaikan, dapat ditarik beberapa kesimpulan penting sebagai berikut:</p>
    <ol>
        @foreach($kesimpulan as $k)
            <li>{{ $k }}</li>
        @endforeach
    </ol>

    <!-- DAFTAR PUSTAKA -->
    <h1 class="bab-title" style="margin-top: 35px;">DAFTAR PUSTAKA</h1>
    <ol>
        @foreach($daftar_pustaka as $dp)
            <li>{{ $dp }}</li>
        @endforeach
    </ol>

</body>
</html>
