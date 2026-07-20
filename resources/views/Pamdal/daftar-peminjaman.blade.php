@extends('layouts.pamdal')

@section('title', 'Manajemen Kunci')
@section('subtitle', 'Riwayat serah terima kunci ruangan')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; gap:16px; flex-wrap:wrap;">
    <h1 style="font-size:22px; font-weight:800; color:#1e293b; margin:0;">
        Konfirmasi Kunci Ruangan
    </h1>
    <div style="position:relative;">
        <input type="text" id="searchInput" placeholder="Cari peminjam / ruangan..."
            style="padding:10px 14px 10px 38px; border:1px solid #ababae; border-radius:10px;
                   font-size:13px; width:260px; outline:none; background:white;">
        <i class="fa-solid fa-magnifying-glass"
           style="position:absolute; left:12px; top:50%; transform:translateY(-50%);
                  color:#94a3b8; font-size:13px;"></i>
    </div>
</div>

<div style="background:white; border-radius:16px; box-shadow:0 4px 15px rgba(0,0,0,0.05); overflow:hidden; padding-bottom:18px;">
    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#abc7e4;">
                <th style="padding:15px; text-align:left;">Peminjam</th>
                <th style="padding:15px; text-align:left;">Ruangan</th>
                <th style="padding:15px; text-align:center;">Waktu</th>
                <th style="padding:15px; text-align:center;">Verifikasi Data Diri</th>
                <th style="padding:15px; text-align:center;">Ambil Kunci</th>
                <th style="padding:15px; text-align:center;">Kunci Kembali</th>
                <th style="padding:15px; text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">

            @forelse($peminjaman_ruangans as $p)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:14px 15px;">
                    <div style="font-weight:450;">{{ $p->user_nama }}</div>
                    <div style="font-size:12px; color:#64748b;">{{ $p->nama_ormawa }}</div>
                </td>
                <td style="padding:14px 15px; font-weight:500;">{{ $p->ruangan_nama }}</td>
                <td style="padding:14px 15px; text-align:center; font-size:13px;">
                    @if($p->multi_hari)
                        {{ $p->tanggal_mulai }} s/d {{ $p->tanggal_selesai }}<br>
                        <span style="color:#64748b;">{{ $p->jam_mulai }}–{{ $p->jam_selesai }}</span>
                    @else
                        {{ $p->tanggal_mulai }}<br>
                        <span style="color:#64748b;">{{ $p->jam_mulai }}–{{ $p->jam_selesai }}</span>
                    @endif
                </td>
                <td style="padding:14px 15px; text-align:center;">
                    @if($p->foto_ktp_url)
                        <a href="{{ $p->foto_ktp_url }}" target="_blank"
                           style="color:#2563eb; font-weight:600; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                            <i class="fa-solid fa-id-card"></i> Lihat File
                        </a>

                        @if($p->status_verifikasi === 'ditolak')
                            <div style="margin-top:6px; color:#dc2626; font-size:11.5px; font-style:italic; max-width:160px;">
                                Ditolak: {{ $p->alasan_verifikasi_ditolak }}
                            </div>
                        @endif
                    @else
                        <span style="color:#dc2626; font-size:12.5px; font-style:italic;">Belum check-in</span>
                    @endif
                </td>
                <td style="padding:14px 15px; text-align:center; white-space:nowrap;">
                    @if($p->waktu_ambil_kunci)
                        <span style="color:#16a34a; font-weight:600; font-size:13px;">
                            <i class="fa-solid fa-check-circle"></i>
                            Diambil {{ $p->waktu_ambil_kunci }}
                        </span>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>
                    @endif
                </td>
                <td style="padding:14px 15px; text-align:center; white-space:nowrap;">
                    @if($p->waktu_kembali_kunci)
                        <span style="color:#16a34a; font-weight:600; font-size:13px;">
                            <i class="fa-solid fa-check-circle"></i>
                            Kembali {{ $p->waktu_kembali_kunci }}
                        </span>
                    @elseif($p->status_checkout === 'menunggu' && $p->waktu_checkout)
                        <div style="color:#d97706; font-weight:600; font-size:12.5px;">
                            <i class="fa-solid fa-clock"></i> Klaim {{ $p->waktu_checkout }}
                        </div>
                        <div style="margin-top:4px;">
                            <button onclick="bukaModalTolakCheckout({{ $p->id }}, '{{ $p->user_nama }}')"
                                style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;
                                       padding:3px 9px; border-radius:6px; font-size:11px;
                                       font-weight:600; cursor:pointer;">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </div>
                    @elseif($p->status_checkout === 'ditolak')
                        <div style="color:#dc2626; font-size:11.5px; font-style:italic; max-width:150px;">
                            Klaim ditolak: {{ $p->alasan_checkout_ditolak }}
                        </div>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>
                    @endif
                </td>
                <td style="padding:14px 15px; text-align:center; white-space:nowrap;">
                    @if($p->status_verifikasi === 'ditolak')
                        <span style="color:#dc2626; font-size:12.5px; font-style:italic;">
                            Menunggu Upload Ulang
                        </span>
                    @elseif(!$p->sudah_ambil_kunci)
                        <div style="display:flex; align-items:stretch; gap:5px;">
                            <button onclick="bukaModal('ambil', {{ $p->id }}, '{{ $p->user_nama }}', '{{ $p->ruangan_nama }}')"
                                style="background:#2f7ea1; color:white; padding:7px 8px; border-radius:8px;
                                       border:none; cursor:pointer; font-size:12px; font-weight:600; flex:1;
                                       white-space:nowrap;">
                                <i class="fa-solid fa-key"></i> Ambil Kunci
                            </button>
                            @if($p->status_verifikasi === 'menunggu')
                                <button onclick="bukaModalTolak({{ $p->id }}, '{{ $p->user_nama }}')"
                                    style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;
                                           padding:7px 10px; border-radius:8px; font-size:12px;
                                           font-weight:600; cursor:pointer; white-space:nowrap;">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </button>
                            @endif
                        </div>
                    @elseif(!$p->sudah_kembali_kunci)
                        <button onclick="bukaModal('kembali', {{ $p->id }}, '{{ $p->user_nama }}', '{{ $p->ruangan_nama }}')"
                            style="background:#16a34a; color:white; padding:7px 14px; border-radius:8px;
                                   border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                            <i class="fa-solid fa-rotate-left"></i> Kembali
                        </button>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:60px; text-align:center; color:#94a3b8; font-style:italic;">
                    Belum ada data peminjaman.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div id="paginationWrap" style="padding:14px; border-top:1px solid #dbdcdd; margin-top:2px;">
        {{ $peminjaman_ruangans->links('layouts.pagination') }}
    </div>
