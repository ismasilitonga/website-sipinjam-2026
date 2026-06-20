<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Dashboard - SC-Space</title>
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
        #clock-display { color: white; font-weight: 500; font-size: 15px; padding: 5px 12px; font-family: monospace; }
        
        .notif-container { position: relative; }
        .fa-bell { color: black; font-size: 22px; cursor: pointer; transition: 0.2s; padding: 8px; }
        .notif-badge {
            position: absolute; top: 5px; right: 5px;
            background: #ff4d4d; color: white; font-size: 10px;
            width: 16px; height: 16px; border-radius: 50%;
            display: none; align-items: center; justify-content: center;
            font-weight: bold; pointer-events: none;
        }
        .notif-badge.active-badge { display: flex; }
        .notif-dropdown {
            position: absolute; top: 56px; right: -60px; width: 320px;
            background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(-10px); pointer-events: none; transition: 0.25s; z-index: 1001;
            overflow: hidden;
        }
        .notif-dropdown.active { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .notif-header { padding: 12px 15px; background: #f8fbff; border-bottom: 1px solid #eee; font-weight: 600; font-size: 14px; color: #2f7ea1; }
        .notif-body { max-height: 300px; overflow-y: auto; }
        .notif-empty { padding: 30px; text-align: center; color: #94a3b8; 
            font-style: italic; font-size: 13px; }
        
        .profile { position: relative; cursor: pointer; }
        .profile-circle {
            width: 40px; height: 40px; background: #ffffff; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
        }
        .profile-circle i { color: #2f7ea1; font-size: 22px; }
        .dropdown {
            position: absolute; top: 56px; right: 0; width: 200px;
            background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(-10px); pointer-events: none; 
            transition: 0.25s; z-index: 1001;
            padding: 8px 0;
        }
        .dropdown.active { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .dropdown div { padding: 12px 20px; cursor: pointer; 
            display: flex; align-items: center; gap: 12px; color: #333; 
            font-size: 15px; font-weight: 500; transition: 0.2s; }
        .dropdown div:hover { background: #f0f8ff; color: #2f7ea1; }

        .main { display: flex; }
        .sidebar {
            width: 235px; background: #E4F0FF;
            position: fixed; top: 70px; height: calc(100vh - 70px + 12px);
            left: 0; display: flex; flex-direction: column; z-index: 999;
        }
        .sidebar-content { flex: 1; overflow-y: auto; }
        .dashboard-menu { padding: 12px 18px; font-weight: 600; 
            cursor: pointer; display: flex; align-items: center; 
            gap: 12px; text-decoration: none; color: black; transition: 0.2s; font-size: 16px; }
        .dashboard-menu:hover, .dashboard-menu.active { background: #c9dcec; }
        
        .menu-group { border-bottom: 1px solid #cbd9e6; }
        .menu-title { padding: 12px 18px; font-weight: 580; cursor: pointer; 
            display: flex; justify-content: space-between; 
            align-items: center; transition: 0.2s; font-size: 16px; }
        .menu-title:hover { background: #c9dcec; }
        .menu-title span { display: flex; align-items: center; gap: 12px; flex: 1; }
        .submenu { display: none; padding-left: 25px; }
        .submenu a { display: block; padding: 6px 14px; text-decoration: none; 
            color: black; margin: 2px 0; border-radius: 6px; transition: 0.2s; 
            font-size: 16px; }
        .submenu a:hover, .submenu a.active { background: #cfe3f1; }
        .menu-arrow.rotate { transform: rotate(180deg); }

        .sidebar-footer {
            background: white; padding: 14px; border-top: 1px solid #cbd9e6;
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

        .content-wrapper {
            margin-left: 235px; margin-top: 70px;
            width: calc(100% - 235px); height: calc(100vh - 70px);
            overflow-y: auto; background: #bcd3e3;
        }
        .welcome-banner { background: #78b4c7; padding: 15px 30px; font-size: 18px; font-weight: bold; }
        .content { padding: 40px 20px; display: flex; flex-direction: column; align-items: center; 
            background: #C3D8E8; min-height: 100%; position: relative; }
        .action-panel-container { align-self: flex-start; margin-left: 40px; 
            margin-bottom: 20px; position: relative; }
        .action-panel {
            background: #90bce9; padding: 12px 18px; border-radius: 8px;
            display: flex; align-items: center; gap: 10px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.3); min-width: 200px;
            position: relative; z-index: 10;
        }
        .action-panel.active-dropdown { border-radius: 8px 8px 0 0; }
        .action-dropdown {
            display: none; background: white; border: 1px solid #333; width: 100%;
            border-radius: 0 0 8px 8px; overflow: hidden; position: absolute; 
            top: 100%; left: 0; z-index: 5;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .action-dropdown a { display: block; padding: 12px 16px; text-decoration: none; 
            color: black; text-align: center; font-weight: 500; 
            border-bottom: 1px solid #ddd; transition: 0.2s; }
        .action-dropdown a:hover { background: #f0f8ff; }
        .activity-panel {
            background: white; border: 1px solid #333; border-radius: 12px;
            width: 100%; max-width: 1000px; margin-bottom: 30px;
            display: flex; flex-direction: column; box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            overflow: hidden;
        }
        .activity-header { text-align: center; padding: 10px; font-weight: bold; 
            border-bottom: 2px solid #333; }
        .activity-body { padding: 20px; }
        .room-active-card {
            background: #fff5f5; border: 1px solid #fab1a0; padding: 15px 25px; 
            border-radius: 10px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .live-indicator { display: flex; align-items: center; gap: 8px; color: #d9534f; font-weight: bold; font-size: 13px; }
        .pulse-dot { width: 10px; height: 10px; background: #d9534f; border-radius: 50%; animation: pulse 1.5s infinite; }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 83, 79, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(217, 83, 79, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 83, 79, 0); }
        }

        .button-group { display: flex; justify-content: center; gap: 30px; padding-bottom: 40px; }
        .btn-green {
            background: #a1d182; border: none; padding: 12px 30px; border-radius: 25px;
            font-weight: bold; font-size: 15px; cursor: pointer; box-shadow: 0 3px 8px rgba(0,0,0,0.2); transition: 0.3s;
        }
        #loginModal {
            display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px);
            align-items: center; justify-content: center;
        }
        #loginModal.show { display: flex; }
        .modal-card { background: white; width: 400px; padding: 45px 35px; border-radius: 32px; text-align: center; }
        .btn-confirm { background: #2f7ea1; color: white; border: none; padding: 15px 40px; border-radius: 16px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>

    <div id="loginModal">
        <div class="modal-card">
            <h2 style="color: #10b981;">Login Berhasil!</h2>
            <p style="margin: 20px 0;">Selamat datang kembali,<br><span>{{ Auth::user()->nama ?? 'User' }}</span>!</p>
            <button class="btn-confirm" onclick="closeLoginModal()">Oke</button>
        </div>
    </div>

    <div class="navbar">
        <div class="nav-left">
            <div class="logo"></div>
            <span class="brand-text">SC-Space</span>
        </div>
        <div class="nav-right">
            <div id="clock-display">00:00:00</div> 
            <div class="notif-container">
                <i class="fa-regular fa-bell" onclick="toggleNotifDropdown(event)"></i>
                <div class="notif-badge" id="notifBadge">0</div>
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">Notifikasi</div>
                    <div class="notif-body" id="notifBody">
                        <div class="notif-empty">Tidak ada notifikasi baru.</div>
                    </div>
                </div>
            </div>
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
                <a href="javascript:void(0)" class="dashboard-menu active" onclick="goToPage('dashboard')">
                    <i class="fa-solid fa-gauge" style="width: 20px;"></i> 
                    <span>Dashboard</span>
                </a>
                
                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span><i class="fa-solid fa-boxes-stacked" style="width: 20px;"></i> Inventaris</span>
                        <i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="javascript:void(0)" onclick="goToPage('daftarruangan')">Daftar Ruangan</a>
                        <a href="javascript:void(0)" onclick="goToPage('daftarbarang')">Daftar Barang</a>
                        <a href="javascript:void(0)" onclick="goToPage('handover')">Handover</a>
                    </div>
                </div>

                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span><i class="fa-solid fa-triangle-exclamation" style="width: 20px;"></i> Laporan Insiden</span>
                        <i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="javascript:void(0)" onclick="goToPage('laporinsiden')">Buat Laporan</a>
                        <a href="javascript:void(0)" onclick="goToPage('riwayatinsiden')">Riwayat Laporan</a>
                    </div>
                </div>

                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span><i class="fa-solid fa-clock-rotate-left" style="width: 20px;"></i> Riwayat</span>
                        <i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="javascript:void(0)" onclick="goToPage('riwayatruangan')">Peminjaman Ruangan</a>
                        <a href="javascript:void(0)" onclick="goToPage('riwayatbarang')">Peminjaman Barang</a>
                    </div>
                </div>
            </div>

            <div class="sidebar-footer">
                <div class="profile-info" onclick="goToPage('profile')">
                    <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->nama ?? 'R', 0, 1)) }}</div>
                    <div class="profile-details">
                        <h4>{{ auth()->user()->nama ?? 'Rindiani' }}</h4>
                        <p>{{ auth()->user()->nim ?? '3312301054' }}</p>
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
                    <div class="activity-header">Aktivitas & Status Terbaru</div>
                    <div class="activity-body">
                        @forelse($peminjamanAktif as $item)
                            <div class="room-active-card" style="margin-bottom: 15px;">
                                <div>
                                    <h4 style="color: #2f7ea1;">{{ $item->ruangan }}</h4>
                                    <p style="font-size: 14px; margin-top: 5px;">
                                        Oleh: <strong>{{ $item->nama_ormawa }}</strong>
                                    </p>
                                    <p style="font-size: 12px; color: #888;">
                                        Mulai: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('H:i') }} WIB
                                    </p>
                                </div>
                                <div class="live-indicator"><div class="pulse-dot"></div> LIVE</div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 20px;">
                                <p style="color: #666; font-size: 14px;">Belum ada aktivitas terbaru</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="button-group">
                    <button class="btn-green" onclick="goToPage('daftarruangan')">Lihat Daftar Ruangan</button>
                    <button class="btn-green" onclick="goToPage('daftarbarang')">Lihat Daftar Barang</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function updateClock() {
        const sekarang = new Date();
        const display = document.getElementById('clock-display');
        if (display) {
            const jam = String(sekarang.getHours()).padStart(2, '0');
            const menit = String(sekarang.getMinutes()).padStart(2, '0');
            const detik = String(sekarang.getSeconds()).padStart(2, '0');
            display.innerText = `${jam}:${menit}:${detik}`;
        }
    }
    setInterval(updateClock, 1000); updateClock();

    window.onload = function() {
        @if(session('success')) 
            document.getElementById("loginModal").classList.add("show"); 
        @endif
    };

    function closeLoginModal() { 
        document.getElementById("loginModal").classList.remove("show"); 
    }

    function toggleNotifDropdown(e) {
        e.stopPropagation();
        document.getElementById("dropdownMenu").classList.remove("active");
        document.getElementById("notifDropdown").classList.toggle("active");
    }

    function toggleProfileDropdown(e) {
        e.stopPropagation();
        document.getElementById("notifDropdown").classList.remove("active");
        document.getElementById("dropdownMenu").classList.toggle("active");
    }

    function toggleMenu(el) {
        const submenu = el.nextElementSibling;
        const arrow = el.querySelector(".menu-arrow");
        submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        arrow.classList.toggle("rotate");
    }

    function toggleActionDropdown(event) {
        event.stopPropagation();
        const menu = document.getElementById("actionDropdownMenu");
        const panel = document.getElementById("actionPanel");
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
        panel.classList.toggle("active-dropdown");
    }

    function goToPage(page) {
        const routes = {
            'dashboard': '/Anggota/dashboardanggota', 'profile': '/Anggota/detailakun',
            'daftarruangan': '/Anggota/daftarruangan', 'daftarbarang': '/Anggota/daftarbarang',
            'handover': '/Anggota/handover', 'laporinsiden': '/Anggota/laporinsiden',
            'riwayatinsiden': '/Anggota/riwayatinsiden', 'riwayatruangan': '/Anggota/riwayatruangan',
            'riwayatbarang': '/Anggota/riwayatbarang', 'pengajuanruangan': '/Anggota/pengajuanruangan',
            'pengajuanbarang': '/Anggota/pengajuanbarang', 'logout': '/Anggota/masuk'
        };
        if (page === 'logout') { if (confirm("Yakin ingin logout?")) window.location.href = routes[page]; }
        else { window.location.href = routes[page] || ("/Anggota/" + page); }
    }

    document.addEventListener('click', function() {
        document.getElementById("dropdownMenu").classList.remove("active");
        document.getElementById("notifDropdown").classList.remove("active");
        const actionMenu = document.getElementById("actionDropdownMenu");
        if(actionMenu) actionMenu.style.display = "none";
        const actionPanel = document.getElementById("actionPanel");
        if(actionPanel) actionPanel.classList.remove("active-dropdown");
    });
    </script>
</body>
</html>