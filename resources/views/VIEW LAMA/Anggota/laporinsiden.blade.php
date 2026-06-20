<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Lapor Insiden - SC-Space</title>
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
            padding: 40px 20px; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 10;
        }

        .main-content-container {
            width: 100%; max-width: 800px;
            display: flex; flex-direction: column; align-items: flex-start;
        }

        .page-header-section { width: 100%; margin-bottom: 25px; display: flex; align-items: center; gap: 18px; }
        .back-arrow-link { text-decoration: none; color: #1e293b; font-size: 26px; height: 36px; display: flex; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin: 0; }

        .account-card { width: 100%; background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); padding: 25px; }
        .form-card-inner { background: #deecf3; padding: 30px; border-radius: 20px; }
        .form-group { margin-bottom: 20px; position: relative; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; }
        .form-control { width: 100%; padding: 12px 16px; border-radius: 12px; border: 2px solid #e5e7eb; font-size: 15px; background: white; outline: none; }

        .select-container { position: relative; width: 100%; }
        .custom-trigger { cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: white; border-radius: 12px; border: 2px solid #e5e7eb; padding: 12px 16px; }
        .dropdown-box {
            position: absolute; top: 100%; left: 0; right: 0;
            background: white; border: 2px solid #e5e7eb; border-top: none;
            border-radius: 0 0 12px 12px; z-index: 2000;
            max-height: 180px; overflow-y: auto; display: none; padding: 5px 0;
        }
        .dropdown-box.show { display: block; }
        .item-row { padding: 8px 15px; font-size: 14px; cursor: pointer; color: #333; }
        .item-row:hover { background-color: #2f7ea1; color: white; }

        .radio-container { display: flex; gap: 50px; margin-bottom: 25px; justify-content: center; }
        .radio-item { display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 500; font-size: 15px; }
        .file-upload-box { background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px; cursor: pointer; }

        .button-group { display: flex; justify-content: center; gap: 25px; margin-top: 40px; }
        .btn-action { padding: 12px 45px; border-radius: 25px; border: none; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s; }
        .btn-cancel { background-color: #8F8F8F; color: white; }
        .btn-submit { background-color: #e5b3b3; color: rgba(0,0,0,0.4); cursor: not-allowed; pointer-events: none; }
        .btn-submit.active-btn { background-color: #D68A8A; color: black; cursor: pointer; pointer-events: auto; }

        @media (max-width: 760px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .content-wrapper { margin-left: 0; width: 100%; }
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
                    <i class="fa-solid fa-chevron-up menu-arrow"></i>
                </div>
                <div class="submenu" style="display: block;">
                    <a href="javascript:void(0)" class="active" onclick="goToPage('laporinsiden')">Buat Laporan</a>
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
        <div class="main-content-container">
            <div class="page-header-section">
                <a href="javascript:void(0)" onclick="window.history.back()" class="back-arrow-link"><i class="fa-solid fa-chevron-left"></i></a>
                <h1 class="page-title">BUAT LAPORAN</h1>
            </div>

            <div class="account-card">
                <div class="form-card-inner">
                    <form id="formLaporan">
                        <div class="form-group">
                            <label class="form-label">Tanggal Kejadian :</label>
                            <input type="date" id="tglInput" class="form-control" required oninput="validateForm()">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Objek Insiden :</label>
                            <div class="radio-container">
                                <label class="radio-item"><input type="radio" name="objek" value="ruangan" onclick="switchForm('ruangan')" required> Ruangan</label>
                                <label class="radio-item"><input type="radio" name="objek" value="barang" onclick="switchForm('barang')"> Barang</label>
                            </div>
                        </div>

                        <div id="dynamic-fields" style="display: none;">
                            <div id="placeholder-inputs"></div>
                            
                            <div class="form-group">
                                <label class="form-label">Jenis Insiden :</label>
                                <div class="select-container">
                                    <div class="custom-trigger" onclick="openBoxGeneric(event, 'jenisBox')">
                                        <span id="jenisLabel">Pilih Jenis Insiden</span>
                                        <i class="fa-solid fa-chevron-down" style="font-size: 12px;"></i>
                                    </div>
                                    <div class="dropdown-box" id="jenisBox">
                                        <div class="item-row" onclick="pickGeneric('Kehilangan', 'jenisLabel', 'jenisInput', 'jenisBox')">Kehilangan</div>
                                        <div class="item-row" onclick="pickGeneric('Kerusakan', 'jenisLabel', 'jenisInput', 'jenisBox')">Kerusakan</div>
                                    </div>
                                    <input type="hidden" id="jenisInput" name="jenis_insiden" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Deskripsi Kejadian :</label>
                                <input type="text" id="descInput" class="form-control" placeholder="Masukkan Deskripsi" oninput="validateForm()" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Foto Bukti :</label>
                                <div class="file-upload-box" onclick="document.getElementById('file-in').click()">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>&nbsp;&nbsp; <span id="file-name">Pilih foto terkait</span>
                                    <input type="file" id="file-in" accept="image/*" style="display:none" onchange="handleFile(this)">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Pelaku :</label>
                                <div class="select-container">
                                    <div class="custom-trigger" onclick="openBoxGeneric(event, 'pelakuBox')">
                                        <span id="pelakuLabel">Pilih pelaku yang bersangkutan</span>
                                        <i class="fa-solid fa-chevron-down" style="font-size: 12px;"></i>
                                    </div>
                                    <div class="dropdown-box" id="pelakuBox">
                                        <div class="item-row" onclick="pickGeneric('Mahasiswa', 'pelakuLabel', 'pelakuInput', 'pelakuBox')">Mahasiswa</div>
                                        <div class="item-row" onclick="pickGeneric('Pihak Luar', 'pelakuLabel', 'pelakuInput', 'pelakuBox')">Pihak Luar</div>
                                    </div>
                                    <input type="hidden" id="pelakuInput" name="pelaku" required>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="button" class="btn-action btn-cancel" onclick="location.reload()">Batal</button>
                                <button type="submit" id="submitBtn" class="btn-action btn-submit">Kirim Laporan</button>
                            </div>
                        </div>
                    </form>
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
            display.innerText = `${String(sekarang.getHours()).padStart(2, '0')}:${String(sekarang.getMinutes()).padStart(2, '0')}:${String(sekarang.getSeconds()).padStart(2, '0')}`;
        }
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

    const mapPIC = { "Student Center Lt.1": "Rey Sastria Harianja", "Student Center Lt.2": "Akyasa Fikiri Ramadhan", "Student Center Lt.3": "Ahmad Fauzan" };

    function switchForm(type) {
        document.getElementById('dynamic-fields').style.display = 'block';
        const placeholder = document.getElementById('placeholder-inputs');
        if (type === 'ruangan') {
            placeholder.innerHTML = `
                <div class="form-group">
                    <label class="form-label">Nama Ruangan :</label>
                    <div class="select-container">
                        <div class="custom-trigger" onclick="openBoxGeneric(event, 'roomBox')">
                            <span id="roomLabel">Pilih Ruangan</span>
                            <i class="fa-solid fa-chevron-down" style="font-size: 12px;"></i>
                        </div>
                        <div class="dropdown-box" id="roomBox">
                            <div class="item-row" onclick="pickRoom('Student Center Lt.1 RB')">Student Center Lt.1 RB</div>
                            <div class="item-row" onclick="pickRoom('Student Center Lt.1 HMJ')">Student Center Lt.1 HMJ</div>
                            <div class="item-row" onclick="pickRoom('Student Center Lt.2A')">Student Center Lt.2A</div>
                        </div>
                        <input type="hidden" id="roomInput" name="lokasi_ruangan" required>
                    </div>
                </div>`;
        } else {
            placeholder.innerHTML = `
                <div class="form-group">
                    <label class="form-label">Lokasi Kejadian :</label>
                    <input type="text" id="locInput" class="form-control" name="lokasi_kejadian" placeholder="Masukkan Lokasi" required oninput="validateForm()">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Barang :</label>
                    <input type="text" id="barInput" class="form-control" name="nama_barang" placeholder="Masukkan Nama Barang" required oninput="validateForm()">
                </div>`;
        }
        validateForm();
    }

    function openBoxGeneric(e, boxId) {
        e.stopPropagation();
        document.querySelectorAll('.dropdown-box').forEach(box => { if (box.id !== boxId) box.classList.remove('show'); });
        document.getElementById(boxId).classList.toggle('show');
    }

    function pickGeneric(val, labelId, inputId, boxId) {
        document.getElementById(labelId).innerText = val;
        document.getElementById(inputId).value = val;
        document.getElementById(boxId).classList.remove('show');
        validateForm();
    }

    function pickRoom(val) {
        document.getElementById('roomLabel').innerText = val;
        document.getElementById('roomInput').value = val;
        document.getElementById('roomBox').classList.remove('show');
        validateForm();
    }

    function handleFile(input) {
        if (input.files.length > 0) document.getElementById('file-name').innerText = input.files[0].name;
        validateForm();
    }

    function validateForm() {
        const tgl = document.getElementById('tglInput').value;
        const jenis = document.getElementById('jenisInput').value;
        const pelaku = document.getElementById('pelakuInput').value;
        const desc = document.getElementById('descInput').value;
        const file = document.getElementById('file-in').files.length;
        const btn = document.getElementById('submitBtn');
        if (tgl && jenis && pelaku && desc && file) { btn.classList.add('active-btn'); } else { btn.classList.remove('active-btn'); }
    }

    document.addEventListener('click', () => {
        document.getElementById("dropdownMenu").classList.remove("active");
        document.getElementById("notifDropdown").classList.remove("active");
        document.querySelectorAll('.dropdown-box').forEach(box => box.classList.remove('show'));
    });

    function goToPage(page) {
        const routes = { 'dashboard': '/Anggota/dashboardanggota', 'profile': '/Anggota/detailakun', 'riwayatinsiden': '/Anggota/riwayatinsiden' };
        if (page === 'logout' && confirm("Logout?")) window.location.href = "/Anggota/masuk";
        else window.location.href = routes[page] || "/Anggota/" + page;
    }
</script>
</body>
</html>