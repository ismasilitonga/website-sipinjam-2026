@extends('layouts.anggota')

@section('title', 'Check-out Ruangan')
@section('subtitle', 'Selesaikan sesi penggunaan ruangan yang sedang berlangsung')

@section('content')

@if($sesi_list->isEmpty())
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
            Halaman ini menampilkan peminjaman yang sudah check-in dan belum check-out,
            termasuk sesi yang telat di-checkout dari tanggal sebelumnya.
        </p>
        <div style="margin-top:20px;">
            <a href="{{ route('anggota.checkin') }}" class="btn btn-primary">Ke Halaman Check-in</a>
        </div>
    </div>
</div>

@else

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
    @foreach($sesi_list as $checkin)
    @php
        $p = $checkin->peminjamanRef;
        $waktuCheckin = $checkin->waktu_checkin ?? $checkin->tanggal;
        $bolehKlik = $checkin->boleh_checkout && !$checkin->harus_checkout_dulu;
    @endphp
    <div class="card" style="border-left:4px solid {{ $checkin->checkout_telat ? '#dc2626' : '#06b6d4' }};">
        <div class="card-body">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                <div>
                    <div style="font-family:'Sora',sans-serif;font-size:16px;font-weight:700;">
                        {{ $p->ruangan->nama_ruangan ?? '-' }}
                    </div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">
                        {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lantai '.$p->ruangan->lantai : '' }}
                        · Sesi {{ \Carbon\Carbon::parse($checkin->tanggal)->translatedFormat('d F Y') }}
                    </div>
                </div>
                @if($checkin->checkout_telat)
                    <span class="badge" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">Terlambat</span>
                @else
                    <span class="badge badge-cyan">Sedang Dipakai</span>
                @endif
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                <div style="background:#ecfeff;border-radius:8px;padding:10px 12px;">
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">CHECK-IN</div>
                    <div style="font-family:'Sora',sans-serif;font-size:18px;font-weight:700;color:#0891b2;">
                        {{ \Carbon\Carbon::parse($waktuCheckin)->format('H:i') }}
                    </div>
                </div>
                <div style="background:#f8fafc;border-radius:8px;padding:10px 12px;">
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">
                        {{ $checkin->checkout_telat ? 'SEHARUSNYA SELESAI' : 'SELESAI' }}
                    </div>
                    <div style="font-family:'Sora',sans-serif;font-size:18px;font-weight:700;
                                color:{{ now()->gt($checkin->batas_checkout) ? '#dc2626' : 'var(--text)' }};">
                        {{ $checkin->checkout_telat ? $checkin->batas_checkout->format('d M, H:i') : $checkin->batas_checkout->format('H:i') }}
                    </div>
                </div>
            </div>

            <div style="background:#f0f9ff;border-radius:8px;padding:10px 12px;margin-bottom:14px;
                        display:flex;align-items:center;gap:8px;font-size:13px;">
                <span>⏱</span>
                <span>Sudah digunakan selama
                    <strong class="durasi-timer" data-checkin="{{ \Carbon\Carbon::parse($waktuCheckin)->toDateTimeString() }}">
                        00:00:00
                    </strong>
                </span>
            </div>

            @if($checkin->harus_checkout_dulu)
            <div class="form-error" style="margin-bottom:12px;background:#fef2f2;border:1px solid #fecaca;
                        padding:10px 12px;border-radius:8px;">
                Ada sesi dari tanggal yang lebih lama pada peminjaman ini yang belum di-checkout.
                Selesaikan sesi yang lebih lama terlebih dahulu sebelum bisa checkout sesi ini.
            </div>
            @elseif(!$checkin->boleh_checkout)
            <div id="checkoutWarning-{{ $checkin->id }}"
                 class="form-error"
                 style="display:block;margin-bottom:12px;background:#fef2f2;border:1px solid #fecaca;
                        padding:10px 12px;border-radius:8px;">
                Belum waktunya check-out. Ruangan wajib digunakan sampai jam
                <strong>{{ $checkin->batas_checkout->format('H:i') }}</strong> sesuai jadwal peminjaman
                sebelum bisa check-out.
            </div>
            @else
            <div id="checkoutWarning-{{ $checkin->id }}" class="form-error" style="display:none;margin-bottom:12px;"></div>
            @endif

            @if($checkin->checkout_telat)
            <div class="alert alert-warning" style="margin-bottom:12px;padding:10px 12px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Sesi ini seharusnya sudah check-out pada
                <strong>{{ $checkin->batas_checkout->translatedFormat('d F Y, H:i') }}</strong>.
                Segera lakukan check-out sekarang.
            </div>
            @elseif(now()->gt($checkin->batas_checkout))
            <div class="alert alert-warning" style="margin-bottom:12px;padding:10px 12px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Waktu penggunaan hari ini sudah berakhir. Segera lakukan check-out!
            </div>
            @endif

            <button type="button"
                id="btnCheckout-{{ $checkin->id }}"
                class="btn btn-primary"
                style="width:100%;justify-content:center;"
                {{ $bolehKlik ? '' : 'disabled' }}
                onclick="bukaModalCheckout('{{ $checkin->id }}', '{{ addslashes($p->ruangan->nama_ruangan ?? '') }}')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
                <span id="btnCheckoutLabel-{{ $checkin->id }}">
                    {{ $checkin->harus_checkout_dulu ? 'Selesaikan Sesi Lama Dulu' : ($bolehKlik ? 'Check-out Sekarang' : 'Belum Bisa Check-out') }}
                </span>
            </button>

            <span class="checkout-time-data"
                  data-id="{{ $checkin->id }}"
                  data-batas-checkout="{{ $checkin->batas_checkout->toIso8601String() }}"
                  data-harus-checkout-dulu="{{ $checkin->harus_checkout_dulu ? '1' : '0' }}"
                  style="display:none;"></span>
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

<div id="modalCheckout" style="
    display:none;position:fixed;inset:0;z-index:999;
    background:rgba(0,0,0,0.45);
    align-items:center;justify-content:center;">

    <div style="
        background:#fff;border-radius:14px;padding:28px 28px 24px;
        width:100%;max-width:420px;margin:16px;
        box-shadow:0 20px 60px rgba(0,0,0,0.2);">

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

        <p style="font-size:14px;color:var(--text-muted);margin:0 0 6px;">
            Anda akan melakukan check-out dari ruangan:
        </p>
        <p id="modalNamaRuangan" style="font-size:15px;font-weight:600;color:var(--text);margin:0 0 18px;"></p>
        <p style="font-size:13px;color:var(--text-muted);margin:0 0 22px;">
            Pastikan ruangan sudah bersih dan kunci siap dikembalikan ke petugas <strong>Pamdal</strong>.
        </p>

        <form id="formCheckout" method="POST" action="{{ route('anggota.checkout.store') }}">
            @csrf
            <input type="hidden" id="inputCheckinId" name="checkin_id" value="">
        </form>

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
    function bukaModalCheckout(checkinId, namaRuangan) {
        document.getElementById('inputCheckinId').value = checkinId;
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

    document.getElementById('modalCheckout').addEventListener('click', function(e) {
        if (e.target === this) tutupModalCheckout();
    });

    function formatDurasi(detik) {
        const h = String(Math.floor(detik / 3600)).padStart(2, '0');
        const m = String(Math.floor((detik % 3600) / 60)).padStart(2, '0');
        const s = String(Math.floor(detik % 60)).padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    function updateAllTimers() {
        document.querySelectorAll('.durasi-timer').forEach(el => {
            const checkinTime = new Date(el.dataset.checkin.replace(' ', 'T'));
            const now = new Date();
            const detik = Math.floor((now - checkinTime) / 1000);
            el.textContent = formatDurasi(Math.max(detik, 0));
        });
    }

    function updateCheckoutAvailability() {
        document.querySelectorAll('.checkout-time-data').forEach(el => {
            const id = el.dataset.id;
            const batasCheckout = new Date(el.dataset.batasCheckout);
            const harusCheckoutDulu = el.dataset.harusCheckoutDulu === '1';
            const now = new Date();
            const boleh = now >= batasCheckout && !harusCheckoutDulu;

            const btn   = document.getElementById('btnCheckout-' + id);
            const label = document.getElementById('btnCheckoutLabel-' + id);
            const warn  = document.getElementById('checkoutWarning-' + id);
            if (!btn) return;

            // Kalau harus menyelesaikan sesi lama dulu, jangan diubah oleh timer ini —
            // biarkan tetap disabled dengan label yang sudah di-render server.
            if (harusCheckoutDulu) return;

            btn.disabled = !boleh;
            label.textContent = boleh ? 'Check-out Sekarang' : 'Belum Bisa Check-out';
            if (warn) warn.style.display = boleh ? 'none' : 'block';
        });
    }

    updateAllTimers();
    updateCheckoutAvailability();
    setInterval(updateAllTimers, 1000);
    setInterval(updateCheckoutAvailability, 1000);
</script>

@endsection