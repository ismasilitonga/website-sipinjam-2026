<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang - Student Center Politeknik Negeri Batam</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2f7ea1;
            --secondary: #4a9dc3;
            --dark: #1e293b;
            --light: #f8fafc;
            --white: #ffffff;
            --glow: rgba(47, 126, 161, 0.6);
            --glass-white: rgba(221, 221, 221, 0.8);
            --blur-strength: 10px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
            background-image: url("{{ asset('images/Me.png') }}");
            background-size: 500px;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* --- NAVBAR --- */
        .navbar {
            height: 70px;
            background: rgba(47, 126, 161, 0.9);
            backdrop-filter: blur(var(--blur-strength));
            -webkit-backdrop-filter: blur(var(--blur-strength));
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-left {
            display: flex; align-items: center; gap: 12px;
            color: white; text-decoration: none;
        }
        .logo {
            width: 50px; height: 50px;
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
        }
        .nav-left b { font-size: 1.125rem; font-weight: 600; }
        .menu { display: flex; align-items: center; gap: 0.5rem; }
        .menu a {
            text-decoration: none; color: white; font-weight: 600;
            font-size: 0.975rem; padding: 0.5rem 1rem; border-radius: 20px; transition: 0.3s;
        }
        .menu a:hover, .menu a.active { background: rgba(255,255,255,0.2); }

        /* --- MAIN GLASS WRAPPER --- */
        .main-content-glass {
            background: var(--glass-white);
            backdrop-filter: blur(var(--blur-strength));
            -webkit-backdrop-filter: blur(var(--blur-strength));
            width: 100%;
        }

        /* --- PAGE HERO --- */
        .page-hero {
            padding: 120px 8% 80px;
            text-align: center;
            position: relative;
        }
        .page-hero .breadcrumb {
            display: flex; align-items: center; justify-content: center;
            gap: 8px; color: var(--primary); font-size: 0.9rem;
            font-weight: 500; margin-bottom: 20px;
        }
        .page-hero .breadcrumb a { color: var(--primary); text-decoration: none; opacity: 0.7; }
        .page-hero .breadcrumb a:hover { opacity: 1; }
        .page-hero h1 {
            font-size: 3rem; color: var(--primary);
            font-weight: 700; margin-bottom: 18px;
        }
        .page-hero p {
            font-size: 1.15rem; color: #334155;
            max-width: 650px; margin: 0 auto;
        }
        .hero-divider {
            width: 80px; height: 4px;
            background: var(--primary); border-radius: 2px;
            margin: 30px auto 0;
        }

        /* --- VISI MISI --- */
        .visi-misi-section {
            padding: 60px 8% 80px;
        }
        .visi-misi-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .vm-card {
            background: var(--white);
            border-radius: 24px;
            padding: 45px 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-top: 5px solid var(--primary);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .vm-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(47, 126, 161, 0.15);
        }
        .vm-icon {
            width: 60px; height: 60px;
            background: var(--primary); color: white;
            border-radius: 16px; display: flex; align-items: center;
            justify-content: center; font-size: 1.6rem; margin-bottom: 20px;
        }
        .vm-card h3 {
            font-size: 1.4rem; color: var(--primary);
            font-weight: 700; margin-bottom: 15px;
        }
        .vm-card p { color: #475569; font-size: 0.95rem; }
        .vm-card ul { list-style: none; padding: 0; }
        .vm-card ul li {
            color: #475569; font-size: 0.95rem;
            padding: 8px 0; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .vm-card ul li:last-child { border-bottom: none; }
        .vm-card ul li i { color: var(--primary); margin-top: 4px; flex-shrink: 0; }

        /* --- TIM / PENGELOLA --- */
        .team-section {
            padding: 60px 8% 80px;
        }
        .section-title { text-align: center; margin-bottom: 50px; }
        .section-title h2 { color: var(--primary); font-size: 2.2rem; font-weight: 700; }
        .section-title p { color: #334155; margin-top: 8px; }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }
        .team-card {
            background: var(--white);
            border-radius: 20px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(47, 126, 161, 0.15);
        }
        .team-avatar {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: 2rem; color: white;
            margin: 0 auto 20px;
        }
        .team-card h4 { font-size: 1.05rem; color: var(--dark); font-weight: 600; }
        .team-card .role {
            font-size: 0.85rem; color: var(--primary);
            font-weight: 500; margin-top: 5px;
            background: rgba(47, 126, 161, 0.1);
            padding: 4px 12px; border-radius: 20px;
            display: inline-block; margin-top: 8px;
        }

        /* --- STATISTIK --- */
        .stats-section {
            padding: 60px 8%;
            background: rgba(47, 126, 161, 0.08);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 25px;
        }
        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 35px 20px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-number {
            font-size: 2.8rem; font-weight: 700;
            color: var(--primary); line-height: 1;
        }
        .stat-label {
            font-size: 0.9rem; color: #64748b;
            margin-top: 8px; font-weight: 500;
        }
        .stat-icon {
            font-size: 1.8rem; color: var(--secondary);
            margin-bottom: 12px;
        }

        /* --- FOOTER --- */
        footer {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            color: white;
            padding: 60px 8% 20px;
        }
        .footer-content { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
        .footer-link { color: #cbd5e1; text-decoration: none; display: block; margin-bottom: 12px; transition: 0.3s; font-size: 0.9rem; }
        .footer-link:hover { color: white; padding-left: 10px; }
        .copyright { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.9rem; color: #94a3b8; margin-top: 50px; }

        /* --- ANIMATIONS --- */
        .fade-in {
            opacity: 0; transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        @media (max-width: 768px) {
            .visi-misi-grid { grid-template-columns: 1fr; }
            .page-hero h1 { font-size: 2rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="{{ route('landingpage') }}" class="nav-left">
            <div class="logo"></div>
            <b>SiPinjam</b>
        </a>

        <div class="menu">
            <a href="{{ route('landingpage') }}">
                Beranda
            </a>

            <a href="{{ route('landingpage.tentang') }}" class="active">
                Tentang
            </a>

            <a href="{{ route('landingpage.panduan') }}">
                Panduan
            </a>

            <a href="{{ route('landingpage.pilih-login') }}">
                Masuk
            </a>
        </div>
    </nav>


    <div class="main-content-glass">

        <!-- Page Hero -->
        <section class="page-hero">
            <div class="breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right" style="font-size:0.75rem;"></i>
                <span>Tentang</span>
            </div>
            <h1>Tentang Student Center</h1>
            <p>Mengenal lebih dekat unit layanan yang mendukung kegiatan organisasi mahasiswa Politeknik Negeri Batam.</p>
            <div class="hero-divider"></div>
        </section>

        <!-- Statistik -->
        <section class="stats-section">
            <div class="stats-grid fade-in">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number">16+</div>
                    <div class="stat-label">Organisasi Mahasiswa</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-door-open"></i></div>
                    <div class="stat-number">8</div>
                    <div class="stat-label">Ruangan Tersedia</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Inventaris Barang</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-number">200+</div>
                    <div class="stat-label">Peminjaman Diproses</div>
                </div>
            </div>
        </section>

        <!-- Visi Misi -->
        <section class="visi-misi-section">
            <div class="section-title fade-in">
                <h2>Visi & Misi</h2>
                <p>Landasan kami dalam memberikan layanan terbaik untuk ormawa.</p>
            </div>
            <div class="visi-misi-grid">
                <div class="vm-card fade-in">
                    <div class="vm-icon"><i class="fas fa-eye"></i></div>
                    <h3>Visi</h3>
                    <p>
                        Menjadi pusat layanan fasilitas mahasiswa yang modern, transparan, dan akuntabel dalam mendukung pengembangan organisasi mahasiswa Politeknik Negeri Batam yang berdaya saing tinggi.
                    </p>
                </div>
                <div class="vm-card fade-in">
                    <div class="vm-icon"><i class="fas fa-bullseye"></i></div>
                    <h3>Misi</h3>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Menyediakan sistem peminjaman ruangan dan barang yang mudah, cepat, dan transparan.</li>
                        <li><i class="fas fa-check-circle"></i> Mendorong budaya digital dan paperless dalam administrasi ormawa.</li>
                        <li><i class="fas fa-check-circle"></i> Menjaga kualitas dan akuntabilitas aset fasilitas Student Center.</li>
                        <li><i class="fas fa-check-circle"></i> Mendukung produktivitas dan kreativitas setiap organisasi mahasiswa.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Tentang Sistem -->
        <section class="team-section" style="padding-top: 0;">
            <div class="section-title fade-in">
                <h2>Tentang Sistem Ini</h2>
                <p>Dibangun untuk memudahkan pengelolaan fasilitas secara digital.</p>
            </div>
            <div class="team-grid fade-in">
                <div class="team-card">
                    <div class="team-avatar"><i class="fas fa-shield-alt"></i></div>
                    <h4>Aman & Terpercaya</h4>
                    <span class="role">Keamanan Data</span>
                    <p style="font-size:0.88rem; color:#64748b; margin-top:12px;">Setiap data peminjaman tersimpan aman dan hanya dapat diakses oleh pihak yang berwenang.</p>
                </div>
                <div class="team-card">
                    <div class="team-avatar"><i class="fas fa-bolt"></i></div>
                    <h4>Cepat & Efisien</h4>
                    <span class="role">Performa Sistem</span>
                    <p style="font-size:0.88rem; color:#64748b; margin-top:12px;">Proses pengajuan hingga persetujuan dapat diselesaikan dalam hitungan menit tanpa perlu tatap muka.</p>
                </div>
                <div class="team-card">
                    <div class="team-avatar"><i class="fas fa-mobile-alt"></i></div>
                    <h4>Akses dari Mana Saja</h4>
                    <span class="role">Responsif & Mobile</span>
                    <p style="font-size:0.88rem; color:#64748b; margin-top:12px;">Sistem dapat diakses melalui perangkat apapun, kapanpun dan dimanapun kamu berada.</p>
                </div>
                <div class="team-card">
                    <div class="team-avatar"><i class="fas fa-history"></i></div>
                    <h4>Riwayat Lengkap</h4>
                    <span class="role">Akuntabilitas</span>
                    <p style="font-size:0.88rem; color:#64748b; margin-top:12px;">Semua aktivitas peminjaman tercatat rapi sehingga memudahkan pengawasan dan evaluasi ormawa.</p>
                </div>
            </div>
        </section>

    </div>

    <!-- Ormawa Slider -->
    <section style="padding: 40px 0; background: rgba(255,255,255,0.8); overflow: hidden;">
        <div style="display:flex; animation: scroll-text 40s linear infinite; width: max-content;">
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">DPM</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">BEM</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">HMTI</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">HME</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">HMM</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">HMMB</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">PEC</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">MAPALA</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">KUAS</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">BLUG</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">DPM</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">BEM</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">HMTI</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">ENERGI</span>
            <span style="display:inline-block; padding:0 50px; font-weight:700; font-size:2.2rem; color:#658bac; text-transform:uppercase; letter-spacing:5px; white-space:nowrap;">KOP</span>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-box">
                <h4>Tentang Kami</h4>
                <p style="color: #cbd5e1;">Unit Student Center Politeknik Negeri Batam berdedikasi untuk memberikan layanan terbaik.</p>
            </div>
            <div class="footer-box">
                <h4>Tautan Cepat</h4>
                <a href="{{ url('/Admin/panduan') }}" class="footer-link">Panduan</a>
                <a href="{{ url('/Admin/tentang') }}" class="footer-link">Tentang</a>
                <a href="{{ url('/Admin/masuk') }}" class="footer-link">Masuk</a>
            </div>
            <div class="footer-box">
                <h4>Kontak</h4>
                <div class="footer-link">studentcenter@polibatam.ac.id</div>
                <div class="footer-link">+62 899 2361 7932</div>
            </div>
        </div>
        <div class="copyright">&copy; 2026 Politeknik Negeri Batam.</div>
    </footer>

    <style>
        @keyframes scroll-text {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>

    <script>
        // Scroll fade-in animation
        const fadeEls = document.querySelectorAll('.fade-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), i * 100);
                }
            });
        }, { threshold: 0.1 });
        fadeEls.forEach(el => observer.observe(el));
    </script>
</body>
</html>
