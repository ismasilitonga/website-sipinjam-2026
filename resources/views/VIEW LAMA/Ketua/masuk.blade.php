<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Halaman Masuk</title>
    <style>
        /* Gaya dasar persis seperti anggota */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%); min-height: 100vh; }
        .navbar { height: 70px; background: #2f7ea1; display: flex; justify-content: space-between; align-items: center; padding: 0 25px; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-left { display: flex; align-items: center; gap: 12px; color: white; }
        .logo { width: 70px; height: 88px; background-image: url("{{ asset('images/logo.png') }}"); background-size: 223%; background-position: 50% 60%; background-repeat: no-repeat; display: flex; align-items: center; justify-content: center; }
        .nav-left b { font-size: 1.125rem; font-weight: 600; line-height: 1.2; }
        
        /* Menu Navigasi untuk Ketua */
        .menu a { text-decoration: none; color: white; font-weight: 600; font-size: 0.975rem; padding: 0.5rem 1rem; border-radius: 20px; transition: all 0.3s ease; line-height: 1.2; }
        .menu a:hover { background: rgba(255,255,255,0.2); transform: translateY(-2px); }
        
        .main-content { margin-top: 70px; padding: 2rem 25px; min-height: calc(100vh - 70px); display: flex; align-items: center; justify-content: center; }
        .login-container { max-width: 430px; width: 100%; }
        .login-box { background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
        .login-box h2 { margin-bottom: 2rem; color: #1e293b; font-size: 1.75rem; font-weight: 700; text-align: center; }
        .input-group { margin-bottom: 1.5rem; }
        .password-group { position: relative; }
        input, select { width: 100%; padding: 1rem 1rem 1rem 1.2rem; border: 2px solid #e5e7eb; border-radius: 12px; font-family: inherit; font-size: 1rem; transition: all 0.3s ease; background: #fafbfc; }
        input:focus, select:focus { outline: none; border-color: #2f7ea1; box-shadow: 0 0 0 4px rgba(47, 126, 161, 0.1); background: white; }
        .eye-icon { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); width: 22px; height: 22px; cursor: pointer; opacity: 0.7; transition: opacity 0.3s ease; }
        select { appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E"); background-position: right 1rem center; background-repeat: no-repeat; background-size: 20px; padding-right: 3.5rem; cursor: pointer; }
        
        #adminWarning { background: #fff1f2; color: #be123c; padding: 14px; border-radius: 12px; border: 1px solid #fecdd3; font-size: 0.85rem; margin-top: 15px; display: none; font-weight: 500; text-align: center; box-shadow: 0 4px 12px rgba(190, 18, 60, 0.08); animation: fadeIn 0.3s ease-out; }
        #adminWarning i { margin-right: 8px; font-size: 1rem; }
        #adminWarning b { display: block; font-size: 0.9rem; margin-bottom: 2px; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

        .remember { display: flex; align-items: center; gap: 0.75rem; margin: 1.5rem 0; }
        .remember input { width: auto; transform: scale(1.2); }
        .remember label { color: #374151; font-weight: 500; cursor: pointer; font-size: 0.95rem; }
        .btn { width: 100%; padding: 1.2rem; border: none; border-radius: 14px; background: linear-gradient(140deg, #2f7ea1, #4a9dc3); color: white; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 6px 15px rgba(47, 126, 161, 0.2); margin-top: 0.5rem; text-align: center; text-decoration: none; display: block; }
        .register { margin-top: 1.5rem; text-align: center; }
        .register-text { color: #6b7280; font-size: 0.95rem; }
        .register a { color: #2f7ea1; text-decoration: none; font-weight: 600; margin-left: 0.25rem; }
        .register a:hover { text-decoration: underline; }
        
        @media (max-width: 768px) { .navbar { padding: 0 15px; } .nav-left b { font-size: 1rem; } .menu { gap: 0.5rem; } .menu a { padding: 0.4rem 0.8rem; font-size: 0.9rem; } .main-content { padding: 1rem; margin-top: 70px; } .login-box { padding: 2rem 1.5rem; } }
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
                <a href="/Ketua/beranda">Beranda</a>
                <a href="/Ketua/tentang">Tentang</a>
                <a href="/Ketua/Panduan">Panduan</a>
                <a href="/Ketua/masuk" style="background: rgba(255,255,255,0.2);">Masuk</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="login-container">
            <div class="login-box">
                <h2>MASUK</h2>
                <form action="/masuk" method="POST">
                 @csrf 
                    <div class="input-group">
                      <select name="role" required>
    <option value="">Pilih Peran</option>
    <option value="admin">Admin</option>
    <option value="anggota">Anggota</option>
    <option value="ketua">Ketua Umum</option>
    <option value="pic">PIC</option>
    <option value="pamdal">Pamdal</option>
</select>
                        <div id="adminWarning">
                            <b><i class="fas fa-lock"></i> Authorized Personnel Only</b>
                            Hanya untuk orang yang berwenang
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    
                    <div class="input-group">
                        <div class="password-group">
                            <input type="password" id="password" name="password" placeholder="Kata Sandi" required>
                            <img src="https://cdn-icons-png.flaticon.com/512/159/159604.png" class="eye-icon" id="eyeIcon" alt="Toggle Password">
                        </div>
                    </div>
                    
                    <div class="remember">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>

                    <button type="submit" class="btn">Masuk</button>
                </form>
                
                <div class="register">
                    <span class="register-text">Belum punya akun?</span>
                    <a href="/Ketua/daftar">Daftar</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.src = 'https://cdn-icons-png.flaticon.com/512/565/565655.png';
            } else {
                passwordInput.type = 'password';
                eyeIcon.src = 'https://cdn-icons-png.flaticon.com/512/159/159604.png';
            }
        }
        document.getElementById('eyeIcon').addEventListener('click', togglePassword);

        document.getElementById('roleSelect').addEventListener('change', function() {
            const warningBox = document.getElementById('adminWarning');
            warningBox.style.display = (this.value === 'admin') ? 'block' : 'none';
        });
    </script>
</body>
</html>