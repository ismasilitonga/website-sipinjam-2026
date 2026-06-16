<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Dashboard Peminjaman Student Center</title>
    <style>
        /* --- CSS DASAR --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: white; overflow-x: hidden; }

        /* --- NAVBAR --- */
        .navbar {
            height: 70px; background: #2f7ea1;
            display: flex; justify-content: space-between;
            align-items: center; padding: 0 25px;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        }
        .nav-left { display: flex; align-items: center; gap: 12px; color: white; }
        .logo {
            width: 70px; height: 88px;
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: 223%; background-position: 50% 60%; background-repeat: no-repeat;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        
        .profile { position: relative; } 
        .profile-circle {
            width: 40px; height: 40px; background: #ffffff; border-radius: 50%;
            display: flex; justify-content: center; align-items: center; cursor: pointer;
        }
        .profile-circle i { color: #2f7ea1; font-size: 22px; }
        .nav-right .fa-bell { color: #000000; font-size: 22px; cursor: pointer; padding: 8px; }
        
        .dropdown {
            position: absolute; top: 56px; right: 0; width: 170px;
            background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(-10px); pointer-events: none; transition: 0.25s; z-index: 1001;
        }
        .dropdown.active { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .dropdown div { padding: 10px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; font-size: 14px; color: #333; }
        .dropdown div:hover { background: #f0f8ff; }

        /* --- SIDEBAR --- */
        .main { display: flex; }
        .sidebar {
            width: 235px; background: #E4F0FF; position: fixed; top: 70px;
            height: calc(100vh - 70px + 12px); left: 0; display: flex; flex-direction: column; z-index: 999;
        }
        .sidebar-content { flex: 1; overflow-y: auto; }

        .menu-group { border-bottom: 1px solid #cbd9e6; }
        .dashboard-menu {
            padding: 12px 16px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 11px; transition: 0.2s;
            text-decoration: none; color: black;
        }
        .dashboard-menu:hover, .dashboard-menu.active { background: #c9dcec; }
        
        .menu-title {
            padding: 12px 20px; font-weight: 580; cursor: pointer;
            display: flex; justify-content: space-between; align-items: center; transition: 0.2s;
        }
        .menu-title:hover { background: #c9dcec; }
        .submenu { display: none; padding-left: 10px; }
        .submenu a {
            display: block; padding: 10px 14px; cursor: pointer; border-radius: 6px;
            transition: 0.2s; text-decoration: none; color: black; margin: 2px 0;
        }
        .submenu a:hover { background: #cfe3f1; }
        .menu-arrow.rotate { transform: rotate(180deg); }

        .sidebar-footer {
            background: white; padding: 14px 14px; border-top: 1px solid #cbd9e6;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        .profile-info { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .profile-avatar {
            width: 35px; height: 35px; background: linear-gradient(135deg, #2f7ea1, #4a9dc3);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 590; font-size: 16px; flex-shrink: 0; text-transform: uppercase;
        }
        .sidebar-footer .profile-details h4 { font-size: 14px; font-weight: 600; color: #1e293b; margin: 0 0 2px 0; }
        .sidebar-footer .profile-details p { font-size: 12px; color: #6b7280; margin: 0; }

        /* --- CONTENT AREA --- */
        .content-wrapper {
            margin-left: 235px; margin-top: 70px;
            width: calc(100% - 235px); min-height: 100vh;
            background: #bcd3e3;
        }
        .welcome-banner { background: #78b4c7; padding: 15px 30px; font-size: 18px; font-weight: bold; }
        .content { padding: 40px 20px; display: flex; flex-direction: column; align-items: center; background: #C3D8E8; min-height: calc(100vh - 120px); position: relative; }

        /* ACTION PANEL DROPDOWN */
        .action-panel-container { align-self: flex-start; margin-left: 40px; margin-bottom: 20px; position: relative; }
        .action-panel {
            background: #90bce9; padding: 12px 18px; border-radius: 8px;
            display: flex; align-items: center; gap: 10px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.3); min-width: 200px;
        }
        .action-panel.active-dropdown { border-radius: 8px 8px 0 0; }
        .action-dropdown {
            display: none; background: white; border: 1px solid #333; width: 100%;
            border-radius: 0 0 8px 8px; overflow: hidden; position: absolute; top: 100%; left: 0; z-index: 50;
        }
        .action-dropdown a { display: block; padding: 12px 16px; text-decoration: none; color: black; text-align: center; font-weight: 500; border-bottom: 1px solid #ddd; transition: 0.2s; }
        .action-dropdown a:hover { background: #f0f8ff; }
        
        .activity-panel {
            background: white; border: 1px solid #333; border-radius: 12px;
            width: 100%; max-width: 1000px; margin-bottom: 30px;
            min-height: 250px; display: flex; flex-direction: column; box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .activity-header { text-align: center; padding: 10px; font-weight: bold; border-bottom: 2px solid #333; }
        .activity-body { padding: 30px; }

        .button-group { display: flex; justify-content: center; gap: 30px; }
        .btn-green {
            background: #a1d182; border: none; padding: 12px 30px; border-radius: 25px;
            font-weight: bold; font-size: 15px; cursor: pointer; box-shadow: 0 3px 8px rgba(0,0,0,0.2); transition: 0.3s;
        }
        .btn-green:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.3); }

        /* MODAL LOGIN */
        #loginModal {
            display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px);
            align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;
        }
        #loginModal.show { opacity: 1; display: flex; }
        .modal-card {
            background: white; width: 400px; padding: 45px 35px; border-radius: 32px;
            text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            transform: scale(0.9); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #loginModal.show .modal-card { transform: scale(1); }
        .modal-icon-wrapper {
            width: 85px; height: 85px; background: #ecfdf5; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 25px; box-shadow: 0 0 0 10px #f0fdf4;
        }
        .modal-icon-wrapper i { font-size: 40px; color: #10b981; }
        .btn-confirm {
            background: linear-gradient(135deg, #2f7ea1, #4a9dc3);
            color: white; border: none; width: 50%; padding: 16px; border-radius: 16px;
            font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.3s;
        }
        /* Status Live Monitoring */
.live-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #d9534f;
    font-weight: bold;
    font-size: 13px;
}
.pulse-dot {
    width: 10px; height: 10px;
    background: #d9534f;
    border-radius: 50%;
    box-shadow: 0 0 0 rgba(217, 83, 79, 0.4);
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 83, 79, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(217, 83, 79, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 83, 79, 0); }
}
.room-active-card {
    background: #fff5f5;
    border: 1px solid #fab1a0;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
    </style>
</head>
<body>

    <div id="loginModal">
        <div class="modal-card">
            <div class="modal-icon-wrapper"><i class="fa-solid fa-circle-check"></i></div>
            <h2>Login Berhasil!</h2>
            <p>Selamat datang kembali,<br><span>{{ Auth::user()->name ?? 'Rindiani' }}</span>!</p>
            <button class="btn-confirm" onclick="closeLoginModal()">Oke</button>
        </div>
    </div>

    <div class="navbar">
        <div class="nav-left"><div class="logo"></div><b>Student Center</b></div>
        <div class="nav-right">
            <i class="fa-regular fa-bell" onclick="alert('Belum ada notifikasi')"></i>
            <div class="profile" onclick="toggleProfileDropdown(event)">
                <div class="profile-circle"><i class="fa-solid fa-user"></i></div>
                <div id="dropdownMenu" class="dropdown">
                    <div onclick="goToPage('profile')"><i class="fa-solid fa-user"></i> Profil Saya</div>
                    <div onclick="goToPage('logout')"><i class="fa-solid fa-right-from-bracket"></i> Logout</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="sidebar">
            <div class="sidebar-content">
                <a href="#" class="dashboard-menu active" onclick="goToPage('dashboard')">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                
                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span>Inventaris</span><i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" onclick="goToPage('daftarruangan')">Daftar Ruangan</a>
                        <a href="#" onclick="goToPage('daftarbarang')">Daftar Barang</a>
                        <a href="#" onclick="goToPage('handover')">Handover</a>
                    </div>
                </div>

                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span>Laporan Insiden</span><i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" onclick="goToPage('buat-laporan')">Buat Laporan</a>
                        <a href="#" onclick="goToPage('riwayat-laporan')">Riwayat Laporan</a>
                    </div>
                </div>

                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span>Riwayat</span><i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" onclick="goToPage('peminjaman-ruangan')">Peminjaman Ruangan</a>
                        <a href="#" onclick="goToPage('peminjaman-barang')">Peminjaman Barang</a>
                    </div>
                </div>
            </div>

               <div class="sidebar-footer">
                <div class="profile-info" onclick="goToPage('profile')">
                    <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}</div>
                    <div class="profile-details">
                        <h4>{{ auth()->user()->name ?? 'User' }}</h4>
                        <p>{{ auth()->user()->nim ?? 'User' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="welcome-banner">Selamat Datang di Sistem Peminjaman Student Center!</div>
            <div class="content">
                <div class="action-panel-container">
                    <div class="action-panel" onclick="toggleActionDropdown(event)" id="actionPanel">
                        <div style="width: 20px; height: 20px; border: 2px solid black; border-radius: 4px; display: flex; align-items: center; justify-content: center;">+</div>
                        <span>Ajukan peminjaman</span>
                    </div>
                    <div class="action-dropdown" id="actionDropdownMenu">
                        <a href="#" onclick="goToPage('pengajuanruangan')">Ruangan</a>
                        <a href="#" onclick="goToPage('pengajuanbarang')">Barang</a>
                    </div>
                </div>

                <div class="activity-panel">
                    <div class="activity-header">Aktivitas Terbaru</div>
                    <div class="activity-body">
                        </div>
                </div>

                <div class="button-group">
                    <button class="btn-green" onclick="goToPage('daftarruangan')">Lihat Daftar Ruangan</button>
                    <button class="btn-green" onclick="goToPage('daftarbarang')">Lihat Daftar Barang</button>
                </div>
            </div>
        </div>
    </div>
    <div class="activity-panel">
    <div class="activity-header">Status Ruangan Student Center (Real-Time)</div>
    <div class="activity-body">
        <div class="room-active-card">
            <div>
                <h4 style="color: #2f7ea1;">Ruang 302 (Lantai 3)</h4>
                <p style="font-size: 14px; margin-top: 5px;">
                    Sedang digunakan oleh: <strong>HMTI</strong>
                </p>
                <small style="color: #666;">Estimasi selesai: 15:00 WIB</small>
            </div>
            <div class="live-indicator">
                <div class="pulse-dot"></div> LIVE
            </div>
        </div>

        <p style="text-align: center; color: #666; font-size: 14px;">
            Ruangan lainnya saat ini tersedia.
        </p>
    </div>
</div>

    <script>
        // Logika Modal Login (Tampil jika ada session success dari Laravel)
        window.onload = function() {
            @if(session('success'))
                const modal = document.getElementById("loginModal");
                if (modal) {
                    modal.classList.add("show");
                }
            @endif
        };

        function closeLoginModal() {
            document.getElementById("loginModal").classList.remove("show");
        }

        // Navigasi Terpusat (Sesuai dengan route laporinsiden)
        function goToPage(page) {
            const routes = {
                'dashboard': '/Anggota/dashboardanggota',
                'profile': '/Anggota/detailakun',
                'daftarruangan': '/Anggota/daftarruangan',
                'daftarbarang': '/Anggota/daftarbarang',
                'handover': '/Anggota/handover',
                'buat-laporan': '/Anggota/laporinsiden',
                'riwayat-laporan': '/Anggota/riwayatinsiden', 
                'peminjaman-ruangan': '/Anggota/riwayatruangan',
                'peminjaman-barang': '/Anggota/riwayatbarang',
                'pengajuanruangan': '/Anggota/pengajuanruangan',
                'pengajuanbarang': '/Anggota/pengajuanbarang',
                'logout': '/Anggota/masuk' // Ganti ke route login kamu
            };

            if (page === 'logout') {
                if (confirm("Yakin ingin logout?")) window.location.href = routes[page];
            } else {
                window.location.href = routes[page] || ("/Anggota/" + page);
            }
        }

        // Dropdown Profil Navbar
        function toggleProfileDropdown(e) {
            e.stopPropagation();
            document.getElementById("dropdownMenu").classList.toggle("active");
        }

        // Accordion Sidebar
        function toggleMenu(el) {
            const submenu = el.nextElementSibling;
            const arrow = el.querySelector(".menu-arrow");
            const isOpen = submenu.style.display === "block";
            
            // Close other submenus (Optional)
            // document.querySelectorAll('.submenu').forEach(s => s.style.display = 'none');
            
            submenu.style.display = isOpen ? "none" : "block";
            arrow.classList.toggle("rotate");
        }

        // Dropdown Ajukan Peminjaman
        function toggleActionDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById("actionDropdownMenu");
            const panel = document.getElementById("actionPanel");
            const isVisible = menu.style.display === "block";
            
            menu.style.display = isVisible ? "none" : "block";
            panel.classList.toggle("active-dropdown");
        }

        // Klik di luar untuk menutup semua dropdown
        document.addEventListener('click', function() {
            document.getElementById("dropdownMenu").classList.remove("active");
            
            const actMenu = document.getElementById("actionDropdownMenu");
            const actPanel = document.getElementById("actionPanel");
            if (actMenu) {
                actMenu.style.display = "none";
                actPanel.classList.remove("active-dropdown");
            }
        });
    </script>
</body>
</html>