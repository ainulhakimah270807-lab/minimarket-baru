<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | POS Minimarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%);
            --glass-bg: rgba(255, 255, 255, 0.07);
            --glass-border: rgba(255, 255, 255, 0.15);
            --accent-red: #ff4757;
            --accent-orange: #ff7f50;
            --accent-purple: #a855f7;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--bg-gradient);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Glow Spheres */
        .glow-sphere-1 {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 71, 87, 0.25) 0%, rgba(0,0,0,0) 70%);
            top: -100px;
            left: -100px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(40px);
        }

        .glow-sphere-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.2) 0%, rgba(0,0,0,0) 70%);
            bottom: -150px;
            right: -100px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(50px);
        }

        .error-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 50px 40px;
            max-width: 580px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(255, 71, 87, 0.15);
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 10;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .shield-icon-wrapper {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(255, 71, 87, 0.2), rgba(255, 127, 80, 0.1));
            border: 1px solid rgba(255, 71, 87, 0.3);
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(255, 71, 87, 0.3);
        }

        .shield-icon-wrapper i {
            font-size: 52px;
            background: linear-gradient(135deg, var(--accent-red), var(--accent-orange));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 4px 10px rgba(255, 71, 87, 0.4));
            animation: pulse 3s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        .error-code {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--accent-red);
            background: rgba(255, 71, 87, 0.12);
            padding: 6px 16px;
            border-radius: 30px;
            display: inline-block;
            margin-bottom: 16px;
            border: 1px solid rgba(255, 71, 87, 0.25);
        }

        .error-title {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
            background: linear-gradient(to right, #ffffff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .error-desc {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .admin-hint-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px dashed rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 32px;
            text-align: left;
        }

        .admin-hint-box .hint-title {
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-hint-box .hint-title i {
            color: #fbbf24;
        }

        .admin-hint-box p {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .admin-hint-box code {
            background: rgba(255, 255, 255, 0.1);
            color: #38bdf8;
            padding: 2px 8px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
        }

        .btn-group-custom {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            border: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.6);
            color: #ffffff;
        }

        .btn-secondary-custom {
            background: rgba(255, 255, 255, 0.08);
            color: #f1f5f9;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .logout-form {
            display: inline;
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 35px 24px;
            }
            .error-title {
                font-size: 26px;
            }
            .btn-group-custom {
                flex-direction: column;
            }
            .btn-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <div class="glow-sphere-1"></div>
    <div class="glow-sphere-2"></div>

    <div class="error-card">
        <div class="shield-icon-wrapper">
            <i class="fa-solid fa-user-shield"></i>
        </div>

        <span class="error-code">HTTP 403 • Restricted Area</span>
        <h1 class="error-title">Akses Ditolak!</h1>
        <p class="error-desc">
            {{ $exception->getMessage() ?: 'Halaman ini khusus untuk pengguna dengan hak akses Admin.' }}
            Akun Anda saat ini tidak memiliki wewenang untuk membuka dashboard ini.
        </p>

        <div class="admin-hint-box">
            <div class="hint-title">
                <i class="fa-solid fa-lightbulb"></i> Cara Mengakses Dashboard Admin:
            </div>
            <p>1. Gunakan Akun Admin: <code>johndoe@example.com</code> / <code>password123</code></p>
            <p>2. Atau ubah role akun Anda via Tinker: <code>$user->role = 'admin'; $user->save();</code></p>
        </div>

        <div class="btn-group-custom">
            <a href="{{ url('/') }}" class="btn-custom btn-secondary-custom">
                <i class="fa-solid fa-house"></i> Halaman Utama
            </a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn-custom btn-primary-custom">
                    <i class="fa-solid fa-right-from-bracket"></i> Switch / Login Admin
                </button>
            </form>
        </div>
    </div>

</body>
</html>
