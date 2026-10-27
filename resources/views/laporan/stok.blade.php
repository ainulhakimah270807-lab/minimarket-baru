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
            border-bottom: 3px solid #198754;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 22px;
            color: #198754;
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
            background: #d1e7dd;
            border: 1px solid #a3cfbb;
            border-radius: 5px;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        .info-box p { margin: 3px 0; font-size: 11px; }
        .alert-box {
            background: #fff3cd;
            border: 1px solid #ffda6a;
            border-radius: 5px;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        .alert-box p { margin: 3px 0; font-size: 11px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #198754;
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
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background: #d1e7dd; color: #0f5132; }
        .badge-warning { background: #fff3cd; color: #664d03; }
        .badge-danger  { background: #f8d7da; color: #842029; }
        .section-title {
            font-size: 14px;
            color: #198754;
            border-bottom: 2px solid #198754;
            padding-bottom: 5px;
            margin: 25px 0 10px 0;
        }
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
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .row-danger { background: #f8d7da !important; }
        .row-warning { background: #fff3cd !important; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MINIMARKET BARU</h1>
        <h2>{{ $title }}</h2>
        <p>Tanggal Cetak: {{ $tanggal }} | Layout: Landscape</p>
    </div>

    <div class="info-box">
        <p><strong>Total Produk:</strong> {{ $totalProduk }} item</p>
        <p><strong>Produk Stok Rendah (≤ 10):</strong> {{ $lowStock->count() }} item</p>
    </div>

    @if($lowStock->count() > 0)
    <div class="alert-box">
        <p><strong>⚠ Peringatan Stok Rendah!</strong></p>
        <p>Terdapat {{ $lowStock->count() }} produk dengan stok rendah yang perlu segera ditambah.</p>
    </div>

    <h3 class="section-title">Produk Stok Rendah (≤ 10 unit)</h3>
    <table>
        <thead>
            <tr>
                <th style="width:35px;">No</th>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStock as $i => $product)
            <tr class="{{ $product->stock == 0 ? 'row-danger' : 'row-warning' }}">
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="text-center">{{ $product->stock }}</td>
                <td class="text-center">
                    @if($product->stock == 0)
                        <span class="badge badge-danger">HABIS</span>
                    @else
                        <span class="badge badge-warning">MENIPIS</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h3 class="section-title">Seluruh Produk (Diurutkan Stok Terendah)</h3>
    <table>
        <thead>
            <tr>
                <th style="width:35px;">No</th>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $i => $product)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="text-center">{{ $product->stock }}</td>
                <td class="text-center">
                    @if($product->stock == 0)
                        <span class="badge badge-danger">Habis</span>
                    @elseif($product->stock <= 10)
                        <span class="badge badge-warning">Menipis</span>
                    @else
                        <span class="badge badge-success">Aman</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem POS Minimarket Baru &mdash; {{ $tanggal }}
    </div>
</body>
</html>
