@php
    $layout = match(auth()->user()->role) {
        'ketua'   => 'layouts.ketua',
        'anggota' => 'layouts.anggota',
        'pamdal'  => 'layouts.pamdal',
        'pic'     => 'layouts.pic',
        'admin'   => 'layouts.admin',
    };
@endphp

@extends($layout)

@section('title', 'Riwayat Peminjaman Ruangan')
@section('subtitle', 'Rekap peminjaman yang telah selesai dan disetujui')

@section('topbar-action')
    <a href="@if(auth()->user()->role === 'admin')
              {{ route('admin.riwayat-peminjaman.export', request()->query()) }}
          @elseif(auth()->user()->role === 'ketua')
          @else
              {{ route('pic.riwayat.export', request()->query()) }}
          @endif"
       class="btn btn-outline" style="display: flex; align-items: center; gap: 6px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 15px; height: 15px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
        </svg>
        Unduh Laporan
    </a>
@endsection

@section('content')

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px;">
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0;
                border-radius: var(--border-radius-lg); padding: 16px 20px;
                display: flex; align-items: center; gap: 14px;">
        <div style="width: 42px; height: 42px; border-radius: 10px; background: #dcfce7;
                    display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size: 11px; color: #16a34a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Selesai</div>
            <div style="font-size: 26px; font-weight: 700; color: #15803d; line-height: 1.2;">{{ $totalSelesai }}</div>
        </div>
    </div>

    <div style="background: #eff6ff; border: 1px solid #bfdbfe;
                border-radius: var(--border-radius-lg); padding: 16px 20px;
                display: flex; align-items: center; gap: 14px;">
        <div style="width: 42px; height: 42px; border-radius: 10px; background: #dbeafe;
                    display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg fill="none" stroke="#2563eb" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <div style="font-size: 11px; color: #2563eb; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Disetujui</div>
            <div style="font-size: 26px; font-weight: 700; color: #1d4ed8; line-height: 1.2;">{{ $totalDisetujui }}</div>
        </div>
    </div>
