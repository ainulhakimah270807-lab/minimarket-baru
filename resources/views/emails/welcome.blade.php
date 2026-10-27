<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Selamat Datang!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e1e8ed;
        }
        .header {
            background: #0d6efd;
            color: #ffffff;
            padding: 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content p {
            font-size: 15px;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            background: #0d6efd;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 15px;
        }
        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang, {{ is_object($user) ? $user->name : ($user['name'] ?? 'Pelanggan') }}!</h1>
        </div>
        <div class="content">
            <p>Terima kasih telah mendaftar di sistem <strong>Minimarket POS</strong> kami.</p>
            <p>Akun Anda telah berhasil dibuat. Anda sekarang dapat mengakses layanan belanja, melihat riwayat transaksi kasir, dan menikmati berbagai promo menarik setiap hari.</p>
            <p style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">Masuk ke Aplikasi</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Minimarket Baru. Dikirim via Laravel Email (Acara 32 BKPM).
        </div>
    </div>
</body>
</html>
