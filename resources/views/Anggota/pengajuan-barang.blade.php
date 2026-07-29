@extends('layouts.anggota')

@section('title', 'Ajukan Peminjaman Barang')
@section('subtitle', 'Isi formulir peminjaman barang inventaris')

@section('topbar-action')
    <a href="{{ route('anggota.riwayat-barang') }}" class="btn btn-outline">Riwayat Saya</a>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')

@if(session('error'))
<div class="alert alert-danger" id="globalError" style="
    display:flex;align-items:flex-start;gap:10px;
    background:#fef2f2;border:1px solid #fecaca;
    border-radius:10px;padding:14px 16px;margin-bottom:20px;
    color:#991b1b;font-size:13.5px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
         style="width:20px;height:20px;flex-shrink:0;margin-top:1px;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <div>
        <strong>Pengajuan Gagal</strong><br>
        {{ session('error') }}
    </div>
</div>
@endif

@if(session('success'))
<div class="alert alert-success" style="
    display:flex;align-items:center;gap:10px;
    background:#f0fdf4;border:1px solid #bbf7d0;
    border-radius:10px;padding:14px 16px;margin-bottom:20px;
    color:#166534;font-size:13.5px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
         style="width:18px;height:18px;flex-shrink:0;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start;">

    <div class="card">
        <div class="card-header"><span class="card-title">Form Pengajuan Barang</span></div>
        <div class="card-body">
           
            <form method="POST" action="{{ route('anggota.pengajuan-barang.store') }}"
                  enctype="multipart/form-data" novalidate id="formPengajuanBarang">
                @csrf

                <div class="form-group">
                    <label class="form-label">Pilih Barang <span style="color:var(--danger)">*</span></label>
                    <select name="barang_id" class="form-select" id="barangSelect"
                            onchange="updateBarangInfo(this)">
                        <option value="">-- Pilih barang --</option>
                        @foreach($barangs as $b)
                        <option value="{{ $b->id }}"
                            data-stok="{{ $b->stok }}"
                            data-satuan="{{ $b->satuan }}"
                            data-kondisi="{{ $b->kondisi ?? 'baik' }}"
                            data-kategori="{{ $b->kategori ?? '' }}"
                            {{ old('barang_id', request('barang_id')) == $b->id ? 'selected' : '' }}>
                            {{ $b->nama }} — Total: {{ $b->stok }} {{ $b->satuan }}
                        </option>
                        @endforeach
                    </select>
                    @error('barang_id') <div class="form-error">{{ $message }}</div> @enderror
                    <div class="form-error" id="err-barang_id" style="display:none;"></div>

                    <div id="barangInfo" style="display:none;margin-top:10px;padding:12px 14px;
                         background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;font-size:13px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;color:var(--text-muted);">
                            <span>Total Stok: <strong id="infoStok" style="color:#16a34a;">—</strong></span>
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
                           placeholder="1" id="jumlahInput">
                    @error('jumlah') <div class="form-error">{{ $message }}</div> @enderror
                    <div class="form-error" id="err-jumlah" style="display:none;"></div>
                    <div class="form-hint" id="stokHint"></div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pinjam <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control"
                               value="{{ old('tanggal_pinjam') }}" autocomplete="off">
                        @error('tanggal_pinjam') <div class="form-error">{{ $message }}</div> @enderror
                        <div class="form-error" id="err-tanggal_pinjam" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam Pinjam <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="jam_pinjam" id="jam_pinjam" class="form-control"
                               placeholder="Pilih jam pinjam" autocomplete="off" readonly
                               value="{{ old('jam_pinjam') }}">
                        @error('jam_pinjam') <div class="form-error">{{ $message }}</div> @enderror
                        <div class="form-error" id="err-jam_pinjam" style="display:none;"></div>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Rencana Tanggal Kembali <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana"
                               class="form-control"
                               value="{{ old('tanggal_kembali_rencana') }}" autocomplete="off">
                        @error('tanggal_kembali_rencana') <div class="form-error">{{ $message }}</div> @enderror
                        <div class="form-error" id="err-tanggal_kembali_rencana" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam Kembali <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="jam_kembali_rencana" id="jam_kembali_rencana" class="form-control"
                               placeholder="Pilih jam kembali" autocomplete="off" readonly
                               value="{{ old('jam_kembali_rencana') }}">
                        <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                            Jam operasional: 07:50 – 23:00.
                        </div>
                        @error('jam_kembali_rencana') <div class="form-error">{{ $message }}</div> @enderror
                        <div class="form-error" id="err-jam_kembali_rencana" style="display:none;"></div>
                    </div>
                </div>

                <div id="jamError" class="form-error" style="display:none;margin:-8px 0 16px;">
                    Jam selesai harus setelah jam mulai.
                </div>

                <div id="durasiInfo" style="display:none;margin:-8px 0 16px;font-size:12.5px;color:var(--text-muted);"></div>
                <div id="durasiError" class="form-error" style="display:none;margin:-8px 0 16px;"></div>

                <div id="stokWarning" style="display:none;margin-top:-10px;margin-bottom:16px;
                     padding:12px 14px;border-radius:8px;font-size:13px;">
                </div>

                <div class="form-group">
                    <label class="form-label">Keperluan <span style="color:var(--danger)">*</span></label>
                    <textarea name="keperluan" class="form-control" rows="3" id="keperluanInput"
                              placeholder="Jelaskan untuk apa barang ini akan digunakan">{{ old('keperluan') }}</textarea>
                    @error('keperluan') <div class="form-error">{{ $message }}</div> @enderror
                    <div class="form-error" id="err-keperluan" style="display:none;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Upload Dokumen Pendukung <span style="color:var(--danger)">*</span>
                    </label>
                    <input type="file" name="dokumen_pendukung" id="dokumen_pendukung" class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png">
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                        Wajib diisi. Contoh: surat kegiatan/acara. Format PDF/JPG/PNG, maks. 5MB.
                    </div>
                    @error('dokumen_pendukung') <div class="form-error">{{ $message }}</div> @enderror
                    <div class="form-error" id="err-dokumen_pendukung" style="display:none;"></div>
                </div>

                <div class="alert alert-info" style="margin-bottom:18px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="width:17px;height:17px;flex-shrink:0;pointer-events:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pengajuan akan langsung diteruskan ke <strong>PIC</strong> untuk disetujui.
                    Barang akan diserahkan oleh PIC setelah disetujui.
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
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
                    <span class="badge {{ $b->stok_tersedia > 5 ? 'badge-green' : ($b->stok_tersedia > 0 ? 'badge-orange' : 'badge-red') }}"
                          style="font-size:11px;">
                        {{ $b->stok_tersedia }} {{ $b->satuan }}
                    </span>
                </div>
            </div>
            @empty
            <div style="padding:32px 16px;text-align:center;color:var(--text-muted);">
                Belum ada barang tersedia.
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
const CEK_STOK_URL = "{{ route('anggota.pengajuan-barang.cek-stok') }}";
let cekStokTimeout = null;

