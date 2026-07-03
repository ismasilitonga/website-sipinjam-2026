<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel – @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 215px;
            --bg: #f4f6fb;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #1d4ed8;
            --accent: #2563eb;
            --accent-light: #dbeafe;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --white: #ffffff;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #d97706;
            --info: #0891b2;
            --radius: 10px;
            --shadow: 0 1px 3px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.05);
        }

        html { -webkit-text-size-adjust: 100%; overflow-x: hidden; }
        body { overflow-x: hidden; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            font-size: 14px;
        }

        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            overflow: hidden;
            z-index: 100;
        }

        .sidebar-logo {
            flex: 0 0 auto;
            display: flex;
            padding: 16px 14px 12px;
            gap: 9px; align-items: center;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-logo-mark {
            width: 34px; height: 34px; flex-shrink: 0;
            background: url('{{ asset('images/logo.png') }}') center/contain no-repeat;
        }
        .sidebar-logo-text span {
            font-family: 'Outfit', sans-serif;
            font-size: 16px; font-weight: 700;
            color: #fff; letter-spacing: -.3px; display: block;
        }
        .sidebar-logo-text small {
            display: block;
            font-size: 9px; color: #94a3b8;
            margin-top: 2px; letter-spacing: .5px; text-transform: uppercase;
        }

        .sidebar-nav {flex: 1 1 auto; min-height: 0; overflow-y: auto; padding: 10px 8px;}
        .sidebar-nav::-webkit-scrollbar { width: 5px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }

        .nav-section-label {
            font-size: 9px; font-weight: 600;
            color: #94a3b8; letter-spacing: 1px;
            text-transform: uppercase;
            padding: 10px 6px 4px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 9px;
            border-radius: 7px;
            color: #ffffff;
            text-decoration: none;
            font-size: 12px; font-weight: 500;
            transition: all .15s;
            margin-bottom: 2px;
            white-space: nowrap;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: #e2e8f0; }
        .nav-item.active { background: var(--sidebar-active); color: #fff; }
        .nav-item svg { width: 15px; height: 15px; flex-shrink: 0; }

        .sidebar-footer {
            flex: 0 0 auto;
            padding: 10px 8px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 9px; border-radius: 7px;
            color: #94a3b8; font-size: 12px;
            margin-bottom: 4px;
        }
        .sidebar-avatar {
            width: 26px; height: 26px; border-radius: 50%;
            background: var(--accent); display: flex;
            align-items: center; justify-content: center;
            color: #fff; font-size: 11px; font-weight: 600;
            flex-shrink: 0;
        }
        .sidebar-user-name { color:#e2e8f0; font-size:12px; font-weight:500; line-height:1.3; }
        .sidebar-user-role { font-size:10px; color:#7e9bf1; line-height:1.3; }

        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 12px 20px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; flex-wrap: wrap;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-title {
            font-family: 'Outfit', sans-serif;
            font-size: 16px; font-weight: 600;
            color: var(--text);
        }
        .topbar-sub {
            font-size: 12px; color: var(--text-muted); margin-top: 1px;
        }

        .page-content {padding: 18px; flex: 1; min-width: 0;}

        .alert {
            padding: 10px 14px; border-radius: 8px;
            font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info    { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;        
        }
        .card-header {
            padding: 15px 16px 0;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 8px;
        }
        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14px; font-weight: 600;
        }
        .card-body { padding: 16px; }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 14px; margin-bottom: 22px;
        }
        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            padding: 16px;
            display: flex; flex-direction: column; gap: 10px;
            min-width: 0;
        }
        .stat-icon {
            width: 38px; height: 38px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon svg { width: 19px; height: 19px; }
        .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 25px; font-weight: 700;
            line-height: 1;
        }
        .stat-label { font-size: 12px; color: var(--text-muted); }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        th {
            background: #f8fafc; text-align: left;
            padding: 8px 10px;
            font-size: 10.5px; font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase; letter-spacing: .5px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        th:first-child, td:first-child { padding-left: 16px; }
        th:last-child, td:last-child { padding-right: 16px; }
        td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        .badge {
            display: inline-flex; align-items: center;
            padding: 2px 9px; border-radius: 999px;
            font-size: 11px; font-weight: 500;
            white-space: nowrap;
        }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }
        .badge-cyan   { background: #ecfeff; color: #0e7490; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }

        .btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 6px 12px; border-radius: 7px;
            font-size: 12px; font-weight: 500;
            cursor: pointer; border: none; text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
        }
        .btn svg { width: 13px; height: 13px; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-danger  { background: #dc2626; color: #fff; }
        .btn-danger:hover  { background: #b91c1c; }
        .btn-outline {
            background: transparent; color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-outline:hover { background: #f8fafc; }
        .btn-sm { padding: 4px 8px; font-size: 10.5px; border-radius: 6px; }
        .btn-icon { padding: 5px; border-radius: 6px; }

        .action-group { display: flex; gap: 5px; flex-wrap: nowrap; }

        .form-group { margin-bottom: 15px; }
        .form-label {
            display: block; font-size: 12.5px; font-weight: 500;
            color: var(--text); margin-bottom: 5px;
        }
        .form-control, .form-select {
            width: 100%; padding: 8px 11px;
            border: 1px solid var(--border); border-radius: 7px;
            font-size: 13px; font-family: inherit;
            background: var(--white); color: var(--text);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .form-hint { font-size: 11px; color: var(--text-muted); margin-top: 4px; }
        .form-error { font-size: 11px; color: var(--danger); margin-top: 4px; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .form-grid-3 {display: grid;grid-template-columns: repeat(3, 1fr);gap: 14px;}

        .form-actions {display: flex;gap: 10px;margin-top: 18px;}

        .preview-image {max-width: 260px;border-radius: 8px;border: 1px solid var(--border);
            margin-top: 10px;
        }
        .pagination-wrap { padding: 12px 16px; border-top: 1px solid var(--border); }
        .pagination-wrap .pagination { display: flex; gap: 4px; list-style: none; flex-wrap: wrap; }
        .pagination-wrap .page-link {
            padding: 5px 10px; border-radius: 6px; font-size: 12px;
            color: var(--text); text-decoration: none;
            border: 1px solid var(--border); background: var(--white);
            transition: all .15s;
        }
        .pagination-wrap .page-link:hover { background: var(--accent-light); border-color: var(--accent); }
        .pagination-wrap .active .page-link { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination-wrap .disabled .page-link { opacity: .4; pointer-events: none; }
        .pagination-wrap .page-link svg {width: 14px;height: 14px;display: block;
        }

        .empty-state {
            text-align: center; padding: 48px 16px; color: var(--text-muted);
        }
        .empty-state svg { width: 42px; height: 42px; margin-bottom: 10px; opacity: .4; }
        .empty-state p { font-size: 13.5px; }

        .detail-row { display: flex; gap: 0; border-bottom: 1px solid var(--border); flex-wrap: nowrap; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            width: 110px; flex-shrink: 0;
            padding: 10px 14px;
            font-size: 12.5px; font-weight: 500; color: var(--text-muted);
            background: #f8fafc;
        }
        .detail-value {
            padding: 10px 14px; font-size: 13px;
            flex: 1 1 0;
            min-width: 0;
            word-break: break-word;
        }

        .item-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 14px; }
        .item-card {
            background: var(--white); border-radius: var(--radius);
            border: 1px solid var(--border); box-shadow: var(--shadow);
            overflow: hidden; transition: transform .15s, box-shadow .15s;
        }
        .item-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.09); }
        .item-card-img {
            width: 100%; height: 120px;
            background: #fafafa;
            display: flex; align-items: center; justify-content: center;
        }
        .item-card-img svg { width: 42px; height: 42px; color: #a1a1aa; }
        .item-card-body { padding: 12px 14px; }
        .item-card-title { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 700; margin-bottom: 4px; }
        .item-card-sub   { font-size: 11px; color: var(--text-muted); margin-bottom: 8px; }

        .hscroll-row { display: flex; gap: 14px; }
        .hscroll-row > .card,
        .hscroll-row > .hscroll-col { flex: 1 1 320px; min-width: 280px; }

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

        .sidebar-overlay {display: none;position: fixed; inset: 0;
            background: rgba(15, 23, 42, .5);
            z-index: 99;
            opacity: 0;
            transition: opacity .22s ease;
        }
        .sidebar-overlay.open { display: block; opacity: 1; }

        @media (max-width: 880px) {
            .hamburger-btn { display: inline-flex; }

            .sidebar {width: 260px;max-width: 78vw;
                transform: translateX(-100%);
                transition: transform .25s ease;
                box-shadow: none;
            }
            .sidebar.open {transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0,0,0,.25);
            }

            .nav-item { justify-content: flex-start; padding: 9px 10px; font-size: 13px; }
            .nav-item svg { width: 17px; height: 17px; }
            .sidebar-user { justify-content: flex-start; }

            .main-wrap { margin-left: 0; }

            .topbar {
                flex-direction: row;
                align-items: center;
                flex-wrap: nowrap;
                padding: 10px 12px;
                gap: 8px;
            }
            .topbar-left {min-width: 0;flex: 1 1 auto;overflow: hidden;
            }
            .topbar-right {
                width: auto;
                flex-shrink: 0;
                justify-content: flex-end;
                padding-top: 0;
                border-top: none;
            }
            .topbar-title {
                font-size: 14px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
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
            .stat-grid::-webkit-scrollbar-thumb { background: rgba(15,23,42,.15); border-radius: 10px; }
            .stat-card {
                flex: 0 0 auto;
                width: 150px;
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
            .hscroll-row::-webkit-scrollbar-thumb { background: rgba(15,23,42,.15); border-radius: 10px; }
            .hscroll-row > .card,
            .hscroll-row > .hscroll-col {flex: 0 0 88%;min-width: 260px; scroll-snap-align: start;}
        }

        @media (max-width: 480px) {
            .topbar-sub { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-mark"></div>
        <div class="sidebar-logo-text">
            <span>SiPinjam</span>
            <small>Admin</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="nav-label">Dashboard</span>
        </a>

        <div class="nav-section-label">Pengguna</div>

        <a href="{{ route('admin.pengguna.index') }}"
           class="nav-item {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="nav-label">Kelola Pengguna</span>
        </a>

        <a href="{{ route('admin.ormawa.index') }}"
           class="nav-item {{ request()->routeIs('admin.ormawa*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="nav-label">Kelola Ormawa</span>
        </a>

        <a href="{{ route('admin.pendaftar.index') }}"
           class="nav-item {{ request()->routeIs('admin.pendaftar*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="nav-label">Validasi Pendaftar</span>
        </a>

        <div class="nav-section-label">Peminjaman</div>

        <a href="{{ route('admin.status-peminjaman') }}"
           class="nav-item {{ request()->routeIs('admin.status-peminjaman*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="nav-label">Status Peminjaman</span>
        </a>

        <a href="{{ route('admin.riwayat-peminjaman') }}"
             class="nav-item {{ request()->routeIs('admin.riwayat-peminjaman*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="nav-label">Riwayat Peminjaman</span>
        </a>

        <div class="nav-section-label">Inventaris</div>

        <a href="{{ route('admin.ruangan.index') }}"
           class="nav-item {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span class="nav-label">Kelola Ruangan</span>
        </a>
        <a href="{{ route('admin.daftar-barang') }}"
           class="nav-item {{ request()->routeIs('admin.daftar-barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
            <span class="nav-label">Daftar Barang</span>
        </a>

        <div class="nav-section-label">Laporan</div>

        <a href="{{ route('admin.laporan.unduh') }}"
           class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="nav-label">Unduh Laporan</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
        </div>
        <a href="{{ route('admin.profil.show') }}"
           class="nav-item {{ request()->routeIs('admin.profil*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="nav-label">Profil Saya</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:4px;">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;background:none;cursor:pointer;border:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="nav-label">Keluar</span>
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
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
                <div class="topbar-sub">@yield('subtitle', 'Panel Administrasi')</div>
            </div>
        </div>

        <div class="topbar-right">
            <div style="display:flex;align-items:center;gap:6px;font-size:12.5px;color:#000000;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="live-clock" style="font-variant-numeric:tabular-nums;font-weight:450;letter-spacing:0.5px;"></span>
            </div>
            @yield('topbar-action')
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('info') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
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