@extends('layouts.anggota')

@section('title', 'Detail Laporan Insiden')
@section('subtitle', $insiden->judul)

@section('topbar-action')
    <a href="{{ route('anggota.riwayat-insiden') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

        <div style="display:grid;grid-template-columns:1fr 450px;gap:20px;align-items:start;">
        <div class="card">
        <div class="card-header">
            <span class="card-title">{{ $insiden->judul }}</span>
            @php
                [$cls, $lbl] = match($insiden->status) {
                    'dilaporkan'      => ['badge-orange', 'Dilaporkan'],
                    'ditindaklanjuti' => ['badge-blue',   'Diproses PIC'],
                    'selesai'         => ['badge-green',  'Selesai'],
                    default           => ['badge-gray',   ucfirst($insiden->status)],
                };
            @endphp
            <span class="badge {{ $cls }}">{{ $lbl }}</span>
        </div>

@if($insiden->foto)
<div style="padding:20px 20px 0;text-align:center;">
    <img src="{{ asset('storage/'.$insiden->foto) }}"
         style="max-width:100%;max-height:340px;height:auto;border-radius:10px;border:1px solid var(--border);"
         alt="Foto insiden">
    </div>
@endif

        <div class="detail-row">
            <div class="detail-label">Judul</div>
            <div class="detail-value" style="font-weight:600;">{{ $insiden->judul }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Lokasi</div>
            <div class="detail-value">{{ $insiden->lokasi }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Tanggal Lapor</div>
            <div class="detail-value">{{ $insiden->created_at->format('d M Y, H:i') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Deskripsi</div>
            <div class="detail-value" style="white-space:pre-line;">{{ $insiden->deskripsi }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Status</div>
            <div class="detail-value"><span class="badge {{ $cls }}">{{ $lbl }}</span></div>
        </div>

        @if($insiden->tindak_lanjut)
        <div class="detail-row" style="background:#f0fdf4;">
            <div class="detail-label" style="background:#dcfce7;color:#15803d;">Tindak Lanjut</div>
            <div class="detail-value" style="white-space:pre-line;">{{ $insiden->tindak_lanjut }}</div>
        </div>
        @if($insiden->waktu_ditindak)
        <div class="detail-row" style="background:#f0fdf4;">
            <div class="detail-label" style="background:#dcfce7;color:#15803d;">Ditindak Pada</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($insiden->waktu_ditindak)->format('d M Y, H:i') }}</div>
        </div>
        @endif
        @endif
    </div>

    {{-- Card kanan: status laporan --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Status Laporan</span></div>
        <div class="card-body">
            @php
                $steps = [
                    ['label' => 'Dilaporkan',  'done' => true,                                                       'icon' => '📝'],
                    ['label' => 'Diproses PIC','done' => in_array($insiden->status, ['ditindaklanjuti','selesai']),   'icon' => '🔍'],
                    ['label' => 'Selesai',     'done' => $insiden->status === 'selesai',                             'icon' => '✅'],
                ];
            @endphp
            <div style="display:flex;flex-direction:column;">
                @foreach($steps as $i => $step)
                <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 0;
                            {{ !$loop->last ? 'border-bottom:1px dashed var(--border);' : '' }}">
                    <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;
                                display:flex;align-items:center;justify-content:center;font-size:16px;
                                {{ $step['done'] ? 'background:#dcfce7;' : 'background:#f3f4f6;opacity:.5;' }}">
                        {{ $step['icon'] }}
                    </div>
                    <div style="padding-top:6px;">
                        <div style="font-size:13.5px;font-weight:{{ $step['done'] ? '600' : '500' }};
                                    color:{{ $step['done'] ? 'var(--text)' : 'var(--text-muted)' }};">
                            {{ $step['label'] }}
                        </div>
                        @if($step['done'] && $i === 0)
                            <div style="font-size:11.5px;color:var(--text-muted);">
                                {{ $insiden->created_at->format('d M Y, H:i') }}
                            </div>
                        @endif
                        @if($step['done'] && $i === 1 && $insiden->waktu_ditindak)
                            <div style="font-size:11.5px;color:var(--text-muted);">
                                {{ \Carbon\Carbon::parse($insiden->waktu_ditindak)->format('d M Y, H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if(!$insiden->tindak_lanjut)
            <div class="alert alert-info" style="margin-top:16px;margin-bottom:0;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:17px;height:17px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Laporan sedang dalam antrian tinjau oleh PIC.
            </div>
            @endif
        </div>
    </div>

</div>

@endsection