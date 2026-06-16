@extends('layouts.anggota')

@section('title', 'Lapor Insiden')
@section('subtitle', 'Laporkan kejadian atau insiden yang terjadi di fasilitas')

@section('topbar-action')
    <a href="{{ route('anggota.riwayat-insiden') }}" class="btn btn-outline">
        Riwayat Laporan
    </a>
@endsection

@section('content')

        <style>
        .insiden-grid{
        display:grid;
        grid-template-columns:minmax(0,2fr) 400px;
        gap:20px;
        align-items:start;
    }

        @media (max-width: 992px){
        .insiden-grid{
        grid-template-columns:1fr;
    }
    }
    </style>
    <div class="insiden-grid">

    <div class="card">
        <div class="card-header">
            <span class="card-title">Form Laporan Insiden</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('anggota.lapor-insiden.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Judul Insiden <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="judul" class="form-control"
                           value="{{ old('judul') }}"
                           placeholder="Contoh: Kursi rusak di Ruang Rapat A" required>
                    @error('judul') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi Kejadian <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="lokasi" class="form-control"
                           value="{{ old('lokasi') }}"
                           placeholder="Contoh: Gedung A, Lantai 2 – Ruang Rapat A" required>
                    @error('lokasi') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Lengkap <span style="color:var(--danger)">*</span></label>
                    <textarea name="deskripsi" class="form-control" rows="5"
                              placeholder="Jelaskan insiden secara detail: apa yang terjadi, kapan, dampaknya, dll." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Bukti (Opsional)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*"
                           id="fotoInput" onchange="previewFoto(this)">
                    @error('foto') <div class="form-error">{{ $message }}</div> @enderror
                    <div class="form-hint">Format JPG/PNG. Maks 2 MB.</div>
                    <div id="fotoPreviewWrap" style="display:none;margin-top:10px;">
                        <img id="fotoPreview" src="" alt="Preview"
                        
     <img id="fotoPreview" src="" alt="Preview"
        style="
        width:100%;
        max-width:500px;
        height:300px;
        border-radius:12px;
        border:1px solid var(--border);
        object-fit:cover;
        ">
            </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:4px;">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Laporan
                    </button>
                    <a href="{{ route('anggota.riwayat-insiden') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Panduan --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Panduan Pelaporan</span></div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach([
                    ['icon'=>'📝','title'=>'Judul Jelas','desc'=>'Sebutkan jenis insiden dan lokasi secara singkat.'],
                    ['icon'=>'📍','title'=>'Lokasi Spesifik','desc'=>'Cantumkan gedung, lantai, dan nama ruangan.'],
                    ['icon'=>'🖊️','title'=>'Deskripsi Detail','desc'=>'Jelaskan apa yang terjadi, kapan, dan dampaknya.'],
                    ['icon'=>'📷','title'=>'Foto Pendukung','desc'=>'Lampirkan foto jika memungkinkan untuk mempercepat penanganan.'],
                ] as $tip)
                <div style="display:flex;gap:10px;align-items:flex-start;">
                    <span style="font-size:20px;flex-shrink:0;margin-top:2px;">{{ $tip['icon'] }}</span>
                    <div>
                        <div style="font-size:13px;font-weight:600;margin-bottom:2px;">{{ $tip['title'] }}</div>
                        <div style="font-size:12.5px;color:var(--text-muted);">{{ $tip['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <hr style="border:none;border-top:1px solid var(--border);margin:16px 0;">
            <div style="font-size:12.5px;color:var(--text-muted);">
                Laporan akan ditinjau dan ditindaklanjuti oleh PIC. Kamu bisa memantau status di
                <a href="{{ route('anggota.riwayat-insiden') }}" style="color:var(--accent);font-weight:600;">Riwayat Laporan</a>.
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewFoto(input) {
        const wrap = document.getElementById('fotoPreviewWrap');
        const img  = document.getElementById('fotoPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; wrap.style.display = 'block'; };
            reader.readAsDataURL(input.files[0]);
        } else {
            wrap.style.display = 'none';
        }
    }
</script>
@endpush
@endsection
