<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #6f42c1;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 22px;
            color: #6f42c1;
            margin: 0 0 5px 0;
        }
        .header h2 {
            font-size: 16px;
            color: #333;
            margin: 0 0 5px 0;
            font-weight: normal;
        }
        .header p {
            font-size: 11px;
            color: #777;
            margin: 0;
        }
        .info-box {
            background: #e8daef;
            border: 1px solid #c8a2de;
            border-radius: 5px;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        .info-box p { margin: 3px 0; font-size: 11px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #6f42c1;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }
        td {
            border-bottom: 1px solid #dee2e6;
            padding: 7px 10px;
            font-size: 11px;
        }
        tr:nth-child(even) { background: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MINIMARKET BARU</h1>
        <h2>{{ $title }}</h2>
        <p>Tanggal Cetak: {{ $tanggal }}</p>
    </div>

    @php
        $totalSalary = 0;
        foreach ($pegawai as $p) {
            $totalSalary += is_array($p) ? ($p['gaji'] ?? 3500000) : ($p->salary ?? 0);
        }
    @endphp

    <div class="info-box">
        <p><strong>Total Pegawai:</strong> {{ count($pegawai) }} orang</p>
        <p><strong>Total Gaji:</strong> Rp {{ number_format($totalSalary, 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th class="text-right">Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $i => $emp)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ is_array($emp) ? ($emp['nama'] ?? $emp['name']) : $emp->name }}</td>
                <td>{{ is_array($emp) ? ($emp['jabatan'] ?? $emp['position'] ?? 'Staff') : ($emp->position ?? 'Staff') }}</td>
                <td class="text-right">Rp {{ number_format(is_array($emp) ? ($emp['gaji'] ?? 3500000) : ($emp->salary ?? 0), 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr style="background: #e8daef; font-weight: bold;">
                <td colspan="3" class="text-right">Total Gaji</td>
                <td class="text-right">Rp {{ number_format($totalSalary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem POS Minimarket Baru &mdash; {{ $tanggal }}
    </div>
</body>
</html>
