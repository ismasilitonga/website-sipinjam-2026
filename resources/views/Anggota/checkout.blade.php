@extends('layouts.anggota')

@section('title', 'Check-out Ruangan')
@section('subtitle', 'Selesaikan sesi penggunaan ruangan yang sedang berlangsung')

@section('content')

@if($peminjaman_ruangan->isEmpty())
<div class="card">
    <div class="empty-state" style="padding:64px 20px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        <p style="font-size:15px;font-weight:600;color:var(--text);margin-bottom:6px;">
            Tidak ada ruangan yang sedang digunakan
        </p>
        <p style="font-size:13.5px;">
            Halaman ini hanya menampilkan peminjaman yang sudah check-in (<em>ongoing</em>).
        </p>
        <div style="margin-top:20px;">
            <a href="{{ route('anggota.checkin') }}" class="btn btn-primary">Ke Halaman Check-in</a>
        </div>
    </div>
</div>

@else

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
    @foreach($peminjaman_ruangan as $p)
    @php
        $mulai    = \Carbon\Carbon::parse($p->tanggal_mulai);
        $selesai  = \Carbon\Carbon::parse($p->tanggal_selesai);
        $durasi   = $mulai->diffInMinutes(now());
    @endphp
    <div class="card" style="border-left:4px solid #06b6d4;">
        <div class="card-body">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                <div>
                    <div style="font-family:'Sora',sans-serif;font-size:16px;font-weight:700;">
                        {{ $p->ruangan->nama_ruangan ?? '-' }}
                    </div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">
                        {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lantai '.$p->ruangan->lantai : '' }}
                    </div>
                </div>
                <span class="badge badge-cyan">Sedang Dipakai</span>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                <div style="background:#ecfeff;border-radius:8px;padding:10px 12px;">
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">CHECK-IN</div>
                    <div style="font-family:'Sora',sans-serif;font-size:18px;font-weight:700;color:#0891b2;">
                        {{ \Carbon\Carbon::parse($p->checkIn->waktu_checkin ?? $p->tanggal_mulai)->format('H:i') }}
                    </div>
                </div>
                <div style="background:#f8fafc;border-radius:8px;padding:10px 12px;">
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">SELESAI</div>
                    <div style="font-family:'Sora',sans-serif;font-size:18px;font-weight:700;
                                color:{{ now()->gt($selesai) ? '#dc2626' : 'var(--text)' }};">
                        {{ $selesai->format('H:i') }}
                    </div>
                </div>
            </div>

            <div style="background:#f0f9ff;border-radius:8px;padding:10px 12px;margin-bottom:14px;
                        display:flex;align-items:center;gap:8px;font-size:13px;">
                <span>⏱</span>
                <span>Sudah digunakan selama <strong>{{ $durasi }} menit</strong></span>
            </div>

            @if(now()->gt($selesai))
            <div class="alert alert-warning" style="margin-bottom:12px;padding:10px 12px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Waktu penggunaan sudah berakhir. Segera lakukan check-out!
            </div>
            @endif

            {{-- Tombol: buka modal konfirmasi, bukan confirm() --}}
            <button type="button" class="btn btn-primary" style="width:100%;justify-content:center;"
                onclick="bukaModalCheckout('{{ $p->id }}', '{{ addslashes($p->ruangan->nama_ruangan ?? '') }}')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
                Check-out Sekarang
            </button>
        </div>
    </div>
    @endforeach
</div>

<div class="alert alert-info" style="margin-top:16px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:17px;height:17px;flex-shrink:0;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Setelah check-out, kembalikan kunci ruangan kepada petugas <strong>Pamdal</strong>.
</div>
@endif

{{-- ===== MODAL KONFIRMASI CHECK-OUT ===== --}}
<div id="modalCheckout" style="
    display:none;position:fixed;inset:0;z-index:999;
    background:rgba(0,0,0,0.45);
    align-items:center;justify-content:center;">

    <div style="
        background:#fff;border-radius:14px;padding:28px 28px 24px;
        width:100%;max-width:420px;margin:16px;
        box-shadow:0 20px 60px rgba(0,0,0,0.2);">

        {{-- Header --}}
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
            <div style="
                width:36px;height:36px;border-radius:50%;
                background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg fill="none" stroke="#2563eb" viewBox="0 0 24 24" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
            </div>
            <h3 style="font-family:'Sora',sans-serif;font-size:16px;font-weight:700;margin:0;">
                Konfirmasi Check-out
            </h3>
        </div>

        {{-- Body --}}
        <p style="font-size:14px;color:var(--text-muted);margin:0 0 6px;">
            Anda akan melakukan check-out dari ruangan:
        </p>
        <p id="modalNamaRuangan" style="font-size:15px;font-weight:600;color:var(--text);margin:0 0 18px;"></p>
        <p style="font-size:13px;color:var(--text-muted);margin:0 0 22px;">
            Pastikan ruangan sudah bersih dan kunci siap dikembalikan ke petugas <strong>Pamdal</strong>.
        </p>

        {{-- Form tersembunyi --}}
        <form id="formCheckout" method="POST" action="{{ route('anggota.checkout.store') }}">
            @csrf
            <input type="hidden" id="inputPeminjamanId" name="peminjaman_id" value="">
        </form>

        {{-- Tombol --}}
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button type="button" onclick="tutupModalCheckout()"
                style="padding:9px 20px;border-radius:8px;border:1px solid #e2e8f0;
                       background:#fff;font-size:14px;cursor:pointer;font-weight:500;">
                Batal
            </button>
            <button type="button" onclick="submitCheckout()"
                class="btn btn-primary" style="padding:9px 20px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
                Check-out Sekarang
            </button>
        </div>
    </div>
</div>

<script>
    function bukaModalCheckout(id, namaRuangan) {
        document.getElementById('inputPeminjamanId').value = id;
        document.getElementById('modalNamaRuangan').textContent = namaRuangan;
        const modal = document.getElementById('modalCheckout');
        modal.style.display = 'flex';
    }

    function tutupModalCheckout() {
        document.getElementById('modalCheckout').style.display = 'none';
    }

    function submitCheckout() {
        document.getElementById('formCheckout').submit();
    }

    // Tutup modal jika klik backdrop
    document.getElementById('modalCheckout').addEventListener('click', function(e) {
        if (e.target === this) tutupModalCheckout();
    });
</script>

@endsection