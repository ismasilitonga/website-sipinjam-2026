@extends('layouts.pamdal')

@section('title', 'Manajemen Kunci')
@section('subtitle', 'Peminjaman aktif & status kunci hari ini')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; gap:16px; flex-wrap:wrap;">
    
    <h1 style="font-size:22px; font-weight:800; color:#1e293b; margin:0;">
        Konfirmasi Kunci Ruangan
    </h1>

    <div style="position:relative;">
        <input 
            type="text"
            id="searchInput"
            placeholder="Cari peminjam / ruangan..."
            style="
                padding:10px 14px 10px 38px;
                border:1px solid #ababae;
                border-radius:10px;
                font-size:13px;
                width:260px;
                outline:none;
                background:white;
            ">
        <i class="fa-solid fa-magnifying-glass"
           style="
                position:absolute;
                left:12px;
                top:50%;
                transform:translateY(-50%);
                color:#94a3b8;
                font-size:13px;
           ">
        </i>
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
                    <div style="font-weight:600;">{{ $p->user->nama ?? '-' }}</div>
                    <div style="font-size:12px; color:#64748b;">{{ $p->nama_ormawa }}</div>
                </td>
                <td style="padding:14px 15px; font-weight:500;">{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
                <td style="padding:14px 15px; text-align:center; font-size:13px;">
                    {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}<br>
                    <span style="color:#64748b;">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}</span>
                </td>
                <td style="padding:14px 15px; text-align:center;">
                    @if($p->waktu_kunci_diambil)
                        <span style="color:#16a34a; font-weight:600; font-size:13px;"><i class="fa-solid fa-check-circle"></i> {{ \Carbon\Carbon::parse($p->waktu_kunci_diambil)->format('H:i') }}</span>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>
                    @endif
                </td>
                <td style="padding:14px 15px; text-align:center;">
                    @if($p->waktu_kunci_dikembalikan)
                        <span style="color:#16a34a; font-weight:600; font-size:13px;"><i class="fa-solid fa-check-circle"></i> {{ \Carbon\Carbon::parse($p->waktu_kunci_dikembalikan)->format('H:i') }}</span>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>
                    @endif
                </td>
                <td style="padding:14px 15px; text-align:center; white-space:nowrap;">
                    @if(!$p->waktu_kunci_diambil)
                        <form method="POST" action="{{ route('pamdal.kunci.ambil', $p->id) }}" style="display:inline">
                            @csrf
                            <button style="background:#2f7ea1; color:white; padding:7px 14px; border-radius:8px; border:none; cursor:pointer; font-size:13px; font-weight:600; margin-bottom:4px; width:100%;">
                                <i class="fa-solid fa-key"></i> Kunci Diambil
                            </button>
                        </form>
                    @elseif(!$p->waktu_kunci_dikembalikan)
                        <form method="POST" action="{{ route('pamdal.kunci.kembalikan', $p->id) }}" style="display:inline">
                            @csrf
                            <button style="background:#16a34a; color:white; padding:7px 14px; border-radius:8px; border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                                <i class="fa-solid fa-rotate-left"></i> Dikembalikan
                            </button>
                        </form>
                    @else
                        <span style="color:#94a3b8; font-size:13px; font-style:italic;">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="padding:60px; text-align:center; color:#94a3b8; font-style:italic;">Tidak ada peminjaman aktif hari ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div id="paginationWrap" style="padding:14px; border-top:1px solid #dbdcdd; margin-top:2px; display:flex; justify-content:flex-start;">
        {{ $peminjaman_ruangans->links('layouts.pagination') }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('tableBody');
    const paginationWrap = document.getElementById('paginationWrap');
    const url = "{{ route('pamdal.daftar-peminjaman') }}";
    let timeout = null;

    searchInput.addEventListener('keyup', function () {
        clearTimeout(timeout);
        const keyword = this.value;

        if (keyword === '') {
            paginationWrap.style.display = 'flex';
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
                    let ambilKunci = p.waktu_kunci_diambil
                        ? `<span style="color:#16a34a; font-weight:600; font-size:13px;"><i class="fa-solid fa-check-circle"></i> ${p.waktu_kunci_diambil}</span>`
                        : `<span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>`;

                    let kembalikan = p.waktu_kunci_dikembalikan
                        ? `<span style="color:#16a34a; font-weight:600; font-size:13px;"><i class="fa-solid fa-check-circle"></i> ${p.waktu_kunci_dikembalikan}</span>`
                        : `<span style="color:#94a3b8; font-size:13px; font-style:italic;">Belum</span>`;

                    let aksi = '';
                    if (!p.waktu_kunci_diambil) {
                        aksi = `<form method="POST" action="/pamdal/kunci/${p.id}/ambil" style="display:inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button style="background:#2f7ea1; color:white; padding:7px 14px; border-radius:8px; border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                                <i class="fa-solid fa-key"></i> Kunci Diambil
                            </button>
                        </form>`;
                    } else if (!p.waktu_kunci_dikembalikan) {
                        aksi = `<form method="POST" action="/pamdal/kunci/${p.id}/kembalikan" style="display:inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button style="background:#16a34a; color:white; padding:7px 14px; border-radius:8px; border:none; cursor:pointer; font-size:13px; font-weight:600; width:100%;">
                                <i class="fa-solid fa-rotate-left"></i> Dikembalikan
                            </button>
                        </form>`;
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
@endsection