const MAKS_DURASI_HARI_BARANG = 7;
const MIN_DURASI_MENIT_BARANG = 60;

function updateBarangInfo(sel) {
    const opt  = sel.options[sel.selectedIndex];
    const info = document.getElementById('barangInfo');
    const hint = document.getElementById('stokHint');
    const inp  = document.getElementById('jumlahInput');

    if (!sel.value) {
        info.style.display = 'none';
        hint.textContent = '';
        inp.removeAttribute('max');
        sembunyikanWarning();
        return;
    }

    document.getElementById('infoStok').textContent     = opt.dataset.stok + ' ' + opt.dataset.satuan;
    document.getElementById('infoSatuan').textContent   = opt.dataset.satuan;
    document.getElementById('infoKategori').textContent = opt.dataset.kategori || '—';
    document.getElementById('infoKondisi').textContent  = opt.dataset.kondisi.replace('_', ' ');

    inp.max = opt.dataset.stok;
    hint.textContent = 'Maksimal ' + opt.dataset.stok + ' ' + opt.dataset.satuan;
    info.style.display = 'block';

    cekStokRealtime();
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

function sembunyikanWarning() {
    const w = document.getElementById('stokWarning');
    w.style.display = 'none';
    if (document.getElementById('durasiError').style.display !== 'block' &&
        document.getElementById('jamError').style.display !== 'block') {
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('submitBtn').style.opacity = '1';
    }
}

function tampilkanWarning(tersedia, stokTersedia, stokTotal, satuan, bentrokMulai, bentrokSelesai, bolehPinjamMulai, bufferMenit) {
    const w = document.getElementById('stokWarning');
    const btn = document.getElementById('submitBtn');

    if (tersedia) {
        w.style.display = 'block';
        w.style.background = '#f0fdf4';
        w.style.border = '1px solid #bbf7d0';
        w.style.color = '#166534';
        w.innerHTML = `
            <div style="display:flex;align-items:center;gap:8px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Pada tanggal yang dipilih tersedia Stok: <strong>${stokTersedia} ${satuan}</strong> dari total ${stokTotal} ${satuan}.</span>
            </div>
            <div style="margin-top:4px;padding-left:24px;font-size:12px;color:#4d7c5f;">
                Ketersediaan ini bersifat sementara — jika pengajuan lain disetujui PIC lebih dulu untuk waktu yang sama, pengajuanmu bisa ditolak.
            </div>`;
        if (document.getElementById('durasiError').style.display !== 'block' &&
            document.getElementById('jamError').style.display !== 'block') {
            btn.disabled = false;
            btn.style.opacity = '1';
        }
    } else {
        w.style.display = 'block';
        w.style.background = '#fef2f2';
        w.style.border = '1px solid #fecaca';
        w.style.color = '#991b1b';

        let infoJadwal;
        if (bentrokMulai && bentrokSelesai) {
            infoJadwal = `Sudah digunakan pihak lain pada
               <strong>${bentrokMulai} WIB</strong> &ndash; <strong>${bentrokSelesai} WIB</strong>.`;
            if (bolehPinjamMulai) {
                infoJadwal += ` Perlu jeda ${bufferMenit} menit untuk serah-terima, jadi barang baru bisa dipinjam lagi mulai
                    <strong>${bolehPinjamMulai} WIB</strong>. Silakan pilih Tanggal/Jam lain.`;
            } else {
                infoJadwal += ` Silakan pilih Tanggal/Jam lain.`;
            }
        } else {
            infoJadwal = `Semua ${stokTotal} ${satuan} sedang dipinjam pihak lain (disetujui PIC) pada waktu yang kamu pilih. Silakan pilih Tanggal/Jam lain.`;
        }

        w.innerHTML = `
            <div style="display:flex;align-items:flex-start;gap:8px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;margin-top:2px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span><strong>Barang tidak tersedia.</strong> ${infoJadwal}</span>
            </div>`;
        btn.disabled = true;
        btn.style.opacity = '0.5';
    }
}

function cekStokRealtime() {
    const barangId   = document.getElementById('barangSelect').value;
    const tglPinjam  = document.getElementById('tanggal_pinjam').value;
    const jamPinjam  = document.getElementById('jam_pinjam').value;
    const tglKembali = document.getElementById('tanggal_kembali_rencana').value;
    const jamKembali = document.getElementById('jam_kembali_rencana').value;

    if (!barangId || !tglPinjam || !jamPinjam) {
        sembunyikanWarning();
        return;
    }

    if (document.getElementById('durasiError').style.display === 'block' ||
        document.getElementById('jamError').style.display === 'block') {
        return;
    }

    clearTimeout(cekStokTimeout);
    cekStokTimeout = setTimeout(() => {
        const w = document.getElementById('stokWarning');
        w.style.display = 'block';
        w.style.background = '#f8fafc';
        w.style.border = '1px solid #e2e8f0';
        w.style.color = '#64748b';
        w.innerHTML = `<div style="display:flex;align-items:center;gap:8px;">
            <svg style="width:14px;height:14px;animation:spin 1s linear infinite;flex-shrink:0;"
                 fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25"/>
                <path d="M12 2a10 10 0 0110 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            </svg>
            Mengecek ketersediaan stok...</div>`;

        const params = new URLSearchParams({
            barang_id: barangId,
            tanggal_pinjam: tglPinjam,
            jam_pinjam: jamPinjam,
        });
        if (tglKembali) params.set('tanggal_kembali_rencana', tglKembali);
        if (jamKembali) params.set('jam_kembali_rencana', jamKembali);

        fetch(`${CEK_STOK_URL}?${params.toString()}`)
            .then(r => r.json())
            .then(data => {
                tampilkanWarning(
                    data.tersedia, data.stok_tersedia, data.stok_total, data.satuan,
                    data.bentrok_mulai, data.bentrok_selesai,
                    data.boleh_pinjam_mulai, data.buffer_menit
                );
                document.getElementById('jumlahInput').max = data.stok_tersedia;
                document.getElementById('stokHint').textContent = 'Maksimal ' + data.stok_tersedia + ' ' + data.satuan;
            })
            .catch(() => sembunyikanWarning());
    }, 400);
}

function validasiDurasiBarang() {
    const tglPinjam  = document.getElementById('tanggal_pinjam');
    const tglKembali = document.getElementById('tanggal_kembali_rencana');
    const durasiInfo  = document.getElementById('durasiInfo');
    const durasiError = document.getElementById('durasiError');
    const submitBtn   = document.getElementById('submitBtn');

    durasiInfo.style.display = 'none';
    durasiError.style.display = 'none';

    if (!tglPinjam.value || !tglKembali.value) return;

    const d1 = new Date(tglPinjam.value);
    const d2 = new Date(tglKembali.value);
    const selisihHari = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));

    if (d2 < d1) {
        durasiError.textContent = 'Rencana tanggal kembali tidak boleh sebelum tanggal pinjam.';
        durasiError.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        return;
    }

    if (selisihHari > MAKS_DURASI_HARI_BARANG) {
        durasiError.textContent = `Peminjaman barang maksimal ${MAKS_DURASI_HARI_BARANG} hari sejak tanggal pinjam. Rentang yang dipilih: ${selisihHari} hari.`;
        durasiError.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        return;
    }

    durasiInfo.textContent = selisihHari === 0
        ? 'Peminjaman dan pengembalian pada hari yang sama.'
        : `Durasi peminjaman: ${selisihHari} hari sejak tanggal pinjam.`;
    durasiInfo.style.display = 'block';
    submitBtn.disabled = false;
    submitBtn.style.opacity = '1';
}

