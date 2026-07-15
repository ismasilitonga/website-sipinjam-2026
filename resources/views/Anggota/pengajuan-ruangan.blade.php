@extends('layouts.anggota')

@section('title', 'Ajukan Peminjaman Ruangan')
@section('subtitle', 'Isi formulir pengajuan penggunaan ruangan')

@section('topbar-action')
    <a href="{{ route('anggota.riwayat-ruangan') }}" class="btn btn-outline">Riwayat Saya</a>
@endsection

@section('content')

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

    <div class="card">
        <div class="card-header"><span class="card-title">Form Pengajuan Ruangan</span></div>
        <div class="card-body">
            <form method="POST" action="{{ route('anggota.pengajuan-ruangan.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Pilih Ruangan <span style="color:var(--danger)">*</span></label>

                    <input type="hidden" name="ruangan_id" id="ruangan_id_input"
                           value="{{ old('ruangan_id', request('ruangan_id')) }}">

                    <div id="ruangan-dropdown" style="position:relative;">
                        <div id="ruangan-trigger" class="form-select"
                             style="cursor:pointer;user-select:none;position:relative;
                                    display:flex;align-items:center;justify-content:space-between;
                                    padding-right:34px;">
                            <span id="ruangan-label">
                                @php
                                    $selectedRuangan = $ruangans->firstWhere('id', old('ruangan_id', request('ruangan_id')));
                                @endphp
                                @if($selectedRuangan)
                                    {{ $selectedRuangan->nama_ruangan }}{{ $selectedRuangan->gedung ? ' – '.$selectedRuangan->gedung : '' }}{{ $selectedRuangan->kapasitas ? ' ('.$selectedRuangan->kapasitas.' org)' : '' }}
                                @else
                                    -- Pilih ruangan tersedia --
                                @endif
                            </span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="width:14px;height:14px;position:absolute;right:12px;top:50%;
                                        transform:translateY(-50%);color:var(--text-muted);pointer-events:none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div id="ruangan-panel"
                             style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;
                                    background:#fff;border:1px solid var(--border);border-radius:8px;
                                    box-shadow:0 4px 16px rgba(0,0,0,.1);z-index:999;
                                    max-height:220px;overflow-y:auto;">

                            <div class="ruangan-option" data-value=""
                                 data-label="-- Pilih ruangan tersedia --"
                                 style="padding:10px 14px;font-size:14px;cursor:pointer;
                                        color:#1e293b;border-bottom:1px solid #f3f4f6;">
                                -- Pilih ruangan tersedia --
                            </div>

                            @foreach($ruangans as $r)
                            <div class="ruangan-option"
                                 data-value="{{ $r->id }}"
                                 data-gedung="{{ $r->gedung ?? '' }}"
                                 data-lantai="{{ $r->lantai ?? '' }}"
                                 data-kapasitas="{{ $r->kapasitas ?? '' }}"
                                 data-fasilitas="{{ $r->fasilitas ?? '' }}"
                                 data-label="{{ $r->nama_ruangan }}{{ $r->gedung ? ' – '.$r->gedung : '' }}{{ $r->kapasitas ? ' ('.$r->kapasitas.' org)' : '' }}"
                                 style="padding:10px 14px;font-size:14px;cursor:pointer;
                                        color:#1e293b;border-bottom:1px solid #f3f4f6;
                                    {{ old('ruangan_id', request('ruangan_id')) == $r->id ? 'background:#ede9fe;color:#6d28d9;font-weight:600;' : '' }}">
                                {{ $r->nama_ruangan }}{{ $r->gedung ? ' – '.$r->gedung : '' }}{{ $r->kapasitas ? ' ('.$r->kapasitas.' org)' : '' }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('ruangan_id') <div class="form-error">{{ $message }}</div> @enderror

                    <div id="ruanganInfo" style="display:none;margin-top:10px;padding:12px 14px;
                         background:#f5f3ff;border-radius:8px;border:1px solid #ddd6fe;font-size:13px;">
                        <div style="font-weight:600;color:var(--accent);margin-bottom:6px;">Info Ruangan</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;color:var(--text-muted);">
                            <span>Gedung: <strong id="infoGedung" style="color:var(--text);">—</strong></span>
                            <span>Lantai: <strong id="infoLantai" style="color:var(--text);">—</strong></span>
                            <span>Kapasitas: <strong id="infoKapasitas" style="color:var(--text);">—</strong></span>
                            <span>Fasilitas: <strong id="infoFasilitas" style="color:var(--text);">—</strong></span>
                        </div>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                               value="{{ old('tanggal_mulai') }}" autocomplete="off" required>
                        @error('tanggal_mulai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                               value="{{ old('tanggal_selesai') }}" autocomplete="off" required>
                        @error('tanggal_selesai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div id="durasiInfo" style="display:none;margin:-8px 0 16px;font-size:12.5px;color:var(--text-muted);"></div>
                <div id="durasiError" class="form-error" style="display:none;margin:-8px 0 16px;"></div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Jam Mulai <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="jam_mulai" id="jam_mulai" class="form-control"
                               placeholder="Pilih jam mulai" autocomplete="off" readonly
                               value="{{ old('jam_mulai') }}" required>
                        @error('jam_mulai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam Selesai <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="jam_selesai" id="jam_selesai" class="form-control"
                               placeholder="Pilih jam selesai" autocomplete="off" readonly
                               value="{{ old('jam_selesai') }}" required>
                        <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                            Durasi peminjaman minimal 1 jam.
                        </div>
                        @error('jam_selesai') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div id="jamInfo" style="display:none;margin:-8px 0 16px;font-size:12.5px;color:var(--text-muted);"></div>
                <div id="jamError" class="form-error" style="display:none;margin:-8px 0 16px;">
                    Jam selesai harus setelah jam mulai.
                </div>
                <div id="bentrokWarning" class="form-error" style="display:none;margin:-8px 0 16px;
                     background:#fef2f2;border:1px solid #fecaca;padding:10px 12px;border-radius:8px;"></div>

                <div class="form-group">
                    <label class="form-label">Keperluan / Kegiatan <span style="color:var(--danger)">*</span></label>
                    <textarea name="keperluan" class="form-control" rows="3"
                              placeholder="Contoh: Rapat koordinasi persiapan dies natalis ormawa"
                              required>{{ old('keperluan') }}</textarea>
                    @error('keperluan') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Upload Dokumen Pendukung <span style="color:var(--danger)">*</span>
                    </label>
                    <input type="file" name="dokumen_pendukung" id="dokumen_pendukung" class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png" required>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                        Wajib diisi. Contoh: surat undangan rapat, notulen rapat pengurus
                        Format PDF/JPG/PNG, maks. 5MB.
                    </div>
                    @error('dokumen_pendukung') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="alert alert-info" style="margin-bottom:18px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:17px;height:17px;flex-shrink:0;pointer-events:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        Pengajuan akan diteruskan ke <strong>Ketua Ormawa</strong> untuk disetujui terlebih dahulu,
                        kemudian ke <strong>PIC</strong> untuk validasi akhir. Ruangan baru terkunci untuk pengaju lain
                        setelah pengajuan ini <strong>disetujui PIC</strong>.
                    </span>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="pointer-events:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Pengajuan
                    </button>
                    <a href="{{ route('anggota.daftar-ruangan') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Alur Pengajuan</span></div>
        <div class="card-body">
            @foreach([
                ['step'=>'1','label'=>'Kamu','sub'=>'Mengajukan peminjaman ruangan','active'=>true],
                ['step'=>'2','label'=>'Ketua Ormawa','sub'=>'Menyetujui pengajuan anggota','active'=>false],
                ['step'=>'3','label'=>'PIC','sub'=>'Validasi akhir & konfirmasi (ruangan terkunci di sini)','active'=>false],
                ['step'=>'4','label'=>'Disetujui','sub'=>'Kamu bisa check-in di hari H','active'=>false],
            ] as $s)
            <div style="display:flex;gap:12px;padding:12px 0;
                        {{ !$loop->last ? 'border-bottom:1px dashed var(--border);' : '' }}">
                <div style="width:30px;height:30px;border-radius:50%;flex-shrink:0;
                            display:flex;align-items:center;justify-content:center;
                            font-size:13px;font-weight:700;
                            {{ $s['active'] ? 'background:var(--accent);color:#fff;' : 'background:#f3f4f6;color:var(--text-muted);' }}">
                    {{ $s['step'] }}
                </div>
                <div style="padding-top:5px;">
                    <div style="font-size:13px;font-weight:600;">{{ $s['label'] }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $s['sub'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
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

        const MAKS_DURASI_HARI = 3;

        const MIN_DURASI_MENIT = 60;

        const trigger = document.getElementById('ruangan-trigger');
        const panel   = document.getElementById('ruangan-panel');
        const label   = document.getElementById('ruangan-label');
        const input   = document.getElementById('ruangan_id_input');
        const options = document.querySelectorAll('.ruangan-option');
        const info    = document.getElementById('ruanganInfo');

        function showInfo(opt) {
            if (!opt || !opt.dataset.value) {
                info.style.display = 'none';
                return;
            }
            document.getElementById('infoGedung').textContent    = opt.dataset.gedung    || '—';
            document.getElementById('infoLantai').textContent    = opt.dataset.lantai    ? 'Lt. '+opt.dataset.lantai      : '—';
            document.getElementById('infoKapasitas').textContent = opt.dataset.kapasitas ? opt.dataset.kapasitas+' orang' : '—';
            document.getElementById('infoFasilitas').textContent = opt.dataset.fasilitas || '—';
            info.style.display = 'block';
        }

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        });

        options.forEach(function (opt) {
            opt.addEventListener('mouseenter', function () {
                this.style.background = '#f5f3ff';
            });
            opt.addEventListener('mouseleave', function () {
                this.style.background = this.dataset.value === input.value ? '#ede9fe' : '';
            });
            opt.addEventListener('click', function () {
                input.value = this.dataset.value;
                label.textContent = this.dataset.label;
                panel.style.display = 'none';

                options.forEach(o => {
                    o.style.background  = '';
                    o.style.color       = '#1e293b';
                    o.style.fontWeight  = '';
                });
                if (this.dataset.value) {
                    this.style.background = '#ede9fe';
                    this.style.color      = '#6d28d9';
                    this.style.fontWeight = '600';
                }

                showInfo(this);
                cekBentrokSekarang();
            });
        });

        document.addEventListener('click', function () {
            panel.style.display = 'none';
        });

        if (input.value) {
            const selected = document.querySelector('.ruangan-option[data-value="' + input.value + '"]');
            showInfo(selected);
        }

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

        const tglMulai   = document.getElementById('tanggal_mulai');
        const tglSelesai = document.getElementById('tanggal_selesai');
        const durasiInfo = document.getElementById('durasiInfo');
        const durasiError = document.getElementById('durasiError');
        const submitBtn  = document.getElementById('submitBtn');

        const fpTglMulai = flatpickr(tglMulai, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd F Y',
            minDate: minDate,
            onChange() { validasiDurasi(); validasiJam(); cekBentrokSekarang(); },
        });

        const fpTglSelesai = flatpickr(tglSelesai, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd F Y',
            minDate: minDate,
            onChange() { validasiDurasi(); validasiJam(); cekBentrokSekarang(); },
        });

        function updateMaxTanggalSelesai() {
            if (!tglMulai.value) {
                fpTglSelesai.set('maxDate', null);
                return;
            }
            const d = new Date(tglMulai.value);
            d.setDate(d.getDate() + (MAKS_DURASI_HARI - 1));
            fpTglSelesai.set('maxDate', d);
        }

        function validasiDurasi() {
            durasiInfo.style.display = 'none';
            durasiError.style.display = 'none';
            submitBtn.disabled = false;

            updateMaxTanggalSelesai();

            if (tglMulai.value) {
                fpTglSelesai.set('minDate', tglMulai.value);
            }

            if (!tglMulai.value || !tglSelesai.value) return;

            const d1 = new Date(tglMulai.value);
            const d2 = new Date(tglSelesai.value);
            const selisihHari = Math.round((d2 - d1) / (1000 * 60 * 60 * 24)) + 1; 

            if (d2 < d1) {
                durasiError.textContent = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
                durasiError.style.display = 'block';
                submitBtn.disabled = true;
                return;
            }

            if (selisihHari > MAKS_DURASI_HARI) {
                durasiError.textContent = `Peminjaman ruangan maksimal ${MAKS_DURASI_HARI} hari. Rentang yang dipilih: ${selisihHari} hari.`;
                durasiError.style.display = 'block';
                submitBtn.disabled = true;
                return;
            }

            durasiInfo.textContent = `Durasi peminjaman: ${selisihHari} hari.`;
            durasiInfo.style.display = 'block';
        }

        validasiDurasi();

        const wibNow = getNowWIB();

        function toHHMM(date) {
            return String(date.getHours()).padStart(2, '0') + ':' + String(date.getMinutes()).padStart(2, '0');
        }

        const timeConfig = {
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            dateFormat: 'H:i',
            minuteIncrement: 1,
            disableMobile: true,
        };

        const jamError = document.getElementById('jamError');
        const jamInfo  = document.getElementById('jamInfo');

        function isSatuHari() {
            return tglMulai.value && tglSelesai.value && tglMulai.value === tglSelesai.value;
        }

        function validasiJam() {
            const mulaiTime   = fpMulai?.selectedDates?.[0];
            const selesaiTime = fpSelesai?.selectedDates?.[0];

            jamError.style.display = 'none';
            jamInfo.style.display  = 'none';

            if (!mulaiTime || !selesaiTime || !tglMulai.value || !tglSelesai.value) return;

            const mulaiFull   = new Date(tglMulai.value + 'T' + toHHMM(mulaiTime) + ':00');
            const selesaiFull = new Date(tglSelesai.value + 'T' + toHHMM(selesaiTime) + ':00');

            if (selesaiFull <= mulaiFull) {
                jamError.textContent = isSatuHari()
                    ? 'Jam selesai harus setelah jam mulai.'
                    : 'Tanggal & jam selesai harus setelah tanggal & jam mulai.';
                jamError.style.display = 'block';
                submitBtn.disabled = true;
                return;
            }

            const menitDurasiCek = Math.round((selesaiFull - mulaiFull) / 60000);
            if (menitDurasiCek < MIN_DURASI_MENIT) {
                jamError.textContent = `Minimal durasi peminjaman ${MIN_DURASI_MENIT} menit.`;
                jamError.style.display = 'block';
                submitBtn.disabled = true;
                return;
            }

            const menitDurasi = Math.round((selesaiFull - mulaiFull) / 60000);
            const hariDurasi  = Math.floor(menitDurasi / (60 * 24));
            const jam         = Math.floor((menitDurasi % (60 * 24)) / 60);
            const menit       = menitDurasi % 60;

            let teks = 'Durasi penggunaan: ';
            if (hariDurasi > 0) teks += `${hariDurasi} hari `;
            teks += `${jam} jam${menit ? ' ' + menit + ' menit' : ''}.`;

            jamInfo.textContent = teks;
            jamInfo.style.display = 'block';

            if (durasiError.style.display === 'none') submitBtn.disabled = false;
        }

        const bentrokWarning = document.getElementById('bentrokWarning');
        let bentrokTimeout = null;

        function cekBentrokSekarang() {
            bentrokWarning.style.display = 'none';

            const ruanganId = input.value;
            const mulaiTime   = fpMulai?.selectedDates?.[0];
            const selesaiTime = fpSelesai?.selectedDates?.[0];

            if (!ruanganId || !tglMulai.value || !tglSelesai.value || !mulaiTime || !selesaiTime) return;
            if (jamError.style.display === 'block' || durasiError.style.display === 'block') return;

            clearTimeout(bentrokTimeout);
            bentrokTimeout = setTimeout(() => {
                fetch('{{ route('anggota.pengajuan-ruangan.cek-bentrok') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({
                        ruangan_id: ruanganId,
                        tanggal_mulai: tglMulai.value,
                        tanggal_selesai: tglSelesai.value,
                        jam_mulai: toHHMM(mulaiTime),
                        jam_selesai: toHHMM(selesaiTime),
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.bentrok) {
                        bentrokWarning.textContent = data.pesan;
                        bentrokWarning.style.display = 'block';
                        submitBtn.disabled = true;
                    } else {
                        submitBtn.disabled = false;
                    }
                })
                .catch(() => { /* diamkan kalau request gagal, jangan blok user */ });
            }, 400);
        }

        const fpMulai = flatpickr('#jam_mulai', {
            ...timeConfig,
            onOpen(_, __, fp) {
                if (!fp.selectedDates.length) {
                    fp.hourElement.value   = String(wibNow.getHours()).padStart(2, '0');
                    fp.minuteElement.value = String(wibNow.getMinutes()).padStart(2, '0');
                }
            },
            onChange() { validasiJam(); cekBentrokSekarang(); },
            onClose(selectedDates, dateStr, fp) {
                if (!selectedDates.length) fp.clear();
                validasiJam();
                cekBentrokSekarang();
            },
        });

        const fpSelesai = flatpickr('#jam_selesai', {
            ...timeConfig,

            onOpen(_, __, fp) {
                const mulaiDate = fpMulai?.selectedDates?.[0];
                if (mulaiDate && isSatuHari()) {
                    const minSelesai = new Date(mulaiDate.getTime() + MIN_DURASI_MENIT * 60 * 1000);
                    fp.set('minTime', toHHMM(minSelesai));
                    if (!fp.selectedDates.length) {
                        fp.hourElement.value   = String(minSelesai.getHours()).padStart(2, '0');
                        fp.minuteElement.value = String(minSelesai.getMinutes()).padStart(2, '0');
                    }
                } else {
                    fp.set('minTime', null);
                }
            },
            onChange() { validasiJam(); cekBentrokSekarang(); },
            onClose(selectedDates, _, fp) {
                if (!selectedDates.length) fp.clear();
                validasiJam();
                cekBentrokSekarang();
            },
        });

        tglMulai.addEventListener('change', validasiJam);
        tglSelesai.addEventListener('change', validasiJam);
        tglMulai.addEventListener('change', cekBentrokSekarang);
        tglSelesai.addEventListener('change', cekBentrokSekarang);
    });
</script>
@endpush
@endsection