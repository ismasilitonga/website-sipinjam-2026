<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Daftar Akun</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; 
        background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%); 
        min-height: 100vh; }
        .navbar { height: 70px; background: #2f7ea1; 
                  display: flex; justify-content: space-between; 
                  align-items: center; padding: 0 25px; 
                  position: fixed; top: 0; left: 0; right: 0; 
                  z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-left { display: flex; align-items: center; 
                    gap: 12px; color: white; }
        .logo { width: 70px; height: 88px; 
                background-image: url("{{ asset('images/logo.png') }}"); 
                background-size: 223%; background-position: 50% 60%; 
                background-repeat: no-repeat; display: flex; 
                align-items: center; justify-content: center; }
        .nav-left b { font-size: 1.125rem;font-weight: 600; line-height: 1.2; }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .menu { display: flex; gap: 1rem; }
        .menu a { text-decoration: none; color: white; 
                  font-weight: 600; font-size: 0.975rem; padding: 0.5rem 1rem; 
                  border-radius: 20px;transition: all 0.3s ease; line-height: 1.2; }
        .menu a:hover { background: rgba(255,255,255,0.2); 
                        transform: translateY(-2px); }
        .main-content { margin-top: 70px; padding: 2rem 25px; 
                        min-height: calc(100vh - 70px); display: flex; 
                        align-items: center; justify-content: center; }
        .register-container { max-width: 430px; width: 100%; }
        .register-box { background: white; padding: 2.5rem; 
                        border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
        .register-box h2 { margin-bottom: 1.5rem; 
                        color: #1e293b; font-size: 1.75rem; 
                        font-weight: 700; text-align: center; }
        
        /* STYLE UNTUK PESAN ERROR */
        .error-container { background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 0.875rem; border: 1px solid #fecaca; }
        .error-container ul { list-style: none; }

        .input-group { margin-bottom: 1.5rem; }
        .password-group { position: relative; }
        input, select { width: 100%; padding: 1rem 1rem 1rem 1.2rem; 
                        border: 2px solid #e5e7eb; border-radius: 12px; 
                        font-family: inherit; font-size: 1rem; 
                        transition: all 0.3s ease; background: #fafbfc; }
        input:focus, select:focus { 
                        outline: none;border-color: #2f7ea1; 
                        box-shadow: 0 0 0 4px rgba(47, 126, 161, 0.1);background: white; }
        .password-group input { padding-right: 3.5rem; }
        select { appearance: none; -webkit-appearance: none; -moz-appearance: none; 
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E"); background-position: right 0.8rem center; background-repeat: no-repeat; background-size: 18px; padding-right: 2.8rem; cursor: pointer; }
        .eye-icon { position: absolute; right: 1rem; 
                    top: 50%; transform: translateY(-50%); width: 22px; 
                    height: 22px; cursor: pointer; opacity: 0.7; 
                    transition: opacity 0.3s ease; }
        .btn { width: 100%; padding: 1.2rem; border: none; 
               border-radius: 14px; background: linear-gradient(140deg, #2f7ea1, #4a9dc3); 
               color: white; font-size: 1.1rem; font-weight: 600; 
               cursor: pointer; transition: all 0.3s ease; 
               box-shadow: 0 6px 15px rgba(47, 126, 161, 0.2); margin-top: 0.5rem; }
        .login-link { margin-top: 1.5rem; text-align: center; 
                      padding-top: 1.5rem; border-top: 1px solid #e5e7eb; }
        .login-link a { color: #2f7ea1; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="nav-left">
            <div class="logo"></div>
            <b>Student Center</b>
        </div>
        <div class="nav-right">
            <div class="menu">
                <a href="/Admin/beranda">Beranda</a>
                <a href="/Admin/tentang">Tentang</a>
                <a href="/Admin/panduan">Panduan</a>
                <a href="/Admin/daftar" style="background: rgba(255,255,255,0.2);">Daftar</a>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="register-container">
            <div class="register-box">
                <h2>DAFTAR AKUN</h2>

                @if ($errors->any())
                    <div class="error-container">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li><i class="fa-solid fa-triangle-exclamation"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form id="registerForm" method="POST" action="/Admin/daftar">
                    @csrf 
                    <div class="input-group">
                        <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
                    </div>
                    <div class="input-group">
                        <select name="peran" required>
                            <option value="">Pilih Peran</option>
                            <option value="anggota" {{ old('peran') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                            <option value="ketua" {{ old('peran') == 'ketua' ? 'selected' : '' }}>Ketua Umum</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <select name="organisasi" required>
                            <option value="">Pilih Organisasi</option>
                            <option value="dpm" {{ old('organisasi') == 'dpm' ? 'selected' : '' }}>DPM</option>
                            <option value="bem" {{ old('organisasi') == 'bem' ? 'selected' : '' }}>BEM</option>
                            <option value="hmti" {{ old('organisasi') == 'hmti' ? 'selected' : '' }}>HMTI</option>
                            <option value="hme" {{ old('organisasi') == 'hme' ? 'selected' : '' }}>HME</option>
                            <option value="hmm" {{ old('organisasi') == 'hmm' ? 'selected' : '' }}>HMM</option>
                            <option value="hmmb" {{ old('organisasi') == 'hmmb' ? 'selected' : '' }}>HMMB</option>
                            <option value="pd-elshaddai" {{ old('organisasi') == 'pd-elshaddai' ? 'selected' : '' }}>PD El-Shaddai</option>
                            <option value="immpb" {{ old('organisasi') == 'immpb' ? 'selected' : '' }}>IMMPB</option> 
                            <option value="menwa" {{ old('organisasi') == 'menwa' ? 'selected' : '' }}>MENWA</option> 
                            <option value="mapala" {{ old('organisasi') == 'mapala' ? 'selected' : '' }}>MAPALA</option> 
                            <option value="pec" {{ old('organisasi') == 'pec' ? 'selected' : '' }}>PEC</option>
                            <option value="kuas" {{ old('organisasi') == 'kuas' ? 'selected' : '' }}>KUAS</option> 
                            <option value="blug" {{ old('organisasi') == 'blug' ? 'selected' : '' }}>BLUG</option>
                            <option value="lpm-paradigma" {{ old('organisasi') == 'lpm-paradigma' ? 'selected' : '' }}>LPM Paradigma</option> 
                            <option value="energi" {{ old('organisasi') == 'energi' ? 'selected' : '' }}>ENERGI</option>
                            <option value="kop" {{ old('organisasi') == 'kop' ? 'selected' : '' }}>KOP</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" id="nim" name="nim" placeholder="NIM" value="{{ old('nim') }}" inputmode="numeric" pattern="\d{10}" title="NIM harus berupa 10 digit angka" minlength="10" maxlength="10" required>
                    </div>
                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder="Email (contoh@gmail.com)" value="{{ old('email') }}" required>
                    </div>
                    <div class="input-group password-group">
                        <input type="password" id="password" name="password" placeholder="Kata Sandi" required>
                        <img src="https://cdn-icons-png.flaticon.com/512/159/159604.png" class="eye-icon" id="eyePassword" alt="Toggle Password">
                    </div>
                    <div class="input-group password-group">
                        <input type="password" id="confirm" name="confirm_password" placeholder="Konfirmasi Kata Sandi" required>
                        <img src="https://cdn-icons-png.flaticon.com/512/159/159604.png" class="eye-icon" id="eyeConfirm" alt="Toggle Password">
                    </div>
                    <button type="submit" class="btn">Daftar Sekarang</button>
                </form>

                <div class="login-link">
                    <span>Sudah punya akun?</span>
                    <a href="/Admin/masuk">Masuk</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const eyeOpen = "https://cdn-icons-png.flaticon.com/512/565/565655.png";
            const eyeClosed = "https://cdn-icons-png.flaticon.com/512/159/159604.png";
            
            if (input.type === "password") {
                input.type = "text";
                icon.src = eyeOpen;
            } else {
                input.type = "password";
                icon.src = eyeClosed;
            }
        }

        document.getElementById('eyePassword').addEventListener('click', function() {
            togglePassword('password', 'eyePassword');
        });

        document.getElementById('eyeConfirm').addEventListener('click', function() {
            togglePassword('confirm', 'eyeConfirm');
        });
        
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const nim = document.getElementById('nim').value;
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm').value;
            
            if (nim.length !== 10) {
                alert("NIM harus tepat 10 karakter!");
                e.preventDefault();
                return;
            }
            if (password !== confirm) {
                alert("Kata sandi dan konfirmasi kata sandi tidak cocok!");
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>