</div>

<div id="modalOverlay"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45);
            z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:32px 28px; width:100%;
                max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.15); margin:16px;">

        <div id="modalIcon" style="width:56px; height:56px; border-radius:14px;
             display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
        </div>

        <h2 id="modalTitle" style="font-size:17px; font-weight:700; color:#1e293b;
            text-align:center; margin:0 0 8px;"></h2>
        <p id="modalDesc" style="font-size:13.5px; color:#64748b; text-align:center;
            margin:0 0 24px; line-height:1.6;"></p>

        <div style="display:flex; gap:10px;">
            <button onclick="tutupModal()"
                style="flex:1; padding:10px; border-radius:10px; border:1px solid #e2e8f0;
                       background:white; color:#475569; font-size:14px; font-weight:600;
                       cursor:pointer;">
                Batal
            </button>
            <form id="modalForm" method="POST" style="flex:1;">
                @csrf
                <button type="submit" id="modalBtn"
                    style="width:100%; padding:10px; border-radius:10px; border:none;
                           color:white; font-size:14px; font-weight:600; cursor:pointer;">
                </button>
            </form>
        </div>
    </div>
</div>

<div id="modalTolakOverlay"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45);
            z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:32px 28px; width:100%;
                max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.15); margin:16px;">

        <div style="width:56px; height:56px; border-radius:14px; background:#fef2f2;
             display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
            <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626; font-size:22px;"></i>
        </div>

        <h2 style="font-size:17px; font-weight:700; color:#1e293b; text-align:center; margin:0 0 8px;">
            Tolak Verifikasi Data Diri
        </h2>
        <p id="modalTolakDesc" style="font-size:13.5px; color:#64748b; text-align:center;
            margin:0 0 20px; line-height:1.6;"></p>

        <form id="modalTolakForm" method="POST">
            @csrf
            <textarea name="alasan" id="alasanTolak" required maxlength="255" rows="3"
                placeholder="Contoh: Foto KTP buram / bukan atas nama yang bersangkutan"
                style="width:100%; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;
                       font-size:13.5px; font-family:inherit; resize:none; outline:none; margin-bottom:20px;"></textarea>

            <div style="display:flex; gap:10px;">
                <button type="button" onclick="tutupModalTolak()"
                    style="flex:1; padding:10px; border-radius:10px; border:1px solid #e2e8f0;
                           background:white; color:#475569; font-size:14px; font-weight:600;
                           cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="flex:1; padding:10px; border-radius:10px; border:none;
                           background:#dc2626; color:white; font-size:14px; font-weight:600;
                           cursor:pointer;">
                    Ya, Tolak Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalTolakCheckoutOverlay"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45);
            z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:32px 28px; width:100%;
                max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.15); margin:16px;">

        <div style="width:56px; height:56px; border-radius:14px; background:#fef2f2;
             display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
            <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626; font-size:22px;"></i>
        </div>

        <h2 style="font-size:17px; font-weight:700; color:#1e293b; text-align:center; margin:0 0 8px;">
            Tolak Klaim Checkout
        </h2>
        <p id="modalTolakCheckoutDesc" style="font-size:13.5px; color:#64748b; text-align:center;
            margin:0 0 20px; line-height:1.6;"></p>

        <form id="modalTolakCheckoutForm" method="POST">
            @csrf
            <textarea name="alasan" id="alasanTolakCheckout" required maxlength="255" rows="3"
                placeholder="Contoh: Kunci belum diserahkan secara fisik ke Pamdal"
                style="width:100%; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;
                       font-size:13.5px; font-family:inherit; resize:none; outline:none; margin-bottom:20px;"></textarea>

            <div style="display:flex; gap:10px;">
                <button type="button" onclick="tutupModalTolakCheckout()"
                    style="flex:1; padding:10px; border-radius:10px; border:1px solid #e2e8f0;
                           background:white; color:#475569; font-size:14px; font-weight:600;
                           cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="flex:1; padding:10px; border-radius:10px; border:none;
                           background:#dc2626; color:white; font-size:14px; font-weight:600;
                           cursor:pointer;">
                    Ya, Tolak Klaim
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const urlAmbil      = "{{ url('pamdal/kunci') }}";
const urlVerifikasi = "{{ url('pamdal/verifikasi') }}";
const urlCheckout   = "{{ url('pamdal/checkout') }}";
const csrfToken     = "{{ csrf_token() }}";

