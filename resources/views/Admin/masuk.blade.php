<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Center Politeknik Negeri Batam</title>
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
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background-image: url("{{ asset('images/Me.png') }}");
            background-size: 500px; background-position: center;
            background-attachment: fixed; background-repeat: no-repeat;
            background-color: #dbeef7; padding: 30px 16px;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: rgba(30, 41, 59, 0.35); z-index: 0;
        }
        .login-wrapper {
            position: relative; z-index: 1;
            width: 100%; max-width: 460px;
            animation: fadeUp 0.4s cubic-bezier(0.4,0,0.2,1);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(var(--blur-strength));
            -webkit-backdrop-filter: blur(var(--blur-strength));
            border-radius: 28px; padding: 46px 44px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.18);
            border: 1px solid rgba(255,255,255,0.6);
        }

        .card-header { text-align: center; margin-bottom: 32px; }
        .card-logo {
            width: 56px; height: 56px;
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: 100%; background-position: 50% 60%;
            background-repeat: no-repeat; margin: 0 auto 14px;
        }
        .role-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(47,126,161,0.1); color: var(--primary);
            border-radius: 30px; padding: 7px 18px;
            font-size: 0.88rem; font-weight: 600; margin-bottom: 10px;
        }
        .card-header h2 {
            font-size: 1.5rem; font-weight: 700;
            color: var(--dark); margin-bottom: 6px;
        }
        .card-header p { font-size: 0.85rem; color: #64748b; }

        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; font-size: 0.85rem; font-weight: 600;
            color: #334155; margin-bottom: 8px;
        }
        .form-group label span { color: #ef4444; }
        .input-wrap { position: relative; }
        .input-wrap .prefix-icon {
            position: absolute; left: 15px; top: 50%;
            transform: translateY(-50%); color: #94a3b8;
            font-size: 0.95rem; pointer-events: none; transition: color 0.2s;
        }
        .input-wrap input {
            width: 100%; padding: 13px 16px 13px 44px;
            border: 2px solid #e2e8f0; border-radius: 12px;
            font-family: 'Poppins', sans-serif; font-size: 0.93rem;
            color: var(--dark); background: white; outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .input-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(47,126,161,0.1);
        }
        .input-wrap:focus-within .prefix-icon { color: var(--primary); }
        .toggle-pass {
            position: absolute; right: 13px; top: 50%;
            transform: translateY(-50%); cursor: pointer;
            color: #94a3b8; font-size: 0.95rem;
            background: none; border: none; padding: 4px;
            transition: color 0.2s;
        }
        .toggle-pass:hover { color: var(--primary); }

        .form-row {
            display: flex; justify-content: space-between;
            align-items: center; margin: -4px 0 24px;
        }
        .remember-wrap { display: flex; align-items: center; gap: 8px; }
        .remember-wrap input[type="checkbox"] { accent-color: var(--primary); width: 15px; height: 15px; }
        .remember-wrap label { font-size: 0.83rem; color: #64748b; cursor: pointer; }
        .forgot-link { font-size: 0.83rem; color: var(--primary); text-decoration: none; font-weight: 500; }
        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; border: none; border-radius: 12px;
            font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 8px 24px rgba(47,126,161,0.35);
            display: flex; align-items: center; justify-content: center; gap: 9px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(47,126,161,0.4); }
        .btn-login:active { transform: scale(0.98); }

        /* Divider */
        .back-link-wrap {
            text-align: center; margin-top: 22px;
        }
        .back-link {
            display: inline-flex; align-items: center; gap: 7px;
            color: #64748b; font-size: 0.85rem; font-weight: 500;
            text-decoration: none; transition: color 0.2s;
        }
        .back-link:hover { color: var(--primary); }

        .to-home {
            display: block; text-align: center; margin-top: 16px;
            color: rgba(255,255,255,0.75); font-size: 0.85rem;
            text-decoration: none; transition: color 0.2s;
        }
        .to-home:hover { color: white; }

        @media (max-width: 480px) {
            .card { padding: 36px 22px; }
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="card">
        <div class="card-header">
            <div class="card-logo"></div>
            <div class="role-badge">
                <i class="fas fa-screwdriver-wrench"></i> Admin
            </div>
            <h2>Selamat Datang</h2>
            <p>Portal manajemen sistem dan konfigurasi Student Center.</p>
        </div>

        @if ($errors->any())
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 16px; margin-bottom:20px; color:#dc2626; font-size:0.85rem;">
                <i class="fas fa-circle-exclamation" style="margin-right:6px;"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('authenticate', 'admin') }}">
            @csrf

            <div class="form-group">
                <label>Email <span>*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-user prefix-icon"></i>
                    <input type="text" name="identifier"
                           placeholder="Masukkan username admin"
                           value="{{ old('identifier') }}"
                           required autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label>Kata Sandi <span>*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-lock prefix-icon"></i>
                    <input type="password" name="password" id="pass-input"
                           placeholder="Masukkan kata sandi"
                           required autocomplete="current-password">
                    <button type="button" class="toggle-pass" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eye-icon"></i>
                    </button>
                </div>
            </div>

            <div class="form-row">
                <div class="remember-wrap">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>
                <a href="#" class="forgot-link">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-right-to-bracket"></i> Masuk
            </button>
        </form>

        <div class="back-link-wrap">
            <a href="{{ route('landingpage.pilih-login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Ganti peran
            </a>
        </div>
    </div>

    <a href="{{ url('/') }}" class="to-home">
        <i class="fas fa-house"></i> Kembali ke Beranda
    </a>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('pass-input');
        const icon  = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</body>
</html>
