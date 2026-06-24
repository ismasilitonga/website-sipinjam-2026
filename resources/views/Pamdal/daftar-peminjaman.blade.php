@extends('layouts.pamdal')

@section('title', 'Manajemen Kunci')
@section('subtitle', 'Peminjaman aktif & status kunci hari ini')

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
                <th style="padding:15px; text-align:center;">Ambil Kunci</th>
                <th style="padding:15px; text-align:center;">Kembalikan</th>
                <th style="padding:15px; text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($peminjaman_ruangans as $p)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:14px 15px;">
                    <div style="font-weight:450;">{{ $p->user->nama ?? '-' }}</div>
                    <div style="font-size:12px; color:#64748b;">{{ $p->nama_ormawa }}</div>
                </td>
                <td style="padding:14px 15px; font-weight:500;">{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
                <td style="padding:14px 15px; text-align:center; font-size:13px;">
                    {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}<br>
                    <span style="color:#64748b;">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                    </span>
                </td>
                <td style="padding:14px 15px; text-align:center;">
                    @if($p->waktu_kunci_diambil)
                        <span style="color:#16a34a; font-weight:600; font-size:13px;">
                            <i class="fa-solid fa-check-circle"></i>
                            Diambil {{ \Carbon\Carbon::parse($p->waktu_kunci_diambil)->format('H:i') }}
                        </span>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>
                    @endif
                </td>
                <td style="padding:14px 15px; text-align:center;">
                    @if($p->waktu_kunci_dikembalikan)
                        <span style="color:#16a34a; font-weight:600; font-size:13px;">
                            <i class="fa-solid fa-check-circle"></i>
                            Kembali {{ \Carbon\Carbon::parse($p->waktu_kunci_dikembalikan)->format('H:i') }}
                        </span>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>
                    @endif
                </td>
                <td style="padding:14px 15px; text-align:center; white-space:nowrap;">
                    @if(!$p->waktu_kunci_diambil)
                        <button onclick="bukaModal('ambil', {{ $p->id }}, '{{ $p->user->nama ?? '-' }}', '{{ $p->ruangan->nama_ruangan ?? '-' }}')"
                            style="background:#2f7ea1; color:white; padding:7px 14px; border-radius:8px;
                                   border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                            <i class="fa-solid fa-key"></i> Ambil Kunci
                        </button>
                    @elseif(!$p->waktu_kunci_dikembalikan)
                        <button onclick="bukaModal('kembali', {{ $p->id }}, '{{ $p->user->nama ?? '-' }}', '{{ $p->ruangan->nama_ruangan ?? '-' }}')"
                            style="background:#16a34a; color:white; padding:7px 14px; border-radius:8px;
                                   border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                            <i class="fa-solid fa-rotate-left"></i> Kembalikan
                        </button>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:60px; text-align:center; color:#94a3b8; font-style:italic;">
                    Tidak ada peminjaman aktif hari ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div id="paginationWrap" style="padding:14px; border-top:1px solid #dbdcdd; margin-top:2px;">
        {{ $peminjaman_ruangans->links('layouts.pagination') }}
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div id="modalOverlay"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45);
            z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:32px 28px; width:100%;
                max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.15); margin:16px;">

        {{-- Icon --}}
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

@push('scripts')
<script>
const urlAmbil   = "{{ url('pamdal/kunci') }}";
const csrfToken  = "{{ csrf_token() }}";

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

// Tutup modal kalau klik di luar
document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

// Search
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
                    tableBody.innerHTML = `<tr><td colspan="6" style="padding:60px; text-align:center; color:#94a3b8; font-style:italic;">Tidak ada hasil ditemukan.</td></tr>`;
                    return;
                }

                data.forEach(p => {
                    const ambilKunci = p.waktu_kunci_diambil
                        ? `<span style="color:#16a34a; font-weight:600; font-size:13px;"><i class="fa-solid fa-check-circle"></i> Diambil ${p.waktu_kunci_diambil}</span>`
                        : `<span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>`;

                    const kembalikan = p.waktu_kunci_dikembalikan
                        ? `<span style="color:#16a34a; font-weight:600; font-size:13px;"><i class="fa-solid fa-check-circle"></i> Kembali ${p.waktu_kunci_dikembalikan}</span>`
                        : `<span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>`;

                    let aksi = '';
                    if (!p.waktu_kunci_diambil) {
                        aksi = `<button onclick="bukaModal('ambil', ${p.id}, '${p.user_nama}', '${p.ruangan_nama}')"
                            style="background:#2f7ea1; color:white; padding:7px 14px; border-radius:8px;
                                   border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                            <i class="fa-solid fa-key"></i> Ambil Kunci
                        </button>`;
                    } else if (!p.waktu_kunci_dikembalikan) {
                        aksi = `<button onclick="bukaModal('kembali', ${p.id}, '${p.user_nama}', '${p.ruangan_nama}')"
                            style="background:#16a34a; color:white; padding:7px 14px; border-radius:8px;
                                   border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                            <i class="fa-solid fa-rotate-left"></i> Kembalikan
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
                            <td style="padding:14px 15px; text-align:center; font-size:13px;">
                                ${p.tanggal_mulai}<br>
                                <span style="color:#64748b;">${p.jam_mulai}–${p.jam_selesai}</span>
                            </td>
                            <td style="padding:14px 15px; text-align:center;">${ambilKunci}</td>
                            <td style="padding:14px 15px; text-align:center;">${kembalikan}</td>
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