function bukaModal(tipe, id, nama, ruangan) {
    const overlay  = document.getElementById('modalOverlay');
    const icon     = document.getElementById('modalIcon');
    const title    = document.getElementById('modalTitle');
    const desc     = document.getElementById('modalDesc');
    const form     = document.getElementById('modalForm');
    const btn      = document.getElementById('modalBtn');

    if (tipe === 'ambil') {
        icon.style.background = '#dbeafe';
        icon.innerHTML = '<i class="fa-solid fa-key" style="color:#2563eb; font-size:22px;"></i>';
        title.textContent = 'Konfirmasi Pengambilan Kunci';
        desc.innerHTML = `Apakah kunci ruangan <strong>${ruangan}</strong> sudah diambil oleh <strong>${nama}</strong>?`;
        form.action = `${urlAmbil}/${id}/ambil`;
        btn.textContent = 'Ya, Kunci Diambil';
        btn.style.background = '#2f7ea1';
    } else {
        icon.style.background = '#dcfce7';
        icon.innerHTML = '<i class="fa-solid fa-rotate-left" style="color:#16a34a; font-size:22px;"></i>';
        title.textContent = 'Konfirmasi Pengembalian Kunci';
        desc.innerHTML = `Apakah kunci ruangan <strong>${ruangan}</strong> sudah dikembalikan oleh <strong>${nama}</strong>?`;
        form.action = `${urlAmbil}/${id}/kembalikan`;
        btn.textContent = 'Ya, Kunci Dikembalikan';
        btn.style.background = '#16a34a';
    }

    overlay.style.display = 'flex';
}

