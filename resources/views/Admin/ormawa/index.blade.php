@extends('layouts.admin')

@section('title', 'Kelola Ormawa')
@section('subtitle', 'Daftar organisasi mahasiswa terdaftar')

@section('topbar-action')
    <a href="{{ route('admin.ormawa.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Ormawa
    </a>
@endsection

@push('styles')
<style>
    .ormawa-table {
        table-layout: fixed;
        width: 100%;
    }
    .ormawa-table th,
    .ormawa-table td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: middle;
    }
    .ormawa-table td:nth-child(2) {
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
    }

    @media (max-width: 760px) {
        .ormawa-table thead { display: none; }

        .ormawa-table, .ormawa-table tbody, .ormawa-table tr, .ormawa-table td {
            display: block;
            width: 100% !important;
        }

        .ormawa-table tr {
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 12px;
            padding: 10px 12px;
            background: var(--white);
        }

        .ormawa-table td {
            border-bottom: none;
            padding: 6px 0;
            white-space: normal;       
            overflow: visible;
            text-overflow: clip;
        }

        .ormawa-table td[data-label]::before {
            content: attr(data-label);
            display: block;
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 2px;
        }

        .ormawa-table td:first-child { display: none; } 

        .ormawa-table .action-group,
        .ormawa-table td > div[style*="flex"] {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:16px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Daftar Ormawa</span>
        <span class="badge badge-gray">{{ $ormawas->count() }} ormawa</span>
    </div>

    <div class="table-wrap">
        <table class="ormawa-table">
            <colgroup>
                <col style="width:45px;">
                <col style="width:35%;">
                <col style="width:15%;">
                <col style="width:16%;">
                <col style="width:90px;">
                <col style="width:90px;">
                <col style="width:240px;">
            </colgroup>
                
                <thead>
                    <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 140px;">Nama Organisasi</th>
                    <th style="width: 90px;">Kontak</th>
                    <th style="width: 150px;">Deskripsi</th>
                    <th style="width: 120px;">Anggota</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 50px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ormawas as $o)
                <tr>
                    <td style="color:var(--text-muted);font-size:13px;">{{ $loop->iteration }}</td>

                    <td data-label="Nama Organisasi">
                        <div style="display:flex;align-items:center;gap:10px;overflow:hidden;">
                            <div style="width:36px;height:36px;border-radius:8px;background:#ede9fe;
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:14px;font-weight:700;color:#7c3aed;flex-shrink:0;">
                                {{ strtoupper(substr($o->singkatan, 0, 2)) }}
                            </div>
                            <div style="overflow:hidden;">
                                <div style="font-weight:500;font-size:15px;white-space:normal;word-break:break-word;line-height:1.3;">
                                    {{ $o->nama_organisasi ?? $o->singkatan }}
                                </div>
                                @if($o->nama_organisasi)
                                    <div style="font-size:11.5px;color:var(--text-muted);">{{ $o->singkatan }}</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td data-label="Kontak" style="font-size:13px;">{{ $o->kontak ?? '-' }}</td>

                    <td data-label="Deskripsi" style="font-size:13px;" title="{{ $o->deskripsi }}">
                        {{ $o->deskripsi ?? '-' }}
                    </td>

                    <td data-label="Anggota" style="font-size:13px;text-align:center;">
                        {{ $o->jumlah_anggota ?? 0 }}
                    </td>

                    <td data-label="Status">
                        @if($o->status === 'aktif')
                            <span class="badge badge-green">Aktif</span>
                        @else
                            <span class="badge badge-gray">Nonaktif</span>
                        @endif
                    </td>

                   <td data-label="Aksi">
                    <div style="display:flex;gap:6px;align-items:center;">
                    <a href="{{ route('admin.ormawa.show', $o->id) }}"
                        class="btn btn-outline btn-sm">Lihat Anggota</a>
                    <a href="{{ route('admin.ormawa.edit', $o->id) }}"
                        class="btn btn-outline btn-sm">Edit</a>
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="bukaModalOrmawa('{{ $o->id }}', '{{ addslashes($o->singkatan) }}')">
                     Hapus
                    </button>
                </div>
                </td>
                </tr>
                @empty
                <tr>
                <td colspan="7">
                <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p>Belum ada ormawa terdaftar.</p>
                </div>
              </td>
            </tr>
                @endforelse
             </tbody>
           </table>
          </div>
        </div>
            <div id="modalHapusOrmawa" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
            z-index:9999;align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:12px;padding:32px 28px;width:100%;max-width:400px;
            box-shadow:0 10px 40px rgba(0,0,0,0.18);margin:16px;">

            <div style="width:52px;height:52px;border-radius:50%;background:#fee2e2;
            display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="26" height="26" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5"/>
            </svg>
        </div>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Hapus Ormawa?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan menghapus ormawa <strong id="modalNamaOrmawa"></strong>.
                Anggota <strong>tidak ikut dihapus</strong>, hanya relasi yang dilepas.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModalOrmawa()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('formHapusOrmawa').submit()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#dc2626;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Hapus
            </button>
           </div>
         </div>
        </div>

    <form id="formHapusOrmawa" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    </form>

<script>
const baseUrlOrmawa = "{{ url('admin/ormawa') }}";

function bukaModalOrmawa(id, nama) {
    document.getElementById('modalNamaOrmawa').textContent = nama;
    document.getElementById('formHapusOrmawa').action = `${baseUrlOrmawa}/${id}`;
    document.getElementById('modalHapusOrmawa').style.display = 'flex';
}

function tutupModalOrmawa() {
    document.getElementById('modalHapusOrmawa').style.display = 'none';
}

document.getElementById('modalHapusOrmawa').addEventListener('click', function(e) {
    if (e.target === this) tutupModalOrmawa();
});
</script>

@endsection