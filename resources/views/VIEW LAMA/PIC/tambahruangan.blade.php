<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Tambah Ruangan</title>
    <style>
        /* --- KODE CSS NAVBAR & SIDEBAR ASLI --- */
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
        .profile { position: relative; cursor: pointer; }
        .profile-circle {
            width: 40px; height: 40px; background: #ffffff; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
        }
        .profile-circle i { color: #2f7ea1; font-size: 22px; }
        .nav-right .fa-bell {
            color: #000000; font-size: 22px; cursor: pointer; position: relative;
            transition: 0.2s; padding: 8px;
        }
        .nav-right .fa-bell:hover { color: #1e5a7a; transform: scale(1.1); }
        .dropdown {
            position: absolute; top: 55px; right: 0; width: 170px;
            background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            opacity: 0; transform: translateY(-10px); pointer-events: none; transition: 0.25s;
        }
        .dropdown.active { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .dropdown div { padding: 10px; cursor: pointer; display: flex; gap: 8px; transition: 0.2s; }
        .dropdown div:hover { background: #f0f8ff; }

        .main { display: flex; }
        .sidebar {
            width: 235px; background: #E4F0FF; position: fixed; top: 70px;
            height: calc(100vh - 70px + 12px); left: 0; display: flex; flex-direction: column; z-index: 999;
        }
        .sidebar-content { flex: 1; overflow-y: auto; }
        .sidebar-footer {
            background: white; padding: 14px 14px; border-top: 1px solid #cbd9e6;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        .sidebar-footer .profile-info { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .sidebar-footer .profile-avatar {
            width: 35px; height: 35px; background: linear-gradient(135deg, #2f7ea1, #4a9dc3);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 590; font-size: 16px;
        }
        .sidebar-footer .profile-details h4 { font-size: 14px; font-weight: 600; color: #1e293b; margin: 0; }
        .sidebar-footer .profile-details p { font-size: 12px; color: #6b7280; margin: 0; }

        .menu-group { border-bottom: 1px solid #cbd9e6; }
        .dashboard-menu {
            padding: 12px 16px; font-weight: 600; cursor: pointer; display: flex;
            align-items: center; gap: 11px; transition: 0.2s; text-decoration: none; color: black;
        }
        .dashboard-menu:hover { background: #c9dcec; }
        .menu-title {
            padding: 12px 20px; font-weight: 580; cursor: pointer;
            display: flex; justify-content: space-between; align-items: center; transition: 0.2s;
        }
        .menu-title:hover { background: #c9dcec; }
        
        .menu-clickable {
            padding: 12px 20px; font-weight: 580; cursor: pointer;
            display: block; transition: 0.2s; text-decoration: none; color: black;
        }
        .menu-clickable:hover { background: #c9dcec; }

        .submenu { display: none; padding-left: 10px; }
        .submenu a { display: block; padding: 10px 14px; cursor: pointer; border-radius: 6px; transition: 0.2s; text-decoration: none; color: black; margin: 2px 0; }
        .submenu a:hover { background: #cfe3f1; }
        .menu-arrow.rotate { transform: rotate(180deg); }

        /* --- CONTENT WRAPPER --- */
        .content-wrapper {
            margin-left: 235px; margin-top: 70px;
            width: calc(100% - 235px); min-height: 100vh;
            background: linear-gradient(135deg, #c3d8e9 30%, #f4f4f4 55%, white 95%);
            padding: 25px; position: relative; z-index: 10;
        }

        .page-header { max-width: 900px; margin: 0 auto 20px; display: flex; align-items: center; gap: 15px; }
        .back-btn {
            font-size: 24px; color: #000; text-decoration: none; width: 45px; height: 45px;
            background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: 0.3s;
        }
        .back-btn:hover { background: #f8f9fa; transform: translateX(-3px); }
        
        .form-card {
            max-width: 900px; margin: 0 auto; background: white; border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08); padding: 40px;
        }
        .form-card h2 { color: #2f7ea1; margin-bottom: 30px; font-weight: 800; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; }

        .form-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 35px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #475569; font-size: 14px; }
        .field {
            width: 100%; padding: 12px 15px; border-radius: 12px; border: 2px solid #e2e8f0;
            background: #f8fafc; font-family: inherit; font-size: 14px; transition: 0.3s;
        }
        .field:focus { outline: none; border-color: #2f7ea1; background: white; box-shadow: 0 0 0 4px rgba(47, 126, 161, 0.1); }

        /* UPLOAD BOX */
        .upload-box {
            border: 2px dashed #cbd5e0; border-radius: 15px; height: 220px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            cursor: pointer; background: #f8fafc; transition: 0.3s; position: relative; overflow: hidden;
        }
        .upload-box:hover { border-color: #2f7ea1; background: #f0f7ff; }
        .upload-box img { width: 100%; height: 100%; object-fit: cover; position: absolute; display: none; }
        .upload-text { text-align: center; color: #94a3b8; font-size: 13px; font-weight: 500; padding: 0 15px; }
        .file-limit { font-size: 11px; color: #a0aec0; margin-top: 5px; font-weight: 400; }

        .button-group { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
        .btn { padding: 12px 30px; border-radius: 12px; font-weight: 600; cursor: pointer; transition: 0.3s; border: none; font-size: 14px; }
        .btn-cancel { background: #f1f5f9; color: #64748b; }
        .btn-save { background: #2f7ea1; color: white; box-shadow: 0 4px 12px rgba(47,126,161,0.2); }
        .btn-save:disabled { background: #cbd5e0; cursor: not-allowed; box-shadow: none; }
        .btn-save:not(:disabled):hover { transform: translateY(-2px); background: #256685; }

        @media (max-width: 760px) {
            .sidebar { transform: translateX(-100%); }
            .content-wrapper { margin-left: 0; width: 100%; padding: 15px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-card { padding: 25px; }
            .button-group { flex-direction: column-reverse; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="nav-left">
            <div class="logo"></div>
            <b style="letter-spacing: 1px;">POLIBATAM</b>
        </div>
        <div class="nav-right">
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
                <a href="#" class="dashboard-menu" onclick="goToPage('dashboard')">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span>Daftar Pengajuan</span><i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" onclick="goToPage('aju-ruangan')">Pengajuan Ruangan</a>
                        <a href="#" onclick="goToPage('aju-barang')">Pengajuan Barang</a>
                    </div>
                </div>
                <div class="menu-group">
                    <div class="menu-title" onclick="toggleMenu(this)">
                        <span>Inventaris</span><i class="fa-solid fa-chevron-down menu-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" onclick="goToPage('daftar-ruangan')">Daftar Ruangan</a>
                        <a href="#" onclick="goToPage('daftar-barang')">Daftar Barang</a>
                    </div>
                </div>
                <div class="menu-group">
                    <a href="#" class="menu-clickable" onclick="goToPage('riwayat')">
                        <span>Riwayat</span>
                    </a>
                </div>
            </div>
            <div class="sidebar-footer">
                <div class="profile-info" onclick="goToPage('profile')">
                    <div class="profile-avatar">IS</div>
                    <div class="profile-details">
                        <h4>Isma Silitonga</h4>
                        <p>3312301022</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="page-header">
                <h1 style="font-size: 22px; font-weight: 800; color: #1e293b;">TAMBAH RUANGAN</h1>
            </div>

            <div class="form-card">
                <form id="formRuangan">
                    <div class="form-grid">
                        <div>
                            <div class="form-group">
                                <label>Nama Ruangan *</label>
                                <input type="text" id="nama" class="field" placeholder="Masukkan nama ruangan" required>
                            </div>
                            <div class="form-group">
                                <label>Kapasitas (Orang)</label>
                                <input type="number" class="field" placeholder="Contoh: 30">
                            </div>
                            <div class="form-group">
                                <label>Lokasi / Gedung</label>
                                <input type="text" class="field" placeholder="Gedung A, Lt. 2">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi & Fasilitas</label>
                                <textarea id="deskripsi" class="field" style="height: 100px; resize: none;" placeholder="Sebutkan fasilitas ruangan..."></textarea>
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label>Foto Ruangan</label>
                                <div class="upload-box" onclick="document.getElementById('fileInput').click()">
                                    <img id="previewImg" alt="Preview">
                                    <div id="placeholder" class="upload-text">
                                        <i class="fa-solid fa-camera fa-3x" style="margin-bottom: 10px;"></i>
                                        <p>Klik untuk unggah/ambil foto</p>
                                        <p class="file-limit">JPG, JPEG, atau PNG (Maks. 2MB)</p>
                                    </div>
                                    <input type="file" id="fileInput" hidden accept="image/*">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="field">
                                    <option value="Rapat">Ruang Rapat</option>
                                    <option value="Seminar">Ruang Seminar</option>
                                    <option value="Kelas">Ruang Kelas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-cancel" onclick="window.history.back()">Batal</button>
                        <button type="submit" id="btnSimpan" class="btn btn-save" disabled>Simpan Ruangan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // SCRIPT ASLI ISMA
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

        // --- VALIDASI DAN PREVIEW FOTO ---
        const fileInput = document.getElementById('fileInput');
        const previewImg = document.getElementById('previewImg');
        const placeholder = document.getElementById('placeholder');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (file) {
                // 1. Validasi Ukuran
                if (file.size > maxSize) {
                    alert("Waduh, fotonya kegedean! Maksimal cuma 2MB ya.");
                    this.value = ""; 
                    return;
                }
                // 2. Preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        const namaInput = document.getElementById('nama');
        const btnSimpan = document.getElementById('btnSimpan');
        namaInput.addEventListener('input', () => {
            btnSimpan.disabled = namaInput.value.trim() === "";
        });

        function showNotifications() { alert("Belum ada notifikasi baru"); }
        function goToPage(page) { alert(`Navigasi ke: ${page}`); }
    </script>
</body>
</html>