function tutupModal() {
    document.getElementById('modalOverlay').style.display = 'none';
}

function bukaModalTolak(id, nama) {
    const overlay = document.getElementById('modalTolakOverlay');
    const desc    = document.getElementById('modalTolakDesc');
    const form    = document.getElementById('modalTolakForm');

    desc.innerHTML = `Jelaskan alasan penolakan foto KTP milik <strong>${nama}</strong>. Peminjam akan diminta upload ulang.`;
    form.action = `${urlVerifikasi}/${id}/tolak`;
    document.getElementById('alasanTolak').value = '';

    overlay.style.display = 'flex';
}

function tutupModalTolak() {
    document.getElementById('modalTolakOverlay').style.display = 'none';
}

function bukaModalTolakCheckout(id, nama) {
    const overlay = document.getElementById('modalTolakCheckoutOverlay');
    const desc    = document.getElementById('modalTolakCheckoutDesc');
    const form    = document.getElementById('modalTolakCheckoutForm');

    desc.innerHTML = `Jelaskan kenapa klaim checkout dari <strong>${nama}</strong> ditolak. Peminjam akan diminta checkout ulang setelah kunci benar-benar dikembalikan.`;
    form.action = `${urlCheckout}/${id}/tolak`;
    document.getElementById('alasanTolakCheckout').value = '';

    overlay.style.display = 'flex';
}

function tutupModalTolakCheckout() {
    document.getElementById('modalTolakCheckoutOverlay').style.display = 'none';
}

document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

document.getElementById('modalTolakOverlay').addEventListener('click', function(e) {
    if (e.target === this) tutupModalTolak();
});

