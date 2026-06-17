@extends('layouts.anggota')

@section('title', 'Ajukan Peminjaman Barang')
@section('subtitle', 'Isi formulir peminjaman barang inventaris')

@section('topbar-action')
    <a href="{{ route('anggota.riwayat-barang') }}" class="btn btn-outline">Riwayat Saya</a>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endsection

@section('content')

<div style="display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start;">

    <div class="card">
        <div class="card-header"><span class="card-title">Form Pengajuan Barang</span></div>
        <div class="card-body">
            <form method="POST" action="{{ route('anggota.pengajuan-barang.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Pilih Barang <span style="color:var(--danger)">*</span></label>
                    <select name="barang_id" class="form-select" required id="barangSelect"
                            onchange="updateBarangInfo(this)">
                        <option value="">-- Pilih barang --</option>
                        @foreach($barangs as $b)
                        <option value="{{ $b->id }}"
                            data-stok="{{ $b->stok }}"
                            data-satuan="{{ $b->satuan }}"
                            data-kondisi="{{ $b->kondisi ?? 'baik' }}"
                            data-kategori="{{ $b->kategori ?? '' }}"
                            {{ old('barang_id', request('barang_id')) == $b->id ? 'selected' : '' }}>
                            {{ $b->nama }} — Stok: {{ $b->stok }} {{ $b->satuan }}
                        </option>
                        @endforeach
                    </select>
                    @error('barang_id') <div class="form-error">{{ $message }}</div> @enderror

                    <div id="barangInfo" style="display:none;margin-top:10px;padding:12px 14px;
                         background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;font-size:13px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;color:var(--text-muted);">
                            <span>Stok Tersedia: <strong id="infoStok" style="color:#16a34a;">—</strong></span>
                            <span>Satuan: <strong id="infoSatuan" style="color:var(--text);">—</strong></span>
                            <span>Kategori: <strong id="infoKategori" style="color:var(--text);">—</strong></span>
                            <span>Kondisi: <strong id="infoKondisi" style="color:var(--text);">—</strong></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="jumlah" class="form-control" min="1"
                           value="{{ old('jumlah', 1) }}"
                           placeholder="1" required id="jumlahInput">
                    @error('jumlah') <div class="form-error">{{ $message }}</div> @enderror
                    <div class="form-hint" id="stokHint"></div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pinjam <span style="color:var(--danger)">*</span></label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control"
                               value="{{ old('tanggal_pinjam') }}"
                               min="{{ date('Y-m-d', strtotime('+2 days')) }}" required>
                        @error('tanggal_pinjam') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rencana Tanggal Kembali <span style="color:var(--danger)">*</span>
                        </label>
                        <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana"
                               class="form-control"
                               value="{{ old('tanggal_kembali_rencana') }}"
                               min="{{ date('Y-m-d', strtotime('+2 days')) }}" required>
                        @error('tanggal_kembali_rencana') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keperluan <span style="color:var(--danger)">*</span></label>
                    <textarea name="keperluan" class="form-control" rows="3"
                              placeholder="Jelaskan untuk apa barang ini akan digunakan" required>{{ old('keperluan') }}</textarea>
                    @error('keperluan') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="alert alert-info" style="margin-bottom:18px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:17px;height:17px;flex-shrink:0;pointer-events:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pengajuan akan langsung diteruskan ke <strong>PIC</strong> untuk disetujui.
                    Barang akan diserahkan oleh PIC setelah disetujui.
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary" style="pointer-events:auto;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="pointer-events:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Pengajuan
                    </button>
                    <a href="{{ route('anggota.daftar-barang') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Daftar Barang Tersedia</span></div>
        <div class="card-body" style="padding:0;max-height:400px;min-height:120px;overflow-y:auto;">
            @forelse($barangs as $b)
            <div style="padding:12px 16px;border-bottom:1px solid var(--border);
                        cursor:pointer;transition:background .1s;"
                 onclick="selectBarang({{ $b->id }})"
                 onmouseover="this.style.background='#f5f3ff'"
                 onmouseout="this.style.background=''">
                <div style="font-weight:600;font-size:13px;">{{ $b->nama }}</div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px;">
                    <span style="font-size:12px;color:var(--text-muted);">{{ $b->kategori ?? 'Umum' }}</span>
                    <span class="badge {{ $b->stok > 5 ? 'badge-green' : ($b->stok > 0 ? 'badge-orange' : 'badge-red') }}" style="font-size:11px;">
                        {{ $b->stok }} {{ $b->satuan }}
                    </span>
                </div>
            </div>
            @empty
            <div style="padding:32px 16px;text-align:center;color:var(--text-muted);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="width:36px;height:36px;margin:0 auto 10px;display:block;opacity:0.4;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>
                </svg>
                <p style="font-size:13px;margin:0;">Belum ada barang tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
function updateBarangInfo(sel) {
    const opt  = sel.options[sel.selectedIndex];
    const info = document.getElementById('barangInfo');
    const hint = document.getElementById('stokHint');
    const inp  = document.getElementById('jumlahInput');

    if (!sel.value) {
        info.style.display = 'none';
        hint.textContent = '';
        inp.removeAttribute('max');
        return;
    }

    document.getElementById('infoStok').textContent     = opt.dataset.stok + ' ' + opt.dataset.satuan;
    document.getElementById('infoSatuan').textContent   = opt.dataset.satuan;
    document.getElementById('infoKategori').textContent = opt.dataset.kategori || '—';
    document.getElementById('infoKondisi').textContent  = opt.dataset.kondisi.replace('_', ' ');

    inp.max = opt.dataset.stok;
    hint.textContent = 'Maksimal ' + opt.dataset.stok + ' ' + opt.dataset.satuan;
    info.style.display = 'block';
}

function selectBarang(id) {
    if (window.tomSelectInstance) {
        window.tomSelectInstance.setValue(id);
    } else {
        const sel = document.getElementById('barangSelect');
        sel.value = id;
        updateBarangInfo(sel);
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('barangSelect');

    if (sel.value) updateBarangInfo(sel);

    window.tomSelectInstance = new TomSelect('#barangSelect', {
        create: false,
        placeholder: 'Ketik nama barang...',
        maxOptions: 500,
        onInitialize() {
            if (sel.value) updateBarangInfo(sel);
        },
        onChange(value) {
            updateBarangInfo(sel);
        }
    });

    const tglPinjam   = document.getElementById('tanggal_pinjam');
    const tglKembali  = document.getElementById('tanggal_kembali_rencana');

    function syncMinTglKembali() {
        if (tglPinjam.value) {
            tglKembali.min = tglPinjam.value; 
            if (tglKembali.value && tglKembali.value < tglPinjam.value) {
                tglKembali.value = tglPinjam.value;
            }
        }
    }

    tglPinjam.addEventListener('change', syncMinTglKembali);
    syncMinTglKembali(); 
});
</script>
@endpush
@endsection