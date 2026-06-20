@extends('layouts.pic')

@section('title', 'Serah Terima Barang')
@section('subtitle', 'Konfirmasi penyerahan dan penerimaan kembali barang')

@section('content')

<div class="card" style="margin-bottom: 20px;">
    <div class="card-header" style="padding-bottom: 16px;">
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
                    <tr style="background: #fffbeb;">
                        <td style="color: var(--text-muted); font-size: 12px;">{{ $i + 1 }}</td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ $pb->user->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $pb->nama_ormawa ?? $pb->user?->organisasi ?? '' }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ $pb->barang->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $pb->barang->kode ?? '' }}</div>
                        </td>
                        <td style="font-size: 13px;">
                            <span style="font-weight: 700;">{{ $pb->jumlah }}</span>
                            <span style="color: var(--text-muted); font-size: 11.5px;">{{ $pb->barang->satuan ?? '' }}</span>
                        </td>
                        <td style="font-size: 12.5px;">{{ \Carbon\Carbon::parse($pb->tanggal_pinjam)->format('d M Y') }}</td>
                        <td style="font-size: 12.5px;">{{ \Carbon\Carbon::parse($pb->tanggal_kembali_rencana)->format('d M Y') }}</td>
                        <td style="font-size: 12.5px; max-width: 140px;">
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $pb->keperluan }}">
                                {{ $pb->keperluan }}
                            </div>
                        </td>
                        <td>
                        <form id="serahForm{{ $pb->id }}" method="POST"
                            action="{{ route('pic.serah-terima.konfirmasi', $pb->id) }}"
                            enctype="multipart/form-data">
                         @csrf
                        <input type="file" name="foto_serah" id="foto_serah_hidden_{{ $pb->id }}"
                                accept="image/*" style="display: none;">
                        <button type="button" class="btn btn-success btn-sm"
                                onclick="openSerahModal('{{ $pb->id }}', '{{ addslashes($pb->user->nama ?? '-') }}', '{{ addslashes($pb->barang->nama ?? '-') }}')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 13px; height: 13px;">
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
                <div class="empty-state" style="padding: 28px 20px;">
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
    <div class="card-header" style="padding-bottom: 16px;">
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
                    <th>Foto Serah</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menungguKembali as $i => $pb)
                    @php
                        $terlambat = \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($pb->tanggal_kembali_rencana))
                            && is_null($pb->waktu_diterima_kembali);
                    @endphp
                    <tr style="{{ $terlambat ? 'background: #fef2f2;' : '' }}">
                        <td style="color: var(--text-muted); font-size: 12px;">{{ $i + 1 }}</td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ $pb->user->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $pb->nama_ormawa ?? $pb->user?->organisasi ?? '' }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ $pb->barang->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $pb->barang->kode ?? '' }}</div>
                        </td>
                        <td style="font-size: 13px;">
                            <span style="font-weight: 700;">{{ $pb->jumlah }}</span>
                            <span style="color: var(--text-muted); font-size: 11.5px;">{{ $pb->barang->satuan ?? '' }}</span>
                        </td>
                        <td style="font-size: 12.5px;">
                            {{ \Carbon\Carbon::parse($pb->waktu_diserahkan)->format('d M Y, H:i') }}
                        </td>
                        <td>
                               @if($pb->foto_serah)
                            <a href="{{ Storage::url($pb->foto_serah) }}" target="_blank">
                                  <img src="{{ Storage::url($pb->foto_serah) }}"
                                 style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb;">
                            </a>
                            @else
                            <span style="font-size: 11px; color: var(--text-muted);">—</span>
                         @endif
                        </td>
                        <td style="font-size: 12.5px;">
                        <span style="{{ $terlambat ? 'color: #dc2626; font-weight: 700;' : '' }}">
                        {{ \Carbon\Carbon::parse($pb->tanggal_kembali_rencana)->format('d M Y') }}
                        </span>
                        @if($terlambat)
                        <span class="badge badge-red" style="margin-left: 4px; font-size: 10px;">Terlambat</span>
                        @endif
                    </td>
                    <td><span class="badge badge-blue">Dipinjam</span></td>
                    <td>
                <form id="kembaliForm{{ $pb->id }}" method="POST"
                    action="{{ route('pic.terima-kembali', $pb->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                <input type="file"   name="foto_kembali"    id="foto_kembali_hidden_{{ $pb->id }}" accept="image/*" style="display: none;">
                <input type="hidden" name="kondisi_barang"  id="kondisi_hidden_{{ $pb->id }}">
                <input type="hidden" name="catatan_kondisi" id="catatan_hidden_{{ $pb->id }}">
                <button type="button" class="btn btn-primary btn-sm"
                     onclick="openKembaliModal('{{ $pb->id }}', '{{ addslashes($pb->user->nama ?? '-') }}', '{{ addslashes($pb->barang->nama ?? '-') }}')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 13px; height: 13px;">
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
            <td colspan="9">
            <div class="empty-state" style="padding: 28px 20px;">
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

<div class="card" style="margin-top: 20px;">
    <div class="card-header" style="padding-bottom: 16px;">
        <span class="card-title">📋 Riwayat Serah Terima Barang</span>
        <span class="badge badge-blue" id="riwayatCount">{{ $riwayat->count() }} item</span>
    </div>

<div style="padding: 16px 20px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
    <div style="display: flex; flex-direction: column; gap: 4px;">
        <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Minggu</label>
        <input type="week" id="filterMinggu"
               style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #374151; background: white;">
        </div>
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Bulan</label>
            <input type="month" id="filterBulan"
                   style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #374151; background: white;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px;">
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Ormawa</label>
            <input type="text" id="filterOrmawa" placeholder="Cari nama ormawa..."
                    style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #374151; background: white; min-width: 150px;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px;">
            <label style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Barang</label>
            <input type="text" id="filterBarang" placeholder="Cari nama barang..."
                    style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #374151; background: white; min-width: 180px;">
                </div>
            <button onclick="resetFilter()" class="btn-reset">Reset</button>
                </div>
                <div class="table-wrap">
                <table id="riwayatTable">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Diserahkan Pada</th>
                    <th>Foto Serah</th>
                    <th>Diterima Kembali</th>
                    <th>Foto Kembali</th>
                    <th>Kondisi</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody id="riwayatBody">
                @forelse($riwayat as $i => $pb)
                    <tr class="riwayat-row"
                        data-ormawa="{{ $pb->nama_ormawa }}"
                        data-barang="{{ $pb->barang->nama ?? '' }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($pb->waktu_diterima_kembali)->format('Y-m-d') }}">
                        <td style="font-size: 12px; color: var(--text-muted);" class="row-no">{{ $i + 1 }}</td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ $pb->user->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $pb->nama_ormawa }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ $pb->barang->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $pb->barang->kode ?? '' }}</div>
                        </td>
                        <td style="font-size: 13px;">{{ $pb->jumlah }} {{ $pb->barang->satuan ?? '' }}</td>
                        <td style="font-size: 12px;">{{ \Carbon\Carbon::parse($pb->waktu_diserahkan)->format('d M Y, H:i') }}</td>
                        <td>
                            @if($pb->foto_serah)
                                <a href="{{ Storage::url($pb->foto_serah) }}" target="_blank">
                                    <img src="{{ Storage::url($pb->foto_serah) }}"
                                         style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb;">
                                </a>
                            @else
                                <span style="font-size: 11px; color: var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="font-size: 12px;">{{ \Carbon\Carbon::parse($pb->waktu_diterima_kembali)->format('d M Y, H:i') }}</td>
                        <td>
                            @if($pb->foto_kembali)
                                <a href="{{ Storage::url($pb->foto_kembali) }}" target="_blank">
                                    <img src="{{ Storage::url($pb->foto_kembali) }}"
                                         style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb;">
                                </a>
                            @else
                                <span style="font-size: 11px; color: var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $kondisiLabel = ['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'];
                                $kondisiBadge = ['baik' => 'badge-green', 'rusak_ringan' => 'badge-yellow', 'rusak_berat' => 'badge-red'];
                            @endphp
                            @if($pb->kondisi_barang)
                                <span class="badge {{ $kondisiBadge[$pb->kondisi_barang] ?? '' }}">
                                    {{ $kondisiLabel[$pb->kondisi_barang] ?? $pb->kondisi_barang }}
                                </span>
                            @else
                                <span style="font-size: 11px; color: var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="font-size: 12px; max-width: 150px;">{{ $pb->catatan_kondisi ?? '—' }}</td>
                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="10">
                            <div class="empty-state" style="padding: 28px 20px;">
                                <p>Belum ada riwayat serah terima.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
        </table>
        <div id="noFilterResult" style="display: none; padding: 28px; text-align: center; color: var(--text-muted); font-size: 13px;">
            Tidak ada data yang sesuai filter.
        </div>
        <div id="paginationWrap" style="display: flex; align-items: center; justify-content: flex-start; gap: 6px; padding: 16px;">
       </div>
     </div>
    </div>

<div class="modal-overlay" id="serahModal">
    <div class="custom-modal">
        <div class="modal-icon success">✓</div>
        <h3 style="margin: 12px 0 6px;">Konfirmasi Penyerahan Barang</h3>
        <p id="serahText" style="font-size: 13px; color: #64748b; margin-bottom: 16px;"></p>
        <div class="foto-upload-area" onclick="document.getElementById('fotoSerahInput').click()">
            <div id="serahFotoPreview" style="display: none; margin-bottom: 8px;">
                <img id="serahFotoImg" style="max-width: 100%; max-height: 140px; object-fit: contain; border-radius: 8px; display: block; margin: auto;">
            </div>
            <div id="serahFotoPlaceholder" style="text-align: center; color: #7c3aed; font-size: 13px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 28px; height: 28px; margin: 0 auto 6px; display: block;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Ambil / Unggah Foto Serah
                <div style="font-size: 11px; color: #a78bfa; margin-top: 2px;">Opsional · Maks. 2MB</div>
            </div>
        </div>
        <input type="file" id="fotoSerahInput" accept="image/*" style="display: none;"
               onchange="previewModalFoto(this, 'serahFotoImg', 'serahFotoPreview', 'serahFotoPlaceholder')">
        <div class="modal-actions">
            <button type="button" class="btn-cancel" onclick="closeSerahModal()">Batal</button>
            <button type="button" class="btn-confirm" onclick="submitSerahForm()">Ya, Serahkan</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="kembaliModal">
    <div class="custom-modal">
        <div class="modal-icon success">↩</div>
        <h3 style="margin: 12px 0 6px;">Konfirmasi Pengembalian Barang</h3>
        <p id="kembaliText" style="font-size: 13px; color: #64748b; margin-bottom: 16px;"></p>
        <div class="foto-upload-area" onclick="document.getElementById('fotoKembaliInput').click()">
            <div id="kembaliFotoPreview" style="display: none; margin-bottom: 8px;">
                <img id="kembaliFotoImg" style="max-width: 100%; max-height: 140px; object-fit: contain; border-radius: 8px; display: block; margin: auto;">
            </div>
            <div id="kembaliFotoPlaceholder" style="text-align: center; color: #7c3aed; font-size: 13px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 28px; height: 28px; margin: 0 auto 6px; display: block;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Ambil / Unggah Foto Kembali
                <div style="font-size: 11px; color: #a78bfa; margin-top: 2px;">Opsional · Maks. 2MB</div>
            </div>
        </div>
        <input type="file" id="fotoKembaliInput" accept="image/*" style="display: none;"
               onchange="previewModalFoto(this, 'kembaliFotoImg', 'kembaliFotoPreview', 'kembaliFotoPlaceholder')">
        <div style="margin-bottom: 12px; text-align: left;">
            <label style="font-size: 13px; font-weight: 600;">Kondisi Barang</label>
            <select id="modalKondisiBarang" style="width: 100%; margin-top: 6px; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="baik">Baik</option>
                <option value="rusak_ringan">Rusak Ringan</option>
                <option value="rusak_berat">Rusak Berat</option>
            </select>
        </div>
        <div style="margin-bottom: 12px; text-align: left;">
            <label style="font-size: 13px; font-weight: 600;">Catatan Kondisi</label>
            <textarea id="modalCatatanKondisi" rows="3" placeholder="Contoh: terdapat goresan pada bagian samping"
                      style="width: 100%; margin-top: 6px; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;"></textarea>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" onclick="closeKembaliModal()">Batal</button>
            <button type="button" class="btn-confirm" onclick="submitKembaliForm()">Ya, Terima Kembali</button>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,.45);
    display: none; align-items: center; justify-content: center; z-index: 9999;
}
.modal-overlay.show { display: flex; }
.custom-modal {
    width: 560px; max-width: 95%; background: white; border-radius: 18px;
    padding: 24px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,.25);
}
.modal-icon {
    width: 70px; height: 70px; border-radius: 50%; margin: auto;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: bold;
}
.modal-icon.success { background: #dcfce7; color: #16a34a; }
.foto-upload-area {
    border: 1.5px dashed #c4b5fd; border-radius: 10px; padding: 10px;
    background: #faf5ff; margin-bottom: 12px; cursor: pointer;
}
.modal-actions { display: flex; gap: 10px; margin-top: 16px; }
.btn-cancel {
    flex: 1; border: none; background: #e2e8f0; color: #374151;
    padding: 12px; border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 500;
}
.btn-confirm {
    flex: 1; border: none; background: #7c3aed; color: white;
    padding: 12px; border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 600;
}
.btn-reset {
    padding: 8px 18px; border: none; border-radius: 8px;
    font-size: 13px; font-weight: 600; background: #7c3aed;
    color: white; cursor: pointer; height: fit-content;
    transition: background 0.15s, transform 0.1s;
}
.btn-reset:hover { background: #6d28d9; }
.btn-reset:active { transform: scale(0.95); }

.pg-btn {
    width: 34px; height: 34px; border-radius: 8px; border: 1px solid #e2e8f0;
    background: white; color: #374151; font-size: 13px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; justify-content: center;
    transition: background 0.15s;
}
.pg-btn:hover { background: #f3f0ff; }
.pg-btn.active { background: #7c3aed; color: white; border-color: #7c3aed; }
.pg-btn:disabled { opacity: 0.4; cursor: default; }
</style>

<script>
let selectedSerahId   = null;
let selectedKembaliId = null;

function openSerahModal(id, peminjam, barang) {
    selectedSerahId = id;
    document.getElementById('serahText').innerHTML =
        `Apakah Anda yakin ingin menyerahkan <b>${barang}</b> kepada <b>${peminjam}</b>?`;
    document.getElementById('fotoSerahInput').value               = '';
    document.getElementById('serahFotoPreview').style.display     = 'none';
    document.getElementById('serahFotoPlaceholder').style.display = 'block';
    document.getElementById('serahModal').classList.add('show');
}

function closeSerahModal() {
    document.getElementById('serahModal').classList.remove('show');
    selectedSerahId = null;
}

function submitSerahForm() {
    if (!selectedSerahId) return;
    const fotoInput = document.getElementById('fotoSerahInput');

    if (fotoInput && fotoInput.files.length > 0) {
        try {
            const hiddenInput = document.getElementById('foto_serah_hidden_' + selectedSerahId);
            const dt = new DataTransfer();
            dt.items.add(fotoInput.files[0]);
            hiddenInput.files = dt.files;
        } catch (err) {
            console.error('Gagal melampirkan foto serah, lanjut submit tanpa foto:', err);
        }
    }
    const form = document.getElementById('serahForm' + selectedSerahId);
    if (!form) {
        console.error('Form serahForm' + selectedSerahId + ' tidak ditemukan');
        return;
    }
    form.submit();
}

function openKembaliModal(id, peminjam, barang) {
    selectedKembaliId = id;
    document.getElementById('kembaliText').innerHTML =
        `Apakah <b>${barang}</b> sudah dikembalikan oleh <b>${peminjam}</b>?`;
    document.getElementById('fotoKembaliInput').value               = '';
    document.getElementById('kembaliFotoPreview').style.display     = 'none';
    document.getElementById('kembaliFotoPlaceholder').style.display = 'block';
    document.getElementById('modalKondisiBarang').value             = 'baik';
    document.getElementById('modalCatatanKondisi').value            = '';
    document.getElementById('kembaliModal').classList.add('show');
}

function closeKembaliModal() {
    document.getElementById('kembaliModal').classList.remove('show');
    selectedKembaliId = null;
}

function submitKembaliForm() {
    if (!selectedKembaliId) return;

    const fotoInput = document.getElementById('fotoKembaliInput');

    if (fotoInput && fotoInput.files.length > 0) {
        try {
            const hiddenInput = document.getElementById('foto_kembali_hidden_' + selectedKembaliId);
            const dt = new DataTransfer();
            dt.items.add(fotoInput.files[0]);
            hiddenInput.files = dt.files;
        } catch (err) {
            console.error('Gagal melampirkan foto kembali, lanjut submit tanpa foto:', err);
        }
    }

    document.getElementById('kondisi_hidden_' + selectedKembaliId).value =
        document.getElementById('modalKondisiBarang').value;
    document.getElementById('catatan_hidden_' + selectedKembaliId).value =
        document.getElementById('modalCatatanKondisi').value;

    const form = document.getElementById('kembaliForm' + selectedKembaliId);
    if (!form) {
        console.error('Form kembaliForm' + selectedKembaliId + ' tidak ditemukan');
        return;
    }
    form.submit();
}

function previewModalFoto(input, imgId, previewId, placeholderId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById(imgId).src                   = e.target.result;
        document.getElementById(previewId).style.display     = 'block';
        document.getElementById(placeholderId).style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}

const PER_PAGE = 10;
let currentPage = 1;
let visibleRows = [];

function applyFilter() {
    const minggu = document.getElementById('filterMinggu').value;
    const bulan  = document.getElementById('filterBulan').value;
    const ormawa = document.getElementById('filterOrmawa').value.toLowerCase();
    const barang = document.getElementById('filterBarang').value.toLowerCase();
    const rows   = document.querySelectorAll('.riwayat-row');

    visibleRows = [];
    rows.forEach(row => {
        const tgl       = row.dataset.tanggal;
        const rowOrmawa = row.dataset.ormawa.toLowerCase();
        const rowBarang = row.dataset.barang.toLowerCase();
        const date      = new Date(tgl);
        let show        = true;

        if (minggu) {
            const [wYear, wWeek] = minggu.split('-W').map(Number);
            if (date.getFullYear() !== wYear || getISOWeek(date) !== wWeek) show = false;
        }
        if (bulan) {
            const [bYear, bMonth] = bulan.split('-').map(Number);
            if (date.getFullYear() !== bYear || date.getMonth() + 1 !== bMonth) show = false;
        }
        if (ormawa && !rowOrmawa.includes(ormawa)) show = false;
        if (barang && !rowBarang.includes(barang))  show = false;

        row.style.display = 'none';
        if (show) visibleRows.push(row);
    });

    currentPage = 1;
    renderPage();
}

function renderPage() {
    const total     = visibleRows.length;
    const totalPage = Math.max(1, Math.ceil(total / PER_PAGE));
    if (currentPage > totalPage) currentPage = totalPage;

    const start = (currentPage - 1) * PER_PAGE;
    const end   = start + PER_PAGE;

    visibleRows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? '' : 'none';
        if (i >= start && i < end) {
            row.querySelector('.row-no').textContent = start + (i - start) + 1;
        }
    });

    document.getElementById('riwayatCount').textContent = total + ' item';
    document.getElementById('noFilterResult').style.display = total === 0 ? 'block' : 'none';

    renderPagination(totalPage);
}

function renderPagination(totalPage) {
    const wrap = document.getElementById('paginationWrap');
    wrap.innerHTML = '';
    if (totalPage <= 1) return;

    const prev = document.createElement('button');
    prev.className = 'pg-btn';
    prev.innerHTML = '‹';
    prev.disabled  = currentPage === 1;
    prev.onclick   = () => { currentPage--; renderPage(); };
    wrap.appendChild(prev);

    for (let p = 1; p <= totalPage; p++) {
        const btn = document.createElement('button');
        btn.className = 'pg-btn' + (p === currentPage ? ' active' : '');
        btn.textContent = p;
        btn.onclick = () => { currentPage = p; renderPage(); };
        wrap.appendChild(btn);
    }

    const next = document.createElement('button');
    next.className = 'pg-btn';
    next.innerHTML = '›';
    next.disabled  = currentPage === totalPage;
    next.onclick   = () => { currentPage++; renderPage(); };
    wrap.appendChild(next);
}

function resetFilter() {
    document.getElementById('filterMinggu').value = '';
    document.getElementById('filterBulan').value  = '';
    document.getElementById('filterOrmawa').value = '';
    document.getElementById('filterBarang').value = '';
    applyFilter();
}

function getISOWeek(date) {
    const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
    d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
    const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
    return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
}

document.getElementById('filterMinggu').addEventListener('change', applyFilter);
document.getElementById('filterBulan').addEventListener('change', applyFilter);
document.getElementById('filterOrmawa').addEventListener('input', applyFilter);
document.getElementById('filterBarang').addEventListener('input', applyFilter);

document.addEventListener('DOMContentLoaded', applyFilter);
</script>
@endsection