<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Detail Ruangan - SC-Space</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: white; overflow-x: hidden; }
        .navbar {
            height: 70px; background: #2f7ea1;
            display: flex; justify-content: space-between;
            align-items: center; padding: 0 25px;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        }
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

        .logo {
            width: 70px; height: 88px;
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: 223%; background-position: 50% 60%; background-repeat: no-repeat;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        #clock-display { color: white; font-weight: 500; font-size: 15px; padding: 5px 12px; font-family: monospace; background: none !important; border: none; }
        
        .fa-bell { color: black; font-size: 22px; cursor: pointer; transition: 0.2s; padding: 8px; }
        .notif-container { position: relative; }
        .notif-badge {
            position: absolute; top: 5px; right: 5px;
            background: #ff4d4d; color: white; font-size: 10px;
            width: 16px; height: 16px; border-radius: 50%;
            display: none; align-items: center; justify-content: center;
            font-weight: bold; pointer-events: none;
        }
        .notif-badge.active-badge { display: flex; }
        .notif-dropdown {
            position: absolute; top: 55px; right: -60px; width: 320px;
            background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(-10px); pointer-events: none; transition: 0.25s; z-index: 1001;
        }
        .notif-dropdown.active { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .notif-header { padding: 12px 15px; background: #f8fbff; border-bottom: 1px solid #eee; font-weight: 600; font-size: 14px; color: #2f7ea1; text-align: left; }
        .notif-body { max-height: 300px; overflow-y: auto; }

        .profile { position: relative; cursor: pointer; }
        .profile-circle {
            width: 40px; height: 40px; background: #ffffff; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
        }
        .profile-circle i { color: #2f7ea1; font-size: 22px; }
        .dropdown {
            position: absolute; top: 55px; right: 0; width: 170px;
            background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(-10px); pointer-events: none; transition: 0.25s; z-index: 1001;
        }
        .dropdown.active { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .dropdown div { padding: 10px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; color: #333; font-size: 14px; }
        .dropdown div:hover { background: #f0f8ff; }

        .main { display: flex; }
        .sidebar {
            width: 235px; background: #E4F0FF;
            position: fixed; top: 70px; height: calc(100vh - 70px + 12px);
            left: 0; display: flex; flex-direction: column; z-index: 999;
        }
        .sidebar-content { flex: 1; overflow-y: auto; }
        .menu-group { border-bottom: 1px solid #cbd9e6; }
        .dashboard-menu { padding: 12px 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 11px; text-decoration: none; color: black; transition: 0.2s; }
        .dashboard-menu:hover { background: #c9dcec; }
        .menu-title { padding: 12px 20px; font-weight: 580; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; }
        .menu-title:hover { background: #c9dcec; }
        .submenu { display: none; padding-left: 10px; }
        .submenu a { display: block; padding: 10px 14px; text-decoration: none; color: black; margin: 2px 0; border-radius: 6px; transition: 0.2s; cursor: pointer; font-size: 16px; }
        .submenu a:hover, .submenu a.active { background: #cfe3f1; }
        
        .menu-arrow { transition: none; }
        .sidebar-footer {
            background: white; padding: 14px; border-top: 1px solid #cbd9e6;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        .profile-info { display: flex; align-items: center; gap: 10px; cursor: pointer; border-radius: 6px; }
        .profile-avatar {
            width: 35px; height: 35px; background: linear-gradient(135deg, #2f7ea1, #4a9dc3);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 16px; flex-shrink: 0; text-transform: uppercase;
        }
        .sidebar-footer .profile-details h4 { font-size: 14px; font-weight: 600; color: #1e293b; margin: 0 0 2px 0; }
        .sidebar-footer .profile-details p { font-size: 12px; color: #6b7280; margin: 0; }
        .content-wrapper {
            margin-left: 235px; margin-top: 70px;
            width: calc(100% - 235px); min-height: 100vh;
            background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%);
            padding: 40px 20px; 
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .page-header-section {
            width: 100%; max-width: 720px;
            margin-bottom: 20px; display: flex; align-items: center; gap: 18px;
        }
        .back-arrow-link {
            text-decoration: none; color: #1e293b; font-size: 26px;
            transition: 0.2s; display: flex; align-items: center;
        }
        .back-arrow-link:hover { color: #2f7ea1; }
        .page-title { font-size: 24px; font-weight: 800; color: #1e293b; margin: 0;}
        .room-detail-container {
            background: white; border-radius: 25px; padding: 35px; 
            max-width: 720px; width: 100%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);
        }
        .room-detail-container h2 { margin-bottom: 25px; font-size: 16px; color: #333; font-weight: 700; }
        .detail-grid { display: flex; gap: 30px; margin-bottom: 20px; }
        .detail-info { flex: 1.5; }
        .detail-item { margin-bottom: 15px; }
        .detail-label { font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600; }
        .detail-value {
            font-weight: 600; font-size: 14px; padding: 8px 12px;
            background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd; color: #555;
        }
    
        .room-image-container { flex: 1; }
        .room-image {
            width: 100%; height: 180px; border-radius: 12px;
            overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .room-image img { width: 100%; height: 100%; object-fit: cover; }
        .button-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
        .btn { padding: 12px 20px; border: none; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 13px; transition: 0.2s; }
        .btn-secondary { background: #cbd9e6; color: #333; }
        .btn-primary { background: #2f7ea1; color: white; }
        @media (max-width: 760px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .content-wrapper { margin-left: 0; width: 100%; }
            .detail-grid { flex-direction: column; }
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
            <div id="clock-display"></div> 
            <div class="notif-container">
                <i class="fa-regular fa-bell" onclick="toggleNotifDropdown(event)"></i>
                <div class="notif-badge" id="notifBadge">0</div>
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">Notifikasi</div>
                    <div class="notif-body"><div style="padding: 20px; text-align: center; color: #999;">Tidak ada notifikasi baru.</div></div>
                </div>
            </div>
            <div class="profile" onclick="toggleProfileDropdown(event)">
                <div class="profile-circle"><i class="fa-solid fa-user"></i></div>
                <div class="dropdown" id="dropdownMenu">
                    <div onclick="goToPage('profile')"><i class="fa-solid fa-user"></i> Profil Saya</div>
                    <div onclick="goToPage('logout')"><i class="fa-solid fa-right-from-bracket"></i> Logout</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="sidebar">
            <div class="sidebar-content">
                <a href="#" class="dashboard-menu" onclick="goToPage('dashboard')"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                
                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span><i class="fa-solid fa-boxes-stacked" style="width: 20px;"></i> Inventaris</span>
                        <i class="fa-solid fa-chevron-up menu-arrow"></i>
                    </div>
                    <div class="submenu" style="display: block;">
                        <a href="javascript:void(0)" onclick="goToPage('daftar-ruangan')">Daftar Ruangan</a>
                        <a href="javascript:void(0)" onclick="goToPage('daftar-barang')">Daftar Barang</a>
                        <a href="javascript:void(0)" onclick="goToPage('handover')">Handover</a>
                    </div>
                </div>

                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span><i class="fa-solid fa-triangle-exclamation" style="width: 20px;"></i> Laporan Insiden</span>
                        <i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="javascript:void(0)" onclick="goToPage('buat-laporan')">Buat Laporan</a>
                        <a href="javascript:void(0)" onclick="goToPage('riwayat-laporan')">Riwayat Laporan</a>
                    </div>
                </div>

                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span><i class="fa-solid fa-clock-rotate-left" style="width: 20px;"></i> Riwayat</span>
                        <i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="javascript:void(0)" onclick="goToPage('peminjaman-ruangan')">Peminjaman Ruangan</a>
                        <a href="javascript:void(0)" onclick="goToPage('peminjaman-barang')">Peminjaman Barang</a>
                    </div>
                </div>
            </div>

            <div class="sidebar-footer">
                <div class="profile-info" onclick="goToPage('profile')">
                    <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}</div>
                    <div class="profile-details">
                        <h4>{{ auth()->user()->name ?? 'Rindiani' }}</h4>
                        <p>{{ auth()->user()->nim ?? '3312301054' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="page-header-section">
                <a href="#" onclick="window.history.back()" class="back-arrow-link">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <h1 class="page-title">Detail Ruangan</h1>
            </div>

            <div class="room-detail-container">
                <h2>Informasi Ruangan</h2>
                <div class="detail-grid">
                    <div class="detail-info">
                        <div class="detail-item">
                            <div class="detail-label">Nama Ruangan</div>
                            <div class="detail-value">Ruang HMJ</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Kapasitas</div>
                            <div class="detail-value">20 Orang</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Lokasi</div>
                            <div class="detail-value">Student Center Lt.1 RB</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Fasilitas</div>
                            <div class="detail-value">AC, kursi, meja</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Deskripsi</div>
                            <div class="detail-value">Digunakan untuk rapat organisasi</div>
                        </div>
                    </div>
                    <div class="room-image-container">
                        <div class="detail-label">Foto Ruangan</div>
                        <div class="room-image">
                            <img src="https://via.placeholder.com/300x200" alt="Foto Ruangan">
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <button class="btn btn-secondary" onclick="window.history.back()">Kembali</button>
                    <button class="btn btn-primary" onclick="goToPage('ajukan-peminjaman')">Ajukan peminjaman</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const sekarang = new Date();
            const jam = String(sekarang.getHours()).padStart(2, '0');
            const menit = String(sekarang.getMinutes()).padStart(2, '0');
            const detik = String(sekarang.getSeconds()).padStart(2, '0');
            const display = document.getElementById('clock-display');
            if (display) display.innerText = `${jam}:${menit}:${detik}`;
        }
        updateClock(); setInterval(updateClock, 1000);

        function toggleNotifDropdown(e) { e.stopPropagation(); document.getElementById("notifDropdown").classList.toggle("active"); }
        function toggleProfileDropdown(e) { e.stopPropagation(); document.getElementById("dropdownMenu").classList.toggle("active"); }

        function toggleMenu(el) {
            const submenu = el.nextElementSibling;
            const arrow = el.querySelector(".menu-arrow");      
            if (submenu.style.display === "block") {
                submenu.style.display = "none";
                arrow.classList.replace("fa-chevron-up", "fa-chevron-down");
            } else {
                submenu.style.display = "block";
                arrow.classList.replace("fa-chevron-down", "fa-chevron-up");
            }
        }

        document.addEventListener('click', () => {
            document.getElementById("dropdownMenu").classList.remove("active");
            document.getElementById("notifDropdown").classList.remove("active");
        });

        function goToPage(page) {
            const routes = {
                'dashboard': '/Anggota/dashboardanggota', 'profile': '/Anggota/detailakun',
                'daftar-ruangan': '/Anggota/daftarruangan', 'daftar-barang': '/Anggota/daftarbarang',
                'handover': '/Anggota/handover', 'buat-laporan': '/Anggota/laporinsiden',
                'riwayat-laporan': '/Anggota/riwayatinsiden', 'peminjaman-ruangan': '/Anggota/riwayatruangan',
                'peminjaman-barang': '/Anggota/riwayatbarang', 'ajukan-peminjaman': '/Anggota/pengajuanruangan'
            };
            if (page === 'logout' && confirm("Logout?")) window.location.href = "/Anggota/masuk";
            else window.location.href = routes[page] || "/Anggota/" + page;
        }
    </script>
</body>
</html>