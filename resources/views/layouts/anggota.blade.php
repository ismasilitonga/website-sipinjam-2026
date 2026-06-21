<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiPinjam – @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 200px;
            --bg: #f0f4ff;
            --sidebar-bg: #1a1f36;
            --sidebar-hover: #252c4a;
            --sidebar-active: #4f46e5;
            --accent: #4f46e5;
            --accent-2: #06b6d4;
            --accent-light: #eef2ff;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --white: #ffffff;
            --success: #059669;
            --danger: #dc2626;
            --warning: #d97706;
            --radius: 10px;
            --shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
        }

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

        .sidebar-logo-text span {
            font-family: 'Sora', sans-serif;
            font-size: 16px; font-weight: 700; color: #fff;
            display: block;
        }
        .sidebar-logo-text small { font-size: 9px; color: #ffffff; letter-spacing: .4px; }

        .sidebar-nav { padding: 10px 8px; flex: 1; }
        .nav-label {
            font-size: 9px; font-weight: 700;
            color: #94a3b8; letter-spacing: 1px;
            text-transform: uppercase; padding: 10px 6px 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 9px; border-radius: 7px;
            color: #ffffff; text-decoration: none;
            font-size: 12px; font-weight: 500;
            transition: all .15s; margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: #c7d2fe; }
        .nav-item.active { background: var(--sidebar-active); color: #fff; }
        .nav-item svg { width: 15px; height: 15px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 10px 8px;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 9px; border-radius: 7px;
        }
        .sidebar-avatar {
            width: 26px; height: 26px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 11px; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-user-info .name { color: #e2e8f0; font-size: 12px; font-weight: 600; }
        .sidebar-user-info .org  { color: #64748b; font-size: 10px; }

        .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 12px 20px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left {}
        .topbar-title { font-family: 'Sora', sans-serif; font-size: 16px; font-weight: 700; color: var(--text); }
        .topbar-sub   { font-size: 12px; color: var(--text-muted); margin-top: 1px; }

        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-muted); margin-bottom: 16px; }
        .breadcrumb a { color: var(--accent); text-decoration: none; }
        .breadcrumb span { color: #cbd5e1; }

        .page-content { padding: 18px; flex: 1; }

        .alert {
            padding: 10px 14px; border-radius: 8px;
            font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: flex-start; gap: 8px;
        }
        .alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info    { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .card-header {
            padding: 15px 16px 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 700; }
        .card-body  { padding: 16px; }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 14px; margin-bottom: 22px;
        }
        .stat-card {
            background: var(--white);
            border-radius: var(--radius); border: 1px solid var(--border);
            box-shadow: var(--shadow); padding: 16px;
            display: flex; flex-direction: column; gap: 10px;
        }
        .stat-icon { width: 38px; height: 38px; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
        .stat-icon svg { width: 19px; height: 19px; }
        .stat-value { font-family: 'Sora', sans-serif; font-size: 25px; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 12px; color: var(--text-muted); }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        th {
            background: #f8fafc; text-align: left;
            padding: 8px 10px; font-size: 10.5px; font-weight: 700;
            color: var(--text-muted); text-transform: uppercase;
            letter-spacing: .5px; border-bottom: 1px solid var(--border);
        }
        td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafbff; }

        .badge {
            display: inline-flex; align-items: center;
            padding: 2px 9px; border-radius: 999px;
            font-size: 11px; font-weight: 600;
        }
        .badge-blue   { background: #eef2ff; color: #4338ca; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .badge-cyan   { background: #ecfeff; color: #0e7490; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }

        .btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 6px 12px; border-radius: 7px;
            font-size: 12px; font-weight: 500;
            cursor: pointer; border: none; text-decoration: none;
            transition: all .15s; font-family: inherit;
        }
        .btn svg { width: 13px; height: 13px; }
        .btn-primary  { background: var(--accent); color: #fff; }
        .btn-primary:hover  { background: #4338ca; }
        .btn-success  { background: var(--success); color: #fff; }
        .btn-success:hover  { background: #047857; }
        .btn-danger   { background: var(--danger); color: #fff; }
        .btn-danger:hover   { background: #b91c1c; }
        .btn-outline  { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-outline:hover  { background: #f8fafc; }
        .btn-cyan     { background: var(--accent-2); color: #fff; }
        .btn-cyan:hover     { background: #0891b2; }
        .btn-sm { padding: 4px 8px; font-size: 10.5px; border-radius: 6px; }

        .form-group { margin-bottom: 15px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 500; color: var(--text); margin-bottom: 5px; }
        .form-control, .form-select, textarea.form-control {
            width: 100%; padding: 8px 11px;
            border: 1px solid var(--border); border-radius: 7px;
            font-size: 13px; font-family: inherit;
            background: var(--white); color: var(--text);
            transition: border-color .15s, box-shadow .15s; outline: none;
        }
        .form-control:focus, .form-select:focus, textarea.form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-hint  { font-size: 11px; color: var(--text-muted); margin-top: 4px; }
        .form-error { font-size: 11px; color: var(--danger); margin-top: 4px; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

        .pagination-wrap { padding: 12px 16px; border-top: 1px solid var(--border); }
        .pagination-wrap .pagination { display: flex; gap: 4px; list-style: none; }
        .pagination-wrap .page-link {
            padding: 5px 10px; border-radius: 6px; font-size: 12px;
            color: var(--text); text-decoration: none;
            border: 1px solid var(--border); background: var(--white);
            transition: all .15s;
        }
        .pagination-wrap .page-link:hover { background: var(--accent-light); border-color: var(--accent); color: var(--accent); }
        .pagination-wrap .active .page-link { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination-wrap .disabled .page-link { opacity: .4; pointer-events: none; }
        .pagination-wrap .page-link svg {
            width: 14px;
            height: 14px;
            display: block;
        }

        .empty-state { text-align: center; padding: 48px 16px; color: var(--text-muted); }
        .empty-state svg { width: 42px; height: 42px; margin-bottom: 10px; opacity: .35; }
        .empty-state p { font-size: 13.5px; }

        .detail-row { display: flex; gap: 0; border-bottom: 1px solid var(--border); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            width: 170px; flex-shrink: 0; padding: 10px 14px;
            font-size: 12.5px; font-weight: 500; color: var(--text-muted);
            background: #f8fafc;
        }
        .detail-value { padding: 10px 14px; font-size: 13px; }

        .item-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 14px; }
        .item-card {
            background: var(--white); border-radius: var(--radius);
            border: 1px solid var(--border); box-shadow: var(--shadow);
            overflow: hidden; transition: transform .15s, box-shadow .15s;
        }
        .item-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.09); }
        .item-card-img {
            width: 100%; height: 120px;
            background: linear-gradient(135deg, #eef2ff, #e0f2fe);
            display: flex; align-items: center; justify-content: center;
        }
        .item-card-img svg { width: 42px; height: 42px; color: #a5b4fc; }
        .item-card-body { padding: 12px 14px; }
        .item-card-title { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 700; margin-bottom: 4px; }
        .item-card-sub   { font-size: 11px; color: var(--text-muted); margin-bottom: 8px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 880px) {
            :root { --sidebar-w: 64px; }
            .sidebar-logo-text,
            .nav-label,
            .sidebar-user-info { display: none; }
            .nav-item { justify-content: center; padding: 9px; }
            .sidebar-user { justify-content: center; }
            .page-content { padding: 12px; }
            .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
<aside class="sidebar">
<div class="sidebar-logo" style="padding: 16px 14px 12px; gap: 9px; align-items: center;">
    <div style="width: 34px; height: 34px; flex-shrink: 0;
                background: url('{{ asset('images/logo.png') }}') center/contain no-repeat;">
    </div>
    <div class="sidebar-logo-text">
        <span>SiPinjam</span>
        <small>PORTAL ANGGOTA</small>
    </div>
</div>

    <nav class="sidebar-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('anggota.dashboard') }}"
           class="nav-item {{ request()->routeIs('anggota.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-label">Katalog</div>
        <a href="{{ route('anggota.daftar-ruangan') }}"
           class="nav-item {{ request()->routeIs('anggota.daftar-ruangan') || request()->routeIs('anggota.detail-ruangan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Daftar Ruangan
        </a>
        <a href="{{ route('anggota.daftar-barang') }}"
           class="nav-item {{ request()->routeIs('anggota.daftar-barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
            Daftar Barang
        </a>

        <div class="nav-label">Pengajuan</div>
        <a href="{{ route('anggota.pengajuan-ruangan') }}"
           class="nav-item {{ request()->routeIs('anggota.pengajuan-ruangan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Ruangan
        </a>
        <a href="{{ route('anggota.pengajuan-barang') }}"
           class="nav-item {{ request()->routeIs('anggota.pengajuan-barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Ajukan Barang
        </a>

        <div class="nav-label">Pemakaian</div>
        <a href="{{ route('anggota.checkin') }}"
           class="nav-item {{ request()->routeIs('anggota.checkin') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Check-in
        </a>
        <a href="{{ route('anggota.checkout') }}"
           class="nav-item {{ request()->routeIs('anggota.checkout') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Check-out
        </a>
        <a href="{{ route('anggota.handover') }}"
           class="nav-item {{ request()->routeIs('anggota.handover') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Handover
        </a>
        <a href="{{ route('anggota.pengalihan-barang') }}"
           class="nav-item {{ request()->routeIs('anggota.pengalihan-barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
            Pengalihan Barang
        </a>

        <div class="nav-label">Laporan & Riwayat</div>
        <a href="{{ route('anggota.lapor-insiden') }}"
           class="nav-item {{ request()->routeIs('anggota.lapor-insiden') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Lapor Insiden
        </a>
        <a href="{{ route('anggota.riwayat-insiden') }}"
           class="nav-item {{ request()->routeIs('anggota.riwayat-insiden') || request()->routeIs('anggota.detail-laporan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Riwayat Insiden
        </a>
        <a href="{{ route('anggota.riwayat-ruangan') }}"
           class="nav-item {{ request()->routeIs('anggota.riwayat-ruangan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Ruangan
        </a>
        <a href="{{ route('anggota.riwayat-barang') }}"
           class="nav-item {{ request()->routeIs('anggota.riwayat-barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Barang
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('anggota.profil.show') }}"
           class="nav-item {{ request()->routeIs('anggota.profil*') ? 'active' : '' }}"
           style="margin-bottom:4px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;background:none;cursor:pointer;border:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
       <div class="topbar-left">
    <div class="topbar-title">@yield('title', 'Dashboard')</div>
    <div class="topbar-sub">@yield('subtitle')</div>
</div>
        <div style="display:flex;align-items:center;gap:14px;">
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
</script>
@stack('scripts')
</body>
</html>