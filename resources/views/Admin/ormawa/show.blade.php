@extends('layouts.admin')

@section('title', 'Anggota Ormawa')
@section('subtitle', ($ormawa->nama_organisasi ?? $ormawa->singkatan))

@section('topbar-action')
    <a href="{{ route('admin.ormawa.index') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="display:flex;gap:24px;align-items:center;flex-wrap:wrap;">
        <div style="width:56px;height:56px;border-radius:12px;background:#ede9fe;
                    display:flex;align-items:center;justify-content:center;
                    font-size:20px;font-weight:700;color:#7c3aed;flex-shrink:0;">
            {{ strtoupper(substr($ormawa->singkatan, 0, 2)) }}
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-size:18px;font-weight:600;">
                {{ $ormawa->nama_organisasi ?? $ormawa->singkatan }}
            </div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">
                Kode: <strong>{{ $ormawa->singkatan }}</strong>
                @if($ormawa->kontak)
                    &nbsp;·&nbsp; Kontak: {{ $ormawa->kontak }}
                @endif
                &nbsp;·&nbsp;
                @if($ormawa->status === 'aktif')
                    <span class="badge badge-green">Aktif</span>
                @else
                    <span class="badge badge-gray">Nonaktif</span>
                @endif
            </div>
            @if($ormawa->deskripsi)
                <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">{{ $ormawa->deskripsi }}</div>
            @endif
        </div>
        <a href="{{ route('admin.ormawa.edit', $ormawa->id) }}" class="btn btn-outline btn-sm">Edit Ormawa</a>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Daftar Anggota</span>
        <span class="badge badge-gray">{{ $anggota->count() }} orang</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Nama</th>
                    <th style="width:160px;">NIM</th>
                    <th style="width:120px;">Role</th>
                    <th style="width:110px;">Periode</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:140px;">Bukti</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggota as $user)
                <tr>
                    <td style="color:var(--text-muted);font-size:13px;">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $user->nama }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $user->email }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $user->nim ?? '-' }}</td>
                    <td>
                        @if($user->role === 'ketua')
                            <span class="badge badge-blue">Ketua</span>
                        @else
                            <span class="badge badge-gray">Anggota</span>
                        @endif
                    </td>
                    <td style="font-size:13px;">
                        @if($user->periode_mulai && $user->periode_selesai)
                            {{ $user->periode_mulai }} – {{ $user->periode_selesai }}
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($user->status === 'aktif')
                            <span class="badge badge-green">Aktif</span>
                        @else
                            <span class="badge badge-gray">{{ ucfirst($user->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            @if($user->bukti_ktm)
                                <a href="{{ asset('storage/' . $user->bukti_ktm) }}" target="_blank"
                                   style="font-size:12px;color:var(--primary,#2563eb);text-decoration:none;display:flex;align-items:center;gap:4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                    </svg>
                                    Lihat KTM
                                </a>
                            @else
                                <span style="font-size:12px;color:var(--text-muted);">KTM belum ada</span>
                            @endif

                            @if($user->bukti_sk)
                                <a href="{{ asset('storage/' . $user->bukti_sk) }}" target="_blank"
                                   style="font-size:12px;color:var(--primary,#2563eb);text-decoration:none;display:flex;align-items:center;gap:4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                    </svg>
                                    Lihat SK
                                </a>
                            @else
                                <span style="font-size:12px;color:var(--text-muted);">SK belum ada</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p>Belum ada anggota di ormawa ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection