<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Center - Politeknik Negeri Batam</title>
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
            font-size: 14px; 

            background-image: url("{{ asset('images/Me.png') }}");
            background-size: 350px;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .navbar {
            height: 60px; 
            background: rgba(47, 126, 161, 0.9);
            backdrop-filter: blur(var(--blur-strength));
            -webkit-backdrop-filter: blur(var(--blur-strength));
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px; 
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
        }

        .logo {
            width: 55px; 
            height: 38px;
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
        }

        .nav-left b { font-size: 0.95rem; font-weight: 600; } 

        .menu { display: flex; align-items: center; gap: 0.25rem; }
        .menu a {
            text-decoration: none; color: white;
            font-weight: 600; font-size: 0.85rem; 
            padding: 0.4rem 0.85rem;
            border-radius: 20px;
            transition: 0.3s;
        }
        .menu a:hover, .menu a.active { background: rgba(255,255,255,0.2); }

        .main-content-glass {
            background: var(--glass-white);
            backdrop-filter: blur(var(--blur-strength));
            -webkit-backdrop-filter: blur(var(--blur-strength));
            width: 100%;
        }

        .hero {
            padding: 72px 7% 90px; 
            display: flex;
            align-items: center;
            gap: 40px;
            min-height: 63vh; 
        }
        .hero-content { flex: 1; }
        .hero-content h1 {
            font-size: 2.2rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 16px;
        }
        .hero-content p {
            font-size: 1.10rem; 
            color: #101010;
            margin-bottom: 24px;
        }

        .btn {
            padding: 10px 22px; 
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: 0.3s;
            display: inline-block;
        }
        .btn-primary { background: var(--primary); color: var(--white); box-shadow: 0 6px 16px rgba(47, 126, 161, 0.2); }
        .btn-secondary { background: var(--white); color: var(--primary); border: 2px solid var(--primary); }
        .hero-buttons {display: flex;flex-wrap: wrap; gap: 12px;}
        .btn:hover { transform: translateY(-2px); }

        .hero-image img { width: 100%; max-width: 380px; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); }

        .features-section {
            padding: 10px 0 80px; 
            position: relative;
            overflow: hidden;
        }
        .section-title { text-align: center; margin-bottom: 40px; } 
        .section-title h2 { color: var(--primary); font-size: 1.8rem; font-weight: 700; } 
        .section-title p { color: #334155; font-size: 0.875rem; }

        .features-container {
            width: 100%;
            display: flex;
            overflow: hidden;
            padding: 28px 0; 
            mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
            position: relative;
        }

        .features-track {
            display: flex;
            gap: 22px; 
            width: max-content;
            animation: scroll-features 18s linear infinite;
        }
        .features-track:hover { animation-play-state: paused; }

        .feature-card {
            flex: 0 0 300px;  
            height: 250px;     
            padding: 30px 22px; 
            background: var(--white);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.8;
            transform: scale(0.9);
        }
        .feature-card h3 { font-size: 0.9rem; margin-bottom: 8px; }
        .feature-card p  { font-size: 0.78rem; color: #475569; }

        .feature-card.is-center, .feature-card:hover {
            opacity: 1;
            transform: scale(1.05) translateY(-12px);
            border-color: var(--primary);
            box-shadow: 0 16px 32px rgba(115, 148, 163, 0.15);
            z-index: 10;
        }

        .feature-icon {
            width: 52px; height: 52px; 
            background: var(--primary); color: #fff;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; 
            margin: 0 auto 18px;
        }

        @keyframes scroll-features {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .ormawa-slider {
            padding: 28px 0; 
            background: rgba(255, 255, 255, 0.8);
            overflow: hidden;
        }
        .slider-track {
            display: flex;
            animation: scroll-text 40s linear infinite;
            width: max-content;
        }
        .slider-track span {
            display: inline-block;
            padding: 0 36px;
            font-weight: 700;
            font-size: 1.5rem; 
            color: #658bac;
            text-transform: uppercase;
            letter-spacing: 4px;
            white-space: nowrap;
        }
        @keyframes scroll-text {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        footer {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            color: white;
            padding: 40px 7% 16px; 
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 28px;
        }
        .footer-content h4 { font-size: 0.9rem; margin-bottom: 8px; }
        .footer-content p  { font-size: 0.8rem; }
        .footer-link {
            color: #cbd5e1; text-decoration: none;
            display: block; margin-bottom: 8px;
            transition: 0.3s; font-size: 0.8rem;
        }
        .footer-link:hover { color: white; padding-left: 8px; }
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 32px;
        }

    .hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    z-index: 1100;
}
.hamburger span {
    width: 24px;
    height: 3px;
    background: white;
    border-radius: 2px;
    transition: 0.3s;
}
.hamburger.active span:nth-child(1) { transform: translateY(8px) rotate(45deg); }
.hamburger.active span:nth-child(2) { opacity: 0; }
.hamburger.active span:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

@media (max-width: 768px) {
    .hero { 
        flex-direction: column; 
        text-align: center; 
        padding-top: 90px; 
    }
    .feature-card { flex: 0 0 220px; }

    .hero-buttons {
        flex-direction: column;
        align-items: center;
        gap: 14px;
    }
    .btn {
        width: 100%;
        max-width: 280px;
        text-align: center;
    }

    .hamburger { display: flex; }

    .menu {
        position: fixed;
        top: 60px;
        right: 0;
        flex-direction: column;
        background: rgba(47, 126, 161, 0.97);
        backdrop-filter: blur(var(--blur-strength));
        width: 200px;
        height: 0;
        overflow: hidden;
        gap: 0;
        transition: height 0.3s ease;
        border-radius: 0 0 0 12px;
        align-items: stretch;
    }
    .menu.open {
        height: auto;
        padding: 10px 0;
    }
    .menu a {
        text-align: center;
        border-radius: 0;
        padding: 0.75rem 1rem;
    }
}

    </style>
</head>
<body>

    <nav class="navbar">
    <a href="{{ route('landingpage') }}" class="nav-left">
        <div class="logo"></div>
        <b>SiPinjam</b>
    </a>

    <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>

    <div class="menu" id="navMenu">
        <a href="{{ route('landingpage') }}" class="active">Beranda</a>
        <a href="{{ route('landingpage.tentang') }}">Tentang</a>
        <a href="{{ route('landingpage.panduan') }}">Panduan</a>
        <a href="{{ route('landingpage.pilih-login') }}">Masuk</a>
    </div>
</nav>

    <div class="main-content-glass">

        <section class="hero">
            <div class="hero-content">
                <h1>Fasilitas Ormawa dalam Genggaman.</h1>
                <p>Manajemen peminjaman ruangan dan barang Student Center Politeknik Negeri Batam.</p>
                <div class="hero-buttons">
                    <a href="{{ route('landingpage.pilih-login') }}" class="btn btn-primary">Mulai Peminjaman</a>
                    <a href="{{ route('landingpage.panduan') }}" class="btn btn-secondary">Lihat Panduan</a>
                </div>
            </div>
        </section>

        <section class="features-section">
            <div class="section-title">
                <h2>Layanan</h2>
                <p>Sistem dirancang untuk mendukung produktivitas organisasi mahasiswa dengan teknologi terkini.</p>
            </div>

            <div class="features-container">
                <div class="features-track" id="featureTrack">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-door-open"></i></div>
                        <h3>Peminjaman Ruangan</h3>
                        <p>Ajukan peminjaman ruangan dengan ketersediaan real-time.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-boxes"></i></div>
                        <h3>Manajemen Inventaris</h3>
                        <p>Kelola peminjaman barang pendukung kegiatan melalui sistem pencatatan digital yang menjamin akuntabilitas aset.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-user-check"></i></div>
                        <h3>E-Approval</h3>
                        <p>Proses persetujuan peminjaman dilakukan secara digital dan mendukung budaya paperless.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                        <h3>Riwayat Transparan</h3>
                        <p>Pantau semua status pengajuan dan penggunaan ruangan secara real-time untuk ormawa.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <h3>Lapor Insiden</h3>
                        <p>Laporkan kondisi fasilitas pasca-penggunaan untuk menjaga kualitas sarana prasarana Student Center.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-location-dot"></i></div>
                        <h3>Check-In dan Check-Out</h3>
                        <p>Lakukan check-in dan check-out langsung di lokasi untuk menghasilkan bukti persetujuan digital bagi petugas keamanan.</p>
                    </div>
                    <!-- duplicate for infinite scroll -->
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-door-open"></i></div>
                        <h3>Peminjaman Ruangan</h3>
                        <p>Ajukan peminjaman ruangan dengan ketersediaan real-time.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-boxes"></i></div>
                        <h3>Manajemen Inventaris</h3>
                        <p>Kelola peminjaman barang pendukung kegiatan melalui sistem pencatatan digital yang menjamin akuntabilitas aset.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-user-check"></i></div>
                        <h3>E-Approval</h3>
                        <p>Proses persetujuan peminjaman dilakukan secara digital dan mendukung budaya paperless.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                        <h3>Riwayat Transparan</h3>
                        <p>Pantau semua status pengajuan dan penggunaan ruangan secara real-time untuk ormawa.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <h3>Lapor Insiden</h3>
                        <p>Laporkan kondisi fasilitas pasca-penggunaan untuk menjaga kualitas sarana prasarana Student Center.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-location-dot"></i></div>
                        <h3>Check-In dan Check-Out</h3>
                        <p>Lakukan check-in dan check-out langsung di lokasi untuk menghasilkan bukti persetujuan digital bagi petugas keamanan.</p>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <section class="ormawa-slider">
        <div class="slider-track">
            <span>DPM</span><span>BEM</span><span>HMTI</span><span>HME</span><span>HMM</span><span>HMMB</span><span>PEC</span><span>MAPALA</span><span>KUAS</span><span>BLUG</span>
            <span>PD El-Shaddai</span><span>MENWA</span><span>IMMPB</span><span>LPM Paradigma</span><span>ENERGI</span><span>KOP</span>
            <span>DPM</span><span>BEM</span><span>HMTI</span><span>HME</span><span>HMM</span><span>HMMB</span><span>PEC</span><span>MAPALA</span><span>KUAS</span><span>BLUG</span>
            <span>PD El-Shaddai</span><span>MENWA</span><span>IMMPB</span><span>LPM Paradigma</span><span>ENERGI</span><span>KOP</span>
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
                <a href="{{ route('landingpage.panduan') }}" class="footer-link">Panduan</a>
                <a href="{{ route('landingpage.tentang') }}" class="footer-link">Tentang</a>
                <a href="{{ route('landingpage.pilih-login') }}" class="footer-link">Masuk</a>
            </div>
            <div class="footer-box">
                <h4>Kontak</h4>
                <div class="footer-link">studentcenter@polibatam.ac.id</div>
                <div class="footer-link">+62 899 2361 7932</div>
            </div>
        </div>
        <div class="copyright">&copy; 2026 Politeknik Negeri Batam.</div>
    </footer>

    <script>
        const cards = document.querySelectorAll('.feature-card');

        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const navMenu = document.getElementById('navMenu');

        hamburgerBtn.addEventListener('click', () => {
        hamburgerBtn.classList.toggle('active');
        navMenu.classList.toggle('open');
});

        function checkCenter() {
            const centerX = window.innerWidth / 2;
            cards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const cardMid = rect.left + rect.width / 2;
                if (Math.abs(cardMid - centerX) < 140) {
                    card.classList.add('is-center');
                } else {
                    card.classList.remove('is-center');
                }
            });
            requestAnimationFrame(checkCenter);
        }
        checkCenter();
    </script>
</body>
</html>