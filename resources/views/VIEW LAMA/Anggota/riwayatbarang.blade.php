<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Validasi Peminjaman Barang - Student Center</title>
    <style>

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
        #clock-display { color: white; font-weight: 500; font-size: 15px; padding: 5px 12px; font-family: monospace; }
        
        /* --- NOTIFIKASI --- */
        .notif-container { position: relative; }
        .fa-bell { color: #000000; font-size: 22px; cursor: pointer; transition: 0.2s; padding: 8px; }
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
        .notif-header { padding: 12px 15px; background: #f8fbff; border-bottom: 1px solid #eee; font-weight: 600; font-size: 14px; color: #2f7ea1; text-align: left; }
        .notif-body { max-height: 300px; overflow-y: auto; }
        .notif-empty { padding: 30px; text-align: center; color: #94a3b8; font-style: italic; font-size: 13px; }
        .notif-item { padding: 15px; border-bottom: 1px solid #f1f5f9; cursor: pointer; display: flex; gap: 12px; transition: 0.2s; text-align: left; }

        /* --- PROFILE DROPDOWN --- */
        .profile { position: relative; cursor: pointer; }
        .profile-circle {
            width: 40px; height: 40px; background: #ffffff; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
        }
        .profile-circle i { color: #2f7ea1; font-size: 22px; }
        .dropdown {
            position: absolute; top: 56px; right: 0; width: 200px;
            background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(-10px); pointer-events: none; transition: 0.25s; z-index: 1001;
            padding: 8px 0;
        }
        .dropdown.active { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .dropdown div { padding: 12px 20px; cursor: pointer; display: flex; align-items: center; gap: 12px; color: #333; font-size: 15px; font-weight: 500; transition: 0.2s; }
        .dropdown div:hover { background: #f0f8ff; color: #2f7ea1; }

        /* --- SIDEBAR --- */
        .main { display: flex; }
        .sidebar {
            width: 235px; background: #E4F0FF;
            position: fixed; top: 70px; height: calc(100vh - 70px + 12px);
            left: 0; display: flex; flex-direction: column; z-index: 999;
        }
        .sidebar-content { flex: 1; overflow-y: auto; }
        .menu-group { border-bottom: 1px solid #cbd9e6; }
        .dashboard-menu { padding: 12px 18px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 12px; text-decoration: none; color: black; transition: 0.2s; font-size: 16px; }
        .dashboard-menu:hover { background: #c9dcec; }
        .menu-title { padding: 12px 18px; font-weight: 580; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; font-size: 16px; }
        .menu-title:hover { background: #c9dcec; }
        .menu-title span { display: flex; align-items: center; gap: 12px; flex: 1; }
        .submenu { display: none; padding-left: 25px; }
        .submenu a { display: block; padding: 6px 14px; text-decoration: none; color: black; margin: 2px 0; border-radius: 6px; transition: 0.2s; font-size: 16px; }
        .submenu a:hover, .submenu a.active { background: #cfe3f1; }
        .menu-arrow.rotate { transform: rotate(180deg); }

        .sidebar-footer {
            background: white; padding: 14px; border-top: 1px solid #cbd9e6;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        .profile-info { display: flex; align-items: center; gap: 10px; cursor: pointer; transition: 0.2s; border-radius: 6px; }
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
            background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%);
            padding: 40px 20px; display: flex; flex-direction: column; align-items: center; 
        }
        .page-header-section { width: 100%; max-width: 1000px; margin-bottom: 25px; display: flex; align-items: center; gap: 18px; }
        .back-arrow-link { text-decoration: none; color: #1e293b; font-size: 26px; display: flex; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #1e293b; text-transform: uppercase; }

        .riwayat-container { width: 100%; max-width: 1000px; }
        .table-riwayat { 
            width: 100%; border-collapse: separate; border-spacing: 0; 
            text-align: center; min-width: 750px; background: white; 
            border-radius: 15px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); 
            overflow: hidden; border: none;
        }
        .table-riwayat thead th { background: #abc7e4 !important; color: #334155; padding: 20px 15px; font-weight: 600; font-size: 15px; border-bottom: 2px solid #f1f5f9; }
        .table-riwayat tbody td { padding: 22px 15px; font-size: 14px; color: #475569; background: white; border-bottom: 1px solid #f1f5f9; }
        .table-riwayat tbody tr:last-child td { border-bottom: none; }

        .empty-state { padding: 80px 0 !important; color: #94a3b8; font-style: italic; font-size: 14px; background: white; }

        .status-container { display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 500; }
        .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
        .dot-pic { background-color: #ff9800; }
        .dot-approved { background-color: #2196f3; }
        .dot-taken { background-color: #8bc34a; }
        .dot-finish { background-color: #9e9e9e; }

        .btn-action { 
            padding: 8px 16px; border-radius: 10px; border: none; cursor: pointer; 
            font-size: 13px; font-weight: 600; color: white; transition: 0.2s; 
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-detail { background: linear-gradient(140deg, #2f7ea1, #4a9dc3); }
        .btn-checkin { background: #4caf50; margin-left: 5px; }
        .btn-checkout { background: #f44336; margin-left: 5px; }
        .btn-action:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="navbar">
    <div class="nav-left"><div class="logo"></div><b>Student Center</b></div>
    <div class="nav-right">
        <div id="clock-display"></div> 
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
            <a href="javascript:void(0)" class="dashboard-menu" onclick="goToPage('dashboard')">
                <i class="fa-solid fa-gauge" style="width: 20px;"></i> 
                <span>Dashboard</span>
            </a>
            
            <div class="menu-group">
                <div class="menu-title" onclick="toggleMenu(this)">
                    <span><i class="fa-solid fa-boxes-stacked" style="width: 20px;"></i> Inventaris</span>
                    <i class="fa-solid fa-chevron-down menu-arrow"></i>
                </div>
                <div class="submenu">
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
                <div class="submenu" style="display: block;">
                    <a href="javascript:void(0)" onclick="goToPage('peminjaman-ruangan')">Peminjaman Ruangan</a>
                    <a href="javascript:void(0)" class="active" onclick="goToPage('peminjaman-barang')">Peminjaman Barang</a>
                </div>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="profile-info" onclick="goToPage('profile')">
                <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                <div class="profile-details">
                    <h4>{{ Auth::user()->nama ?? 'User' }}</h4>
                    <p>{{ Auth::user()->nim ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="page-header-section">
            <a href="javascript:void(0)" onclick="window.history.back()" class="back-arrow-link"><i class="fa-solid fa-chevron-left"></i></a>
            <h1 class="page-title">Validasi Peminjaman Barang</h1>
        </div>

        <div class="riwayat-container">
            <table class="table-riwayat">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat_barang ?? [] as $data)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y') }}</td>
                        <td>{{ $data->nama_barang }}</td>
                        <td>{{ $data->jumlah }} pcs</td>
                        <td>
                            <div class="status-container">
                                @if($data->status == 'menunggu_pic')
                                    <span class="dot dot-pic"></span> Menunggu PIC
                                @elseif($data->status == 'disetujui')
                                    <span class="dot dot-approved"></span> Disetujui
                                @elseif($data->status == 'diambil')
                                    <span class="dot dot-taken"></span> Diambil
                                @elseif($data->status == 'selesai')
                                    <span class="dot dot-finish"></span> Selesai
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="/Anggota/detailbarang/{{ $data->id }}" class="btn-action btn-detail">Detail <i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i></a>
                            @if($data->status == 'disetujui')
                                <button class="btn-action btn-checkin" onclick="location.href='/Anggota/ambilbarang/{{ $data->id }}'">Ambil</button>
                            @elseif($data->status == 'diambil')
                                <button class="btn-action btn-checkout" onclick="location.href='/Anggota/kembalibarang/{{ $data->id }}'">Kembali</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fa-solid fa-box-open" style="display: block; font-size: 30px; margin-bottom: 10px; opacity: 0.5;"></i>
                            Belum ada riwayat peminjaman barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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

    let notifications = []; 
    function updateNotifUI() {
        const badge = document.getElementById('notifBadge');
        const body = document.getElementById('notifBody');
        if (notifications.length > 0) {
            badge.innerText = notifications.length;
            badge.classList.add('active-badge'); 
            body.innerHTML = notifications.map(n => `<div class="notif-item"><div class="notif-content"><p>${n.text}</p><span>${n.time}</span></div></div>`).join('');
        } else {
            badge.classList.remove('active-badge');
            body.innerHTML = '<div class="notif-empty">Tidak ada notifikasi baru.</div>';
        }
    }
    updateNotifUI();

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
        submenu.style.display = (submenu.style.display === "block") ? "none" : "block";
        if(arrow) arrow.classList.toggle("rotate");
    }

    document.addEventListener('click', function () {
        document.getElementById("dropdownMenu").classList.remove("active");
        document.getElementById("notifDropdown").classList.remove("active");
    });

    function goToPage(page) {
        const routes = {
            'dashboard': '/Anggota/dashboardanggota',
            'profile': '/Anggota/detailakun',
            'daftar-ruangan': '/Anggota/daftarruangan',
            'daftar-barang': '/Anggota/daftarbarang',
            'handover': '/Anggota/handover',
            'buat-laporan': '/Anggota/laporinsiden',
            'riwayat-laporan': '/Anggota/riwayatinsiden',
            'peminjaman-ruangan': '/Anggota/riwayatruangan',
            'peminjaman-barang': '/Anggota/riwayatbarang'
        };
        if (page === 'logout') { 
            if (confirm("Yakin ingin logout?")) window.location.href = "/Anggota/masuk"; 
        } else { 
            window.location.href = routes[page] || ("/Anggota/" + page); 
        }
    }
</script>
</body>
</html>