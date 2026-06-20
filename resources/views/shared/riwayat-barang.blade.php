@php
    $layout = match(auth()->user()->role) {
        'ketua'   => 'layouts.ketua',
        'anggota' => 'layouts.anggota',
        'pamdal'  => 'layouts.pamdal',
        'pic'     => 'layouts.pic',
        'admin'   => 'layouts.admin'
    };

    $routePrefix = auth()->user()->role;
@endphp

@extends($layout)

@section('title', 'Validasi Peminjaman Barang')
@section('subtitle', 'Seluruh riwayat peminjaman barang oleh ormawa')

@push('styles')
<style>
.pagination-wrap nav > div:first-child { display: none; }
.pagination-wrap nav > div:last-child { display: flex; gap: 4px; padding: 16px 20px; align-items: center; }

.pagination-wrap span[aria-current="page"] span,
.pagination-wrap a,
.pagination-wrap span span {
    .pagination-wrap nav > div:last-child > * {
    height: 34px;
    min-width: 34px;
}
    border-radius: 8px; border: 1px solid #e2e8f0;
    background: white; color: #374151;
    font-size: 13px; font-weight: 600;
    text-decoration: none; transition: background 0.15s;
}
.pagination-wrap a:hover { background: #f3f0ff; border-color: #7c3aed; color: #7c3aed; }
.pagination-wrap span[aria-current="page"] span { background: #7c3aed; color: white; border-color: #7c3aed; }
.pagination-wrap span.disabled span { opacity: 0.4; }

.pagination-wrap p { display: none; }
</style>
@endpush

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Validasi Peminjaman Barang</span>
        <span class="badge badge-gray">{{ $riwayat->total() }} data</span>
    </div>

<div style="padding: 0 20px 20px; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <span style="font-size:14px; color:var(--text-muted); margin-right:4px;">Filter:</span>
        @php
            $filterStatus = request('status', '');
            $filters = [
                ''                 => 'Semua',
                'menunggu_ketua'   => 'Menunggu Ketua',
                'menunggu_pic'     => 'Menunggu PIC',
                'disetujui'        => 'Disetujui',
                'ditolak'          => 'Ditolak',
            ];
        @endphp
        @foreach($filters as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => 1]) }}"
               class="badge {{ $filterStatus === (string)$val ? 'badge-purple' : 'badge-gray' }}"
               style="text-decoration:none; padding: 8px 20px; border-radius: 999px; font-size: 12px; font-weight: 600;">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="table-wrap" style="overflow-x:auto;">
    <table style="width:100%;">
        <thead>
    <tr>
        <th style="width:40px;">No</th>
        <th style="width:130px;">Peminjam</th>
        <th style="width:120px;">Ormawa</th>
        <th style="width:100px;">Barang</th>
        <th style="width:80px;">Jumlah</th>
        <th style="width:110px;">Tgl Pinjam</th>
        <th style="width:130px;">Status</th>
        <th style="width:200px;">Aksi</th>
    </tr>
</thead>
<tbody>
    @forelse($riwayat as $p)
    <tr>
        <td style="color:var(--text-muted);font-size:12px;">
            {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
        </td>
        <td>
            <div style="font-weight:500;font-size:13px;white-space:nowrap;">{{ $p->user->nama ?? '-' }}</div>
            <div style="font-size:11px;color:var(--text-muted);">{{ $p->user->nim ?? '' }}</div>
        </td>
        <td style="font-size:12.5px;">{{ $p->nama_ormawa ?? '-' }}</td>
        <td style="font-size:13px;font-weight:500;white-space:nowrap;">{{ $p->barang->nama ?? '-' }}</td>
        <td style="font-size:12.5px;">{{ $p->jumlah ?? '-' }} {{ $p->barang->satuan ?? 'unit' }}</td>
        <td style="font-size:12.5px;white-space:nowrap;">
            {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
        </td>
        <td>
            @php
                [$cls, $lbl] = match($p->status) {
                    'menunggu_ketua' => ['badge-yellow',  'Menunggu Ketua'],
                    'menunggu_pic'   => ['badge-purple',  'Menunggu PIC'],
                    'disetujui'      => ['badge-green',   'Disetujui'],
                    'ditolak'        => ['badge-red',     'Ditolak'],
                    default          => ['badge-gray',    ucfirst($p->status)],
                };
            @endphp
            <span class="badge {{ $cls }}" style="white-space:nowrap;">{{ $lbl }}</span>
        </td>
        <td>
            <div style="display:flex;gap:6px;align-items:center;">
                <a href="{{ route($routePrefix . '.barang.detail', $p->id) }}"
                   class="btn btn-outline btn-sm" style="font-size:12px;padding:4px 12px;">
                    Detail
                </a>

                @if(auth()->user()->role == 'pic' && $p->status == 'menunggu_pic')
                    <form method="POST" action="{{ route('pic.barang.setujui', $p->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm"
                                style="display:flex;align-items:center;gap:4px;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="bukaModalTolakBarang('{{ $p->id }}')"
                            style="display:flex;align-items:center;gap:4px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak
                    </button>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8">
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                </svg>
                <p>Belum ada riwayat peminjaman barang.</p>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
        </table>
    </div>

    @if($riwayat->hasPages())
    <div class="pagination-wrap">{{ $riwayat->links() }}</div>
    @endif
    </div>


<div id="modalTolakBarang" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:32px 28px;width:100%;max-width:400px;
                box-shadow:0 10px 40px rgba(0,0,0,0.18);margin:16px;">

            <div style="width:52px;height:52px;border-radius:50%;background:#fee2e2;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="26" height="26" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </div>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Tolak Pengajuan?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan menolak pengajuan peminjaman barang ini.
                Tindakan ini <strong>tidak dapat dibatalkan</strong>.
            </div>
        </div>

            <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModalTolakBarang()"
            style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                   Batal
                </button>
                <button type="button" onclick="document.getElementById('formTolakBarang').submit()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#dc2626;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Tolak
            </button>
        </div>
    </div>
  </div>

    <form id="formTolakBarang" method="POST" style="display:none;">
    @csrf
    </form>

  <script>
    const baseUrlTolakBarang = "{{ url($routePrefix . '/barang') }}";

    function bukaModalTolakBarang(id) {
    document.getElementById('formTolakBarang').action = `${baseUrlTolakBarang}/${id}/tolak`;
    document.getElementById('modalTolakBarang').style.display = 'flex';
  }
    function tutupModalTolakBarang() {
    document.getElementById('modalTolakBarang').style.display = 'none';
 }
   document.getElementById('modalTolakBarang').addEventListener('click', function(e) {
    if (e.target === this) tutupModalTolakBarang();
 });
</script>

@endsection