<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Dashboard Ketua - SC-Space</title>

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins', sans-serif; background: white; overflow-x:hidden; }

        /* NAVBAR */
        .navbar {
            height:70px;
            background:#2f7ea1;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 25px;
            position:fixed;
            top:0;
            left:0;
            right:0;
            z-index:1000;
        }

        .nav-left { display:flex; align-items:center; gap:12px; color:white; }

        /* Gaya Teks Logo SC-Space Mengkilap */
        .brand-text {
            font-size: 22px;
            font-weight: 700;
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

        .nav-right { display:flex; align-items:center; gap:20px; }
        #clock-display { color:white; font-size:15px; font-family:monospace; }

        .notif-container { position: relative; }
        .nav-right .fa-bell { color: black; font-size: 22px; cursor: pointer; transition: 0.2s; padding: 8px; }
        
        .notif-badge {
            position:absolute; top:4px; right:1px;
            background:#ff4d4d; color:white; font-size:10px;
            width:16px; height:16px; border-radius:50%;
            display:none; align-items:center; justify-content:center;
        }
        .notif-badge.active-badge { display:flex; }

        .notif-dropdown {
            position:absolute; top:60px; right:15px; width:340px;
            background:#fff; border-radius:16px; box-shadow:0 15px 35px rgba(0,0,0,0.12);
            opacity:0; transform:translateY(-10px); pointer-events:none; transition:0.25s;
        }
        .notif-dropdown.active { opacity:1; transform:translateY(0); pointer-events:auto; }
        .notif-header { padding:14px 18px; font-weight:600; font-size:16px; color:#2f7ea1; border-bottom:1px solid #eee; }
        .notif-body { padding:25px 20px; text-align:center; color: #666; }

        /* PROFILE DROPDOWN */
        .profile { position:relative; cursor:pointer; }
        .profile-circle {
            width:40px; height:40px; background:#fff; border-radius:50%;
            display:flex; justify-content:center; align-items:center;
        }
        .profile-circle i { color:#2f7ea1; font-size:22px; }
        .dropdown {
            position:absolute; top:56px; right:0; width:170px;
            background:#fff; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.15);
            opacity:0; transform:translateY(-10px); pointer-events:none; transition:0.25s; z-index: 1001;
        }
        .dropdown.active { opacity:1; transform:translateY(0); pointer-events:auto; }
        .dropdown div { padding:10px; display:flex; gap:8px; align-items:center; cursor:pointer; transition: 0.2s; color: #333; font-size: 14px; }
        .dropdown div:hover { background:#f0f8ff; }

        /* MAIN & SIDEBAR */
        .main { display:flex; }
        .sidebar {
            width:235px; background:#E4F0FF;
            position:fixed; top:70px; height:calc(100vh - 70px + 12px);
            left: 0; display:flex; flex-direction:column; z-index: 999;
        }

        .sidebar-content { flex:1; overflow-y:auto; }

        .dashboard-menu {
            padding:12px 16px; display:flex; align-items:center; gap:11px;
            font-weight:600; color:black; text-decoration:none; transition:0.2s;
        }
        .dashboard-menu:hover, .dashboard-menu.active { background:#c9dcec; }

        .menu-group { border-bottom:1px solid #cbd9e6; }
        .menu-title {
            padding:12px 16px; display:flex; align-items:center; justify-content:space-between;
            cursor:pointer; transition:0.2s;
        }
        .menu-title span { display:flex; align-items:center; gap:11px; font-weight:600; }
        .menu-title:hover { background:#c9dcec; }

        .submenu { display:none; padding-left:25px; }
        .submenu a {
            display:block; padding:8px 14px; text-decoration:none;
            color:black; margin: 2px 0; border-radius:6px; transition:0.2s; font-size: 14px;
        }
        .submenu a:hover { background:#cfe3f1; }

        /* SIDEBAR FOOTER */
        .sidebar-footer {
            background:white; padding:14px 14px; border-top:1px solid #cbd9e6;
            box-shadow:0 -2px 10px rgba(0,0,0,0.05);
        }
        .profile-info { display:flex; align-items:center; gap:10px; cursor:pointer; }
        .profile-avatar {
            width:35px; height:35px; background:linear-gradient(135deg,#2f7ea1,#4a9dc3);
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            color:white; font-weight:590; font-size: 16px; flex-shrink: 0; text-transform: uppercase;
        }
        .profile-details h4 { font-size:14px; font-weight: 600; color: #1e293b; margin:0 0 2px 0; }
        .profile-details p { font-size:12px; color: #6b7280; margin:0; }

        /* CONTENT */
        .content-wrapper {
            margin-left:235px; margin-top:70px;
            width:calc(100% - 235px); min-height:100vh;
            background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%);
            padding: 25px; position: relative; z-index: 10;
        }

        .content { padding: 5px; }
        .content h2 { font-size: 24px; font-weight: 700; color: #1e293b; text-transform: uppercase; }

    </style>
</head>

<body>

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
                <div class="notif-body">
                    <div class="notif-empty">Tidak ada notifikasi baru.</div>
                </div>
            </div>
        </div>

        <div class="profile" onclick="toggleProfileDropdown(event)">
            <div class="profile-circle">
                <i class="fa-solid fa-user"></i>
            </div>

            <div id="dropdownMenu" class="dropdown">
                <div onclick="goToPage('profile')">
                    <i class="fa-solid fa-user"></i> Profil Saya
                </div>
                <div onclick="goToPage('logout')">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main">
    <div class="sidebar">
        <div class="sidebar-content">
            <a href="javascript:void(0)" onclick="goToPage('dashboard')" class="dashboard-menu active">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>

            <a href="javascript:void(0)" onclick="goToPage('pengajuan')" class="dashboard-menu">
                <i class="fa-solid fa-inbox"></i> Pengajuan Masuk
            </a>

            <div class="menu-group">
                <div class="menu-title" onclick="toggleMenu(this)">
                    <span><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="submenu">
                    <a href="javascript:void(0)" onclick="goToPage('riwayat-ruangan')">Peminjaman Ruangan</a>
                    <a href="javascript:void(0)" onclick="goToPage('riwayat-barang')">Peminjaman Barang</a>
                </div>
            </div>

            <div class="menu-group">
                <div class="menu-title" onclick="toggleMenu(this)">
                    <span><i class="fa-solid fa-boxes-stacked"></i> Inventaris</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="submenu">
                    <a href="javascript:void(0)" onclick="goToPage('ruangan')">Daftar Ruangan</a>
                    <a href="javascript:void(0)" onclick="goToPage('barang')">Daftar Barang</a>
                </div>
            </div>

            <div class="menu-group">
                <div class="menu-title" onclick="toggleMenu(this)">
                    <span><i class="fa-solid fa-triangle-exclamation"></i> Laporan</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="submenu">
                    <a href="javascript:void(0)" onclick="goToPage('laporan-riwayat')">Riwayat Laporan</a>
                    <a href="javascript:void(0)" onclick="goToPage('laporan-masuk')">Laporan Masuk</a>
                </div>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="profile-info" onclick="goToPage('profile')">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}
                </div>
                <div class="profile-details">
                    <h4>{{ auth()->user()->name ?? 'Rindiani Ketua' }}</h4>
                    <p>{{ auth()->user()->nim ?? '3312301054' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="content">
            <h2>Dashboard Ketua</h2>
        </div>
    </div>
</div>

<script>
function toggleMenu(el){
    const submenu = el.nextElementSibling;
    submenu.style.display = submenu.style.display === "block" ? "none" : "block";
}

function toggleNotifDropdown(e){
    e.stopPropagation();
    document.getElementById("notifDropdown").classList.toggle("active");
    document.getElementById("dropdownMenu").classList.remove("active");
}

function toggleProfileDropdown(e){
    e.stopPropagation();
    document.getElementById("dropdownMenu").classList.toggle("active");
    document.getElementById("notifDropdown").classList.remove("active");
}

document.addEventListener('click', function(){
    document.getElementById("notifDropdown").classList.remove("active");
    document.getElementById("dropdownMenu").classList.remove("active");
});

function updateClock() {
    const now = new Date();
    let h = String(now.getHours()).padStart(2,'0');
    let m = String(now.getMinutes()).padStart(2,'0');
    let s = String(now.getSeconds()).padStart(2,'0');
    document.getElementById('clock-display').textContent = `${h}:${m}:${s}`;
}

updateClock();
setInterval(updateClock, 1000);

function updateNotifBadge(count) {
    const badge = document.getElementById("notifBadge");
    if (count > 0) {
        badge.textContent = count;
        badge.classList.add("active-badge");
    } else {
        badge.classList.remove("active-badge");
    }
}
updateNotifBadge(0);

function goToPage(page) {
    const routes = {
        'dashboard': '/ketua/dashboard',
        'pengajuan': '/ketua/pengajuan',
        'profile': '/Ketua/detailakun',
        'logout': '/logout',
        'riwayat-ruangan': '/ketua/riwayat-ruangan',
        'riwayat-barang': '/ketua/riwayat-barang',
        'ruangan': '/ketua/ruangan',
        'barang': '/ketua/barang',
        'laporan-riwayat': '/ketua/laporan-riwayat',
        'laporan-masuk': '/ketua/laporan-masuk'
    };

    if (page === 'logout') {
        if (confirm("Yakin ingin logout?")) {
            window.location.href = routes[page];
        }
    } else {
        window.location.href = routes[page] || "#";
    }
}
</script>

</body>
</html>