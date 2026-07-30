@extends('layouts.pic')

@section('title', 'Detail Serah Terima Barang')
@section('subtitle', ($pb->barang->nama ?? '-') . ' — ' . ($pb->user->nama ?? '-'))

@section('content')

@php
    $kondisiLabel = ['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'];
    $kondisiBadge = ['baik' => 'badge-green', 'rusak_ringan' => 'badge-yellow', 'rusak_berat' => 'badge-red'];
    $kondisiLbl = $kondisiLabel[$pb->kondisi_barang] ?? ($pb->kondisi_barang ?? '-');
    $kondisiBadgeClass = $kondisiBadge[$pb->kondisi_barang] ?? '';
    $waktuServahkanFmt = $pb->waktu_diserahkan
        ? \Carbon\Carbon::parse($pb->waktu_diserahkan)->locale('id')->translatedFormat('l, d F Y, H:i')
        : '-';
    $waktuKembaliFmt = $pb->waktu_diterima_kembali
        ? \Carbon\Carbon::parse($pb->waktu_diterima_kembali)->locale('id')->translatedFormat('l, d F Y, H:i')
        : '-';
    $fotoServahUrl = $pb->foto_serah ? Storage::url($pb->foto_serah) : null;
    $fotoKembaliUrl = $pb->foto_kembali ? Storage::url($pb->foto_kembali) : null;
@endphp

<div class="detail-page-wrap">
<div class="card detail-card">
    <div class="card-header detail-card-header">
        <div class="detail-header-info">
            <span class="card-title">{{ $pb->barang->nama ?? '-' }}</span>
            @if($pb->barang->kode ?? false)
                <span class="kode-chip">{{ $pb->barang->kode }}</span>
            @endif
        </div>
        @if($pb->kondisi_barang)
            <span class="badge {{ $kondisiBadgeClass }}">{{ $kondisiLbl }}</span>
        @endif
    </div>

    <div class="detail-grid">
        <div class="detail-field">
            <label>Peminjam</label>
            <div class="value">{{ $pb->user->nama ?? '-' }}</div>
        </div>
        <div class="detail-field">
            <label>Ormawa</label>
            <div class="value">{{ $pb->nama_ormawa ?? $pb->user?->organisasi ?? '-' }}</div>
        </div>
        <div class="detail-field">
            <label>Jumlah</label>
            <div class="value">{{ $pb->jumlah }} {{ $pb->barang->satuan ?? '' }}</div>
        </div>
        <div class="detail-field">
            <label>Keperluan</label>
            <div class="value">{{ $pb->keperluan ?? '-' }}</div>
        </div>
    </div>

    <div class="timeline">
        <div class="timeline-item">
            <div class="timeline-dot serah"></div>
            <div>
                <div class="timeline-label">Diserahkan</div>
                <div class="timeline-value">{{ $waktuServahkanFmt }}</div>
            </div>
        </div>
        <div class="timeline-line"></div>
        <div class="timeline-item">
            <div class="timeline-dot kembali"></div>
            <div>
                <div class="timeline-label">Diterima Kembali</div>
                <div class="timeline-value">{{ $waktuKembaliFmt }}</div>
            </div>
        </div>
    </div>

    @if($pb->catatan_kondisi)
        <div class="note-box">
            <label>Catatan Kondisi</label>
            <p>{{ $pb->catatan_kondisi }}</p>
        </div>
    @endif

    <div class="section-divider">
        <span>Dokumentasi Foto</span>
    </div>

    <div class="photo-grid">
        <div class="photo-block">
            <div class="photo-block-label">Foto Serah</div>
            @if($fotoServahUrl)
                <div class="photo-frame" onclick="openLightbox('{{ $fotoServahUrl }}')">
                    <img src="{{ $fotoServahUrl }}" alt="Foto Serah">
                    <div class="photo-zoom-hint">Perbesar</div>
                </div>
            @else
                <div class="photo-empty">Tidak ada foto</div>
            @endif
        </div>
        <div class="photo-block">
            <div class="photo-block-label">Foto Kembali</div>
            @if($fotoKembaliUrl)
                <div class="photo-frame" onclick="openLightbox('{{ $fotoKembaliUrl }}')">
                    <img src="{{ $fotoKembaliUrl }}" alt="Foto Kembali">
                    <div class="photo-zoom-hint">Perbesar</div>
                </div>
            @else
                <div class="photo-empty">Tidak ada foto</div>
            @endif
        </div>
    </div>
</div>

<a href="{{ route('pic.serah-terima') }}" class="btn-back btn-back-bottom">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Kembali ke Riwayat
</a>

</div>

<div id="lightbox" class="lightbox-overlay" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox(event)">&times;</button>
    <img id="lightboxImg" src="">
</div>

<style>
.detail-page-wrap { max-width: 780px; }

