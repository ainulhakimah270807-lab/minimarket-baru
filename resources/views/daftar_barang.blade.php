<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Barang</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Daftar Barang Toko Kelontong</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
        @foreach ($barang as $index => $b)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $b->nama_barang }}</td>
            <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
            <td>{{ $b->stok }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
