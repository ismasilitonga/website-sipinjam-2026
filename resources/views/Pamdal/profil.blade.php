@extends('layouts.pamdal')

@section('title', 'Profil Saya')
@section('subtitle', 'Informasi akun Pamdal')

@section('content')

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start;">

    <div class="card" style="text-align:center;">
        <div class="card-body" style="padding:32px 20px;">

            <div style="
                width:80px;height:80px;border-radius:50%;
                background:linear-gradient(135deg,#dc2626,#f97316);
                display:flex;align-items:center;justify-content:center;
                font-family:'Outfit',sans-serif;font-size:32px;font-weight:700;
                color:#fff;margin:0 auto 16px;">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>

            <div style="font-family:'Outfit',sans-serif;font-size:18px;font-weight:700;">
                {{ $user->nama }}
            </div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">
                {{ $user->email }}
            </div>

            <div style="margin-top:10px;">
                <span class="badge badge-red">Pamdal</span>
                <span class="badge badge-green" style="margin-left:4px;">Aktif</span>
            </div>

            <div style="margin-top:14px;padding:12px;background:#fef2f2;border-radius:10px;">
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">Peran</div>
                <div style="font-size:13.5px;font-weight:700;color:#dc2626;">Petugas Pamdal</div>
            </div>

            <div style="margin-top:14px;font-size:12px;color:var(--text-muted);">
                Bergabung {{ $user->created_at->format('M Y') }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Informasi Profil</span>
        </div>
        <div class="card-body">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:500;color:var(--text);margin-bottom:5px;">Nama Lengkap</label>
                    <input type="text"
                        value="{{ $user->nama }}"
                        disabled
                        style="width:100%;padding:8px 11px;border:1px solid var(--border);border-radius:7px;font-size:13px;font-family:inherit;background:#f9fafb;color:var(--text-muted);outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:500;color:var(--text);margin-bottom:5px;">ID Pamdal</label>
                    <input type="text"
                        value="PMD-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}"
                        disabled
                        style="width:100%;padding:8px 11px;border:1px solid var(--border);border-radius:7px;font-size:13px;font-family:inherit;background:#f9fafb;color:var(--text-muted);outline:none;">
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:12.5px;font-weight:500;color:var(--text);margin-bottom:5px;">Email</label>
                <input type="email"
                    value="{{ $user->email }}"
                    disabled
                    style="width:100%;padding:8px 11px;border:1px solid var(--border);border-radius:7px;font-size:13px;font-family:inherit;background:#f9fafb;color:var(--text-muted);outline:none;">
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:12.5px;font-weight:500;color:var(--text);margin-bottom:5px;">No. Telepon</label>
                <input type="text"
                    value="{{ $user->no_hp ?? '-' }}"
                    disabled
                    style="width:100%;padding:8px 11px;border:1px solid var(--border);border-radius:7px;font-size:13px;font-family:inherit;background:#f9fafb;color:var(--text-muted);outline:none;">
            </div>

             <div style="margin-bottom:14px;">
                <label style="display:block;font-size:12.5px;font-weight:500;color:var(--text);margin-bottom:5px;">Peran</label>
                <input type="text"
                    value="Petugas Pamdal"
                    disabled
                    style="width:100%;padding:8px 11px;border:1px solid var(--border);border-radius:7px;font-size:13px;font-family:inherit;background:#f9fafb;color:var(--text-muted);outline:none;">
            </div>

            <div style="padding:12px;background:#fef2f2;border-radius:10px;border-left:4px solid #dc2626;">
                <small style="color:#7f1d1d;">
                    Akun Pamdal dikelola oleh Admin.
                    Hubungi Admin apabila terdapat perubahan data.
                </small>
            </div>

        </div>
    </div>

</div>
@endsection