function isSatuHariBarang() {
    const tglPinjam  = document.getElementById('tanggal_pinjam').value;
    const tglKembali = document.getElementById('tanggal_kembali_rencana').value;
    return tglPinjam && tglKembali && tglPinjam === tglKembali;
}

function validasiJamBarang() {
    const jamError = document.getElementById('jamError');
    const submitBtn = document.getElementById('submitBtn');

    jamError.style.display = 'none';

    const mulaiTime   = window.fpJamPinjam?.selectedDates?.[0];
    const selesaiTime = window.fpJamKembali?.selectedDates?.[0];

    if (!mulaiTime || !selesaiTime) return;
    if (!isSatuHariBarang()) return;

    const menitMulai   = mulaiTime.getHours() * 60 + mulaiTime.getMinutes();
    const menitSelesai = selesaiTime.getHours() * 60 + selesaiTime.getMinutes();
    const selisihMenit = menitSelesai - menitMulai;

    if (selisihMenit <= 0) {
        jamError.textContent = 'Jam kembali harus setelah jam pinjam.';
        jamError.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        return;
    }
    if (selisihMenit < MIN_DURASI_MENIT_BARANG) {
        jamError.textContent = `Minimal durasi peminjaman ${MIN_DURASI_MENIT_BARANG} menit.`;
        jamError.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        return;
    }
    if (document.getElementById('durasiError').style.display !== 'block') {
        submitBtn.disabled = false; submitBtn.style.opacity = '1';
    }
}

