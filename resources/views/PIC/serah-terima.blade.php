@extends('layouts.pic')

@section('title', 'Serah Terima Barang')
@section('subtitle', 'Konfirmasi penyerahan dan penerimaan kembali barang')

@section('content')

<div class="card" style="margin-bottom:20px;">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">⬇️ Barang Menunggu Diserahkan ke Peminjam</span>
        <span class="badge badge-orange">{{ $menungguSerah->count() }} item</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Keperluan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menungguSerah as $i => $pb)
                <tr style="background:#fffbeb;">
                    <td style="color:var(--text-muted);font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $pb->user->name ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $pb->nama_ormawa ?? $pb->user?->organisasi ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $pb->barang->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $pb->barang->kode ?? '' }}</div>
                    </td>
                    <td style="font-size:13px;">
                        <span style="font-weight:700;">{{ $pb->jumlah }}</span>
                        <span style="color:var(--text-muted);font-size:11.5px;">{{ $pb->barang->satuan ?? '' }}</span>
                    </td>
                    <td style="font-size:12.5px;">{{ \Carbon\Carbon::parse($pb->tanggal_pinjam)->format('d M Y') }}</td>
                    <td style="font-size:12.5px;">{{ \Carbon\Carbon::parse($pb->tanggal_kembali_rencana)->format('d M Y') }}</td>
                    <td style="font-size:12.5px;max-width:140px;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $pb->keperluan }}">
                            {{ $pb->keperluan }}
                        </div>
                    </td>
                    <td>
                        <form id="serahForm{{ $pb->id }}" method="POST"
                        action="{{ route('pic.serah-terima.konfirmasi', $pb->id) }}">
                            @csrf
                           <button type="button"
    class="btn btn-success btn-sm"
    onclick="openSerahModal(
        '{{ $pb->id }}',
        '{{ addslashes($pb->user->name ?? '-') }}',
        '{{ addslashes($pb->barang->nama ?? '-') }}'
    )">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Serahkan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state" style="padding:28px 20px;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Tidak ada barang yang menunggu untuk diserahkan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">⬆️ Barang Menunggu Dikembalikan dari Peminjam</span>
        <span class="badge badge-blue">{{ $menungguKembali->count() }} item</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Diserahkan Pada</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menungguKembali as $i => $pb)
                @php
                $terlambat = \Carbon\Carbon::today()->gt(
                \Carbon\Carbon::parse($pb->tanggal_kembali_rencana)
                ) && is_null($pb->waktu_diterima_kembali);
                @endphp
                
                <tr style="{{ $terlambat ? 'background:#fef2f2;' : '' }}">
                    <td style="color:var(--text-muted);font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $pb->user->name ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $pb->nama_ormawa ?? $pb->user?->organisasi ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $pb->barang->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $pb->barang->kode ?? '' }}</div>
                    </td>
                    <td style="font-size:13px;">
                        <span style="font-weight:700;">{{ $pb->jumlah }}</span>
                        <span style="color:var(--text-muted);font-size:11.5px;">{{ $pb->barang->satuan ?? '' }}</span>
                    </td>
                    <td style="font-size:12.5px;">
                        {{ \Carbon\Carbon::parse($pb->waktu_diserahkan)->format('d M Y, H:i') }}
                    </td>
                    <td style="font-size:12.5px;">
                        <span style="{{ $terlambat ? 'color:#dc2626;font-weight:700;' : '' }}">
                            {{ \Carbon\Carbon::parse($pb->tanggal_kembali_rencana)->format('d M Y') }}
                        </span>
                        @if($terlambat)
                            <span class="badge badge-red" style="margin-left:4px;font-size:10px;">Terlambat</span>
                        @endif
                    </td>
                    <td><span class="badge badge-blue">Dipinjam</span></td>
                    <td>
                        <form method="POST" action="{{ route('pic.terima-kembali', $pb->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm"
                                onclick="return confirm('Konfirmasi barang dikembalikan oleh {{ addslashes($pb->user->name ?? '') }}?')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4"/>
                                </svg>
                                Terima Kembali
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state" style="padding:28px 20px;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M5 13l4 4L19 7"/>
                            </svg>
                            <p>Tidak ada barang yang sedang dipinjam.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.modal-overlay.show{
    display:flex;
}

.custom-modal{
    width:420px;
    background:white;
    border-radius:18px;
    padding:24px;
    text-align:center;
    box-shadow:0 20px 50px rgba(0,0,0,.25);
}

.modal-icon{
    width:70px;
    height:70px;
    border-radius:50%;
    margin:auto;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:32px;
    font-weight:bold;
}

.modal-icon.success{
    background:#dcfce7;
    color:#16a34a;
}

.modal-actions{
    display:flex;
    gap:10px;
    margin-top:20px;
}

.btn-cancel{
    flex:1;
    border:none;
    background:#e2e8f0;
    padding:12px;
    border-radius:12px;
    cursor:pointer;
}

.btn-confirm{
    flex:1;
    border:none;
    background:#7c3aed;
    color:white;
    padding:12px;
    border-radius:12px;
    cursor:pointer;
}
</style>

<script>
let selectedSerahForm = null;

function openSerahModal(id, peminjam, barang)
{
    selectedSerahForm = document.getElementById('serahForm' + id);

    document.getElementById('serahText').innerHTML =
        `Apakah Anda yakin ingin menyerahkan <b>${barang}</b> kepada <b>${peminjam}</b>?`;

    document.getElementById('serahModal').classList.add('show');
}

function closeSerahModal()
{
    document.getElementById('serahModal').classList.remove('show');
}

function submitSerahForm()
{
    if(selectedSerahForm){
        selectedSerahForm.submit();
    }
}
</script>

<div class="modal-overlay" id="serahModal">
    <div class="custom-modal">

        <div class="modal-icon success">
            ✓
        </div>

        <h3>Konfirmasi Penyerahan Barang</h3>

        <p id="serahText"></p>

        <div class="modal-actions">
            <button type="button"
                class="btn-cancel"
                onclick="closeSerahModal()">
                Batal
            </button>

            <button type="button"
                class="btn-confirm"
                onclick="submitSerahForm()">
                Ya, Serahkan
            </button>
        </div>

    </div>
</div>

@endsection
