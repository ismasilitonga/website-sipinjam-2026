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

            background-image: url("{{ asset('images/Me.png') }}");
            background-size: 500px;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

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
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
        }

        .logo {
    width: 50px; 
    height: 50px;
    background-image: url("{{ asset('images/logo.png') }}");
    background-size: contain; 
    background-position: center; 
    background-repeat: no-repeat;
}

        .nav-left b { font-size: 1.125rem; font-weight: 600; }

        .menu { display: flex; align-items: center; gap: 0.5rem; }
        .menu a {
            text-decoration: none; color: white;
            font-weight: 600; font-size: 0.975rem;
            padding: 0.5rem 1rem; border-radius: 20px;
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
            padding: 90px 8% 130px;
            display: flex;
            align-items: center;
            gap: 50px;
            min-height: 80vh;
        }
        .hero-content { flex: 1; }
        .hero-content h1 { font-size: 3rem; color: var(--primary); font-weight: 700; margin-bottom: 25px; }
        .hero-content p { font-size: 1.5rem; color:  #101010; margin-bottom: 35px; }

        .btn { padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: 600; transition: 0.3s; display: inline-block; }
        .btn-primary { background: var(--primary); color: var(--white); box-shadow: 0 8px 20px rgba(47, 126, 161, 0.2); }
        .btn-secondary { background: var(--white); color: var(--primary); border: 2px solid var(--primary); margin-left: 10px; }
        .btn:hover { transform: translateY(-3px); }
        .hero-image img { width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }

        .features-section {
            padding: 20px 0 120px;
            position: relative;
            overflow: hidden;
        }
        .section-title { text-align: center; margin-bottom: 60px; }
        .section-title h2 { color: var(--primary); font-size: 2.4rem; font-weight: 700; }
        .section-title p { color: #334155; }

        .features-container {
            width: 100%;
            display: flex;
            overflow: hidden;
            padding: 40px 0;
            mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
            position: relative;
        }

        .features-track {
            display: flex;
            gap: 30px;
            width: max-content;
            animation: scroll-features 18s linear infinite;
        }

        .features-track:hover { animation-play-state: paused; }

        .feature-card {
            flex: 0 0 340px;
            height: 300px;
            padding: 45px 30px;
            background: var(--white); 
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.8;
            transform: scale(0.9);
        }

        .feature-card.is-center, .feature-card:hover {
            opacity: 1;
            transform: scale(1.05) translateY(-15px);
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(115, 148, 163, 0.15);
            z-index: 10;
        }
        .feature-icon {
            width: 70px; height: 70px;
            background: var(--primary); color: #fff;
            border-radius: 18px; display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 25px;
        }

        @keyframes scroll-features {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .ormawa-slider {
            padding: 40px 0;
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
            padding: 0 50px;
            font-weight: 700;
            font-size: 2.2rem;
            color: #658bac;
            text-transform: uppercase;
            letter-spacing: 5px;
            white-space: nowrap;
        }
        @keyframes scroll-text {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

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

        @media (max-width: 768px) {
            .hero { flex-direction: column; text-align: center; padding-top: 120px; }
            .feature-card { flex: 0 0 280px; }
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
            <a href="{{ route('landingpage') }}" class="active">
                Beranda
            </a>

            <a href="{{ route('landingpage.tentang') }}">
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

        <section class="hero">
            <div class="hero-content">
                <h1>Fasilitas Ormawa dalam Genggaman.</h1>
                <p>Manajemen peminjaman ruangan dan barang Student Center Politeknik Negeri Batam.</p>
                <div>
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
    <div class="feature-icon"><i class="fas fa-location-dot"></i></div>
    <h3>Check-In dan Check-out</h3>
    <p>Lakukan check-in dan check-out langsung di lokasi untuk menghasilkan bukti persetujuan digital bagi petugas keamanan.</p>
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
</div>
        </section>

    </div> <section class="ormawa-slider">
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

        function checkCenter() {
            const centerX = window.innerWidth / 2;
            cards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const cardMid = rect.left + rect.width / 2;

                if (Math.abs(cardMid - centerX) < 180) {
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