function tampilkanErrorField(id, pesan) {
    const el = document.getElementById('err-' + id);
    if (el) {
        el.textContent = pesan; el.style.display = 'block';
    }
}

function sembunyikanErrorField(id) {
    const el = document.getElementById('err-' + id);
    if (el) {
        el.style.display = 'none'; el.textContent = '';
    }
}

function sembunyikanSemuaErrorField() {
    ['barang_id', 'jumlah', 'tanggal_pinjam', 'jam_pinjam',
     'tanggal_kembali_rencana', 'jam_kembali_rencana', 'keperluan', 'dokumen_pendukung']
        .forEach(sembunyikanErrorField);
}

function validasiFormSebelumSubmit(e) {
    sembunyikanSemuaErrorField();
    let valid = true;
    let elemenErrorPertama = null;

    const barangSelect = document.getElementById('barangSelect');
    const jumlahInput   = document.getElementById('jumlahInput');
    const tglPinjam     = document.getElementById('tanggal_pinjam');
    const jamPinjam     = document.getElementById('jam_pinjam');
    const tglKembali    = document.getElementById('tanggal_kembali_rencana');
    const jamKembali    = document.getElementById('jam_kembali_rencana');
    const keperluan     = document.getElementById('keperluanInput');
    const dokumen       = document.getElementById('dokumen_pendukung');

    function tandaiSalah(input, id, pesan) {
        tampilkanErrorField(id, pesan);
        if (!elemenErrorPertama) elemenErrorPertama = input;
        valid = false;
    }
    if (!barangSelect.value) {
        tandaiSalah(barangSelect, 'barang_id', 'Barang harus dipilih.');
    }
    const jumlahVal = parseInt(jumlahInput.value, 10);
    const jumlahMax = parseInt(jumlahInput.max || '0', 10);
    if (!jumlahInput.value || isNaN(jumlahVal) || jumlahVal < 1) {
        tandaiSalah(jumlahInput, 'jumlah', 'Jumlah harus diisi, minimal 1.');
    } else if (jumlahInput.max && jumlahVal > jumlahMax) {
        tandaiSalah(jumlahInput, 'jumlah', `Jumlah maksimal ${jumlahMax} ${document.getElementById('infoSatuan').textContent || 'unit'}.`);
    }
    if (!tglPinjam.value) {
        tandaiSalah(tglPinjam, 'tanggal_pinjam', 'Tanggal pinjam harus diisi.');
    }
    if (!jamPinjam.value) {
        tandaiSalah(jamPinjam, 'jam_pinjam', 'Jam pinjam harus diisi.');
    }
    if (!tglKembali.value) {
        tandaiSalah(tglKembali, 'tanggal_kembali_rencana', 'Rencana tanggal kembali harus diisi.');
    }
    if (!jamKembali.value) {
        tandaiSalah(jamKembali, 'jam_kembali_rencana', 'Jam kembali harus diisi.');
    }
    if (!keperluan.value.trim()) {
        tandaiSalah(keperluan, 'keperluan', 'Keperluan harus diisi.');
    }
    if (!dokumen.files || dokumen.files.length === 0) {
        tandaiSalah(dokumen, 'dokumen_pendukung', 'Dokumen pendukung wajib diunggah.');
    }
    if (!valid) {
        e.preventDefault();
        if (elemenErrorPertama) {
            elemenErrorPertama.scrollIntoView({ behavior: 'smooth', block: 'center' });
            elemenErrorPertama.focus({ preventScroll: true });
        }
    }

    return valid;
}

