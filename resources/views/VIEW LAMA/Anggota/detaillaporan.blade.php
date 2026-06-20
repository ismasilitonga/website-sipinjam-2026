<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Detail Laporan - SC-Space</title>
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
            font-size: 22px; font-weight: 800; letter-spacing: 0.5px;
            position: relative; display: inline-block;
            background: linear-gradient(to bottom, #ffffff 40%, #ffffff 70%, #d1d1d1 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            filter: brightness(1.1) contrast(1.1);
        }
        .brand-text::after {
            content: ''; display: block; width: 35%; height: 3px;
            background: #ffce2e; border-radius: 10px; margin-top: -2px;
        }

        .logo {
            width: 70px; height: 88px;
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: 223%; background-position: 50% 60%; background-repeat: no-repeat;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        #clock-display { color: white; font-weight: 500; font-size: 15px; padding: 5px 12px; font-family: monospace; background: none !important; box-shadow: none !important; }
        
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
            overflow: hidden;
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
        .menu-title span { display: flex; align-items: center; gap: 12px; flex: 1; }
        .submenu { display: none; padding-left: 10px; }
        .submenu a { display: block; padding: 10px 14px; text-decoration: none; color: black; margin: 2px 0; border-radius: 6px; transition: 0.2s; }
        .submenu a:hover, .submenu a.active { background: #cfe3f1; }
        .menu-arrow { transition: none; }

        .sidebar-footer { background: white; padding: 14px; border-top: 1px solid #cbd9e6; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); }
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
            padding: 25px 25px; 
            display: flex;
            justify-content: center;
            position: relative; z-index: 10;
        }

        .main-content-container {
            width: 100%;
            max-width: 820px; 
            display: flex;
            flex-direction: column;
            align-items: flex-start; 
        }
        .page-title-section { 
            width: 100%; 
            margin-bottom: 15px; 
            display: flex; 
            justify-content: flex-start;
            padding-left: 0;
        }
        .page-title { 
            font-size: 24px; font-weight: 700; color: #1e293b; 
            text-transform: uppercase; margin: 0; text-align: left;
        }
        .history-card {
            width: 100%; 
            background: #ffffff; 
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; color: #333; }
        .info-grid { display: grid; grid-template-columns: 200px 20px 1fr; row-gap: 20px; font-size: 14px; background: #deecf3; padding: 30px; border-radius: 20px; margin-bottom: 25px; }
        .label { font-weight: 700; color: #444; }
        .value { color: #333; }
        
        .status-table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: center; }
        .status-table th { background: #d7d7d7; color: #333; padding: 12px; font-weight: 700; }
        .status-table td { padding: 12px 10px; border-bottom: 3px solid #eee; color: #000; }
        
        .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #cbd9e6; color: #333; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; margin-top: 25px; transition: 0.2s; border: none; cursor: pointer; }
        .btn-back:hover { background: #b8c7d6; }

        @media (max-width: 760px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .content-wrapper { margin-left: 0; width: 100%; padding-left: 20px; }
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
                <div class="notif-body" id="notifBody">
                    <div style="padding: 30px; text-align: center; color: #94a3b8; font-style: italic; font-size: 13px;">Tidak ada notifikasi baru.</div>
                </div>
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
            <a href="javascript:void(0)" class="dashboard-menu" onclick="goToPage('dashboard')"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            
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
                <div class="submenu" style="display: block;">
                    <a href="javascript:void(0)" onclick="goToPage('laporinsiden')">Buat Laporan</a>
                    <a href="javascript:void(0)" class="active" onclick="goToPage('riwayatinsiden')">Riwayat Laporan</a>
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
        <!-- PEMBUNGKUS BARU UNTUK KONTEN TENGAH -->
        <div class="main-content-container">
            
            <div class="page-title-section">
                <h1 class="page-title">Detail Riwayat Laporan</h1>
            </div>

            <div class="history-card">
                <h2 class="section-title">Informasi Laporan</h2>
                
                <div class="info-grid">
                    <div class="label">Tanggal Kejadian</div><div class="separator">:</div><div class="value">-</div>
                    <div class="label">Objek Insiden</div><div class="separator">:</div><div class="value">-</div>
                    <div class="label">Nama Ruangan</div><div class="separator">:</div><div class="value">-</div>
                    <div class="label">Jenis Insiden</div><div class="separator">:</div><div class="value">-</div>
                    <div class="label">Deskripsi Kejadian</div><div class="separator">:</div><div class="value">-</div>
                    <div class="label">Bukti Awal</div><div class="separator">:</div><div class="value">-</div>
                    <div class="label">Pelaku</div><div class="separator">:</div><div class="value">-</div>
                </div>

                <div class="tindak-lanjut-section">
                    <h2 class="section-title">Hasil Tindaklanjut PIC</h2>
                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>PIC Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                        </tbody>
                    </table>
                </div>

                <button class="btn-back" onclick="goToPage('riwayatinsiden')">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
            </div>

        </div> 
    </div>
</div>

<script>
    function updateClock() {
        const sekarang = new Date();
        const display = document.getElementById('clock-display');
        if (display) display.innerText = `${String(sekarang.getHours()).padStart(2, '0')}:${String(sekarang.getMinutes()).padStart(2, '0')}:${String(sekarang.getSeconds()).padStart(2, '0')}`;
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
        const routes = {'dashboard': '/Anggota/dashboardanggota', 'profile': '/Anggota/detailakun', 'riwayatinsiden': '/Anggota/riwayatinsiden'};
        if (page === 'logout' && confirm("Logout?")) window.location.href = "/Anggota/masuk";
        else window.location.href = routes[page] || "/Anggota/" + page;
    }
</script>
</body>
</html>