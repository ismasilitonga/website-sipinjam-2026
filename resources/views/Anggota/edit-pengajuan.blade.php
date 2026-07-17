@extends('layouts.anggota')

@section('title', 'Edit Pengajuan')
@section('subtitle', 'Perbarui data pengajuan ruangan kamu')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <span class="card-title">Edit Pengajuan Ruangan</span>
    </div>

    <form method="POST"
          action="{{ route('anggota.riwayat-ruangan.update', $peminjaman->id) }}"
          enctype="multipart/form-data"
          style="padding:20px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom:16px;">
            <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Ruangan</label>
            <select name="ruangan_id" class="form-control" required>
                @foreach($ruangans as $r)
                    <option value="{{ $r->id }}" {{ $peminjaman->ruangan_id == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_ruangan }} - {{ $r->gedung }} Lt.{{ $r->lantai }}
                    </option>
                @endforeach
            </select>
            @error('ruangan_id')
                <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display:flex;gap:12px;margin-bottom:16px;">
            <div style="flex:1;">
                <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Mulai</label>
                <input type="text" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                    value="{{ \Carbon\Carbon::parse(old('tanggal_mulai', $peminjaman->tanggal_mulai))->format('Y-m-d\TH:i') }}"
                    autocomplete="off" required>
                @error('tanggal_mulai')
                    <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            <div style="flex:1;">
                <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Selesai</label>
                <input type="text" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                    value="{{ \Carbon\Carbon::parse(old('tanggal_selesai', $peminjaman->tanggal_selesai))->format('Y-m-d\TH:i') }}"
                    autocomplete="off" required>
                @error('tanggal_selesai')
                    <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div id="durasiInfo" style="display:none;margin:-8px 0 16px;font-size:12.5px;color:var(--text-muted);"></div>
        <div id="durasiError" class="form-error" style="display:none;margin:-8px 0 16px;color:var(--danger);"></div>

        <div style="margin-bottom:16px;">
            <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Keperluan</label>
            <input type="text" name="keperluan" class="form-control"
                value="{{ $peminjaman->keperluan }}" required>
            @error('keperluan')
                <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Dokumen Pendukung (opsional)</label>
            @if($peminjaman->dokumen_pendukung)
                <div style="font-size:12px;margin-bottom:6px;">
                    File saat ini:
                    <a href="{{ Storage::url($peminjaman->dokumen_pendukung) }}" target="_blank">Lihat Dokumen</a>
                </div>
            @endif
            <input type="file" name="dokumen_pendukung" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                Kosongkan jika tidak ingin mengganti file.
            </div>
            @error('dokumen_pendukung')
                <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <a href="{{ route('anggota.riwayat-ruangan') }}" class="btn btn-outline" style="padding:8px 16px;">
                Batal
            </a>
            <button type="submit" id="submitBtn" class="btn btn-primary" style="padding:8px 16px;">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    #submitBtn:disabled {
        background: #cbd5e1 !important;
        border-color: #cbd5e1 !important;
        color: #64748b !important;
        cursor: not-allowed !important;
        opacity: 1 !important;
        pointer-events: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', () => {

        flatpickr.localize(flatpickr.l10ns.id);

        const MAKS_DURASI_HARI  = 3;
        const MIN_DURASI_MENIT  = 60;

        const tglMulai    = document.getElementById('tanggal_mulai');
        const tglSelesai  = document.getElementById('tanggal_selesai');
        const durasiInfo  = document.getElementById('durasiInfo');
        const durasiError = document.getElementById('durasiError');
        const submitBtn   = document.getElementById('submitBtn');

        function getNowWIB() {
            const wibStr = new Date().toLocaleString('sv-SE', { timeZone: 'Asia/Jakarta' });
            return new Date(wibStr);
        }

        const nowWIB = getNowWIB();

        const fpMulai = flatpickr(tglMulai, {
            enableTime: true,
            time_24hr: true,
            dateFormat: 'Y-m-d\\TH:i',   
            altInput: true,
            altFormat: 'd/m/Y H:i',     
            minDate: nowWIB,
            onChange() { updateBatasSelesai(); validasiDurasi(); },
        });

        const fpSelesai = flatpickr(tglSelesai, {
            enableTime: true,
            time_24hr: true,
            dateFormat: 'Y-m-d\\TH:i',
            altInput: true,
            altFormat: 'd/m/Y H:i',
            onChange() { validasiDurasi(); },
        });

        function updateBatasSelesai() {
            const mulaiDate = fpMulai.selectedDates[0];
            if (!mulaiDate) {
                fpSelesai.set('minDate', null);
                fpSelesai.set('maxDate', null);
                return;
            }

            fpSelesai.set('minDate', mulaiDate);

            const maxDate = new Date(mulaiDate.getFullYear(), mulaiDate.getMonth(), mulaiDate.getDate());
            maxDate.setDate(maxDate.getDate() + (MAKS_DURASI_HARI - 1));
            maxDate.setHours(23, 59, 0, 0);
            fpSelesai.set('maxDate', maxDate);
        }

        function validasiDurasi() {
            durasiInfo.style.display  = 'none';
            durasiError.style.display = 'none';
            submitBtn.disabled = false;

            const mulaiDate   = fpMulai.selectedDates[0];
            const selesaiDate = fpSelesai.selectedDates[0];

            if (!mulaiDate || !selesaiDate) return;

            if (selesaiDate <= mulaiDate) {
                durasiError.textContent = 'Tanggal & jam selesai harus setelah tanggal & jam mulai.';
                durasiError.style.display = 'block';
                submitBtn.disabled = true;
                return;
            }

            const menitDurasi = Math.round((selesaiDate - mulaiDate) / 60000);

            if (menitDurasi < MIN_DURASI_MENIT) {
                durasiError.textContent = `Minimal durasi peminjaman ${MIN_DURASI_MENIT} menit.`;
                durasiError.style.display = 'block';
                submitBtn.disabled = true;
                return;
            }

            const batasHari = new Date(mulaiDate.getFullYear(), mulaiDate.getMonth(), mulaiDate.getDate());
            batasHari.setDate(batasHari.getDate() + (MAKS_DURASI_HARI - 1));
            batasHari.setHours(23, 59, 0, 0);

            if (selesaiDate > batasHari) {
                durasiError.textContent = `Peminjaman ruangan maksimal ${MAKS_DURASI_HARI} hari.`;
                durasiError.style.display = 'block';
                submitBtn.disabled = true;
                return;
            }

            const hari  = Math.floor(menitDurasi / (60 * 24));
            const jam   = Math.floor((menitDurasi % (60 * 24)) / 60);
            const menit = menitDurasi % 60;

            let teks = 'Durasi peminjaman: ';
            if (hari > 0) teks += `${hari} hari `;
            teks += `${jam} jam${menit ? ' ' + menit + ' menit' : ''}.`;

            durasiInfo.textContent = teks;
            durasiInfo.style.display = 'block';
        }

        updateBatasSelesai();
        validasiDurasi();
    });
</script>
@endpush
@endsection