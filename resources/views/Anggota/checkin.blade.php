@extends('layouts.anggota')

@section('title', 'Check-in Ruangan')
@section('subtitle', 'Konfirmasi kehadiran untuk peminjaman hari ini')

@section('content')

@if($peminjaman_ruangan->isEmpty())
<div class="card">
    <div class="empty-state" style="padding:64px 20px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p style="font-size:15px;font-weight:600;color:var(--text);margin-bottom:6px;">
            Tidak ada peminjaman yang perlu check-in hari ini
        </p>
        <p style="font-size:13.5px;">
            Hanya peminjaman dengan status <strong>Disetujui</strong> dan jadwal hari ini yang muncul di sini.
        </p>
        <div style="margin-top:20px;display:flex;gap:10px;justify-content:center;">
            <a href="{{ route('anggota.riwayat-ruangan') }}" class="btn btn-outline">Lihat Riwayat</a>
            <a href="{{ route('anggota.pengajuan-ruangan') }}" class="btn btn-primary">Ajukan Peminjaman</a>
        </div>
    </div>
</div>

@else

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
    @foreach($peminjaman_ruangan as $p)
    <div class="card" style="border-left:4px solid var(--accent);">
        <div class="card-body">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                <div>
                    <div style="font-family:'Sora',sans-serif;font-size:16px;font-weight:700;">
                        {{ $p->ruangan->nama ?? '-' }}
                    </div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">
                        {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lantai '.$p->ruangan->lantai : '' }}
                    </div>
                </div>
                <span class="badge badge-green">Disetujui</span>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
                <div style="background:#f5f3ff;border-radius:8px;padding:10px 12px;">
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">MULAI</div>
                    <div style="font-family:'Sora',sans-serif;font-size:20px;font-weight:700;color:var(--accent);">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}
                    </div>
                </div>
                <div style="background:#f0fdf4;border-radius:8px;padding:10px 12px;">
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px;">SELESAI</div>
                    <div style="font-family:'Sora',sans-serif;font-size:20px;font-weight:700;color:#16a34a;">
                        {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                    </div>
                </div>
            </div>

            <div style="font-size:12.5px;color:var(--text-muted);margin-bottom:14px;">
                <strong style="color:var(--text);">Keperluan:</strong> {{ $p->keperluan }}
            </div>

<form method="POST"
      action="{{ route('anggota.checkin.store') }}"
      enctype="multipart/form-data">

    @csrf

    <input type="hidden" name="peminjaman_id" value="{{ $p->id }}">
    <div style="margin-bottom:16px;">

    <label style="font-weight:600;font-size:14px;">
        Upload Foto KTP
    </label>

    <input type="file"
        name="foto_ktp"
        accept="image/*"
        required
        class="form-control"
        style="margin-top:8px;"
        onchange="previewKTP(event, '{{ $p->id }}')">

    <img id="preview-{{ $p->id }}"
        src="#"
        alt="Preview KTP"
        style="
            display:none;
            margin-top:12px;
            width:100%;
            max-width:420px;
            max-height:170px;
            object-fit:cover;
            border-radius:10px;
            border:1px solid #e2e8f0;">

    <small style="color:#64748b;font-size:12px;">
        Pastikan foto KTP yang diupload jelas dan sesuai dengan identitas peminjam.
    </small>

    <div id="ktp-confirmation-{{ $p->id }}"
        style="margin-top:12px;display:none;">

        <label style="
            display:flex;
            align-items:flex-start;
            gap:8px;
            font-size:13px;
            color:#475569;
            line-height:1.5;">

            <input type="checkbox"
                id="ktp-check-{{ $p->id }}"
                style="margin-top:3px;accent-color:#4f46e5;">

            <span>
                Saya menyatakan bahwa foto yang diupload adalah KTP asli milik saya.
                Upload selain KTP dapat menyebabkan check-in ditolak oleh petugas Pamdal.
            </span>
        </label>
    </div>
</div>

    <button type="button"
    class="btn btn-success"
    style="width:100%;justify-content:center;"
    onclick="validateCheckin(event, '{{ $p->id }}')">

            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
             </svg>
             Check-in Sekarang
             </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
<div class="alert alert-info" style="margin-top:16px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:17px;height:17px;flex-shrink:0;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Setelah check-in, pastikan kamu juga melakukan <strong>check-out</strong> setelah selesai menggunakan ruangan.
    Petugas Pamdal akan mengkonfirmasi pengambilan dan pengembalian kunci.
</div>

<div id="modalKtpWarning" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:32px 28px;width:100%;max-width:400px;
                box-shadow:0 10px 40px rgba(0,0,0,0.18);margin:16px;">

        <div style="width:52px;height:52px;border-radius:50%;background:#fef3c7;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="26" height="26" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m0 3.75h.008M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Persetujuan Diperlukan</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Silakan centang pernyataan KTP terlebih dahulu sebelum melakukan check-in.
            </div>
        </div>

        <div style="display:flex;">
            <button type="button" onclick="tutupModalKtpWarning()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:var(--accent);color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                OK
            </button>
        </div>
    </div>
</div>


<div id="modalKonfirmasiCheckin" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:32px 28px;width:100%;max-width:400px;
                box-shadow:0 10px 40px rgba(0,0,0,0.18);margin:16px;">

        <div style="width:52px;height:52px;border-radius:50%;background:#dcfce7;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="26" height="26" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Konfirmasi Check-in?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Pastikan data dan foto KTP yang kamu upload sudah benar sebelum melanjutkan.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModalKonfirmasiCheckin()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="submitCheckinForm()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#16a34a;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Check-in
            </button>
        </div>
    </div>
</div>

<script>
let activeCheckinForm = null;

function previewKTP(event, id) {
    const input = event.target;
    const preview = document.getElementById('preview-' + id);
    const confirmation = document.getElementById('ktp-confirmation-' + id);
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
        confirmation.style.display = 'block';
    }
}

function validateCheckin(event, id) {
    const checkbox = document.getElementById('ktp-check-' + id);

    if (!checkbox.checked) {
        document.getElementById('modalKtpWarning').style.display = 'flex';
        return;
    }
    activeCheckinForm = event.target.closest('form');
    document.getElementById('modalKonfirmasiCheckin').style.display = 'flex';
}

function tutupModalKtpWarning() {
    document.getElementById('modalKtpWarning').style.display = 'none';
}

function tutupModalKonfirmasiCheckin() {
    document.getElementById('modalKonfirmasiCheckin').style.display = 'none';
    activeCheckinForm = null;
}

function submitCheckinForm() {
    if (activeCheckinForm) {
        activeCheckinForm.submit();
    }
}

document.getElementById('modalKtpWarning').addEventListener('click', function (e) {
    if (e.target === this) tutupModalKtpWarning();
});
document.getElementById('modalKonfirmasiCheckin').addEventListener('click', function (e) {
    if (e.target === this) tutupModalKonfirmasiCheckin();
});
</script>
@endif
@endsection
