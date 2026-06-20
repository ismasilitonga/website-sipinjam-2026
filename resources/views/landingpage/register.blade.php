<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SiPinjam</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2f7ea1;
            --secondary: #4a9dc3;
            --dark: #1e293b;
            --white: #ffffff;
            --blur-strength: 14px;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Poppins',sans-serif;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background-image:url("{{ asset('images/Me.png') }}");
            background-size:500px;
            background-position:center;
            background-repeat:no-repeat;
            background-attachment:fixed;
            background-color:#dbeef7;
            padding:30px 16px;
        }

        body::before{
            content:'';
            position:fixed;
            inset:0;
            background:rgba(30,41,59,.35);
            z-index:0;
        }

        .wrapper{
            position:relative;
            z-index:1;
            width:100%;
            max-width:650px;
        }

        .card{
            background:rgba(255,255,255,.90);
            backdrop-filter:blur(var(--blur-strength));
            border-radius:28px;
            padding:40px;
            box-shadow:0 24px 60px rgba(0,0,0,.18);
        }

        .header{
            text-align:center;
            margin-bottom:30px;
        }

        .logo{
            width:64px;
            height:64px;
            margin:0 auto 12px;
            background-image:url("{{ asset('images/logo.png') }}");
            background-size:contain;
            background-repeat:no-repeat;
            background-position:center;
        }

        .header h2{
            color:var(--primary);
            font-size:1.8rem;
        }

        .header p{
            color:#64748b;
        }

        .form-group{
            margin-bottom:15px;
        }

        input,
        select{
            width:100%;
            padding:13px 16px;
            border:1px solid #d1d5db;
            border-radius:12px;
            font-size:.95rem;
            font-family:'Poppins',sans-serif;
        }

        input:focus,
        select:focus{
            outline:none;
            border-color:var(--primary);
        }

        /* === Password show/hide icon === */
        .password-wrapper{
            position:relative;
        }

        .password-wrapper input{
            padding-right:46px;
        }

        .toggle-password{
            position:absolute;
            top:50%;
            right:14px;
            transform:translateY(-50%);
            display:flex;
            align-items:center;
            cursor:pointer;
            color:#94a3b8;
            user-select:none;
        }

        .toggle-password:hover{
            color:var(--primary);
        }

        .toggle-password svg{
            width:20px;
            height:20px;
            display:block;
        }

        .btn{
            width:100%;
            border:none;
            padding:14px;
            border-radius:14px;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:white;
            font-size:1rem;
            font-weight:600;
            cursor:pointer;
            margin-top:10px;
        }

        .btn:hover{
            opacity:.95;
        }

        .footer-link{
            text-align:center;
            margin-top:20px;
            color:#64748b;
        }

        .footer-link a{
            color:var(--primary);
            text-decoration:none;
            font-weight:600;
        }

        .back-home{
            display:block;
            text-align:center;
            margin-top:18px;
            color:white;
            text-decoration:none;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <div class="card">

        <div class="header">
            <div class="logo"></div>
            <h2>Daftar Akun</h2>
            <p>SiPinjam - Politeknik Negeri Batam</p>
        </div>

    @if ($errors->any())
    <div style="
        background:#fee2e2;
        color:#991b1b;
        padding:12px;
        border-radius:10px;
        margin-bottom:15px;
        ">
        <ul style="margin:0;padding-left:18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form action="{{ route('register.store') }}" method="POST">
        @csrf

            <div class="form-group">
                <input type="text" name="nama" placeholder="Nama Lengkap" required>
            </div>

            <div class="form-group">
                <input type="text" name="nim" placeholder="NIM" required>
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <select name="role" required>
                    <option value="">Pilih Peran</option>
                    <option value="anggota">Anggota Ormawa</option>
                    <option value="ketua">Ketua Ormawa</option>
                </select>
            </div>

            <div class="form-group">
                <select name="organisasi" required>
                    <option value="">Pilih Organisasi</option>
                    @foreach($ormawas as $ormawa)
                        <option value="{{ $ormawa->singkatan }}">{{ $ormawa->nama_organisasi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Kata Sandi" required>
                    <span class="toggle-password" onclick="togglePassword('password', this)" aria-label="Tampilkan kata sandi">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Kata Sandi" required>
                    <span class="toggle-password" onclick="togglePassword('password_confirmation', this)" aria-label="Tampilkan konfirmasi kata sandi">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn">
                Daftar
            </button>

        </form>

        <div class="footer-link">
            Sudah punya akun?
            <a href="{{ route('landingpage.pilih-login') }}">
                Masuk
            </a>
        </div>

    </div>

    <a href="{{ url('/') }}" class="back-home">
        ← Kembali ke Beranda
    </a>

</div>

<script>
    const eyeIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>`;

    const eyeOffIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.62 21.62 0 0 1 5.06-6.94"></path>
            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a21.6 21.6 0 0 1-3.22 4.56"></path>
            <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        </svg>`;

    function togglePassword(fieldId, iconEl) {
        const field = document.getElementById(fieldId);
        const isHidden = field.type === 'password';
        field.type = isHidden ? 'text' : 'password';
        iconEl.innerHTML = isHidden ? eyeOffIcon : eyeIcon;
    }
</script>

</body>
</html>