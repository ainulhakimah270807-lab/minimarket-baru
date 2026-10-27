<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi POS & Management Minimarket Baru</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --accent: #06b6d4;
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #0f172a 100%);
            --glass: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        .ambient-light-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(0,0,0,0) 70%);
            top: -200px;
            left: -150px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
        }

        .ambient-light-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.2) 0%, rgba(0,0,0,0) 70%);
            bottom: -150px;
            right: -100px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
        }

        /* Navbar */
        header {
            padding: 24px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 20;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }

        .auth-nav {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-link {
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 12px;
            transition: all 0.25s ease;
        }

        .btn-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-main {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(79, 70, 229, 0.6);
        }

        /* Hero Container */
        .hero {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .badge-pill {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .hero h1 {
            font-size: 52px;
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.15;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            max-width: 850px;
        }

        .hero h1 span {
            background: linear-gradient(135deg, #818cf8 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 18px;
            color: #94a3b8;
            max-width: 650px;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            width: 100%;
            margin-top: 20px;
        }

        .feature-card {
            background: var(--glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 32px 28px;
            text-align: left;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        .card-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .icon-blue { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .icon-green { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .icon-purple { background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .feature-card p {
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .card-action {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #818cf8;
        }

        footer {
            padding: 30px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (max-width: 768px) {
            header { padding: 20px; }
            .hero h1 { font-size: 34px; }
            .hero p { font-size: 15px; }
        }
    </style>
</head>
<body>
    <div class="ambient-light-1"></div>
    <div class="ambient-light-2"></div>

    <header>
        <a href="{{ url('/') }}" class="logo">
            <div class="logo-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            Minimarket POS
        </a>

        <div class="auth-nav">
            @auth
                <a href="{{ url('/admin') }}" class="btn-main">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard Admin
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-link">Masuk (Login)</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-main">
                        <i class="fa-solid fa-user-plus"></i> Daftar Akun
                    </a>
                @endif
            @endauth
        </div>
    </header>

    <main class="hero">
        <div class="badge-pill">
            <i class="fa-solid fa-bolt"></i> Sistem Informasi POS Minimarket Modern
        </div>

        <h1>Kelola Transaksi & Stok Toko <span>Lebih Cepat dan Efisien</span></h1>
        <p>Solusi manajemen kasir, inventaris barang, laporan keuangan harian, serta otorisasi hak akses terintegrasi untuk toko kelontong dan minimarket Anda.</p>

        <div class="features-grid">
            <a href="{{ url('/admin') }}" class="feature-card">
                <div>
                    <div class="card-icon icon-blue">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3>Dashboard Admin</h3>
                    <p>Pantau omset harian, jumlah transaksi, stok menipis, dan grafik grafik penjualan secara real-time.</p>
                </div>
                <div class="card-action">
                    Buka Dashboard <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <a href="{{ route('kasir.transaksi') }}" class="feature-card">
                <div>
                    <div class="card-icon icon-green">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                    <h3>Kasir & Penjualan</h3>
                    <p>Antarmuka kasir cepat untuk input barang, hitung kembalian, dan cetak nota transaksi dengan mudah.</p>
                </div>
                <div class="card-action">
                    Buka Kasir POS <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <a href="{{ url('/produk-toko') }}" class="feature-card">
                <div>
                    <div class="card-icon icon-purple">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <h3>Katalog Produk</h3>
                    <p>Lihat daftar seluruh barang toko, harga, stok terkini, serta kategori produk minimarket.</p>
                </div>
                <div class="card-action">
                    Lihat Produk <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>
        </div>
    </main>

    <footer>
        &copy; {{ date('Y') }} Minimarket Baru — Powered by Laravel & Modern Web Standards
    </footer>
</body>
</html>
