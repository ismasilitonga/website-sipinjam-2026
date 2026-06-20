<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Daftar Ruangan - SC-Space</title>
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
        .notif-header { padding: 12px 15px; background: #f8fbff; border-bottom: 1px solid #eee; font-weight: 600; font-size: 14px; color: #2f7ea1; text-align: left; }
        .notif-body { max-height: 300px; overflow-y: auto; }
        .notif-empty { padding: 30px; text-align: center; color: #94a3b8; font-style: italic; font-size: 13px; }
        
        /* --- PROFILE --- */
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
        .content-wrapper {
            margin-left: 235px; margin-top: 70px;
            width: calc(100% - 235px); min-height: 100vh;
            background: #bcd3e3; padding: 25px;
        }
        .content-box { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 8px 25px rgba(0,0,0,0.05); min-height: 80vh; }
        .content-box h2 { color: #272929; margin-bottom: 20px; font-weight: 700; font-size: 24px; }
        
        .search-box { position: relative; margin-bottom: 25px; max-width: 450px; }
        .search-box i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .search-box input { width: 100%; padding: 12px 12px 12px 50px; border: 2px solid #e2e8f0; border-radius: 30px; outline: none; font-size: 15px; transition: 0.3s; }
        .search-box input:focus { border-color: #2f7ea1; }

        .filter { display: flex; gap: 12px; margin-bottom: 30px; overflow-x: auto; padding-bottom: 8px; }
        .filter-btn { padding: 10px 22px; border: none; background: #f1f5f9; border-radius: 25px; cursor: pointer; transition: 0.3s; font-weight: 600; font-size: 14px; white-space: nowrap; color: #64748b; }
        .filter-btn.active { background: linear-gradient(135deg, #2f7ea1, #4a9dc3) !important; color: white; box-shadow: 0 4px 12px rgba(47, 126, 161, 0.3); }
        
        .room-wrapper { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        .room { background: #f8fbff; border: 1px solid #e2e8f0; padding: 20px; border-radius: 18px; transition: 0.3s; display: flex; flex-direction: column; justify-content: space-between; height: 100%; }
        .room:hover { transform: translateY(-8px); box-shadow: 0 20px 30px rgba(0,0,0,0.08); border-color: #2f7ea1; }
        .room-info strong { display: block; margin-bottom: 8px; font-size: 16px; font-weight: 700; color: #1e293b; }
        .room-info small { color: #64748b; font-size: 14px; line-height: 1.5; display: block; }
        
        .detail-btn { margin-top: 20px; background: white; border: 2px solid #2f7ea1; color: #2f7ea1; padding: 10px; border-radius: 12px; cursor: pointer; font-weight: 700; font-size: 13px; transition: 0.3s; text-align: center; text-decoration: none; }
        .detail-btn:hover { background: linear-gradient(135deg, #2f7ea1, #4a9dc3); color: white !important; border-color: transparent; }
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
                <div class="submenu" style="display: block;">
                    <a href="javascript:void(0)" class="active" onclick="goToPage('daftarruangan')">Daftar Ruangan</a>
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
        <div class="content-box">
            <h2>DAFTAR RUANGAN</h2>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="roomSearch" placeholder="Cari Nama Ruangan...">
            </div>
            <div class="filter">
                <button class="filter-btn active" data-lantai="all">Semua</button>
                <button class="filter-btn" data-lantai="1">Lantai 1</button>
                <button class="filter-btn" data-lantai="2">Lantai 2</button>
                <button class="filter-btn" data-lantai="3">Lantai 3</button>
            </div>

            <div class="room-wrapper" id="roomContainer">
                <div class="room" data-lantai="1">
                    <div class="room-info"><strong>Student Center Lt.1 RB</strong><small>Ruang Badan Eksekutif Mahasiswa</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="1">
                    <div class="room-info"><strong>Student Center Lt.1 HMJ</strong><small>Himpunan Mahasiswa Jurusan</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="1">
                    <div class="room-info"><strong>Student Center Lt.1 UKM</strong><small>Unit Kegiatan Mahasiswa</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="2">
                    <div class="room-info"><strong>Student Center Lt.2A</strong><small>Ruang Kegiatan A</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="2">
                    <div class="room-info"><strong>Student Center Lt.2B</strong><small>Ruang Kegiatan B</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="2">
                    <div class="room-info"><strong>Student Center Lt.2C</strong><small>Ruang Kegiatan C</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="2">
                    <div class="room-info"><strong>Student Center Lt.2D</strong><small>Ruang Kegiatan D</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="3">
                    <div class="room-info"><strong>Student Center Lt.3A</strong><small>Ruang Kegiatan A</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="3">
                    <div class="room-info"><strong>Student Center Lt.3B</strong><small>Ruang Kegiatan B</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="3">
                    <div class="room-info"><strong>Student Center Lt.3C</strong><small>Ruang Kegiatan C</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
                <div class="room" data-lantai="3">
                    <div class="room-info"><strong>Student Center Lt.3D</strong><small>Ruang Kegiatan D</small></div>
                    <a href="#" class="detail-btn">Lihat Detail ></a>
                </div>
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
    const filterBtns = document.querySelectorAll(".filter-btn");
    const rooms = document.querySelectorAll(".room");
    const searchInput = document.getElementById("roomSearch");

    function filterRooms() {
        const keyword = searchInput.value.toLowerCase();
        const activeFilter = document.querySelector(".filter-btn.active").dataset.lantai;
        rooms.forEach(room => {
            const text = room.innerText.toLowerCase();
            const lantai = room.dataset.lantai;
            const matchSearch = text.includes(keyword);
            const matchFilter = (activeFilter === "all" || lantai === activeFilter);
            room.style.display = (matchSearch && matchFilter) ? "flex" : "none";
        });
    }

    filterBtns.forEach(btn => {
        btn.onclick = function() {
            filterBtns.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            filterRooms();
        };
    });
    
    if (searchInput) searchInput.oninput = filterRooms;

    document.addEventListener('click', function () {
        document.getElementById("dropdownMenu").classList.remove("active");
        document.getElementById("notifDropdown").classList.remove("active");
    });

    function goToPage(page) {
        const routes = {
            'dashboard': '/Anggota/dashboardanggota', 'profile': '/Anggota/detailakun',
            'daftarruangan': '/Anggota/daftarruangan', 'daftarbarang': '/Anggota/daftarbarang',
            'handover': '/Anggota/handover', 'laporinsiden': '/Anggota/laporinsiden',
            'riwayatinsiden': '/Anggota/riwayatinsiden', 'riwayatruangan': '/Anggota/riwayatruangan',
            'riwayatbarang': '/Anggota/riwayatbarang', 'logout': '/Anggota/masuk'
        };
        if (page === 'logout') { if (confirm("Yakin ingin logout?")) window.location.href = routes[page]; }
        else { window.location.href = routes[page] || ("/Anggota/" + page); }
    }
</script>
</body>
</html>