</div>

    <div class="card">
    <div class="card-header" style="padding-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div>
        <span class="card-title">Riwayat Peminjaman</span>
        <span class="badge badge-gray" style="margin-left: 8px;">{{ $riwayat->total() }} data</span>
        </div>
    </div>

    <div style="padding: 0 20px 16px;">
        <form method="GET" action="{{ request()->url() }}" id="filter-form"
              style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; width: 100%;">

            <div style="display: flex; flex-direction: column; gap: 4px;">
                <label style="font-size: 11px; color: var(--color-text-secondary); font-weight: 500;">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    style="border: 1px solid var(--color-border-tertiary); border-radius: 8px;
                    padding: 7px 10px; font-size: 13px; background: var(--color-background-primary);
                    color: var(--color-text-primary);">
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <label style="font-size: 11px; color: var(--color-text-secondary); font-weight: 500;">Minggu</label>
                <input type="week" name="minggu" value="{{ request('minggu') }}"
                       style="border: 1px solid var(--color-border-tertiary); border-radius: 8px;
                              padding: 7px 10px; font-size: 13px; background: var(--color-background-primary);
                              color: var(--color-text-primary);">
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <label style="font-size: 11px; color: var(--color-text-secondary); font-weight: 500;">Bulan</label>
                <input type="month" name="bulan" value="{{ request('bulan') }}"
                       style="border: 1px solid var(--color-border-tertiary); border-radius: 8px;
                        padding: 7px 10px; font-size: 13px; background: var(--color-background-primary);
                        color: var(--color-text-primary);">
                    </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                <label style="font-size: 11px; color: var(--color-text-secondary); font-weight: 500;">Ruangan</label>

                <input type="hidden" name="ruangan_id" id="ruangan_id_input" value="{{ request('ruangan_id') }}">

                <div id="custom-dropdown" style="position: relative; min-width: 180px;">
                    <div id="dropdown-trigger"
                         style="border: 1px solid var(--color-border-tertiary); border-radius: 8px;
                                padding: 7px 32px 7px 10px; font-size: 13px;
                                background: var(--color-background-primary);
                                color: var(--color-text-primary);
                                cursor: pointer; user-select: none; position: relative;">
                        <span id="dropdown-label">
                            @if(request('ruangan_id') && $ruangans->firstWhere('id', request('ruangan_id')))
                                {{ $ruangans->firstWhere('id', request('ruangan_id'))->nama_ruangan }}
                            @else
                                Semua Ruangan
                            @endif
                        </span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="width: 14px; height: 14px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #64748b;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <div id="dropdown-panel"
                         style="display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0;
                                background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
                                box-shadow: 0 4px 16px rgba(0,0,0,.1); z-index: 999;
                                max-height: 185px; overflow-y: auto;">

                        <div class="dd-option" data-value=""
                             style="padding: 8px 12px; font-size: 13px; cursor: pointer;
                                    color: #1e293b; border-bottom: 1px solid #f3f4f6;">
                            Semua Ruangan
                        </div>

                    @foreach($ruangans as $r)
                        <div class="dd-option" data-value="{{ $r->id }}"
                        style="padding: 8px 12px; font-size: 13px; cursor: pointer;
                        color: #1e293b; border-bottom: 1px solid #f3f4f6;
                    {{ request('ruangan_id') == $r->id ? 'background: #ede9fe; color: #6d28d9; font-weight: 600;' : '' }}">
                    {{ $r->nama_ruangan }}
                    </div>
                @endforeach
                </div>
                </div>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 18px; font-size: 13px;">
                    Terapkan
                </button>
                @if(request()->hasAny(['tanggal', 'minggu', 'bulan', 'ruangan_id']))
                    <a href="{{ request()->url() }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 13px;">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    @if(request()->hasAny(['tanggal', 'minggu', 'bulan', 'ruangan_id']))
        <div style="padding: 0 20px 12px;">
            <div style="background: var(--color-background-info); border: 1px solid var(--color-border-info);
                        border-radius: 8px; padding: 8px 14px; font-size: 12.5px; color: var(--color-text-info);
                        display: flex; align-items: center; gap: 6px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px; flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Menampilkan hasil filter:
                @if(request('tanggal'))
                    <strong>Tanggal {{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}</strong>
                @endif
                @if(request('minggu'))
                    <strong>Minggu {{ request('minggu') }}</strong>
                @endif
                @if(request('bulan'))
                    <strong>Bulan {{ \Carbon\Carbon::parse(request('bulan') . '-01')->translatedFormat('F Y') }}</strong>
                @endif
                @if(request('ruangan_id') && $ruangans->firstWhere('id', request('ruangan_id')))
                    · <strong>{{ $ruangans->firstWhere('id', request('ruangan_id'))->nama_ruangan }}</strong>
                @endif
                — ditemukan <strong>{{ $riwayat->total() }} data</strong>
            </div>
        </div>
    @endif

    <div class="table-wrap" style="overflow-x: auto;">
        <table style="width: 100%;">
            <thead>
             <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 140px;">Peminjam</th>
                <th style="width: 90px;">Ormawa</th>
                <th style="width: 150px;">Ruangan</th>
                <th style="width: 120px;">Tanggal</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 70px;">Aksi</th>
            </tr>
        </thead>
            <tbody>
                @forelse($riwayat as $p)
                    <tr>
                        <td style="color: var(--text-muted); font-size: 12px;">
                            {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div style="font-weight: 500; font-size: 13px; white-space: nowrap;">{{ $p->user->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $p->user->nim ?? '' }}</div>
                        </td>
                        <td style="font-size: 12.5px;">{{ $p->nama_ormawa ?? '-' }}</td>
                        <td style="font-size: 13px; font-weight: 500; white-space: nowrap;">{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
                        <td style="font-size: 12px; white-space: nowrap;">
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->translatedFormat('d M Y') }}
                        </td>
                        <td>
                            @php
                                [$cls, $lbl] = match($p->status) {
                                    'menunggu_ketua' => ['badge-yellow', 'Menunggu Ketua'],
                                     'menunggu_pic'   => ['badge-purple', 'Menunggu PIC'],  
                                    'disetujui' => ['badge-green', 'Disetujui'],
                                    'selesai'   => ['badge-gray',  'Selesai'],
                                    default     => ['badge-gray',  ucfirst($p->status)],
                                };
                            @endphp
                            <span class="badge {{ $cls }}">{{ $lbl }}</span>
                        </td>
                        <td>
                        <a href="@if(auth()->user()->role === 'admin')
                 {{ route('admin.riwayat-peminjaman.detail', $p->id) }}
             @elseif(auth()->user()->role === 'ketua')
                 {{ route('ketua.riwayat-peminjaman.show', $p->id) }}
             @else
                 {{ route('pic.riwayat-peminjaman.detail', $p->id) }}
             @endif"
       class="btn btn-outline" style="font-size: 12px; padding: 4px 12px;">
        Detail
    </a>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>Tidak ada riwayat untuk filter ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($riwayat->hasPages())
        <div class="pagination-wrap">{{ $riwayat->links('layouts.pagination') }}</div>
    @endif
</div>

@push('scripts')
<script>
    const trigger = document.getElementById('dropdown-trigger');
    const panel   = document.getElementById('dropdown-panel');
    const label   = document.getElementById('dropdown-label');
    const input   = document.getElementById('ruangan_id_input');
    const options = document.querySelectorAll('.dd-option');

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });

    options.forEach(function (opt) {
        opt.addEventListener('mouseenter', function () {
            this.style.background = '#f5f3ff';
        });
        opt.addEventListener('mouseleave', function () {
            if (input.value != this.dataset.value) {
                this.style.background = this.dataset.value == '{{ request('ruangan_id') }}' ? '#ede9fe' : '';
            }
        });
        opt.addEventListener('click', function () {
            input.value  = this.dataset.value;
            label.textContent = this.textContent.trim();
            panel.style.display = 'none';

            options.forEach(o => o.style.background = '');
            this.style.background = '#ede9fe';
            this.style.color = '#6d28d9';
            this.style.fontWeight = '600';
        });
    });

    document.addEventListener('click', function () {
        panel.style.display = 'none';
    });
</script>
@endpush

@endsection