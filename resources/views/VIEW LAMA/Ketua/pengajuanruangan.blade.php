<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <title>Pengajuan Ruangan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: white; }
        
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

        #clock-display {
        color: white; 
        font-weight: 500; 
        font-size: 15px; 
        padding: 5px 12px; 
        font-family: monospace;
        background: none !important;
        box-shadow: none !important;
        border: none;
        }
        .profile { position: relative; cursor: pointer; }
        .profile-circle {
            width: 40px; height: 40px; background: #ffffff; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
        }
        .profile-circle i { color: #2f7ea1; font-size: 22px; }
        .nav-right .fa-bell { color: #000000; font-size: 22px; cursor: pointer; transition: 0.2s; padding: 8px; }
        
        .dropdown {
            position: absolute; top: 55px; right: 0; width: 170px;
            background: white; border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(-10px); 
            pointer-events: none; transition: 0.25s; z-index: 1001;
        }
        .dropdown.active { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .dropdown div { 
            padding: 10px; cursor: pointer; display: flex; 
            align-items: center; gap: 8px; transition: 0.2s; color: #333; }
        .dropdown div:hover { background: #f0f8ff; }

        .main { display: flex; }
        .sidebar {
            width: 235px; background: #E4F0FF;
            position: fixed; top: 70px; height: calc(100vh - 70px + 12px);
            left: 0; display: flex; flex-direction: column; z-index: 999;
        }
        .sidebar-content { flex: 1; overflow-y: auto; }

        .sidebar-footer {
            background: white; padding: 14px 14px; border-top: 1px solid #cbd9e6;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        .profile-info { display: flex; align-items: center; 
            gap: 10px; cursor: pointer; transition: 0.2s; border-radius: 6px; }
        .profile-info:hover { background: #f0f8ff; }
        .profile-avatar {
            width: 35px; height: 35px; background: linear-gradient(135deg, #2f7ea1, #4a9dc3);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 590; font-size: 16px; flex-shrink: 0; text-transform: uppercase;
        }
        .sidebar-footer .profile-details h4 { font-size: 14px; font-weight: 600; color: #1e293b; margin: 0 0 2px 0; }
        .sidebar-footer .profile-details p { font-size: 12px; color: #6b7280; margin: 0; }

        .menu-group { border-bottom: 1px solid #cbd9e6; }
        .dashboard-menu { padding: 12px 16px; font-weight: 600; 
            cursor: pointer; display: flex; align-items: center; gap: 11px; 
            text-decoration: none; color: black; transition: 0.2s; }
        .dashboard-menu:hover { background: #c9dcec; }
        .menu-title { padding: 12px 20px; font-weight: 580; cursor: pointer; 
            display: flex; justify-content: space-between; 
            align-items: center; transition: 0.2s; }
        .menu-title:hover { background: #c9dcec; }
        .submenu { display: none; padding-left: 10px; }
        .submenu a { display: block; padding: 10px 14px; text-decoration: none; 
            color: black; margin: 2px 0; 
            border-radius: 6px; transition: 0.2s; }
        .submenu a:hover, .submenu a.active { background: #cfe3f1; }
        .menu-arrow.rotate { transform: rotate(180deg); }

        .content-wrapper {
            margin-left: 235px; margin-top: 70px;
            width: calc(100% - 235px); min-height: 100vh;
            background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%);
            padding: 25px; position: relative; z-index: 10;
        }
        
        .form-container {
            background: white; border-radius: 30px;
            padding: 45px; max-width: 750px;
            margin: auto; width: 95%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        .form-container h2 { margin-bottom: 30px; text-align: center; 
            color: #2f7ea1; font-size: 26px; font-weight: 600;}
        .status-info { background: linear-gradient(135deg, #d4edda, #c3e6cb); 
            border-radius: 12px; padding: 20px; 
            margin-bottom: 25px; text-align: center; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; 
            gap: 16px; margin-bottom: 18px; }
        .form-group { display: flex; flex-direction: column; }
        .full-width { grid-column: 1 / -1;}
        .form-label { font-size: 14px; color: #555; margin-bottom: 8px; font-weight: 500; }
        .form-input { padding: 12px 16px; border: 2px solid #e1e5e9; 
            border-radius: 12px; font-size: 15px; background: #f8f9fa; 
            transition: 0.3s; font-family: 'Poppins', sans-serif; }
        .form-input:focus { outline: none; border-color: #2f7ea1; 
            background: white; box-shadow: 0 0 0 3px rgba(47, 126, 161, 0.1); }
        .form-input:disabled { background: #f1f3f4; cursor: not-allowed; }
        .search-room { display: flex; align-items: center; gap: 8px; 
            background: #f8f9fa; border: 2px solid #e1e5e9; 
            border-radius: 10px; padding: 6px; margin-bottom: 10px; max-width: 240px; }
        .search-room input { border: none; outline: none; 
            background: transparent; font-size: 14px; flex-grow: 1; }

        .room-list { display: flex; gap: 12px; overflow-x: auto; 
            padding: 15px 10px; background: #f8f9fa; 
            border-radius: 16px; margin-bottom: 15px; 
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.05); scrollbar-width: none; }
        .room-chip { flex-shrink: 0; padding: 12px 15px; 
            background: white; border: 2px solid #e1e5e9; 
            border-radius: 20px; cursor: pointer; transition: 0.3s; 
            font-size: 14px; font-weight: 500; min-width: 100px; text-align: center;}
        .room-chip.selected { 
            background: linear-gradient(135deg, #2f7ea1, #1e5a7a); 
            color: white; border-color: #2f7ea1; 
            transform: translateY(-1px); box-shadow: 0 4px 12px rgba(47,126,161,0.3); }

        .button-group { display: flex; 
            justify-content: center; gap: 40px; margin-top: 30px;}
        .btn { padding: 14px 24px; border: none; 
            border-radius: 12px; cursor: pointer; 
            font-weight: 600; font-size: 15px; 
            transition: 0.2s; min-width: 135px; }
        .btn-primary { 
            background: linear-gradient(135deg, #2f7ea1, #1e5a7a); color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        .modal-overlay { position: fixed; top: 0; 
            left: 0; width: 100%; height: 100%; 
            background: rgba(8, 24, 40, 0.55); 
            display: none; justify-content: center; align-items: center; 
            z-index: 2000; backdrop-filter: blur(8px); }
        .modal-box { background: #ffffff; width: min(520px, 95%); 
            padding: 30px 40px; border-radius: 40px; text-align: center; 
            position: relative; box-shadow: 0 22px 65px rgba(15, 23, 42, 0.18); 
            animation: popIn 0.28s ease-out; }
        @keyframes popIn { 0% { transform: scale(0.86); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        .modal-close { position: absolute; 
            top: 18px; right: 18px; width: 40px; height: 40px; 
            border-radius: 50%; background: #f4f5f8; display: flex; 
            align-items: center; justify-content: center; cursor: pointer; }
        .modal-icon { width: 75px; height: 75px; margin: 0 auto 15px; 
            border-radius: 50%; background: #e7f3e9; display: flex; 
            align-items: center; justify-content: center; }
        .modal-icon i { font-size: 32px; color: #2d6c44; }
        .modal-btn { background: #b8d6b3; color: #102b12; 
            border: none; padding: 12px 80px; border-radius: 999px; 
            font-weight: 700; cursor: pointer; transition: 0.2s; }

        @media (max-width: 760px) {.nav-right { gap: 10px; } 
        #clock-display {font-size: 13px; padding: 4px 5px; }
        }
    </style>
</head>
<body>

    <div class="modal-overlay" id="modalNotif">
        <div class="modal-box">
            <i class="fa-solid fa-xmark modal-close" onclick="closeModal()"></i>
            <div class="modal-icon"><i class="fa-solid fa-check"></i></div>
            <h3>Berhasil Melakukan Pengajuan!</h3>
            <p style="margin-bottom: 25px; color: #475569;">Menunggu Konfirmasi dari Ketua</p>
            <button class="modal-btn" onclick="closeModal()">Oke</button>
        </div>
    </div>

    <div class="navbar">
        <div class="nav-left"><div class="logo"></div><b>Student Center</b></div>
        <div class="nav-right">
            <div id="clock-display">00:00:00</div>
            
            <i class="fa-regular fa-bell" onclick="showNotifications()"></i>
            <div class="profile">
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
                    <div class="menu-title" onclick="toggleMenu(this)"><span>Inventaris</span><i class="fa-solid fa-chevron-down menu-arrow"></i></div>
                    <div class="submenu">
                        <a href="#" onclick="goToPage('daftar-ruangan')">Daftar Ruangan</a>
                        <a href="#" onclick="goToPage('daftar-barang')">Daftar Barang</a>
                        <a href="#" onclick="goToPage('handover')">Handover</a>
                    </div>
                </div>

                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)"><span>Laporan Insiden</span><i class="fa-solid fa-chevron-down menu-arrow"></i></div>
                    <div class="submenu">
                        <a href="#" onclick="goToPage('buat-laporan')">Buat Laporan</a>
                        <a href="#" onclick="goToPage('riwayat-laporan')">Riwayat Laporan</a>
                    </div>
                </div>

                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)"><span>Riwayat</span><i class="fa-solid fa-chevron-down menu-arrow"></i></div>
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
            <div class="form-container">
                <h2><i class="fa-solid fa-clipboard-list"></i> Form Pengajuan Peminjaman</h2>
                <div class="status-info">
                    <h3 style="color: #155724; margin-bottom: 5px;">Isi form dengan lengkap</h3>
                    <p style="color: #155724; margin: 0;">Pastikan data yang diisi sudah benar sebelum mengajukan</p>
                </div>
                <form id="pengajuanForm">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" id="userNama" class="form-input" value="{{ auth()->user()->name }}" disabled>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">NIM</label>
                            <input type="text" id="userNIM" class="form-input" value="{{ auth()->user()->nim }}" disabled>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="tel" id="inputHP" class="form-input" required placeholder="08...">
                        </div> 
                        <div class="form-group full-width">
    <label class="form-label">Ketua Ormawa (Penyetuju 1)</label>
    <input type="text" id="ketuaOrmawa" class="form-input" value="Andi Hermawan (Ketua HME)" readonly style="background: #e9ecef;">
    <small style="color: #6c757d; font-size: 11px; margin-top: 4px;">*Otomatis terdeteksi berdasarkan organisasi Anda</small>
</div>

<div class="form-group full-width">
    <label class="form-label">PIC Ruangan (Penyetuju 2)</label>
    <input type="text" id="picRuangan" class="form-input" placeholder="Pilih ruangan di bawah terlebih dahulu..." readonly style="background: #e9ecef;">
</div>
                        <div class="form-group full-width">
                            <label class="form-label">Ruangan yang Dipinjam</label>
                            <div class="room-selection">
                                <div class="search-room">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input type="text" id="searchRoom" placeholder="Cari ruangan...">
                                </div>
                                <div class="room-list">
                                    <div class="room-chip">Lt.1 RB</div>
                                    <div class="room-chip">Lt.1 HMJ</div>
                                    <div class="room-chip">Lt.1 UKM</div>
                                    <div class="room-chip">Lt.2A</div>
                                    <div class="room-chip">Lt.2B</div>
                                    <div class="room-chip">Lt.2C</div>
                                    <div class="room-chip">Lt.2D</div>
                                    <div class="room-chip">Lt.3A</div>
                                    <div class="room-chip">Lt.3B</div>
                                    <div class="room-chip">Lt.3C</div>
                                    <div class="room-chip">Lt.3D</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Tanggal Penggunaan</label>
                            <input type="text" id="tanggal_penggunaan" class="form-input" placeholder="Pilih Tanggal..." readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" id="jamMulai" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" id="jamSelesai" class="form-input" required>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Keperluan Peminjaman</label>
                            <textarea id="keperluan" class="form-input" style="height: 90px; resize: none;" placeholder="Jelaskan secara singkat keperluan..." required></textarea>
                        </div>
                       <div class="form-group full-width">
                       <label class="form-label">Estimasi Jumlah Pengguna</label>
                       <input type="number" id="estimasi" class="form-input" placeholder="0" min="1" required>
                       </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" id="btnBatal" onclick="resetForm()" disabled>Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnAjukan" disabled>Ajukan Peminjaman</button>
                    </div>
                    <div class="form-group full-width">
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
       function updateClock() {
    const sekarang = new Date();
    const jam = String(sekarang.getHours()).padStart(2, '0');
    const menit = String(sekarang.getMinutes()).padStart(2, '0');
    const detik = String(sekarang.getSeconds()).padStart(2, '0');
    
    const display = document.getElementById('clock-display');
    if (display) {
        display.innerText = `${jam}:${menit}:${detik}`;
    }
}
updateClock(); 
setInterval(updateClock, 1000);
        const profile = document.querySelector(".profile");
        const dropdown = document.getElementById("dropdownMenu");
        profile.onclick = (e) => { e.stopPropagation(); dropdown.classList.toggle("active"); };
        document.onclick = () => dropdown.classList.remove("active");

        function toggleMenu(el) {
            const submenu = el.nextElementSibling;
            const arrow = el.querySelector(".menu-arrow");
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
            arrow.classList.toggle("rotate");
        }
        flatpickr("#tanggal_penggunaan", {
            locale: "id",
            dateFormat: "j F Y",
            minDate: new Date().fp_incr(2),
            disable: [function(date) { return date.getDay() === 0; }],
            onChange: checkFormValidity
        });

        function checkFormValidity() {
            const hp = document.getElementById('inputHP').value.trim();
            const tgl = document.getElementById('tanggal_penggunaan').value;
            const jM = document.getElementById('jamMulai').value;
            const jS = document.getElementById('jamSelesai').value;
            const kep = document.getElementById('keperluan').value.trim();
            const est = document.getElementById('estimasi').value;
            const selectedRoom = document.querySelector('.room-chip.selected');

            const isStarted = hp || tgl || jM || jS || kep || est || selectedRoom;
            const isComplete = hp && tgl && jM && jS && kep && est && selectedRoom;

            document.getElementById('btnBatal').disabled = !isStarted;
            document.getElementById('btnAjukan').disabled = !isComplete;
        }

        document.querySelectorAll('input, textarea').forEach(el => {
            el.addEventListener('input', checkFormValidity);
        });

        document.querySelectorAll('.room-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                document.querySelectorAll('.room-chip').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                checkFormValidity();
            });
        });

        document.getElementById("searchRoom").addEventListener("keyup", function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll(".room-chip").forEach(room => {
                room.style.display = room.textContent.toLowerCase().includes(keyword) ? "inline-block" : "none";
            });
        });

        function resetForm() {
            document.getElementById('pengajuanForm').reset();
            document.querySelectorAll('.room-chip').forEach(c => c.classList.remove('selected'));
            checkFormValidity();
        }

        document.getElementById('pengajuanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('modalNotif').style.display = 'flex';
        });

        function closeModal() {
            document.getElementById('modalNotif').style.display = 'none';
            resetForm();
        }

        function showNotifications() { alert("Belum ada notifikasi baru."); }
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
             if (page === 'logout' && confirm("Yakin ingin logout?")) window.location.href = "/Anggota/masuk";
            else if (page !== 'logout') window.location.href = routes[page] || "/Anggota/" + page;
        }
const dataPIC = {
    "Lt.1 RB": "Bpk. Budi Santoso (Staff Sarpras)",
    "Lt.1 HMJ": "Ibu Siti Aminah (Pembina HMJ)",
    "Lt.1 UKM": "Bpk. Rahmat Hidayat (Koordinator UKM)",
    "Lt.2A": "Staf Admin Lantai 2",
    "Lt.2B": "Staf Admin Lantai 2",
    "Lt.3A": "Koordinator Lantai 3"
};

document.querySelectorAll('.room-chip').forEach(chip => {
    chip.addEventListener('click', function() {
        document.querySelectorAll('.room-chip').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        const namaRuangan = this.innerText;
        const inputPIC = document.getElementById('picRuangan');
        
        if (dataPIC[namaRuangan]) {
            inputPIC.value = dataPIC[namaRuangan];
        } else {
            inputPIC.value = "PIC Umum (Sarpras)";
        }
        
        checkFormValidity();
    });
});
    </script>
</body>
</html>