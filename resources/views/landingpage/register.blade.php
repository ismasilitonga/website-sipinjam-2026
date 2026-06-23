<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SiPinjam</title>

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
            background-size:350px;
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
            max-width:450px;
        }

        .card{
            background:rgba(255,255,255,.90);
            backdrop-filter:blur(var(--blur-strength));
            border-radius:26px;
            padding:40px;
            box-shadow:0 24px 60px rgba(0,0,0,.18);
        }

        .header{
            text-align:center;
            margin-bottom:28px;
        }

        .logo{
            width:60px;
            height:45px;
            margin:0 auto 12px;
            background-image:url("{{ asset('images/logo.png') }}");
            background-size:contain;
            background-repeat:no-repeat;
            background-position:center;
        }

        .header h2{
            color:var(--primary);
            font-size:1.1rem;
        }

        .header p{
            color:#64748b;
        }

        .form-group{
            margin-bottom:13px;
        }

        input,
        select{
            width:100%;
            padding:13px 14px;
            border:1px solid #d1d5db;
            border-radius:12px;
            font-size: 0.85rem;
            font-family:'Poppins',sans-serif;
        }

        input:focus,
        select:focus{
            outline:none;
            border-color:var(--primary);
        }

        .password-wrapper{
            position:relative;
        }

        .password-wrapper input{
            padding-right:46px;
        }

        .toggle-password {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 0.95rem;
            padding: 4px;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .btn{
            width:100%;
            border:none;
            padding:14px;
            border-radius:14px;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:white;
            font-size:0.85rem;
            font-weight:600;
            cursor:pointer;
            margin-top:10px;
        }

        .btn:hover{
            opacity:.95;
        }

        .footer-link{
            text-align:center;
            margin-top:18px;
            color:#64748b;
            font-size:0.85rem;
        }

        .footer-link a{
            color:var(--primary);
            text-decoration:none;
            font-weight:480;
            font-size:0.85rem;
        }

        .back-home{
            display:block;
            text-align:center;
            margin-top:14px;
            color:white;
            text-decoration:none;
            font-size:0.85rem;
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
                    <input type="password" name="password" id="password"
                           placeholder="Kata Sandi" required>
                    <button type="button" class="toggle-password"
                            onclick="togglePassword('password', 'eye-icon-1')"
                            aria-label="Tampilkan kata sandi">
                        <i class="fas fa-eye" id="eye-icon-1"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Konfirmasi Kata Sandi" required>
                    <button type="button" class="toggle-password"
                            onclick="togglePassword('password_confirmation', 'eye-icon-2')"
                            aria-label="Tampilkan konfirmasi kata sandi">
                        <i class="fas fa-eye" id="eye-icon-2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn">
                Daftar
            </button>

        </form>

        <div class="footer-link">
            Sudah punya akun?
            <a href="{{ route('landingpage.pilih-login') }}">Masuk</a>
        </div>

    </div>

    <a href="{{ url('/') }}" class="back-home">
        ← Kembali ke Beranda
    </a>

</div>

<script>
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>