@extends('layouts.pic')

@section('title', 'Profil Saya')
@section('subtitle', 'Informasi akun PIC')

@section('content')

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start;">

    <div class="card" style="text-align:center;">
        <div class="card-body" style="padding:32px 20px;">

            <div style="
                width:80px;
                height:80px;
                border-radius:50%;
                background:linear-gradient(135deg,#7c3aed,#06b6d4);
                display:flex;
                align-items:center;
                justify-content:center;
                font-family:'Sora',sans-serif;
                font-size:32px;
                font-weight:700;
                color:#fff;
                margin:0 auto 16px;
            ">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div style="font-family:'Sora',sans-serif;font-size:18px;font-weight:700;">
                {{ $user->name }}
            </div>

            <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">
                {{ $user->email }}
            </div>

            <div style="margin-top:10px;">
                <span class="badge badge-purple">PIC</span>
                <span class="badge badge-green" style="margin-left:4px;">Aktif</span>
            </div>

            @if($user->organisasi)
                <div style="margin-top:14px;padding:12px;background:#f5f3ff;border-radius:10px;">
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">
                        Organisasi
                    </div>
                    <div style="font-size:13.5px;font-weight:700;color:#6d28d9;">
                        {{ $user->organisasi }}
                    </div>
                </div>
            @endif

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
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ $user->name }}"
                        disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">NIM</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ $user->nim }}"
                      disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    value="{{ $user->email }}"
                    disabled>
            </div>
            <div class="form-group">
                <label class="form-label">Organisasi</label>
                <input
                    type="text"
                    class="form-control"
                    value="{{ $user->organisasi }}"
                    disabled>
            </div>

            <div class="form-group">
                <label class="form-label">Peran</label>
                <input
                    type="text"
                    class="form-control"
                    value="PIC"
                    disabled>
            </div>

            <div style="
                margin-top:18px;
                padding:12px;
                background:#f5f3ff;
                border-radius:10px;
                border-left:4px solid #7c3aed;
            ">
                <small style="color:#6b7280;">
                    Akun PIC dikelola oleh Admin.
                    Hubungi Admin apabila terdapat perubahan data.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection