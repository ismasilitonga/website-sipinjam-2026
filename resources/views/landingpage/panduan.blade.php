<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan - Student Center Politeknik Negeri Batam</title>
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

        .main-content-glass {
            background: var(--glass-white);
            backdrop-filter: blur(var(--blur-strength));
            -webkit-backdrop-filter: blur(var(--blur-strength));
            width: 100%;
        }

        .page-hero {
            padding: 120px 8% 80px;
            text-align: center;
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
        .tab-section {
            padding: 50px 8% 30px;
        }
        .tab-nav {
            display: flex; gap: 12px; flex-wrap: wrap;
            justify-content: center; margin-bottom: 50px;
        }
        .tab-btn {
            padding: 10px 22px; border-radius: 25px;
            border: 2px solid var(--primary);
            background: transparent; color: var(--primary);
            font-family: 'Poppins', sans-serif;
            font-weight: 600; font-size: 0.9rem;
            cursor: pointer; transition: 0.3s;
        }
        .tab-btn:hover, .tab-btn.active {
            background: var(--primary); color: white;
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .guide-section { padding: 0 0 80px; }

        .guide-header {
            display: flex; align-items: center; gap: 15px;
            margin-bottom: 35px;
        }
        .guide-header-icon {
            width: 55px; height: 55px;
            background: var(--primary); color: white;
            border-radius: 16px; display: flex; align-items: center;
            justify-content: center; font-size: 1.4rem; flex-shrink: 0;
        }
        .guide-header h2 { font-size: 1.7rem; color: var(--primary); font-weight: 700; }
        .guide-header p { color: #475569; font-size: 0.95rem; margin-top: 3px; }

        .steps-container { display: flex; flex-direction: column; gap: 20px; }

        .step-card {
            background: var(--white);
            border-radius: 20px;
            padding: 30px 35px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
            display: flex;
            align-items: flex-start;
            gap: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid transparent;
        }
        .step-card:hover {
            transform: translateX(8px);
            box-shadow: 0 15px 35px rgba(47, 126, 161, 0.12);
            border-left-color: var(--primary);
        }
        .step-number {
            width: 50px; height: 50px;
            background: var(--primary); color: white;
            border-radius: 14px; display: flex; align-items: center;
            justify-content: center; font-size: 1.3rem; font-weight: 700;
            flex-shrink: 0;
        }
        .step-body h4 { font-size: 1.05rem; font-weight: 600; color: var(--dark); margin-bottom: 6px; }
        .step-body p { color: #475569; font-size: 0.92rem; }
        .step-body .tip {
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 10px; padding: 6px 12px; border-radius: 8px;
            background: rgba(47, 126, 161, 0.08); color: var(--primary);
            font-size: 0.85rem; font-weight: 500;
        }

        .faq-section { padding: 60px 8% 80px; }
        .faq-list { display: flex; flex-direction: column; gap: 15px; max-width: 800px; margin: 0 auto; }
        .faq-item {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        }
        .faq-question {
            padding: 20px 25px;
            display: flex; justify-content: space-between; align-items: center;
            cursor: pointer; transition: background 0.3s;
            font-weight: 600; color: var(--dark);
        }
        .faq-question:hover { background: rgba(47, 126, 161, 0.05); }
        .faq-question i { color: var(--primary); transition: transform 0.3s; flex-shrink: 0; margin-left: 15px; }
        .faq-item.open .faq-question i { transform: rotate(180deg); }
        .faq-answer {
            max-height: 0; overflow: hidden;
            transition: max-height 0.4s ease, padding 0.3s;
            padding: 0 25px;
            color: #475569; font-size: 0.93rem;
        }
        .faq-item.open .faq-answer { max-height: 200px; padding: 0 25px 20px; }

        .cta-section {
            padding: 60px 8%;
        }
        .cta-banner {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 28px;
            padding: 60px 50px;
            text-align: center;
            color: white;
            box-shadow: 0 20px 50px rgba(47, 126, 161, 0.35);
        }
        .cta-banner h2 { font-size: 2rem; font-weight: 700; margin-bottom: 15px; }
        .cta-banner p { font-size: 1.05rem; opacity: 0.9; margin-bottom: 30px; }
        .btn {
            padding: 14px 28px; border-radius: 12px;
            text-decoration: none; font-weight: 600; transition: 0.3s;
            display: inline-block;
        }
        .btn-white {
            background: white; color: var(--primary);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .btn-white:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }

        footer {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            color: white; padding: 60px 8% 20px;
        }
        .footer-content { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
        .footer-link { color: #cbd5e1; text-decoration: none; display: block; margin-bottom: 12px; transition: 0.3s; font-size: 0.9rem; }
        .footer-link:hover { color: white; padding-left: 10px; }
        .copyright { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.9rem; color: #94a3b8; margin-top: 50px; }

        .fade-in { opacity: 0; transform: translateY(25px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        @media (max-width: 768px) {
            .page-hero h1 { font-size: 2rem; }
            .step-card { flex-direction: column; gap: 15px; }
            .cta-banner { padding: 40px 25px; }
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

            <a href="{{ route('landingpage.tentang') }}">
                Tentang
            </a>

            <a href="{{ route('landingpage.panduan') }}" class="active">
                Panduan
            </a>

            <a href="{{ route('landingpage.pilih-login') }}">
                Masuk
            </a>
        </div>
    </nav>


    <div class="main-content-glass">

        <section class="page-hero">
            <div class="breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right" style="font-size:0.75rem;"></i>
                <span>Panduan</span>
            </div>
            <h1>Panduan Penggunaan</h1>
            <p>Ikuti langkah-langkah berikut untuk menggunakan sistem peminjaman fasilitas Student Center dengan mudah.</p>
            <div class="hero-divider"></div>
        </section>

        <section class="tab-section">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab('ruangan')">
                    <i class="fas fa-door-open"></i> Peminjaman Ruangan
                </button>
                <button class="tab-btn" onclick="switchTab('barang')">
                    <i class="fas fa-boxes"></i> Peminjaman Barang
                </button>
                <button class="tab-btn" onclick="switchTab('checkin')">
                    <i class="fas fa-location-dot"></i> Check-In & Check-Out
                </button>
                <button class="tab-btn" onclick="switchTab('insiden')">
                    <i class="fas fa-exclamation-triangle"></i> Lapor Insiden
                </button>
            </div>

            <div class="tab-content active" id="tab-ruangan">
                <div class="guide-section">
                    <div class="guide-header fade-in">
                        <div class="guide-header-icon"><i class="fas fa-door-open"></i></div>
                        <div>
                            <h2>Peminjaman Ruangan</h2>
                            <p>Ajukan peminjaman ruangan Student Center secara online tanpa perlu antri.</p>
                        </div>
                    </div>
                    <div class="steps-container">
                        <div class="step-card fade-in">
                            <div class="step-number">1</div>
                            <div class="step-body">
                                <h4>Login ke Akun Ormawa</h4>
                                <p>Masuk menggunakan akun yang telah didaftarkan oleh ketua atau pengurus ormawa kamu. Setiap ormawa memiliki satu akun yang dikelola bersama.</p>
                                <span class="tip"><i class="fas fa-info-circle"></i> Belum punya akun? Hubungi pengelola Student Center.</span>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">2</div>
                            <div class="step-body">
                                <h4>Pilih Menu Peminjaman Ruangan</h4>
                                <p>Setelah login, buka menu <strong>"Peminjaman Ruangan"</strong> di dashboard utama. Kamu akan melihat daftar ruangan yang tersedia beserta kapasitas dan fasilitasnya.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">3</div>
                            <div class="step-body">
                                <h4>Cek Ketersediaan & Pilih Ruangan</h4>
                                <p>Periksa jadwal ketersediaan ruangan yang kamu inginkan. Pilih ruangan yang sesuai dengan kebutuhan kegiatan ormawa.</p>
                                <span class="tip"><i class="fas fa-calendar"></i> Pastikan tanggal dan jam tidak bertabrakan dengan peminjaman lain.</span>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">4</div>
                            <div class="step-body">
                                <h4>Isi Formulir Pengajuan</h4>
                                <p>Lengkapi formulir peminjaman: nama kegiatan, tanggal, jam mulai dan selesai, jumlah peserta, dan keperluan lainnya. Pastikan semua data terisi dengan benar.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">5</div>
                            <div class="step-body">
                                <h4>Kirim & Tunggu Persetujuan</h4>
                                <p>Setelah mengisi formulir, klik <strong>"Ajukan"</strong> dan tunggu notifikasi persetujuan dari Admin Student Center. Proses biasanya selesai dalam 1×24 jam.</p>
                                <span class="tip"><i class="fas fa-bell"></i> Pantau status di halaman "Riwayat Peminjaman".</span>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">6</div>
                            <div class="step-body">
                                <h4>Gunakan Ruangan Sesuai Jadwal</h4>
                                <p>Setelah disetujui, datang ke ruangan sesuai jadwal yang telah diajukan. Jangan lupa lakukan check-in saat tiba dan check-out saat selesai.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tab-barang">
                <div class="guide-section">
                    <div class="guide-header fade-in">
                        <div class="guide-header-icon"><i class="fas fa-boxes"></i></div>
                        <div>
                            <h2>Peminjaman Barang</h2>
                            <p>Pinjam peralatan dan inventaris Student Center untuk mendukung kegiatan ormawa.</p>
                        </div>
                    </div>
                    <div class="steps-container">
                        <div class="step-card fade-in">
                            <div class="step-number">1</div>
                            <div class="step-body">
                                <h4>Login & Buka Menu Peminjaman Barang</h4>
                                <p>Login ke akun ormawa, lalu pilih menu <strong>"Peminjaman Barang"</strong> di dashboard untuk melihat daftar inventaris yang tersedia.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">2</div>
                            <div class="step-body">
                                <h4>Cari & Pilih Barang</h4>
                                <p>Telusuri katalog barang yang tersedia. Klik barang yang ingin dipinjam untuk melihat detail, kondisi, dan ketersediaannya.</p>
                                <span class="tip"><i class="fas fa-search"></i> Gunakan fitur pencarian untuk menemukan barang lebih cepat.</span>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">3</div>
                            <div class="step-body">
                                <h4>Tentukan Jumlah & Jadwal Pinjam</h4>
                                <p>Masukkan jumlah barang yang dibutuhkan dan pilih tanggal peminjaman serta pengembalian. Sistem akan otomatis memeriksa stok yang tersedia.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">4</div>
                            <div class="step-body">
                                <h4>Isi Tujuan Penggunaan</h4>
                                <p>Jelaskan keperluan penggunaan barang tersebut untuk keperluan kegiatan apa. Informasi ini diperlukan untuk proses persetujuan admin.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">5</div>
                            <div class="step-body">
                                <h4>Tunggu Persetujuan & Ambil Barang</h4>
                                <p>Setelah pengajuan disetujui, ambil barang di Student Center dengan menunjukkan bukti persetujuan digital kepada petugas.</p>
                                <span class="tip"><i class="fas fa-check-circle"></i> Periksa kondisi barang saat pengambilan dan kembalikan tepat waktu.</span>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">6</div>
                            <div class="step-body">
                                <h4>Kembalikan Barang & Konfirmasi</h4>
                                <p>Kembalikan barang sesuai tanggal yang disepakati dalam kondisi baik. Petugas akan mengkonfirmasi pengembalian melalui sistem.</p>
                                <span class="tip"><i class="fas fa-exclamation-triangle"></i> Kerusakan atau kehilangan barang wajib dilaporkan segera.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tab-checkin">
                <div class="guide-section">
                    <div class="guide-header fade-in">
                        <div class="guide-header-icon"><i class="fas fa-location-dot"></i></div>
                        <div>
                            <h2>Check-In & Check-Out</h2>
                            <p>Proses kehadiran digital sebagai bukti penggunaan ruangan yang sah.</p>
                        </div>
                    </div>
                    <div class="steps-container">
                        <div class="step-card fade-in">
                            <div class="step-number">1</div>
                            <div class="step-body">
                                <h4>Buka Aplikasi & Login</h4>
                                <p>Pastikan kamu sudah login ke akun ormawa di perangkat yang akan digunakan untuk check-in. Akses halaman peminjaman yang telah disetujui.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">2</div>
                            <div class="step-body">
                                <h4>Pilih Peminjaman Aktif</h4>
                                <p>Di halaman <strong>"Riwayat Peminjaman"</strong>, pilih peminjaman yang berstatus <strong>"Disetujui"</strong> yang akan kamu gunakan hari ini.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">3</div>
                            <div class="step-body">
                                <h4>Klik Tombol Check-In</h4>
                                <p>Saat tiba di lokasi, klik tombol <strong>"Check-In"</strong>. Sistem akan mencatat waktu kedatangan kamu dan menghasilkan bukti kehadiran digital.</p>
                                <span class="tip"><i class="fas fa-map-marker-alt"></i> Check-in hanya bisa dilakukan dalam radius lokasi Student Center.</span>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">4</div>
                            <div class="step-body">
                                <h4>Tunjukkan Bukti ke Petugas Keamanan</h4>
                                <p>Setelah check-in berhasil, layar akan menampilkan bukti persetujuan digital. Tunjukkan bukti ini kepada petugas keamanan jika diminta.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">5</div>
                            <div class="step-body">
                                <h4>Check-Out Setelah Selesai</h4>
                                <p>Saat kegiatan selesai, klik tombol <strong>"Check-Out"</strong> untuk mengakhiri sesi penggunaan. Jangan lupa bersihkan ruangan sebelum meninggalkan lokasi.</p>
                                <span class="tip"><i class="fas fa-clock"></i> Check-out tepat waktu membantu ormawa lain yang membutuhkan ruangan.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tab-insiden">
                <div class="guide-section">
                    <div class="guide-header fade-in">
                        <div class="guide-header-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div>
                            <h2>Laporan Insiden</h2>
                            <p>Laporkan kondisi fasilitas yang bermasalah untuk menjaga kualitas Student Center.</p>
                        </div>
                    </div>
                    <div class="steps-container">
                        <div class="step-card fade-in">
                            <div class="step-number">1</div>
                            <div class="step-body">
                                <h4>Temukan Masalah atau Kerusakan</h4>
                                <p>Jika menemukan kerusakan fasilitas, kebersihan yang buruk, atau kondisi tidak normal pada ruangan atau barang setelah digunakan, segera catat dan dokumentasikan.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">2</div>
                            <div class="step-body">
                                <h4>Buka Menu Lapor Insiden</h4>
                                <p>Login ke akun ormawa, lalu pilih menu <strong>"Lapor Insiden"</strong> atau akses langsung dari halaman riwayat peminjaman terkait.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">3</div>
                            <div class="step-body">
                                <h4>Isi Detail Laporan</h4>
                                <p>Pilih ruangan atau barang yang bermasalah, pilih kategori insiden (kerusakan, kehilangan, kebersihan, dll), dan deskripsikan kondisi secara jelas dan lengkap.</p>
                                <span class="tip"><i class="fas fa-camera"></i> Unggah foto sebagai bukti pendukung laporan jika tersedia.</span>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">4</div>
                            <div class="step-body">
                                <h4>Kirim Laporan</h4>
                                <p>Setelah semua terisi, klik <strong>"Kirim Laporan"</strong>. Laporan akan diteruskan ke pengelola Student Center untuk segera ditindaklanjuti.</p>
                            </div>
                        </div>
                        <div class="step-card fade-in">
                            <div class="step-number">5</div>
                            <div class="step-body">
                                <h4>Pantau Tindak Lanjut</h4>
                                <p>Kamu dapat memantau status penanganan laporan di menu <strong>"Riwayat Insiden"</strong>. Admin akan memberikan update mengenai penanganan yang dilakukan.</p>
                                <span class="tip"><i class="fas fa-heart"></i> Melaporkan insiden adalah bentuk kepedulian terhadap fasilitas bersama.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section">
            <div class="section-title fade-in" style="text-align:center; margin-bottom:40px;">
                <h2 style="color: var(--primary); font-size: 2.2rem; font-weight: 700;">Pertanyaan Umum</h2>
                <p style="color: #334155; margin-top:8px;">Jawaban atas pertanyaan yang sering ditanyakan.</p>
            </div>
            <div class="faq-list fade-in">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Siapa saja yang bisa menggunakan sistem ini?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Sistem ini dapat digunakan oleh seluruh organisasi mahasiswa (ormawa) yang terdaftar di Politeknik Negeri Batam dan memiliki akun aktif di Student Center.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Berapa lama proses persetujuan peminjaman?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Proses persetujuan peminjaman ruangan maupun barang biasanya memakan waktu maksimal 1×24 jam pada hari kerja. Pastikan pengajuan dilakukan jauh-jauh hari sebelum kegiatan berlangsung.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Apa yang terjadi jika peminjaman saya ditolak?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Jika pengajuan ditolak, kamu akan menerima notifikasi beserta alasan penolakan. Kamu dapat mengajukan kembali dengan memperbaiki data sesuai catatan dari pic.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Bisakah saya membatalkan peminjaman?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Ya, kamu dapat membatalkan peminjaman selama statusnya masih menunggu persetujuan. Untuk peminjaman yang sudah disetujui, hubungi pengelola Student Center secara langsung.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Apakah ada denda jika terlambat mengembalikan barang?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Keterlambatan pengembalian barang akan dicatat dalam riwayat ormawa kamu dan dapat mempengaruhi prioritas pengajuan peminjaman berikutnya. Selalu kembalikan barang tepat waktu.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Bagaimana cara mendapatkan akun untuk ormawa saya?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Hubungi pengelola Student Center secara langsung di kantor Student Center atau melalui email/WhatsApp yang tersedia. Setiap ormawa terdaftar akan diberikan satu akun resmi.
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <div class="cta-banner fade-in">
                <h2>Siap untuk Memulai?</h2>
                <p>Daftarkan ormawa kamu dan nikmati kemudahan manajemen fasilitas Student Center secara digital.</p>
            <a href="{{ route('landingpage.pilih-login') }}" class="btn btn-white">
                Masuk Sekarang &nbsp;<i class="fas fa-arrow-right"></i></a>            </div>
        </section>

    </div>

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

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');
            event.target.closest('.tab-btn').classList.add('active');

            document.querySelectorAll('#tab-' + tab + ' .fade-in').forEach(el => {
                el.classList.remove('visible');
                setTimeout(() => el.classList.add('visible'), 50);
            });
        }

        function toggleFaq(el) {
            const item = el.parentElement;
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        }

        const fadeEls = document.querySelectorAll('.fade-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), i * 80);
                }
            });
        }, { threshold: 0.1 });
        fadeEls.forEach(el => observer.observe(el));

        document.querySelectorAll('#tab-ruangan .fade-in').forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), 200 + i * 80);
        });
    </script>
</body>
</html>
