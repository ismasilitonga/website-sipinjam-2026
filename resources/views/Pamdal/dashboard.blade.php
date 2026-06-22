@extends('layouts.pamdal')

@section('title', 'Dashboard Pamdal')
@section('subtitle', 'Peminjaman aktif & status kunci hari ini')

@section('topbar-action')
    <a href="{{ route('pamdal.daftar-peminjaman') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
        Manajemen Kunci
    </a>
@endsection

@section('content')

@php
    $totalHariIni    = $peminjamansHariIni->count();
    $sudahDiambil    = $peminjamansHariIni->filter(fn($p) => !is_null($p->waktu_kunci_diambil))->count();
    $sudahDikembali  = $peminjamansHariIni->filter(fn($p) => !is_null($p->waktu_kunci_dikembalikan))->count();
    $menungguKunciVal = $peminjamansHariIni->filter(fn($p) => is_null($p->waktu_kunci_diambil))->count();
@endphp

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;">
            <svg fill="none" stroke="#1d4ed8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#1d4ed8;">{{ $totalHariIni }}</div>
            <div class="stat-label">PEMINJAMAN HARI INI</div>
        </div>
    </div>

    <div class="stat-card" style="{{ $menungguKunciVal > 0 ? 'border-color:#fca5a5;' : '' }}">
        <div class="stat-icon" style="background:#fee2e2;">
            <svg fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#dc2626;">{{ $menungguKunciVal }}</div>
            <div class="stat-label">KUNCI BELUM DIAMBIL</div>
        </div>
        @if($menungguKunciVal > 0)
        <a href="{{ route('pamdal.daftar-peminjaman') }}"
           style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:600;">
            Proses sekarang →
        </a>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;">
            <svg fill="none" stroke="#ca8a04" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#ca8a04;">{{ $sudahDiambil }}</div>
            <div class="stat-label">KUNCI SUDAH DIAMBIL</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;">
            <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#16a34a;">{{ $sudahDikembali }}</div>
            <div class="stat-label">KUNCI SUDAH DIKEMBALIKAN</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">📋 Daftar Peminjaman Hari Ini</span>
        <span style="font-size:12px;color:var(--text-muted);">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Ruangan</th>
                    <th>Waktu</th>
                    <th>Keperluan</th>
                    <th>Status Kunci</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamansHariIni as $i => $p)
                @php
                    $sudahAmbil   = !is_null($p->waktu_kunci_diambil);
                    $sudahKembali = !is_null($p->waktu_kunci_dikembalikan);
                    $rowClass     = $sudahKembali ? 'row-done' : (!$sudahAmbil ? 'row-waiting' : '');
                @endphp
                <tr class="{{ $rowClass }}">
                    <td style="color:var(--text-muted);font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $p->user->nama ?? '-' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">
                            {{ $p->user->organisasi ?? '' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">
                            {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lt.'.$p->ruangan->lantai : '' }}
                        </div>
                    </td>
                    <td style="font-size:12.5px;">
                        <span style="font-weight:600;">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}</span>
                        –
                        <span>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}</span>
                    </td>
                    <td style="font-size:12.5px;max-width:160px;">
                        <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $p->keperluan }}">
                            {{ $p->keperluan }}
                        </div>
                    </td>
                    <td>
                        <div class="kunci-flow">
                            <span class="kunci-step {{ $sudahAmbil ? 'kunci-step-done' : 'kunci-step-pending' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sudahAmbil)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                Ambil
                            </span>
                            <span class="kunci-arrow">›</span>
                            <span class="kunci-step {{ $sudahKembali ? 'kunci-step-done' : 'kunci-step-pending' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sudahKembali)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                Kembali
                            </span>
                        </div>
                        @if($sudahAmbil && !$sudahKembali)
                        <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">
                            Diambil {{ \Carbon\Carbon::parse($p->waktu_kunci_diambil)->format('H:i') }}
                        </div>
                        @endif
                        @if($sudahKembali)
                        <div style="font-size:11px;color:#15803d;margin-top:3px;">
                            Kembali {{ \Carbon\Carbon::parse($p->waktu_kunci_dikembalikan)->format('H:i') }}
                        </div>
                        @endif
                    </td>
                    <td>
                        @if(!$sudahAmbil)
                            <button type="button" class="btn btn-warning btn-sm"
                                onclick="bukaModalAmbil(
                                    {{ $p->id }},
                                    '{{ e($p->user->nama ?? '') }}',
                                    '{{ e($p->ruangan->nama_ruangan ?? '') }}',
                                    '{{ route('pamdal.kunci.ambil', $p->id) }}'
                                )">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Ambil Kunci
                            </button>
                        @elseif(!$sudahKembali)
                            <button type="button" class="btn btn-success btn-sm"
                                onclick="bukaModalKembali(
                                    {{ $p->id }},
                                    '{{ e($p->user->nama ?? '') }}',
                                    '{{ e($p->ruangan->nama_ruangan ?? '') }}',
                                    '{{ route('pamdal.kunci.kembalikan', $p->id) }}'
                                )">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Kembali
                            </button>
                        @else
                            <span class="badge badge-green">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Selesai
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p>Tidak ada peminjaman yang dijadwalkan hari ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalAmbilKunci" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;">
    <div onclick="tutupModal('modalAmbilKunci')"
         style="position:absolute;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);"></div>

    <div style="position:relative;background:#fff;border-radius:16px;padding:32px 28px;width:100%;max-width:420px;
                box-shadow:0 20px 60px rgba(0,0,0,0.2);animation:slideUp .2s ease;">
        <div style="display:flex;justify-content:center;margin-bottom:20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;">
                <svg fill="none" stroke="#d97706" viewBox="0 0 24 24" style="width:32px;height:32px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
        </div>

        <h3 style="text-align:center;font-size:18px;font-weight:700;margin:0 0 8px;color:#111;">Konfirmasi Pengambilan Kunci</h3>
        <p style="text-align:center;font-size:13.5px;color:#6b7280;margin:0 0 20px;line-height:1.6;">
            Pastikan peminjam sudah hadir dan identitas telah diverifikasi sebelum menyerahkan kunci.
        </p>

        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:14px 16px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <svg fill="none" stroke="#374151" viewBox="0 0 24 24" style="width:15px;height:15px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span style="font-size:13px;color:#374151;">Peminjam: <strong id="modalAmbilNama">—</strong></span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <svg fill="none" stroke="#374151" viewBox="0 0 24 24" style="width:15px;height:15px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span style="font-size:13px;color:#374151;">Ruangan: <strong id="modalAmbilRuangan">—</strong></span>
            </div>
        </div>

        <form id="formAmbilKunci" method="POST" action="">
            @csrf
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="tutupModal('modalAmbilKunci')"
                    style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;background:#fff;
                           font-size:14px;font-weight:600;color:#374151;cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="flex:1;padding:10px;border:none;border-radius:8px;background:#d97706;
                           font-size:14px;font-weight:600;color:#fff;cursor:pointer;
                           display:flex;align-items:center;justify-content:center;gap:6px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Ya, Serahkan Kunci
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalKembaliKunci" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;">
    <div onclick="tutupModal('modalKembaliKunci')"
         style="position:absolute;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);"></div>

    <div style="position:relative;background:#fff;border-radius:16px;padding:32px 28px;width:100%;max-width:420px;
                box-shadow:0 20px 60px rgba(0,0,0,0.2);animation:slideUp .2s ease;">
        <div style="display:flex;justify-content:center;margin-bottom:20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
                <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24" style="width:32px;height:32px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <h3 style="text-align:center;font-size:18px;font-weight:700;margin:0 0 8px;color:#111;">Konfirmasi Pengembalian Kunci</h3>
        <p style="text-align:center;font-size:13.5px;color:#6b7280;margin:0 0 20px;line-height:1.6;">
            Pastikan kunci telah diterima dan ruangan sudah dikunci kembali sebelum konfirmasi.
        </p>

        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:14px 16px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <svg fill="none" stroke="#374151" viewBox="0 0 24 24" style="width:15px;height:15px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span style="font-size:13px;color:#374151;">Peminjam: <strong id="modalKembaliNama">—</strong></span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <svg fill="none" stroke="#374151" viewBox="0 0 24 24" style="width:15px;height:15px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span style="font-size:13px;color:#374151;">Ruangan: <strong id="modalKembaliRuangan">—</strong></span>
            </div>
        </div>

        <form id="formKembaliKunci" method="POST" action="">
            @csrf
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="tutupModal('modalKembaliKunci')"
                    style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;background:#fff;
                           font-size:14px;font-weight:600;color:#374151;cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="flex:1;padding:10px;border:none;border-radius:8px;background:#16a34a;
                           font-size:14px;font-weight:600;color:#fff;cursor:pointer;
                           display:flex;align-items:center;justify-content:center;gap:6px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Ya, Kunci Dikembalikan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity:0; transform:translateY(24px) scale(.97); }
    to   { opacity:1; transform:translateY(0)    scale(1);   }
}
</style>

<script>
function bukaModalAmbil(id, nama, ruangan, actionUrl) {
    document.getElementById('modalAmbilNama').textContent    = nama;
    document.getElementById('modalAmbilRuangan').textContent = ruangan;
    document.getElementById('formAmbilKunci').action         = actionUrl;

    const modal = document.getElementById('modalAmbilKunci');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden'; 
}

function bukaModalKembali(id, nama, ruangan, actionUrl) {
    document.getElementById('modalKembaliNama').textContent    = nama;
    document.getElementById('modalKembaliRuangan').textContent = ruangan;
    document.getElementById('formKembaliKunci').action         = actionUrl;

    const modal = document.getElementById('modalKembaliKunci');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function tutupModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        tutupModal('modalAmbilKunci');
        tutupModal('modalKembaliKunci');
    }
});
</script>

@endsection