.btn-back {
    display: inline-flex; align-items: center; gap: 8px;
    background: #7c3aed; color: #ffffff;
    font-size: 13.5px; font-weight: 600;
    text-decoration: none; padding: 10px 18px;
    border-radius: 8px; margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(124, 58, 237, 0.25);
    transition: background 0.15s ease, transform 0.1s ease;
}
.btn-back:hover { background: #6d28d9; }
.btn-back:active { transform: translateY(1px); }
.btn-back-bottom { margin: 20px 0 0; }

.detail-card { overflow: hidden; }

.detail-card-header {
    padding: 22px 24px 18px;
    display: flex; justify-content: space-between; align-items: flex-start;
    flex-wrap: wrap; gap: 10px;
    background: linear-gradient(135deg, #faf5ff 0%, #ffffff 60%);
    border-bottom: 1px solid #f1f5f9;
}
.detail-header-info { display: flex; flex-direction: column; gap: 6px; }
.detail-card-header .card-title { font-size: 18px; }
.kode-chip {
    display: inline-block; width: fit-content;
    font-size: 11px; font-weight: 700; letter-spacing: 0.4px;
    color: #7c3aed; background: #f3e8ff;
    padding: 2px 8px; border-radius: 999px;
}
.badge { font-size: 13px; padding: 6px 14px; border-radius: 999px; font-weight: 600; }

.detail-grid {
    display: grid; grid-template-columns: 1fr; gap: 18px;
    padding: 22px 24px;
}
.detail-field label {
    display: block; font-size: 11px; font-weight: 600; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;
}
.detail-field .value { font-size: 15px; font-weight: 600; color: #111827; }

.timeline {
    display: flex; flex-direction: column; gap: 0;
    padding: 4px 24px 22px;
}
.timeline-item { display: flex; align-items: center; gap: 12px; }
.timeline-dot {
    width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0;
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.08);
}
.timeline-dot.serah { background: #7c3aed; }
.timeline-dot.kembali { background: #16a34a; box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.08); }
.timeline-line {
    width: 2px; height: 22px;
    background: linear-gradient(180deg, #7c3aed, #16a34a);
    opacity: 0.3; margin: 2px 0 2px 5px;
}
.timeline-label { font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.timeline-value { font-size: 14px; font-weight: 600; color: #111827; }

.note-box {
    margin: 0 24px 22px; padding: 14px 16px; background: #f8fafc;
    border-left: 3px solid #7c3aed; border-radius: 8px;
}
.note-box label {
    display: block; font-size: 11px; font-weight: 600; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;
}
.note-box p { font-size: 13.5px; color: #374151; margin: 0; line-height: 1.5; }

.section-divider {
    padding: 16px 24px 12px; border-top: 1px solid #f1f5f9;
    font-size: 13px; font-weight: 700; color: #111827;
}

.photo-grid {
    display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 4px 24px 24px;
}
.photo-block-label {
    font-size: 12px; font-weight: 600; color: var(--text-muted);
    margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;
}
.photo-block { text-align: center; }
.photo-frame {
    position: relative;
    display: inline-flex; align-items: center; justify-content: center;
    height: 190px; max-width: 100%; border-radius: 12px;
    border: 1px solid #e5e7eb; background: #f8fafc; cursor: zoom-in;
    overflow: hidden; transition: opacity 0.15s, box-shadow 0.15s;
}
.photo-frame:hover { opacity: 0.95; box-shadow: 0 6px 18px rgba(0,0,0,.08); }
.photo-frame img {
    height: 100%; width: auto; max-width: 100%; display: block;
}
.photo-zoom-hint {
    position: absolute; bottom: 8px; right: 8px;
    background: rgba(17, 24, 39, 0.65); color: #fff;
    font-size: 10.5px; font-weight: 600; padding: 3px 8px; border-radius: 999px;
    opacity: 0; transition: opacity 0.15s;
}
.photo-frame:hover .photo-zoom-hint { opacity: 1; }
.photo-empty {
    width: 100%; height: 190px; display: flex; align-items: center; justify-content: center;
    background: #f8fafc; border: 1px dashed #d1d5db; border-radius: 12px;
    color: var(--text-muted); font-size: 13px;
}

.lightbox-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,.85);
    display: none; align-items: center; justify-content: center;
    z-index: 10000; cursor: zoom-out; padding: 40px;
}
.lightbox-overlay.show { display: flex; }
.lightbox-overlay img {
    max-width: 100%; max-height: 100%; object-fit: contain;
    border-radius: 8px; box-shadow: 0 20px 60px rgba(0,0,0,.4);
}
.lightbox-close {
    position: absolute; top: 20px; right: 28px; background: none; border: none;
    color: white; font-size: 36px; line-height: 1; cursor: pointer; font-weight: 300;
}

@media (max-width: 768px) {
    .detail-grid, .photo-grid { grid-template-columns: 1fr; }
}
</style>

<script>
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('show');
}
function closeLightbox(e) {
    if (e) e.stopPropagation();
    document.getElementById('lightbox').classList.remove('show');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>

@endsection