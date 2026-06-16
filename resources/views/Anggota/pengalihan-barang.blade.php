@extends('layouts.anggota')

@section('title', 'Pengalihan Barang')
@section('subtitle', 'Alihkan tanggung jawab barang ke anggota lain atau terima barang dari orang lain')

@section('content')

@if($pengalihanMasuk->isNotEmpty())
<div class="alert alert-warning">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:17px;height:17px;flex-shrink:0;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>
    Kamu memiliki <strong>{{ $pengalihanMasuk->count() }} permintaan pengalihan barang</strong> yang menunggu konfirmasi kamu.
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    <div>
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header" style="padding-bottom:16px;">
                <span class="card-title">📬 Permintaan Masuk</span>
                <span class="badge {{ $pengalihanMasuk->isNotEmpty() ? 'badge-orange' : 'badge-gray' }}">
                    {{ $pengalihanMasuk->count() }}
                </span>
            </div>
            @if($pengalihanMasuk->isEmpty())
            <div class="empty-state" style="padding:32px 20px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p>Tidak ada permintaan pengalihan masuk.</p>
            </div>
            @else
            <div>
                @foreach($pengalihanMasuk as $pg)
                <div style="padding:16px 20px;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                    <div style="display:flex;gap:10px;align-items:flex-start;margin-bottom:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:#ffedd5;
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:14px;font-weight:700;color:#c2410c;flex-shrink:0;">
                            {{ strtoupper(substr($pg->dariUser->name ?? 'A', 0, 1)) }}
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:13px;font-weight:600;">
                                {{ $pg->dariUser->name ?? '-' }} mengalihkan barang kepadamu
                            </div>
                            <div style="font-size:12px;color:var(--text-muted);">
                                {{ $pg->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <div style="background:#f8fafc;border-radius:8px;padding:10px 12px;margin-bottom:12px;font-size:13px;">
                        <div style="font-weight:600;">{{ $pg->peminjamanBarang->barang->nama ?? '-' }}</div>
                        <div style="color:var(--text-muted);font-size:12px;margin-top:2px;">
                            Jumlah: {{ $pg->peminjamanBarang->jumlah ?? '-' }}
                            {{ $pg->peminjamanBarang->barang->satuan ?? '' }}
                        </div>
                        @if($pg->alasan)
                        <div style="margin-top:6px;font-size:12px;color:var(--text-muted);">
                            <strong>Alasan:</strong> {{ $pg->alasan }}
                        </div>
                        @endif
                    </div>

                    <div style="display:flex;gap:8px;">
                        <form method="POST" action="{{ route('anggota.pengalihan-barang.konfirmasi', $pg->id) }}"
                              style="flex:1;">
                            @csrf
                            <input type="hidden" name="aksi" value="terima">
                            <button type="submit" class="btn btn-success btn-sm" style="width:100%;justify-content:center;"
                                onclick="return confirm('Terima pengalihan barang ini?')">
                                ✓ Terima
                            </button>
                        </form>
                        <form method="POST" action="{{ route('anggota.pengalihan-barang.konfirmasi', $pg->id) }}"
                              style="flex:1;">
                            @csrf
                            <input type="hidden" name="aksi" value="tolak">
                            <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;"
                                onclick="return confirm('Tolak pengalihan barang ini?')">
                                ✕ Tolak
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header" style="padding-bottom:16px;">
                <span class="card-title">📤 Ajukan Pengalihan</span>
            </div>
            <div class="card-body">
                @if($pengalihanKeluar)
            <div class="alert alert-warning">
            Menunggu konfirmasi dari
            <strong>{{ $pengalihanKeluar->keUser->name ?? 'Penerima' }}</strong>
            untuk barang
            <strong>{{ $pengalihanKeluar->peminjamanBarang->barang->nama ?? '-' }}</strong>.
            </div>
            @elseif($peminjamanAktif->isEmpty())

                <div class="empty-state" style="padding:28px 0;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
                    </svg>
                    <p>Tidak ada barang yang bisa kamu alihkan saat ini.</p>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                        Barang harus berstatus disetujui dan sudah diserahkan.
                    </div>
                </div>
                @else
                <form method="POST" action="{{ route('anggota.pengalihan-barang.store') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Pilih Barang yang Dialihkan <span style="color:var(--danger)">*</span></label>
                        <select name="peminjaman_barang_id" class="form-select" required>
                            <option value="">-- Pilih barang --</option>
                            @foreach($peminjamanAktif as $pb)
                            <option value="{{ $pb->id }}"
                                {{ old('peminjaman_barang_id') == $pb->id ? 'selected' : '' }}>
                                {{ $pb->barang->nama ?? '-' }}
                                ({{ $pb->jumlah }} {{ $pb->barang->satuan ?? '' }})
                            </option>
                            @endforeach
                        </select>
                        @error('peminjaman_barang_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">NIM / Nama Penerima <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="ke_user_id" class="form-control"
                               value="{{ old('ke_user_id') }}"
                               placeholder="Masukkan ID pengguna penerima"
                               list="userList" required>
                        @error('ke_user_id') <div class="form-error">{{ $message }}</div> @enderror
                        <div class="form-hint">Masukkan user ID anggota yang akan menerima barang.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alasan Pengalihan <span style="color:var(--danger)">*</span></label>
                        <textarea name="alasan" class="form-control" rows="3"
                                  placeholder="Jelaskan alasan pengalihan barang ini" required>{{ old('alasan') }}</textarea>
                        @error('alasan') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="alert alert-info" style="margin-bottom:16px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Penerima perlu mengkonfirmasi pengalihan ini. Tanggung jawab berpindah setelah dikonfirmasi.
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        Kirim Permintaan Pengalihan
                    </button>
                </form>
                @endif
            </div>
        </div>

        @if($peminjamanAktif->isNotEmpty())
        <div class="card" style="margin-top:16px;">
            <div class="card-header" style="padding-bottom:12px;">
                <span class="card-title">Barang Aktif Kamu</span>
            </div>
            @foreach($peminjamanAktif as $pb)
            <div style="padding:12px 20px;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                <div style="font-weight:600;font-size:13px;">{{ $pb->barang->nama ?? '-' }}</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                    {{ $pb->jumlah }} {{ $pb->barang->satuan ?? '' }} ·
                    Diserahkan {{ \Carbon\Carbon::parse($pb->waktu_diserahkan)->format('d M Y') }}
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection
