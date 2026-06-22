<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Student Center Politeknik Negeri Batam</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2f7ea1;
            --secondary: #4a9dc3;
            --dark: #1e293b;
            --white: #ffffff;
            --blur-strength: 14px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background-image: url("{{ asset('images/Me.png') }}");
            background-size: 350px; background-position: center;
            background-attachment: fixed; background-repeat: no-repeat;
            background-color: #dbeef7; padding: 20px 16px;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: rgba(30, 41, 59, 0.35); z-index: 0;
        }
        .login-wrapper {
            position: relative; z-index: 1;
            width: 100%; max-width: 450px; 
            animation: fadeUp 0.45s cubic-bezier(0.4,0,0.2,1);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(var(--blur-strength));
            -webkit-backdrop-filter: blur(var(--blur-strength));
            border-radius: 26px; 
            padding: 65px 44px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.18);
            border: 1px solid rgba(255,255,255,0.6);
        }
        .card-header { text-align: center; margin-bottom: 28px; } 
        .card-logo {
            width: 60px; height: 45px; 
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: 100%; background-position: 50% 60%;
            background-repeat: no-repeat; margin: 0 auto 10px;
        }
        .card-header h2 { font-size: 1.26rem; font-weight: 700; color: var(--primary); } 
        .card-header p { font-size: 0.8rem; color: #64748b; margin-top: 3px; }

        .screen-label {
            font-size: 0.72rem; font-weight: 600; letter-spacing: 1.5px;
            text-transform: uppercase; color: #94a3b8;
            margin-bottom: 14px; text-align: center;
        }

        .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; } 

        .role-card {
            background: var(--white);
            border: 2px solid #e2e8f0; border-radius: 16px; 
            padding: 20px 10px; 
            text-align: center;
            text-decoration: none; display: block;
            transition: all 0.28s cubic-bezier(0.4,0,0.2,1);
            position: relative; overflow: hidden;
        }
        .role-card::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0; transition: opacity 0.28s;
        }
        .role-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 12px 26px rgba(47,126,161,0.2);
        }
        .role-card:hover::after { opacity: 0.06; }
        .role-card:active { transform: scale(0.97); }

        .role-icon {
            width: 44px; height: 40px; 
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px; display: flex;
            align-items: center; justify-content: center;
            font-size: 1.1rem; 
            color: white;
            margin: 0 auto 9px; 
            box-shadow: 0 5px 14px rgba(47,126,161,0.25);
            transition: transform 0.28s; position: relative; z-index: 1;
        }
        .role-card:hover .role-icon { transform: scale(1.1) rotate(-5deg); }

        .role-card h4 {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--dark); margin-bottom: 3px;
            position: relative; z-index: 1;
        }
        .role-card span { font-size: 0.72rem; color: #64748b; position: relative; z-index: 1; } 

        .role-card.full {
            grid-column: 1 / -1;
            display: flex; align-items: center;
            gap: 16px; text-align: left; padding: 12px 16px; 
        }
        .role-card.full .role-icon { margin: 0; flex-shrink: 0; }
        .role-card.full .role-text h4 { margin-bottom: 2px; }

        .to-home {
            display: block; text-align: center; margin-top: 14px; 
            color: rgba(255,255,255,0.75); font-size: 0.8rem;
            text-decoration: none; transition: color 0.2s;
        }
        .to-home:hover { color: white; }

        .register-link {
            margin-top: 16px; 
            text-align: center;
        }
        .register-link p { font-size: 0.82rem; color: #64748b; }
        .register-link a { color: var(--primary); font-weight: 600; text-decoration: none; }
        .register-link a:hover { text-decoration: underline; }

        .login-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 11px 16px;
            border-radius: 11px;
            margin-bottom: 18px;
            text-align: center;
            font-size: 0.82rem;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(239,68,68,0.08);
        }
        .login-alert i { margin-right: 5px; }

        .success-alert {
            background: #ecfdf5;
            border: 1px solid #86efac;
            color: #166534;
            padding: 11px 16px;
            border-radius: 11px;
            margin-bottom: 18px;
            text-align: center;
            font-size: 0.82rem;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(34,197,94,0.08);
        }
        .success-alert i { margin-right: 5px; }

        @media (max-width: 480px) {
            .card { padding: 28px 18px; }
            .role-grid { grid-template-columns: 1fr; }
            .role-card.full { grid-column: auto; }
        }
    </style>
</head>
<body>
<div class="login-wrapper">

    <div class="card">
        @if(session('loginError'))
            <div class="login-alert">
                <i class="fas fa-circle-exclamation"></i>
                {{ session('loginError') }}
            </div>
        @endif

        @if(session('success'))
            <div class="success-alert">
                <i class="fas fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="card-header">
            <div class="card-logo"></div>
            <h2>SiPinjam</h2>
            <p>Politeknik Negeri Batam</p>
        </div>

        <p class="screen-label">Masuk sebagai</p>

        <div class="role-grid">
            <a href="{{ route('landingpage.pilih-login.anggota') }}" class="role-card">
                <div class="role-icon"><i class="fas fa-users"></i></div>
                <h4>Anggota Ormawa</h4>
                <span>Pengajuan peminjaman</span>
            </a>

            <a href="{{ route('landingpage.pilih-login.ketua') }}" class="role-card">
                <div class="role-icon"><i class="fas fa-user-tie"></i></div>
                <h4>Ketua Ormawa</h4>
                <span>Persetujuan internal</span>
            </a>

            <a href="{{ route('landingpage.pilih-login.pic') }}" class="role-card">
                <div class="role-icon"><i class="fas fa-user-shield"></i></div>
                <h4>PIC SC</h4>
                <span>Kelola &amp; validasi</span>
            </a>

            <a href="{{ route('landingpage.pilih-login.pamdal') }}" class="role-card">
                <div class="role-icon"><i class="fas fa-id-card-clip"></i></div>
                <h4>Pamdal</h4>
                <span>Verifikasi check-in</span>
            </a>

            <a href="{{ route('landingpage.pilih-login.admin') }}" class="role-card full">
                <div class="role-icon"><i class="fas fa-screwdriver-wrench"></i></div>
                <div class="role-text">
                    <h4>Admin</h4>
                    <span>Manajemen sistem keseluruhan</span>
                </div>
            </a>
        </div>

        <div class="register-link">
            <p>Belum punya akun? <a href="{{ route('landingpage.register') }}">Daftar</a></p>
        </div>
    </div>

    <a href="{{ url('/') }}" class="to-home">
        <i class="fas fa-house"></i> Kembali ke Beranda
    </a>
</div>
</body>
</html>