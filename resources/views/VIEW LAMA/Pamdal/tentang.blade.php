<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:      #2c7da0;
            --primary-dark: #1a5f80;
            --primary-light:#e8f4fa;
            --bg-outer:     #f0f6fb;
            --bg-inner:     #dce9f3;
            --card-bg:      #ffffff;
            --text-dark:    #1e2d3d;
            --text-muted:   #4a6278;
            --white:        #ffffff;
            --border:       #c4d6e3;
            --shadow-sm:    0 2px 8px rgba(44,125,160,.12);
            --shadow-md:    0 8px 24px rgba(44,125,160,.18);
            --shadow-lg:    0 16px 32px rgba(44,125,160,.22);
            --radius-sm:    10px;
            --radius-lg:    20px;
            --radius-xl:    28px;
            --transition:   0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--bg-outer) 0%, #e1eff8 100%);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        .navbar {
            height: 70px;
            background: #2f7ea1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(47,126,161,0.3);
        }

        .nav-left { display: flex; align-items: center; gap: 12px; color: white; }
        .logo { width: 70px; height: 88px; background-image: url("{{ asset('images/logo.png') }}"); background-size: 223%; background-position: 50% 60%; background-repeat: no-repeat; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-brand { font-weight: 700; font-size: 1.125rem; letter-spacing: 0.5px; }

        .menu { display: flex; gap: 0.75rem; }
        .menu a { color: rgba(255,255,255,0.95); text-decoration: none; font-weight: 700; font-size: 0.975rem; padding: 0.4rem 0.9rem; border-radius: 20px; transition: all var(--transition); }
        .menu a:hover, .menu a.active { color: white; background: rgba(255,255,255,0.3); }

        .wrapper { padding: 85px 25px 25px; min-height: calc(100vh - 70px); }
        .inner-wrapper { background: rgba(255,255,255,0.97); backdrop-filter: blur(15px); padding: 35px 30px; border-radius: var(--radius-xl); max-width: 950px; width: 100%; margin: 0 auto; box-shadow: var(--shadow-md); border: 1px solid rgba(196,214,227,0.4); }
        .card { background: var(--white); border-radius: var(--radius-lg); padding: 35px 30px; text-align: center; box-shadow: var(--shadow-sm); border: 1px solid rgba(196,214,227,0.3); }
        .card-title { font-size: 1.9rem; font-weight: 800; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.3; margin-bottom: 30px; letter-spacing: -0.01em; }

        .accordion { text-align: left; display: flex; flex-direction: column; gap: 12px; }
        .accordion-item { background: var(--white); border-radius: var(--radius-sm); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border); transition: all var(--transition); }
        .accordion-item:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .accordion-header { background: var(--white); padding: 16px 22px; font-weight: 700; font-size: 0.98rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border: none; border-bottom: 1px solid var(--border); transition: all var(--transition); user-select: none; width: 100%; font-family: inherit; }
        .accordion-header:hover { background: var(--primary-light); }
        .accordion-icon { width: 20px; height: 20px; color: var(--primary); transition: all var(--transition); flex-shrink: 0; }
        .accordion-item.active .accordion-icon { transform: rotate(180deg); }
        .accordion-body { max-height: 0; overflow: hidden; transition: max-height 0.4s var(--transition); padding: 0 1px; }
        .accordion-item.active .accordion-body { max-height: 450px; }
        .accordion-box { padding: 15px 22px; border-left: 1px solid var(--border); border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); font-size: 0.9rem; background: var(--white); color: var(--text-muted); transition: all var(--transition); }
        .accordion-box:hover { background: var(--primary-light); color: var(--text-dark); }
        .accordion-box strong { color: var(--primary); font-weight: 700; display: block; margin-bottom: 4px; font-size: 0.95rem; }

        @media (max-width: 768px) { .navbar { padding: 0 20px; } .wrapper { padding: 85px 15px 20px; } .inner-wrapper { padding: 25px 20px; margin: 0 5px; } .card { padding: 25px 20px; } .card-title { font-size: 1.6rem; margin-bottom: 25px; } .accordion-header { padding: 14px 18px; } .accordion-box { padding: 12px 18px; font-size: 0.88rem; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <div class="logo"></div>
            <span class="nav-brand">Student Center</span>
        </div>
        <div class="nav-right">
            <div class="menu">
                <a href="/Pamdal/beranda">Beranda</a>
                <a href="/Pamdal/tentang" class="active">Tentang</a>    
                <a href="/Pamdal/panduan">Panduan</a>
                <a href="/Pamdal/masuk">Masuk</a>
            </div>
        </div>
    </nav>
    <main class="wrapper">
        <div class="inner-wrapper">
            <article class="card">
                <h1 class="card-title">
                    Solusi Digital Memudahkan<br>Peminjaman Ruangan & Barang ORMAWA
                </h1>
<section class="accordion">
               <div class="accordion-item">
               <button class="accordion-header" aria-expanded="false">
                  Tujuan Sistem
               <svg class="accordion-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M7 10l5 5 5-5z"/>
            </svg>
        </button>
        <div class="accordion-body" role="region">
            <div class="accordion-box">Mempermudah proses peminjaman ruangan dan barang ORMAWA</div>
            <div class="accordion-box">Menyatukan semua sistem peminjaman dalam satu platform</div>
        </div>
    </div>

    <div class="accordion-item">
        <button class="accordion-header" aria-expanded="false">
            Fitur Utama
            <svg class="accordion-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M7 10l5 5 5-5z"/>
            </svg>
        </button>
        <div class="accordion-body" role="region">
            <div class="accordion-box"><strong>Live Monitoring Ruangan:</strong> Menampilkan kartu status dinamis yang memberitahu seluruh pengguna mengenai ormawa mana yang sedang menggunakan ruangan tertentu saat itu juga</div>
            <div class="accordion-box"><strong>Check-in & Check-out:</strong> Fitur konfirmasi kehadiran di lokasi yang menghasilkan bukti digital untuk verifikasi pengambilan kunci oleh petugas keamanan (Pamdal).</div>
            <div class="accordion-box"><strong>Handover:</strong> Fitur serah terima barang dengan unggah foto digital untuk memastikan pertanggungjawaban yang jelas atas kondisi barang</div>
            <div class="accordion-box"><strong>Pelaporan Insiden:</strong> Memungkinkan pengguna melaporkan kerusakan secara langsung dan melihat rekam jejak peminjaman untuk transparansi data.</div>
        </div> 
    </div> <div class="accordion-item">
        <button class="accordion-header" aria-expanded="false">
            Manfaat
            <svg class="accordion-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M7 10l5 5 5-5z"/>
            </svg>
        </button>
        <div class="accordion-body" role="region">
            <div class="accordion-box"><strong>1. Mempermudah Proses Peminjaman</strong></div>
            <div class="accordion-box"><strong>2. Akuntabilitas dan Keamanan Barang</strong></div>
            <div class="accordion-box"><strong>3. Transparansi Informasi</strong></div>
            <div class="accordion-box"><strong>4. Verifikasi Penggunaan Real-time</strong></div>
        </div>
    </div>

    <div class="accordion-item">
        <button class="accordion-header" aria-expanded="false">
            FAQ
            <svg class="accordion-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M7 10l5 5 5-5z"/>
            </svg>
        </button>
        <div class="accordion-body" role="region">
            <div class="accordion-box"><strong>Bagaimana cara mengajukan peminjaman?</strong> Pilih ajukan peminjaman pada halaman dashboard dengan mengisi form yang tersedia.</div>
            <div class="accordion-box"><strong>Bagaimana proses persetujuan peminjaman?</strong> Peminjaman akan melalui proses persetujuan oleh Ketua Umum dan PIC terkait.</div>
            <div class="accordion-box"><strong>Bagaimana melihat status peminjaman?</strong> Status dapat dilihat pada menu riwayat peminjaman ruangan atau barang.</div>
                        </div>
                    </div>
                </section>
            </article>
        </div>
    </main>

    <script>
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const item = header.parentElement;
                const isOpen = item.classList.contains('active');

                document.querySelectorAll('.accordion-item').forEach(i => {
                    i.classList.remove('active');
                    i.querySelector('.accordion-header').setAttribute('aria-expanded', 'false');
                });

                if (!isOpen) {
                    item.classList.add('active');
                    header.setAttribute('aria-expanded', 'true');
                }
            });
        });
    </script>
</body>
</html>