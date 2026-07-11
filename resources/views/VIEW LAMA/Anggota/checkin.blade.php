<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Form Check In - Student Center</title>
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
        .logo {
            width: 70px; height: 88px;
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: 223%; background-position: 50% 60%; background-repeat: no-repeat;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        #clock-display { color: white; font-weight: 500; font-size: 15px; padding: 5px 12px; font-family: monospace; }
        
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
        .dashboard-menu { padding: 12px 18px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 15px; text-decoration: none; color: black; transition: 0.2s; font-size: 16px; }
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
            background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%);
            padding: 40px 20px; display: flex; justify-content: center; align-items: flex-start;
        }

        .card { background: white; width: 100%; max-width: 500px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px; }
        h2 { font-size: 20px; font-weight: 700; margin-bottom: 20px; color: #1e293b; border-left: 5px solid #2f7ea1; padding-left: 15px; }
        .form-group { margin-bottom: 20px; position: relative; }
        label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        
        .input-style { 
            width: 100%; height: 48px; padding: 12px 15px; 
            border: 2px solid #e2e8f0; border-radius: 12px; 
            font-size: 14px; outline: none; background: #f8fafc; transition: 0.3s;
            display: flex; align-items: center; justify-content: space-between; 
            cursor: pointer; font-family: 'Poppins', sans-serif;
        }
        .input-style:focus { border-color: #2f7ea1; background: white; }

        .select-container { position: relative; width: 100%; }
        .dropdown-box {
            position: absolute; top: 100%; left: 0; right: 0;
            background: white; border: 2px solid #e2e8f0; border-top: none;
            border-radius: 0 0 12px 12px; z-index: 2000;
            display: none; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .dropdown-box.show { display: block; }
        .item-row { 
            padding: 12px 15px; font-size: 14px; cursor: pointer; 
            color: #333; transition: 0.2s; border-bottom: 1px solid #f1f5f9;
            text-align: left;
        }
        .item-row:last-child { border-bottom: none; }
        .item-row:hover { background-color: #2f7ea1; color: white; }

        .btn-container { display: flex; gap: 10px; margin-top: 30px; }
        .btn { 
            flex: 1; height: 48px; border-radius: 12px; font-weight: 600; 
            cursor: pointer; border: none; font-size: 14px; transition: 0.2s; 
            text-align: center; text-decoration: none; display: flex; 
            align-items: center; justify-content: center; 
        }
        .btn-batal { background: #8F8F8F; color: white; }
        .btn-checkin { background: linear-gradient(135deg, #2f7ea1, #1e5a7a); color: white; }
        .btn:hover { transform: translateY(-2px); filter: brightness(1.1); }

        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(8, 24, 40, 0.55); display: none; justify-content: center; align-items: center; 
            z-index: 2000; backdrop-filter: blur(8px);
        }
        .modal-box {
            background: #ffffff; width: min(520px, 95%); padding: 30px 40px; border-radius: 40px; 
            text-align: center; box-shadow: 0 22px 65px rgba(15, 23, 42, 0.18);
            animation: slideUp 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative; 
        }
        @keyframes slideUp { 0% { transform: translateY(30px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .modal-icon-circle { width: 75px; height: 75px; background: #e7f3e9; color: #2f7ea1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px; }
        .modal-btn-primary { background: #b3d6be; color: #102b17; border: none; padding: 12px 70px; border-radius: 999px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.2s; }

        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .content-wrapper { margin-left: 0; width: 100%; } }
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
                <a href="#" class="dashboard-menu" onclick="goToPage('dashboard')"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                
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
                        <i class="fa-solid fa-chevron-down menu-arrow rotate"></i>
                    </div>
                    <div class="submenu" style="display: block;">
                        <a href="javascript:void(0)" class="active" onclick="goToPage('riwayatruangan')">Peminjaman Ruangan</a>
                        <a href="javascript:void(0)" onclick="goToPage('riwayatbarang')">Peminjaman Barang</a>
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
            <div class="card">
                <h2>Form Check In</h2>
                <form id="checkinForm">
                    <div class="form-group">
                        <label>Status Kehadiran</label>
                        <div class="select-container">
                            <div class="input-style" onclick="toggleDropdownBox(event)">
                                <span id="hadirLabel">Pilih Status</span>
                                <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #000;"></i>
                            </div>
                            <div class="dropdown-box" id="hadirBox">
                                <div class="item-row" onclick="pickHadir('Hadir')">Hadir</div>
                            </div>
                            <input type="hidden" id="hadirInput" name="status_hadir" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Waktu Check In</label>
                        <input type="text" id="jamOtomatis" class="input-style" readonly style="cursor: default;">
                    </div>

                    <div class="form-group">
                        <label>Upload KTP (Syarat Ambil Kunci)</label>
                        <input type="file" id="fileInput" class="input-style" accept="image/*" style="padding-top: 10px;">
                        <small style="color: #94a3b8; font-size: 11px;">*Wajib menunjukkan kartu asli ke Pamdal</small>
                    </div>

                    <div class="btn-container">
                        <button type="button" class="btn btn-batal" onclick="actionBatal()">Batal</button>
                        <button type="button" class="btn btn-checkin" onclick="validateAndShow()">Check In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modalNotif">
        <div class="modal-box">
            <div class="modal-icon-circle"><i class="fa-solid fa-check"></i></div>
            <h3>Check In Berhasil !</h3>
            <p>Silahkan gunakan ruangan sesuai jadwal peminjaman. Selamat berkegiatan!</p>
            <div class="modal-footer">
                <button class="modal-btn-primary" onclick="goToRiwayat()">Oke</button>
            </div>
        </div>
    </div>

 <script>
    function updateAllClocks() {
        const sekarang = new Date();
        const jam = String(sekarang.getHours()).padStart(2, '0');
        const menit = String(sekarang.getMinutes()).padStart(2, '0');
        const detik = String(sekarang.getSeconds()).padStart(2, '0');
        const waktuString = `${jam}:${menit}:${detik}`;

        const navDisplay = document.getElementById('clock-display');
        if (navDisplay) navDisplay.innerText = waktuString;

        const formDisplay = document.getElementById('jamOtomatis');
        if (formDisplay) formDisplay.value = waktuString;
    }
    setInterval(updateAllClocks, 1000);
    updateAllClocks();

    function toggleDropdownBox(e) {
        e.stopPropagation();
        document.getElementById('hadirBox').classList.toggle('show');
    }

    function pickHadir(val) {
        document.getElementById('hadirLabel').innerText = val;
        document.getElementById('hadirLabel').style.color = "#333";
        document.getElementById('hadirInput').value = val;
        document.getElementById('hadirBox').classList.remove('show');
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

    function validateAndShow() {
        const hadir = document.getElementById('hadirInput').value;
        if(!hadir) { 
            alert("Mohon pilih status kehadiran!"); 
            return; 
        }
        document.getElementById('modalNotif').style.display = 'flex';
    }

    function goToRiwayat() { window.location.href = "/Anggota/riwayatruangan"; }
    function actionBatal() { window.history.back(); }

    document.onclick = () => {
        const menu = document.getElementById("dropdownMenu");
        if(menu) menu.classList.remove("active");
        const notif = document.getElementById("notifDropdown");
        if(notif) notif.classList.remove("active");
        const box = document.getElementById('hadirBox');
        if(box) box.classList.remove('show');
    };

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
            'peminjaman-barang': '/Anggota/riwayatbarang'
        };
        if (page === 'logout' && confirm("Yakin ingin logout?")) window.location.href = "/Anggota/masuk";
        else if (page !== 'logout') window.location.href = routes[page] || "/Anggota/" + page;
    }
 </script>
</body>
</html>