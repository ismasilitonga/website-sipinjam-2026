<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Halaman Masuk - SC-Space</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%); min-height: 100vh; }
    
        .navbar { height: 70px; background: #2f7ea1; display: flex; 
            justify-content: space-between; align-items: center; padding: 0 25px; 
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-left { display: flex; align-items: center; gap: 12px; color: white; }
        
        .brand-text {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.5px;
            position: relative;
            display: inline-block;
            background: linear-gradient(to bottom, #ffffff 40%, #ffffff 70%, #d1d1d1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            filter: brightness(1.1) contrast(1.1);
        }
        .brand-text::after {
            content: '';
            display: block;
            width: 35%;
            height: 3px;
            background: #ffce2e;
            border-radius: 10px;
            margin-top: -2px;
        }

        .logo { width: 70px; height: 88px; background-image: url("{{ asset('images/logo.png') }}"); background-size: 223%; background-position: 50% 60%; background-repeat: no-repeat; display: flex; align-items: center; justify-content: center; }
        .menu a { text-decoration: none; color: white; font-weight: 600; 
            font-size: 0.975rem; padding: 0.5rem 1rem; border-radius: 20px; transition: 0.3s; }
        .menu a.active { background: rgba(255,255,255,0.2); }

        /* CONTENT */
        .main-content { margin-top: 70px; padding: 2rem 25px; min-height: calc(100vh - 70px); 
            display: flex; align-items: center; justify-content: center; }
        .login-container { max-width: 430px; width: 100%; }
        .login-box { background: white; padding: 2.5rem; border-radius: 20px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
        .login-box h2 { margin-bottom: 2rem; color: #1e293b; 
            font-size: 1.75rem; font-weight: 700; text-align: center; }
        
        .input-group { margin-bottom: 1.5rem; position: relative; }

        input, select { 
            width: 100%; 
            padding: 1rem 1rem 1rem 1.2rem; 
            border: 2px solid #e5e7eb; 
            border-radius: 12px; 
            font-family: inherit; 
            font-size: 1rem; 
            transition: all 0.3s ease; 
            background: #fafbfc; 
        }
        input:focus, select:focus { outline: none; border-color: #2f7ea1; box-shadow: 0 0 0 4px rgba(47, 126, 161, 0.1); background: white; }
        
        .password-group { position: relative; }
        .eye-icon { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); 
            width: 22px; height: 22px; cursor: pointer; opacity: 0.7; }
        select { 
            appearance: none; 
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E"); 
            background-position: right 1rem center; 
            background-repeat: no-repeat; 
            background-size: 20px; 
            padding-right: 3.5rem; 
        }
        .remember-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
            padding-left: 2px;
        }
        .remember-section input[type="checkbox"] {
            width: auto;
            transform: scale(1.2);
            cursor: pointer;
        }
        .remember-section label {
            color: #374151;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
        }
        .btn { 
            width: 100%; 
            padding: 1.2rem; 
            border: none; 
            border-radius: 14px; 
            background: linear-gradient(140deg, #2f7ea1, #4a9dc3); 
            color: white; 
            font-size: 1.1rem; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.3s ease; 
            box-shadow: 0 6px 15px rgba(47, 126, 161, 0.2); 
            margin-top: 0.5rem;
        }

        .register { margin-top: 1.5rem; text-align: center; }
        .register-text { color: #6b7280; font-size: 0.95rem; }
        .register a { color: #2f7ea1; text-decoration: none; font-weight: 600; margin-left: 0.25rem; }

        #adminWarning { 
            background: #fff1f2; color: #be123c; padding: 14px; border-radius: 12px; border: 1px solid #fecdd3; 
            font-size: 0.85rem; margin-top: 15px; display: none; text-align: center; font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="nav-left">
            <div class="logo"></div>
            <span class="brand-text">SC-Space</span>
        </div>
        <div class="nav-right">
            <div class="menu">
                <a href="/Anggota/beranda">Beranda</a>
                <a href="/Anggota/tentang">Tentang</a>
                <a href="/Anggota/panduan">Panduan</a>
                <a href="/Anggota/masuk" class="active">Masuk</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="login-container">
            <div class="login-box">
                <h2>MASUK</h2>

                <form method="POST" action="/Anggota/masuk">
                    @csrf 
                    <div class="input-group">
                        <select name="role" id="roleSelect" required>
                            <option value="">Pilih Peran</option>
                            <option value="admin">Admin</option>
                            <option value="pic">PIC</option>
                            <option value="anggota">Anggota</option>
                            <option value="ketua_umum">Ketua Umum</option>
                            <option value="pamdal">Pamdal</option>
                        </select>
                        <div id="adminWarning">
                            <b>Authorized Personnel Only</b><br>Hanya untuk orang yang berwenang
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    
                    <div class="input-group password-group">
                        <input type="password" id="password" name="password" placeholder="Kata Sandi" required>
                        <img src="https://cdn-icons-png.flaticon.com/512/159/159604.png" 
                             class="eye-icon" id="eyeIcon" alt="Toggle Password" onclick="togglePassword()">
                    </div>
                    
                    <div class="remember-section">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>

                    <button type="submit" class="btn">Masuk</button>
                </form>
                
                <div class="register">
                    <span class="register-text">Belum punya akun?</span>
                    <a href="/Anggota/daftar">Daftar</a>
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
        document.getElementById('roleSelect').addEventListener('change', function() {
            const warningBox = document.getElementById('adminWarning');
            warningBox.style.display = (this.value === 'admin') ? 'block' : 'none';
        });
    </script>
</body>
</html>