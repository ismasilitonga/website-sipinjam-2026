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
            --sidebar-w: 260px;
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
            --radius: 12px;
            --shadow: 0 1px 3px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.05);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-logo span {
            font-family: 'Outfit', sans-serif;
            font-size: 20px; font-weight: 700;
            color: #fff; letter-spacing: -.3px;
        }
        .sidebar-logo small {
            display: block;
            font-size: 10px; color: #64748b;
            margin-top: 2px; letter-spacing: .5px; text-transform: uppercase;
        }

        .sidebar-nav { padding: 16px 12px; flex: 1; }

        .nav-section-label {
            font-size: 10px; font-weight: 600;
            color: #94a3b8; letter-spacing: 1px;
            text-transform: uppercase;
            padding: 12px 8px 6px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #ffffff;
            text-decoration: none;
            font-size: 13px; font-weight: 500;
            transition: all .15s;
            margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: #e2e8f0; }
        .nav-item.active { background: var(--sidebar-active); color: #fff; }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: #94a3b8; font-size: 13px;
        }
        .sidebar-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--accent); display: flex;
            align-items: center; justify-content: center;
            color: #fff; font-size: 13px; font-weight: 600;
        }

        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 16px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px; font-weight: 600;
            color: var(--text);
        }
        .topbar-sub {
            font-size: 13px; color: var(--text-muted); margin-top: 1px;
        }

        .page-content { padding: 28px; flex: 1; }

        .alert {
            padding: 12px 16px; border-radius: 8px;
            font-size: 14px; margin-bottom: 20px;
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
        }
        .card-header {
            padding: 18px 20px 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 15px; font-weight: 600;
        }
        .card-body { padding: 20px; }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px; margin-bottom: 28px;
        }
        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            padding: 20px;
            display: flex; flex-direction: column; gap: 12px;
        }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon svg { width: 22px; height: 22px; }
        .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 30px; font-weight: 700;
            line-height: 1;
        }
        .stat-label { font-size: 13px; color: var(--text-muted); }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th {
            background: #f8fafc; text-align: left;
            padding: 11px 14px;
            font-size: 12px; font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase; letter-spacing: .5px;
            border-bottom: 1px solid var(--border);
        }
        td { padding: 13px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 999px;
            font-size: 12px; font-weight: 500;
        }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 500;
            cursor: pointer; border: none; text-decoration: none;
            transition: all .15s;
        }
        .btn svg { width: 15px; height: 15px; }
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
        .btn-sm { padding: 5px 10px; font-size: 12px; border-radius: 6px; }
        .btn-icon { padding: 6px; border-radius: 7px; }

        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block; font-size: 13px; font-weight: 500;
            color: var(--text); margin-bottom: 6px;
        }
        .form-control, .form-select {
            width: 100%; padding: 9px 12px;
            border: 1px solid var(--border); border-radius: 8px;
            font-size: 14px; font-family: inherit;
            background: var(--white); color: var(--text);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        .form-grid-3{
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:16px;
    }

        .form-actions{
        display:flex;
        gap:10px;
        margin-top:20px;
    }

        .preview-image{
        max-width:300px;
        border-radius:8px;
        border:1px solid var(--border);
        margin-top:10px;
    }
        .pagination-wrap { padding: 14px 20px; border-top: 1px solid var(--border); }
        .pagination-wrap .pagination { display: flex; gap: 4px; list-style: none; }
        .pagination-wrap .page-link {
            padding: 6px 11px; border-radius: 6px; font-size: 13px;
            color: var(--text); text-decoration: none;
            border: 1px solid var(--border); background: var(--white);
            transition: all .15s;
        }
        .pagination-wrap .page-link:hover { background: var(--accent-light); border-color: var(--accent); }
        .pagination-wrap .active .page-link { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination-wrap .disabled .page-link { opacity: .4; pointer-events: none; }
        .pagination-wrap .page-link svg {
            width: 16px;
            height: 16px;
            display: block;
        }

        .empty-state {
            text-align: center; padding: 60px 20px; color: var(--text-muted);
        }
        .empty-state svg { width: 48px; height: 48px; margin-bottom: 12px; opacity: .4; }
        .empty-state p { font-size: 15px; }

        .detail-row { display: flex; gap: 0; border-bottom: 1px solid var(--border); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            width: 200px; flex-shrink: 0;
            padding: 12px 16px;
            font-size: 13px; font-weight: 500; color: var(--text-muted);
            background: #f8fafc;
        }
        .detail-value { padding: 12px 16px; font-size: 14px; }

        .item-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
        .item-card {
            background: var(--white); border-radius: var(--radius);
            border: 1px solid var(--border); box-shadow: var(--shadow);
            overflow: hidden; transition: transform .15s, box-shadow .15s;
        }
        .item-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.09); }
        .item-card-img {
            width: 100%; height: 140px;
            background: #fafafa;
            display: flex; align-items: center; justify-content: center;
        }
        .item-card-img svg { width: 48px; height: 48px; color: #a1a1aa; }
        .item-card-body { padding: 14px 16px; }
        .item-card-title { font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .item-card-sub   { font-size: 12px; color: var(--text-muted); margin-bottom: 10px; }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
<div class="sidebar-logo" style="display: flex; padding: 18px 16px 14px; gap: 10px; align-items: center;">
    <div style="width: 42px; height: 42px; flex-shrink: 0;
                background: url('{{ asset('images/logo.png') }}') center/contain no-repeat;">
    </div>
    <div>
        <span style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -.3px; display: block;">SiPinjam</span>
        <small style="display: block; font-size: 11px; color: #ffffff; margin-top: 2px; letter-spacing: .5px; text-transform: uppercase;">Admin</small>
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
            Dashboard
        </a>

        <div class="nav-section-label">Pengguna</div>

        <a href="{{ route('admin.pengguna.index') }}"
           class="nav-item {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Kelola Pengguna
        </a>

        <a href="{{ route('admin.ormawa.index') }}"
           class="nav-item {{ request()->routeIs('admin.ormawa*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Kelola Ormawa
        </a>

        <a href="{{ route('admin.pendaftar.index') }}"
           class="nav-item {{ request()->routeIs('admin.pendaftar*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Validasi Pendaftar
        </a>

        <div class="nav-section-label">Peminjaman</div>

        <a href="{{ route('admin.status-peminjaman') }}"
           class="nav-item {{ request()->routeIs('admin.status-peminjaman*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Status Peminjaman
        </a>

        <a href="{{ route('admin.riwayat-peminjaman') }}"
           class="nav-item {{ request()->routeIs('admin.riwayat-peminjaman') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Peminjaman
        </a>

        <div class="nav-section-label">Inventaris</div>

       <a href="{{ route('admin.ruangan.index') }}"
            class="nav-item {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        Kelola Ruangan
       </a>
        <a href="{{ route('admin.daftar-barang') }}"
           class="nav-item {{ request()->routeIs('admin.daftar-barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
            Daftar Barang
        </a>

        <div class="nav-section-label">Laporan</div>

        <a href="{{ route('admin.laporan.unduh') }}"
            class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Unduh Laporan
    </a>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div style="color:#e2e8f0;font-size:13px;font-weight:500;">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div style="font-size:11px;color:#7e9bf1;">Administrator</div>
            </div>
        </div>
        <a href="{{ route('admin.profil.show') }}"
        class="nav-item {{ request()->routeIs('admin.profil*') ? 'active' : '' }}">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
    Profil Saya
</a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:8px;">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;background:none;cursor:pointer;border:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;">
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
        <div>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-sub">@yield('subtitle', 'Panel Administrasi')</div>
        </div>

    <div style="display:flex;align-items:center;gap:14px;">
    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#000000;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;flex-shrink:0;">
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
</script>
@stack('scripts')

@stack('scripts')
</body>
</html>
