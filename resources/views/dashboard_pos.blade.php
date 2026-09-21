<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi POS Toko Kelontong</title>
</head>
<body>
    <header>
        <h2>Sistem Point of Sale (POS) - Toko Mandiri</h2>
        <hr>
    </header>

    <main>
        <h3>Selamat Datang, {{ $nama_pegawai }}!</h3>
        <p>Status Shift Anda hari ini: <strong>{{ $shift }}</strong></p>

        <h4>Menu Cepat:</h4>
        <ul>
            <li>Kasir Aktif: Jam Operasional Terpantau</li>
            <li>Jumlah Transaksi Hari Ini: 0</li>
        </ul>
    </main>

    <footer>
        <hr>
        <p>&copy; 2026 POS Toko Kelontong - Praktikum Pemrograman Web</p>
    </footer>
</body>
</html>
