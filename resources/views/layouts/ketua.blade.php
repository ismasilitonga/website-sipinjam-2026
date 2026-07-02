<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiPinjam Ketua – @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 215px;
            --bg: #f0fdf4;
            --sidebar-bg: #0f2318;
            --sidebar-hover: #1a3826;
            --sidebar-active: #16a34a;
            --accent: #16a34a;
            --accent-2: #0891b2;
            --accent-light: #dcfce7;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --white: #ffffff;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #d97706;
            --radius: 10px;
            --shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
        }

        html, body { overflow-x: hidden; width: 100%; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; font-size: 14px; }

        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            overflow-y: auto; z-index: 100;
        }
        .sidebar-logo {
            padding: 16px 14px 12px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: 9px;
        }
        .sidebar-logo-icon {
            width: 34px; height: 34px; border-radius: 9px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .sidebar-logo-text span { font-family: 'Sora', sans-serif; font-size: 16px; font-weight: 700; color: #fff; display: block; }
        .sidebar-logo-text small { font-size: 9px; color: #4d7a5e; letter-spacing: .4px; }

        .sidebar-nav { padding: 10px 8px; flex: 1; }
        .nav-label { font-size: 9px; font-weight: 700; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; padding: 10px 6px 4px; }

        .nav-item {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 9px; border-radius: 7px;
            color: #ffffff; text-decoration: none;
            font-size: 12px; font-weight: 500;
            transition: all .15s; margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: #9bffc3; }
        .nav-item.active { background: var(--sidebar-active); color: #fff; }
        .nav-item svg { width: 15px; height: 15px; flex-shrink: 0; }

        .sidebar-footer { padding: 10px 8px; border-top: 1px solid rgba(255,255,255,.06); }

        .sidebar-ormawa {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 9px; margin-bottom: 4px;
            background: rgba(22,163,74,.12); border-radius: 7px;
        }
        .ormawa-icon {
            width: 26px; height: 26px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; flex-shrink: 0;
        }
        .ormawa-name { color: #a7f3c0; font-size: 12px; font-weight: 600; }
        .ormawa-role { color: #ffffff; font-size: 10px; }

        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 10px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }

        .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        .topbar {
            background: var(--white); border-bottom: 1px solid var(--border);
            padding: 12px 20px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
            gap: 12px; flex-wrap: wrap;
        }
        .topbar-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-title { font-family: 'Sora', sans-serif; font-size: 16px; font-weight: 700; color: var(--text); }
        .topbar-sub   { font-size: 12px; color: var(--text-muted); margin-top: 1px; }

        .page-content { padding: 18px; flex: 1; }

        .alert {
            padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: flex-start; gap: 8px;
        }
        .alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info    { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        .card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); }
        .card-header { padding: 15px 16px 0; display: flex; align-items: center; justify-content: space-between; }
        .card-title  { font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 700; }
        .card-body   { padding: 16px; }

        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-bottom: 22px; }
        .stat-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); padding: 16px; display: flex; flex-direction: column; gap: 10px; min-width: 0; }
        .stat-icon { width: 38px; height: 38px; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
        .stat-icon svg { width: 19px; height: 19px; }
        .stat-value { font-family: 'Sora', sans-serif; font-size: 25px; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 12px; color: var(--text-muted); }

        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        th { background: #f8fafc; text-align: left; padding: 8px 10px; font-size: 10.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--border); }
        td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f7fef9; }

        .badge { display: inline-flex; align-items: center; padding: 2px 9px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .badge-blue   { background: #eef2ff; color: #4338ca; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .badge-cyan   { background: #ecfeff; color: #0e7490; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }

        .btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: all .15s; font-family: inherit; }
        .btn svg { width: 13px; height: 13px; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #15803d; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-danger:hover  { background: #b91c1c; }
        .btn-outline { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-outline:hover { background: #f8fafc; }
        .btn-sm { padding: 4px 8px; font-size: 10.5px; border-radius: 6px; }

        .form-group { margin-bottom: 15px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 500; color: var(--text); margin-bottom: 5px; }
        .form-control, .form-select, textarea.form-control {
            width: 100%; padding: 8px 11px; border: 1px solid var(--border); border-radius: 7px;
            font-size: 13px; font-family: inherit; background: var(--white); color: var(--text);
            transition: border-color .15s, box-shadow .15s; outline: none;
        }
        .form-control:focus, .form-select:focus, textarea.form-control:focus {
            border-color: var(--accent); box-shadow: 0 0 0 3px rgba(22,163,74,.1);
        }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-hint  { font-size: 11px; color: var(--text-muted); margin-top: 4px; }
        .form-error { font-size: 11px; color: var(--danger); margin-top: 4px; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

        .pagination-wrap { padding: 12px 16px; border-top: 1px solid var(--border); }
        .pagination-wrap .pagination { display: flex; gap: 4px; list-style: none; }
        .pagination-wrap .page-link { padding: 5px 10px; border-radius: 6px; font-size: 12px; color: var(--text); text-decoration: none; border: 1px solid var(--border); background: var(--white); transition: all .15s; }
        .pagination-wrap .page-link:hover { background: var(--accent-light); border-color: var(--accent); }
        .pagination-wrap .active .page-link  { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination-wrap .disabled .page-link { opacity: .4; pointer-events: none; }
        .pagination-wrap .page-link svg {
            width: 14px;
            height: 14px;
            display: block;
        }

        .empty-state { text-align: center; padding: 48px 16px; color: var(--text-muted); }
        .empty-state svg { width: 42px; height: 42px; margin-bottom: 10px; opacity: .35; }
        .empty-state p { font-size: 13.5px; }

        .detail-row { display: flex; border-bottom: 1px solid var(--border); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 170px; flex-shrink: 0; padding: 10px 14px; font-size: 12.5px; font-weight: 500; color: var(--text-muted); background: #f8faf8; }
        .detail-value { padding: 10px 14px; font-size: 13px; }

        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:200; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal-box { background:#fff; border-radius:14px; padding:24px; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,.2); }
        .modal-title { font-family:'Sora',sans-serif; font-size:15px; font-weight:700; margin-bottom:14px; }

        .item-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 14px; }
        .item-card {
            background: var(--white); border-radius: var(--radius);
            border: 1px solid var(--border); box-shadow: var(--shadow);
            overflow: hidden; transition: transform .15s, box-shadow .15s;
        }
        .item-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.09); }
        .item-card-img {
            width: 100%; height: 110px;
            background: #fafafa;
            display: flex; align-items: center; justify-content: center;
        }
        .item-card-img svg { width: 42px; height: 42px; color: #a1a1aa; }
        .item-card-body{padding:12px 14px;}
        .item-card-title { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 700; margin-bottom: 4px; }
        .item-card-sub   { font-size: 11px; color: var(--text-muted); margin-bottom: 8px; }

        /* ===== Hamburger + overlay (mobile sidebar) ===== */
        .hamburger-btn {
            display: none;
            align-items: center; justify-content: center;
            width: 34px; height: 34px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--white);
            cursor: pointer;
            flex-shrink: 0;
            padding: 0; margin: 0;
        }
        .hamburger-btn svg { width: 18px; height: 18px; color: var(--text); }

        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, .5);
            z-index: 99;
            opacity: 0;
            transition: opacity .22s ease;
        }
        .sidebar-overlay.open { display: block; opacity: 1; }

        /* ===== Horizontal scroll row (Jadwal + panel samping, dsb) ===== */
        .hscroll-row { display: flex; gap: 14px; }
        .hscroll-row > .card,
        .hscroll-row > .hscroll-col { flex: 1 1 320px; min-width: 280px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 880px) {
            .hamburger-btn { display: inline-flex; }

            .sidebar {
                width: 260px;
                max-width: 78vw;
                transform: translateX(-100%);
                transition: transform .25s ease;
                box-shadow: none;
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0,0,0,.25);
            }

            .nav-item { justify-content: flex-start; padding: 9px 10px; font-size: 13px; }
            .nav-item svg { width: 17px; height: 17px; }
            .sidebar-ormawa { justify-content: flex-start; }

            .main-wrap { margin-left: 0; }
            .topbar {
                flex-direction: column;
                align-items: stretch;
                padding: 10px 14px;
                gap: 8px;
            }
            .topbar-right {
                width: 100%;
                justify-content: space-between;
                padding-top: 8px;
                border-top: 1px solid var(--border);
            }
            .topbar-title { font-size: 14.5px; }
            .topbar-sub { font-size: 11px; }
            .page-content { padding: 12px; }
            .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }

            .stat-grid {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                scroll-snap-type: x proximity;
                -webkit-overflow-scrolling: touch;
                gap: 10px;
                margin-bottom: 18px;
                padding-bottom: 6px;
                scrollbar-width: thin;
            }
            .stat-grid::-webkit-scrollbar { height: 4px; }
            .stat-grid::-webkit-scrollbar-thumb { background: rgba(15,35,24,.15); border-radius: 10px; }
            .stat-card {
                flex: 0 0 auto;
                width: 148px;
                scroll-snap-align: start;
                padding: 12px 10px;
                gap: 8px;
                border-radius: 10px;
            }
            .stat-icon { width: 30px; height: 30px; border-radius: 7px; }
            .stat-icon svg { width: 15px; height: 15px; }
            .stat-value { font-size: 20px; }
            .stat-label { font-size: 10.5px; line-height: 1.2; word-break: break-word; }

            .hscroll-row {
                flex-wrap: nowrap;
                overflow-x: auto;
                scroll-snap-type: x proximity;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 6px;
            }
            .hscroll-row::-webkit-scrollbar { height: 4px; }
            .hscroll-row::-webkit-scrollbar-thumb { background: rgba(15,35,24,.15); border-radius: 10px; }
            .hscroll-row > .card,
            .hscroll-row > .hscroll-col {
                flex: 0 0 88%;
                min-width: 260px;
                scroll-snap-align: start;
            }
        }

        @media (max-width: 480px) {
            .topbar-sub { display: none; }
        }
    </style>
    @php
        $pendingKetua = \App\Models\PeminjamanRuangan::where('status', 'menunggu_ketua')
        ->where('nama_ormawa', auth()->user()->organisasi)
        ->count();
    @endphp
    @stack('styles')
</head>
<body>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div style="width:34px; height:34px; flex-shrink:0;
                    background: url('{{ asset('images/logo.png') }}') center/contain no-repeat;"></div>
        <div class="sidebar-logo-text">
            <span>SiPinjam</span>
            <small style="color:#ffffff;">PORTAL KETUA ORMAWA</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('ketua.dashboard') }}"
                class="nav-item {{ request()->routeIs('ketua.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <div class="nav-label">Pengajuan Anggota</div>
        <a href="{{ route('ketua.daftar-pengajuan') }}"
            class="nav-item {{ request()->routeIs('ketua.daftar-pengajuan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Kelola Pengajuan</span>
             @if($pendingKetua > 0)
                <span style="margin-left:auto; background:#42c565; color:#fff;
                     font-size:10px; font-weight:700; min-width:18px; height:18px;
                     border-radius:999px; display:flex; align-items:center;
                     justify-content:center; padding:0 5px;">
                {{ $pendingKetua }}
                </span>
            @endif
            </a>
        <a href="{{ route('ketua.riwayat-peminjaman') }}"
            class="nav-item {{ request()->routeIs('ketua.riwayat-peminjaman') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Riwayat Peminjaman</span>
        </a>

        <div class="nav-label">Katalog</div>
        <a href="{{ route('ketua.daftar-ruangan') }}"
           class="nav-item {{ request()->routeIs('ketua.daftar-ruangan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span>Daftar Ruangan</span>
        </a>
        <a href="{{ route('ketua.daftar-barang') }}"
           class="nav-item {{ request()->routeIs('ketua.daftar-barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
            <span>Daftar Barang</span>
        </a>
        <a href="{{ route('ketua.barang-ormawa.index') }}"
           class="nav-item {{ request()->routeIs('ketua.barang-ormawa.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
            <span>Barang Ormawa</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-ormawa">
            <div class="ormawa-icon">🏢</div>
            <div>
                <div class="ormawa-name">{{ auth()->user()->organisasi ?? 'Ormawa' }}</div>
                <div class="ormawa-role">Ketua Organisasi</div>
            </div>
        </div>
        <a href="{{ route('ketua.profil.show') }}"
           class="nav-item {{ request()->routeIs('ketua.profil*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Profil Saya</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;background:none;cursor:pointer;border:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger-btn" id="hamburgerBtn" type="button" aria-label="Buka menu">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <div class="topbar-title">@yield('title', 'Tambah Barang')</div>
                <div class="topbar-sub">@yield('subtitle', 'Form tambah barang ormawa')</div>
            </div>
        </div>
        <div class="topbar-right">
            <div style="display:flex;align-items:center;gap:6px;font-size:12.5px;color:#000000;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="live-clock" style="font-variant-numeric:tabular-nums;font-weight:600;letter-spacing:0.5px;"></span>
            </div>
            @yield('topbar-action')
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
        <div class="alert alert-success">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('info') }}
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('live-clock').textContent = h + ':' + m + ':' + s;
    }
    updateClock();
    setInterval(updateClock, 1000);

    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebarEl = document.getElementById('sidebar');
    const overlayEl = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebarEl.classList.add('open');
        overlayEl.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebarEl.classList.remove('open');
        overlayEl.classList.remove('open');
        document.body.style.overflow = '';
    }

    hamburgerBtn?.addEventListener('click', function () {
        sidebarEl.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    overlayEl?.addEventListener('click', closeSidebar);

    document.querySelectorAll('.sidebar .nav-item').forEach(function (el) {
        el.addEventListener('click', closeSidebar);
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 880) closeSidebar();
    });
</script>
@stack('scripts')
</body>
</html>