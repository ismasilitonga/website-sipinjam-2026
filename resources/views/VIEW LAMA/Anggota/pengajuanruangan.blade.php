<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <title>Pengajuan Ruangan - SC-Space</title>
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
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); filter: brightness(1.1) contrast(1.1);
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
        .dashboard-menu.active { background: #c9dcec; font-weight: 700; border-radius: 0; width: 100%; }

        .menu-title { padding: 12px 20px; font-weight: 580; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; }
        .menu-title:hover { background: #c9dcec; }
        .menu-title span { display: flex; align-items: center; gap: 12px; flex: 1; }
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
            padding: 40px 20px; position: relative; z-index: 10;
            display: flex; flex-direction: column; align-items: center;
        }
        
        .main-content-container {
            width: 100%; max-width: 1200px;
            display: flex; flex-direction: column; align-items: center;
        }

        .form-container {
            background: white; border-radius: 30px;
            padding: 40px; width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            height: fit-content;
        }
        .form-container h2 { margin-bottom: 25px; text-align: center; color: #2f7ea1; font-size: 26px; font-weight: 700; text-transform: uppercase;}

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px; }
        .full-width { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-label { font-size: 14px; color: #555; font-weight: 500; }
        .form-input { padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 12px; font-size: 14px; background: #f8f9fa; transition: 0.3s; font-family: 'Poppins'; }
        .form-input:focus { border-color: #2f7ea1; background: white; outline: none; }

        .room-selection { background: #f8f9fa; padding: 15px; border-radius: 15px; border: 2px solid #e1e5e9; }
        .room-list { display: flex; gap: 12px; overflow-x: auto; padding: 10px 5px; scrollbar-width: none; }
        .room-chip { flex-shrink: 0; padding: 10px 20px; background: white; border: 2px solid #e1e5e9; border-radius: 20px; cursor: pointer; transition: 0.3s; font-size: 13px; font-weight: 500; white-space: nowrap; }
        .room-chip.selected { background: linear-gradient(135deg, #2f7ea1, #1e5a7a); color: white; border-color: #2f7ea1; }

        .button-group { display: flex; justify-content: center; gap: 30px; margin-top: 30px; }
        .btn { padding: 14px 30px; border: none; border-radius: 12px; cursor: pointer; font-weight: 700; font-size: 16px; min-width: 180px; transition: 0.3s; }
        .btn-secondary { background: #bdc3c7; color: white; }
        .btn-primary { background: #2f7ea1; color: white; }
        .btn-primary:disabled { background: #96b9cc; cursor: not-allowed; }

        @media (max-width: 760px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .content-wrapper { margin-left: 0; width: 100%; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
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
                        <p>{{ auth()->user()->nim ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="main-content-container">
                <div class="form-container">
                    <h2>Form Pengajuan Peminjaman</h2>
                    
                    <form id="pengajuanForm" action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ruangan" id="selectedRoomInput">

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-input" value="{{ auth()->user()->name }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-input" value="{{ auth()->user()->nim }}" disabled>
                            </div>

                            <div class="form-group">
                                <label class="form-label">No. HP (WhatsApp)</label>
                                <input type="tel" name="no_hp" id="inputHP" class="form-input" required placeholder="08...">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Ketua Ormawa</label>
                                @php
                                    $ormawaUser = auth()->user()->ormawa; 
                                    $daftarKetua = [
                                        'DPM' => 'Ketua DPM', 'BEM' => 'Ketua BEM', 'HMTI' => 'Ketua HMTI',
                                        'HME' => 'Andi Hermawan (Ketua HME)', 'HMM' => 'Ketua HMM', 'HMMB' => 'Ketua HMMB',
                                        'PD El-Shaddai' => 'Ketua PD El-Shaddai', 'IMMPB' => 'Ketua IMMPB',
                                        'MENWA' => 'Komandan MENWA', 'MAPALA' => 'Ketua MAPALA', 'PEC' => 'President of PEC',
                                        'KUAS' => 'Ketua KUAS', 'BLUG' => 'Ketua BLUG', 'LPM Paradigma' => 'Pimpinan Umum Paradigma',
                                        'ENERGI' => 'Ketua ENERGI', 'KOP' => 'Ketua KOP'
                                    ];
                                    $namaKetua = $daftarKetua[$ormawaUser] ?? "Ketua " . ($ormawaUser ?? 'Belum Terdaftar');
                                @endphp
                                <input type="text" class="form-input" value="{{ $namaKetua }}" readonly>
                                <input type="hidden" name="ketua_penyetuju" value="{{ $namaKetua }}">
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Pilih Ruangan</label>
                                <div class="room-selection">
                                    <div class="room-list">
                                        <div class="room-chip">Sc Lt.1 RB</div>
                                        <div class="room-chip">Sc Lt.1 HMJ</div>
                                        <div class="room-chip">Sc Lt.1 UKM</div>
                                        <div class="room-chip">Sc Lt.2A</div>
                                        <div class="room-chip">Sc Lt.2B</div>
                                        <div class="room-chip">Sc Lt.2C</div>
                                        <div class="room-chip">Sc Lt.2D</div>
                                        <div class="room-chip">Sc Lt.3A</div>
                                        <div class="room-chip">Sc Lt.3B</div>
                                        <div class="room-chip">Sc Lt.3C</div>
                                        <div class="room-chip">Sc Lt.3D</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">PIC Ruangan</label>
                                <input type="text" id="picRuangan" class="form-input" readonly placeholder="Pilih ruangan...">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estimasi Pengguna</label>
                                <input type="number" name="estimasi" class="form-input" min="1" required placeholder="0">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal Penggunaan</label>
                                <input type="text" name="tanggal_penggunaan" id="tanggal_penggunaan" class="form-input" readonly placeholder="Pilih Tanggal...">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Waktu (Mulai - Selesai)</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="time" name="jamMulai" class="form-input" style="flex: 1;">
                                    <input type="time" name="jamSelesai" class="form-input" style="flex: 1;">
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Keperluan Peminjaman</label>
                                <textarea name="keperluan" class="form-input" style="height: 100px; resize: none;" required placeholder="Jelaskan tujuan peminjaman..."></textarea>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnAjukan" disabled>Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

        flatpickr("#tanggal_penggunaan", { dateFormat: "Y-m-d", minDate: "today", onChange: validateForm });

        const mapLantai = { "Sc Lt.1": "Rey Sastria Harianja", "Sc Lt.2": "Akyasa Fikiri Ramadhan", "Sc Lt.3": "Ahmad Fauzan" };

        document.querySelectorAll('.room-chip').forEach(chip => {
            chip.onclick = function() {
                document.querySelectorAll('.room-chip').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                const roomName = this.innerText;
                const identitasLantai = roomName.substring(0, 7); 
                const picName = mapLantai[identitasLantai] || "PIC Umum";
                document.getElementById('selectedRoomInput').value = roomName;
                document.getElementById('picRuangan').value = picName;
                validateForm();
            };
        });

        function validateForm() {
            const hp = document.getElementById('inputHP').value;
            const tgl = document.getElementById('tanggal_penggunaan').value;
            const room = document.querySelector('.room-chip.selected');
            document.getElementById('btnAjukan').disabled = !(hp && tgl && room);
        }
        document.getElementById('inputHP').oninput = validateForm;

        function resetForm() {
            document.getElementById('pengajuanForm').reset();
            document.querySelectorAll('.room-chip').forEach(c => c.classList.remove('selected'));
            validateForm();
        }

        document.addEventListener('click', () => {
            document.getElementById("dropdownMenu").classList.remove("active");
            document.getElementById("notifDropdown").classList.remove("active");
        });

        function goToPage(page) {
            const routes = { 'dashboard': '/Anggota/dashboardanggota', 'profile': '/Anggota/detailakun', 'logout': '/Anggota/masuk' };
            if (page === 'logout' && confirm("Logout?")) window.location.href = "/Anggota/masuk";
            else window.location.href = routes[page] || "/Anggota/" + page;
        }
    </script>
</body>
</html>