document.getElementById('modalTolakCheckoutOverlay').addEventListener('click', function(e) {
    if (e.target === this) tutupModalTolakCheckout();
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput    = document.getElementById('searchInput');
    const tableBody      = document.getElementById('tableBody');
    const paginationWrap = document.getElementById('paginationWrap');
    const url            = "{{ route('pamdal.daftar-peminjaman') }}";
    let timeout = null;

    searchInput.addEventListener('keyup', function () {
        clearTimeout(timeout);
        const keyword = this.value;

        if (keyword === '') {
            paginationWrap.style.display = 'block';
            location.reload();
            return;
        }

        paginationWrap.style.display = 'none';

        timeout = setTimeout(() => {
            fetch(`${url}?search=${encodeURIComponent(keyword)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="7" style="padding:60px; text-align:center; color:#94a3b8; font-style:italic;">Tidak ada hasil ditemukan.</td></tr>`;
                    return;
                }
                data.forEach(p => {
                    const waktu = p.multi_hari
                        ? `${p.tanggal_mulai} s/d ${p.tanggal_selesai}<br>
                           <span style="color:#64748b;">${p.jam_mulai}–${p.jam_selesai}</span>`
                        : `${p.tanggal_mulai}<br>
                           <span style="color:#64748b;">${p.jam_mulai}–${p.jam_selesai}</span>`;

                    let verifikasiDataDiri = '';
                    if (p.foto_ktp_url) {
                        verifikasiDataDiri = `<a href="${p.foto_ktp_url}" target="_blank" style="color:#2563eb; font-weight:600; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:5px;"><i class="fa-solid fa-id-card"></i> Lihat File</a>`;

                        if (p.status_verifikasi === 'ditolak') {
                            verifikasiDataDiri += `<div style="margin-top:6px; color:#dc2626; font-size:11.5px; font-style:italic; max-width:160px;">
                                Ditolak: ${p.alasan_verifikasi_ditolak ?? ''}
                            </div>`;
                        }
                    } else {
                        verifikasiDataDiri = `<span style="color:#dc2626; font-size:12.5px; font-style:italic;">Belum check-in</span>`;
                    }

                    const ambilKunci = p.waktu_ambil_kunci
                        ? `<span style="color:#16a34a; font-weight:600; font-size:13px;"><i class="fa-solid fa-check-circle"></i> Diambil ${p.waktu_ambil_kunci}</span>`
                        : `<span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>`;

                    let kembalikan = '';
                    if (p.waktu_kembali_kunci) {
                        kembalikan = `<span style="color:#16a34a; font-weight:600; font-size:13px;"><i class="fa-solid fa-check-circle"></i> Kembali ${p.waktu_kembali_kunci}</span>`;
                    } else if (p.status_checkout === 'menunggu' && p.waktu_checkout) {
                        kembalikan = `<div style="color:#d97706; font-weight:600; font-size:12.5px;"><i class="fa-solid fa-clock"></i> Klaim ${p.waktu_checkout}</div>
                            <div style="margin-top:4px;">
                                <button onclick="bukaModalTolakCheckout(${p.id}, '${p.user_nama}')"
                                    style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;
                                           padding:3px 9px; border-radius:6px; font-size:11px;
                                           font-weight:600; cursor:pointer;">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </button>
                            </div>`;
                    } else if (p.status_checkout === 'ditolak') {
                        kembalikan = `<div style="color:#dc2626; font-size:11.5px; font-style:italic; max-width:150px;">Klaim ditolak: ${p.alasan_checkout_ditolak ?? ''}</div>`;
                    } else {
                        kembalikan = `<span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>`;
                    }

                    let aksi = '';
                    if (p.status_verifikasi === 'ditolak') {
                        aksi = `<span style="color:#dc2626; font-size:12.5px; font-style:italic;">Menunggu Upload Ulang</span>`;
                    } else if (!p.sudah_ambil_kunci) {
                        let tombolTolak = '';
                        if (p.status_verifikasi === 'menunggu') {
                            tombolTolak = `<button onclick="bukaModalTolak(${p.id}, '${p.user_nama}')"
                                style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;
                                       padding:7px 10px; border-radius:8px; font-size:12px;
                                       font-weight:600; cursor:pointer; white-space:nowrap;">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>`;
                        }
                        aksi = `<div style="display:flex; align-items:stretch; gap:5px;">
                            <button onclick="bukaModal('ambil', ${p.id}, '${p.user_nama}', '${p.ruangan_nama}')"
                                style="background:#2f7ea1; color:white; padding:7px 8px; border-radius:8px;
                                       border:none; cursor:pointer; font-size:12px; font-weight:600; flex:1;
                                       white-space:nowrap;">
                                <i class="fa-solid fa-key"></i> Ambil Kunci
                            </button>
                            ${tombolTolak}
                        </div>`;
                    } else if (!p.sudah_kembali_kunci) {
                        aksi = `<button onclick="bukaModal('kembali', ${p.id}, '${p.user_nama}', '${p.ruangan_nama}')"
                            style="background:#16a34a; color:white; padding:7px 14px; border-radius:8px;
                                   border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                            <i class="fa-solid fa-rotate-left"></i> Kembali
                        </button>`;
                    } else {
                        aksi = `<span style="color:#94a3b8; font-size:13px; font-style:italic;">Selesai</span>`;
                    }

                    tableBody.innerHTML += `
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:14px 15px;">
                                <div style="font-weight:600;">${p.user_nama}</div>
                                <div style="font-size:12px; color:#64748b;">${p.nama_ormawa}</div>
                            </td>
                            <td style="padding:14px 15px; font-weight:500;">${p.ruangan_nama}</td>
                            <td style="padding:14px 15px; text-align:center; font-size:13px;">${waktu}</td>
                            <td style="padding:14px 15px; text-align:center;">${verifikasiDataDiri}</td>
                            <td style="padding:14px 15px; text-align:center; white-space:nowrap;">${ambilKunci}</td>
                            <td style="padding:14px 15px; text-align:center; white-space:nowrap;">${kembalikan}</td>
                            <td style="padding:14px 15px; text-align:center; white-space:nowrap;">${aksi}</td>
                        </tr>`;
                });
            });
        }, 300);
    });
});
</script>
@endpush
@endsection