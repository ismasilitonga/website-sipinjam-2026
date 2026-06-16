<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiPinjam Pamdal – @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 255px;
            --bg: #fafafa;
            --sidebar-bg: #18181b;
            --sidebar-hover: #27272a;
            --sidebar-active: #dc2626;
            --accent: #dc2626;
            --accent-2: #f97316;
            --accent-light: #fee2e2;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e4e4e7;
            --white: #ffffff;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #d97706;
            --radius: 12px;
            --shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 14px rgba(0,0,0,.05);
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            overflow-y: auto; z-index: 100;
        }
        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.63);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-logo-text span { font-family: 'Sora', sans-serif; font-size: 16px; font-weight: 700; color: #fff; display: block; }
        .sidebar-logo-text small { font-size: 10px; color: #ffffff; letter-spacing: .4px; }

        .sidebar-nav { padding: 12px 10px; flex: 1; }
        .nav-label { font-size: 10px; font-weight: 700; color: #94a3b8; letter-spacing: 1.2px; text-transform: uppercase; padding: 10px 10px 4px; }

        .nav-item {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 12px; border-radius: 8px;
            color: #ffffff; text-decoration: none;
            font-size: 13px; font-weight: 500;
            transition: all .15s; margin-bottom: 1px;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: #ffadad; }
        .nav-item.active { background: var(--sidebar-active); color: #fff; }
        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }

        .nav-badge {
            margin-left: auto; background: var(--accent);
            color: #fff; font-size: 11px; font-weight: 700;
            padding: 1px 7px; border-radius: 999px;
        }
        .nav-item.active .nav-badge { background: rgba(255,255,255,.25); }

        .sidebar-footer { padding: 12px 10px; border-top: 1px solid rgba(255,255,255,.05); }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            background: rgba(255,255,255,.04); margin-bottom: 6px;
        }
        .sidebar-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 13px; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-user-name { color: #ffffff; font-size: 13px; font-weight: 600; }
        .sidebar-user-role { color: #7791f8; font-size: 11px; }

        .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        .topbar {
            background: var(--white); border-bottom: 1px solid var(--border);
            padding: 13px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-family: 'Sora', sans-serif; font-size: 17px; font-weight: 700; }
        .topbar-sub   { font-size: 12px; color: var(--text); margin-top: 1px; }

        .clock-strip {
            background: #18181b; color: #a1a1aa;
            padding: 8px 28px; font-size: 12.5px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .clock-strip strong { color: #fff; font-family: 'Sora', sans-serif; font-size: 11px; }
        .page-content { padding: 24px 28px; flex: 1; }

        .alert {
            padding: 12px 16px; border-radius: 10px; font-size: 13.5px; margin-bottom: 18px;
            display: flex; align-items: flex-start; gap: 9px;
        }
        .alert svg { width: 17px; height: 17px; flex-shrink: 0; margin-top: 1px; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info    { background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        .card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); }
        .card-header { padding: 16px 20px 0; display: flex; align-items: center; justify-content: space-between; }
        .card-title  { font-family: 'Sora', sans-serif; font-size: 14.5px; font-weight: 700; }
        .card-body   { padding: 20px; }

        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 22px; }
        .stat-card {
            background: var(--white); border-radius: var(--radius); border: 1px solid var(--border);
            box-shadow: var(--shadow); padding: 18px;
            display: flex; flex-direction: column; gap: 10px;
        }
        .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-value { font-family: 'Sora', sans-serif; font-size: 26px; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 12.5px; color: var(--text-muted); }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { background: #fafafa; text-align: left; padding: 10px 14px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--border); }
        td { padding: 11px 14px; border-bottom: 1px solid #f4f4f5; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafafa; }

        tr.row-waiting td { background: #fff7ed; }
        tr.row-waiting:hover td { background: #ffedd5; }
        tr.row-done td { opacity: .6; }

        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; gap: 4px; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-gray   { background: #f4f4f5; color: #52525b; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }

        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all .15s; font-family: inherit; }
        .btn svg { width: 15px; height: 15px; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #b91c1c; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-warning { background: #d97706; color: #fff; }
        .btn-warning:hover { background: #b45309; }
        .btn-outline { background: transparent; color: var(--text); border: 1.5px solid var(--border); }
        .btn-outline:hover { background: #f4f4f5; }
        .btn-sm { padding: 5px 11px; font-size: 12px; border-radius: 7px; }

        .kunci-flow { display: flex; align-items: center; gap: 6px; font-size: 12.5px; }
        .kunci-step { display: flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-weight: 600; }
        .kunci-step svg { width: 13px; height: 13px; }
        .kunci-step-done    { background: #dcfce7; color: #15803d; }
        .kunci-step-pending { background: #f4f4f5; color: #a1a1aa; }
        .kunci-arrow        { color: #d4d4d8; font-size: 14px; }

        .pagination-wrap { padding: 12px 20px; border-top: 1px solid var(--border); }
        .pagination-wrap .pagination { display: flex; gap: 4px; list-style: none; }
        .pagination-wrap .page-link { padding: 6px 11px; border-radius: 6px; font-size: 13px; color: var(--text); text-decoration: none; border: 1px solid var(--border); background: var(--white); transition: all .15s; }
        .pagination-wrap .page-link:hover { background: var(--accent-light); border-color: var(--accent); }
        .pagination-wrap .active .page-link  { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination-wrap .disabled .page-link { opacity: .4; pointer-events: none; }
        .pagination-wrap .page-link svg { width: 16px; height: 16px; display: block; }

        .empty-state { text-align: center; padding: 48px 20px; color: var(--text-muted); }
        .empty-state svg { width: 44px; height: 44px; margin-bottom: 10px; opacity: .3; }
        .empty-state p { font-size: 14px; }

        .detail-row { display: flex; border-bottom: 1px solid var(--border); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 180px; flex-shrink: 0; padding: 11px 16px; font-size: 13px; font-weight: 600; color: var(--text-muted); background: #fafafa; }
        .detail-value { padding: 11px 16px; font-size: 13.5px; }

        .item-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
        .item-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; transition: transform .15s, box-shadow .15s; }
        .item-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.09); }
        .item-card-img { width: 100%; height: 140px; background: #fafafa; display: flex; align-items: center; justify-content: center; }
        .item-card-img svg { width: 48px; height: 48px; color: #a1a1aa; }
        .item-card-body { padding: 14px 16px; }
        .item-card-title { font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .item-card-sub   { font-size: 12px; color: var(--text-muted); margin-bottom: 10px; }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo" style="padding: 18px 16px 14px; gap: 10px; align-items: center;">
        <div style="width: 42px; height: 42px; flex-shrink: 0;
                    background: url('{{ asset('images/logo.png') }}') center/contain no-repeat;">
        </div>
        <div class="sidebar-logo-text">
            <span>SiPinjam</span>
            <small>PORTAL PAMDAL</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('pamdal.dashboard') }}"
           class="nav-item {{ request()->routeIs('pamdal.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-label">Kunci Ruangan</div>
        <a href="{{ route('pamdal.daftar-peminjaman') }}"
           class="nav-item {{ request()->routeIs('pamdal.daftar-peminjaman') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            Manajemen Kunci
            @php
                $pending = \App\Models\PeminjamanRuangan::where('status','disetujui')->whereNull('waktu_kunci_diambil')->count();
            @endphp
            @if($pending > 0)
                <span class="nav-badge">{{ $pending }}</span>
            @endif
        </a>

        <div class="nav-label">Katalog</div>
        <a href="{{ route('pamdal.daftar-ruangan') }}"
           class="nav-item {{ request()->routeIs('pamdal.daftar-ruangan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Daftar Ruangan
        </a>
        <a href="{{ route('pamdal.daftar-barang') }}"
           class="nav-item {{ request()->routeIs('pamdal.daftar-barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
            Daftar Barang
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}</div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Pamdal' }}</div>
                <div class="sidebar-user-role">Petugas Pamdal</div>
            </div>
        </div>
        <a href="{{ route('pamdal.profil.show') }}"
            class="nav-item {{ request()->routeIs('pamdal.profil*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
         Profil Saya
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;background:none;cursor:pointer;border:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:17px;height:17px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

<div class="main-wrap">

    <div class="clock-strip">
        <span>📅 <strong id="tanggalHariIni"></strong></span>
        <span style="display:flex;align-items:center;gap:5px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <strong id="jamSekarang"></strong>
        </span>
        <span style="font-size:11px;color:#ffffff;font-weight:600;">{{ auth()->user()->name ?? 'Pamdal' }} · Shift aktif</span>
    </div>

    <header class="topbar">
        <div>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-sub">@yield('subtitle', 'Portal Pamdal · SiPinjam')</div>
        </div>
        <div>@yield('topbar-action')</div>
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

        @yield('content')
    </main>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const opt = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
        document.getElementById('tanggalHariIni').textContent = now.toLocaleDateString('id-ID', opt);
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('jamSekarang').textContent = h + ':' + m + ':' + s;
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
@stack('scripts')
</body>
</html>