<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiPinjam PIC – @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 260px;
            --bg: #f8f7ff;
            --sidebar-bg: #1e1b4b;
            --sidebar-hover: #2e2a5e;
            --sidebar-active: #7c3aed;
            --accent: #7c3aed;
            --accent-2: #06b6d4;
            --accent-light: #ede9fe;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e5e7eb;
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
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .sidebar-logo span { font-family: 'Sora', sans-serif; font-size: 16px; font-weight: 700; color: #fff; display: block; }
        .sidebar-logo small { font-size: 10px; color: #4c4980; letter-spacing: .4px; }

        .sidebar-nav { padding: 12px 10px; flex: 1; }
        .nav-section { font-size: 10px; font-weight: 700; color: #94a3b8; letter-spacing: 1.2px; text-transform: uppercase; padding: 10px 10px 4px; }

        .nav-item {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 12px; border-radius: 8px;
            color: #ffffff; text-decoration: none;
            font-size: 13px; font-weight: 500;
            transition: all .15s; margin-bottom: 1px;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: #c4b5fd; }
.nav-item.active {
    background: linear-gradient(135deg, #7c3aed, #9333ea);
    color: #fff;
    border-radius: 12px;

    box-shadow:
        0 6px 18px rgba(124,58,237,.45),
        0 0 24px rgba(124,58,237,.30);

    transform: translateX(2px);
}        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }

        .nav-badge {
            margin-left: auto; font-size: 11px; font-weight: 700;
            padding: 1px 7px; border-radius: 999px;
            background: var(--accent); color: #fff;
        }
        .nav-item.active .nav-badge { background: rgba(255,255,255,.25); }

        .sidebar-footer { padding: 12px 10px; border-top: 1px solid rgba(255,255,255,.05); }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            background: rgba(124,58,237,.12); margin-bottom: 6px;
        }
        .sidebar-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
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
        .topbar-sub   { font-size: 12px; color: var(--text-muted); margin-top: 1px; }

        .page-content { padding: 24px 28px; flex: 1; }

        .alert {
            padding: 12px 16px; border-radius: 10px; font-size: 13.5px; margin-bottom: 18px;
            display: flex; align-items: flex-start; gap: 9px;
        }
        .alert svg { width: 17px; height: 17px; flex-shrink: 0; margin-top: 1px; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info    { background: #f5f3ff; color: #5b21b6; border: 1px solid #ddd6fe; }
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
        .stat-icon { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-value { font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 12.5px; color: var(--text-muted); }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { background: #fafafa; text-align: left; padding: 10px 14px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--border); }
        td { padding: 11px 14px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fdfcff; }

        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-gray   { background: #f3f4f6; color: #52525b; }
        .badge-cyan   { background: #ecfeff; color: #0e7490; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }

        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all .15s; font-family: inherit; }
        .btn svg { width: 15px; height: 15px; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #6d28d9; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-danger  { background: #dc2626; color: #fff; }
        .btn-danger:hover  { background: #b91c1c; }
        .btn-outline { background: transparent; color: var(--text); border: 1.5px solid var(--border); }
        .btn-outline:hover { background: #f9fafb; }
        .btn-sm { padding: 5px 11px; font-size: 12px; border-radius: 7px; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-control, .form-select, textarea.form-control {
            width: 100%; padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 8px;
            font-size: 13px; font-family: inherit; background: var(--white); color: var(--text);
            transition: border-color .15s, box-shadow .15s; outline: none;
        }
        .form-control:focus, .form-select:focus, textarea.form-control:focus {
            border-color: var(--accent); box-shadow: 0 0 0 3px rgba(124,58,237,.1);
        }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-hint  { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

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
        .detail-label { width: 180px; flex-shrink: 0; padding: 11px 16px; font-size: 13px; font-weight: 600; color: var(--text-muted); background: #fafaf9; }
        .detail-value { padding: 11px 16px; font-size: 13.5px; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 200; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal-box { background: #fff; border-radius: 16px; padding: 28px; width: 100%; max-width: 460px; box-shadow: 0 20px 60px rgba(0,0,0,.18); }
        .modal-title { font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 700; margin-bottom: 14px; }

        .foto-preview { width: 100%; height: 140px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border); }
        .foto-placeholder {
            width: 100%; height: 140px; border-radius: 8px;
            background: linear-gradient(135deg, #f5f3ff, #ede9fe);
            display: flex; align-items: center; justify-content: center;
            border: 1.5px dashed #c4b5fd; color: #8b5cf6; font-size: 13px;
            cursor: pointer; transition: all .15s;
        }
        .foto-placeholder:hover { background: #ede9fe; }

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
<div class="sidebar-logo" style="padding: 18px 16px 14px; gap: 10px; align-items: center;">
    <div style="width: 42px; height: 42px; flex-shrink: 0;
                background: url('{{ asset('images/logo.png') }}') center/contain no-repeat;">
    </div>
    <div class="sidebar-logo-text">
        <span>SiPinjam</span>
        <small style="color: #ffffff;">PORTAL PIC</small>
    </div>
</div>

    <nav class="sidebar-nav">
        <div class="nav-section">Utama</div>
        <a href="{{ route('pic.dashboard') }}"
           class="nav-item {{ request()->routeIs('pic.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-section">Pengajuan</div>
        <a href="{{ route('pic.daftar-pengajuan') }}"
           class="nav-item {{ request()->routeIs('pic.daftar-pengajuan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Validasi Pengajuan Ruangan
            @php 
                $lantaiPic = (string) auth()->user()->lantai_pic;
                $pending = \App\Models\PeminjamanRuangan::where('status','menunggu_pic')
                ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantaiPic))
                ->count(); 
            @endphp
            @if($pending > 0)
                <span class="nav-badge">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('pic.status-peminjaman') }}"
           class="nav-item {{ request()->routeIs('pic.status-peminjaman') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Status Peminjaman
        </a>
        <a href="{{ route('pic.riwayat-peminjaman') }}"
           class="nav-item {{ request()->routeIs('pic.riwayat-peminjaman') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Peminjaman Ruangan
        </a>

        </a>
        <a href="{{ route('pic.riwayat.barang') }}"
           class="nav-item {{ request()->routeIs('pic.riwayat.barang') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
            Validasi Peminjaman Barang
        
        </a>
        <div class="nav-section">Serah Terima</div>
        <a href="{{ route('pic.serah-terima') }}"
           class="nav-item {{ request()->routeIs('pic.serah-terima') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Serah Terima Barang
        </a>
        <div class="nav-section">Inventaris</div>
        <a href="{{ route('pic.ruangan.index') }}"
           class="nav-item {{ request()->routeIs('pic.ruangan*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Kelola Ruangan
        </a>
        <a href="{{ route('pic.barang.index') }}"
           class="nav-item {{ request()->routeIs('pic.barang*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
            Kelola Barang
        </a>

        <div class="nav-section">Laporan</div>
        <a href="{{ route('pic.laporan-insiden') }}"
           class="nav-item {{ request()->routeIs('pic.laporan-insiden') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Laporan Insiden
            @php $insidenAktif = \App\Models\Insiden::whereIn('status',['dilaporkan','ditindaklanjuti'])->count(); @endphp
            @if($insidenAktif > 0)
                <span class="nav-badge">{{ $insidenAktif }}</span>
            @endif
        </a>
    <a href="{{ route('pic.laporan.unduh') }}"
    class="nav-item {{ request()->routeIs('pic.laporan.unduh') ? 'active' : '' }}">            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Unduh Laporan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}</div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name ?? 'PIC' }}</div>
                <div class="sidebar-user-role">Person In Charge</div>
            </div>
        </div>

        <a href="{{ route('pic.profil.show') }}"
     class="nav-item {{ request()->routeIs('pic.profil*') ? 'active' : '' }}">
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
    <header class="topbar">
        <div>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-sub">@yield('subtitle', 'Portal PIC · SiPinjam')</div>
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