window.addEventListener('DOMContentLoaded', () => {
    flatpickr.localize(flatpickr.l10ns.id);

    const sel = document.getElementById('barangSelect');
    if (sel.value) updateBarangInfo(sel);

    window.tomSelectInstance = new TomSelect('#barangSelect', {
        create: false,
        placeholder: 'Ketik nama barang...',
        maxOptions: 500,
        onInitialize() { if (sel.value) updateBarangInfo(sel); },
        onChange()     { updateBarangInfo(sel); sembunyikanErrorField('barang_id'); }
    });

    function getNowWIB() {
        const now = new Date();
        const wibStr = now.toLocaleString('sv-SE', { timeZone: 'Asia/Jakarta' });
        return new Date(wibStr);
    }

    const minDate = (() => {
        const d = getNowWIB();
        d.setDate(d.getDate() + 2);
        const yyyy = d.getFullYear();
        const mm   = String(d.getMonth() + 1).padStart(2, '0');
        const dd   = String(d.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    })();

    const tglPinjam  = document.getElementById('tanggal_pinjam');
    const tglKembali = document.getElementById('tanggal_kembali_rencana');

    const fpTglPinjam = flatpickr(tglPinjam, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd F Y',
        minDate: minDate,
        onChange() { syncMinTglKembali(); cekStokRealtime(); sembunyikanErrorField('tanggal_pinjam'); },
    });

    const fpTglKembali = flatpickr(tglKembali, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd F Y',
        minDate: minDate,
        onChange() { validasiDurasiBarang(); validasiJamBarang(); cekStokRealtime(); sembunyikanErrorField('tanggal_kembali_rencana'); },
    });

    function updateMaxTglKembali() {
        if (!tglPinjam.value) {
            fpTglKembali.set('maxDate', null);
            return;
        }
        const d = new Date(tglPinjam.value);
        d.setDate(d.getDate() + (MAKS_DURASI_HARI_BARANG - 1));
        fpTglKembali.set('maxDate', d);
    }

    function syncMinTglKembali() {
        if (tglPinjam.value) {
            fpTglKembali.set('minDate', tglPinjam.value);
            if (tglKembali.value && tglKembali.value < tglPinjam.value) {
                fpTglKembali.setDate(tglPinjam.value, true);
            }
        }
        updateMaxTglKembali();
        validasiDurasiBarang();
        validasiJamBarang();
        cekStokRealtime();
    }

    const wibNow = getNowWIB();

    function toHHMM(date) {
        return String(date.getHours()).padStart(2, '0') + ':' + String(date.getMinutes()).padStart(2, '0');
    }

    const timeConfig = {
        enableTime: true, noCalendar: true, time_24hr: true,
        dateFormat: 'H:i', minuteIncrement: 1,
        disableMobile: true,
    };

    window.fpJamPinjam = flatpickr('#jam_pinjam', {
        ...timeConfig,
        minTime: '07:50',
        maxTime: '23:00',
        onOpen(_, __, fp) {
            if (!fp.selectedDates.length) {
                fp.hourElement.value   = String(wibNow.getHours()).padStart(2, '0');
                fp.minuteElement.value = String(wibNow.getMinutes()).padStart(2, '0');
            }
        },
        onChange() { validasiJamBarang(); cekStokRealtime(); sembunyikanErrorField('jam_pinjam'); },
        onClose(selectedDates, _, fp) {
            if (!selectedDates.length) fp.clear();
            validasiJamBarang();
            cekStokRealtime();
        },
    });
    window.fpJamKembali = flatpickr('#jam_kembali_rencana', {
        ...timeConfig,
        minTime: '07:50',
        maxTime: '23:00',
        onOpen(_, __, fp) {
            const mulaiTime = window.fpJamPinjam?.selectedDates?.[0];
            if (mulaiTime && isSatuHariBarang()) {
                const minKembali = new Date(mulaiTime.getTime() + MIN_DURASI_MENIT_BARANG * 60 * 1000);
                fp.set('minTime', toHHMM(minKembali));
                if (!fp.selectedDates.length) {
                    fp.hourElement.value   = String(minKembali.getHours()).padStart(2, '0');
                    fp.minuteElement.value = String(minKembali.getMinutes()).padStart(2, '0');
                }
            } else {
                fp.set('minTime', '07:50');
            }
        },
        onChange() { validasiJamBarang(); cekStokRealtime(); sembunyikanErrorField('jam_kembali_rencana'); },
        onClose(selectedDates, _, fp) {
            if (!selectedDates.length) fp.clear();
            validasiJamBarang();
            cekStokRealtime();
        },
    });

    syncMinTglKembali();

    document.getElementById('jumlahInput').addEventListener('input', () => sembunyikanErrorField('jumlah'));
    document.getElementById('keperluanInput').addEventListener('input', () => sembunyikanErrorField('keperluan'));
    document.getElementById('dokumen_pendukung').addEventListener('change', () => sembunyikanErrorField('dokumen_pendukung'));
    document.getElementById('formPengajuanBarang').addEventListener('submit', validasiFormSebelumSubmit);
});
</script>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
#submitBtn:disabled {
    cursor: not-allowed !important;
    pointer-events: none;
}
</style>
@endpush
@endsection