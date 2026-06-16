@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('subtitle', 'Manajemen akun pengguna aktif')

@section('topbar-action')
    <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pengguna
    </a>
@endsection

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Daftar Pengguna Aktif</span>
        <span class="badge badge-gray">{{ $users->total() }} pengguna</span>
        <div style="position:relative; margin-left:auto;">
            <input
                type="text"
                id="searchInput"
                placeholder="Cari nama / NIM / email / role..."
                style="
                    padding:8px 14px 8px 36px;
                    border:1px solid #ababae;
                    border-radius:8px;
                    font-size:13px;
                    width:260px;
                    outline:none;
                    background:white;
                ">
            <i class="fa-solid fa-magnifying-glass"
               style="
                    position:absolute;
                    left:11px;
                    top:50%;
                    transform:translateY(-50%);
                    color:#94a3b8;
                    font-size:13px;
               ">
            </i>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Email</th>
                    <th>Organisasi</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($users as $user)
                <tr>
                    <td style="color:var(--text-muted);font-size:13px;">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:13px;font-weight:600;color:#1d4ed8;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:500;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:13px;font-family:monospace;">{{ $user->nim }}</td>
                    <td style="font-size:13px;">{{ $user->email }}</td>
                    <td style="font-size:13px;">{{ $user->organisasi ?? '-' }}</td>
                    <td>
                        @php
                            $roleClass = match($user->role) {
                                'admin'  => 'badge-red',
                                'ketua'  => 'badge-blue',
                                'pic'    => 'badge-purple',
                                'pamdal' => 'badge-orange',
                                default  => 'badge-gray',
                            };
                        @endphp
                        <span class="badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.pengguna.show', $user->id) }}"
                               class="btn btn-outline btn-sm">Detail</a>
                            <a href="{{ route('admin.pengguna.edit', $user->id) }}"
                               class="btn btn-outline btn-sm">Edit</a>
                            {{-- Ganti form+confirm dengan tombol trigger modal --}}
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="bukaModa('{{ $user->id }}', '{{ addslashes($user->name) }}')">
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p>Belum ada pengguna terdaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="paginationWrap" class="pagination-wrap">
        @if($users->hasPages())
            {{ $users->links('layouts.pagination') }}
        @endif
    </div>
</div>

<div id="modalHapus" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
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
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Hapus Pengguna?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan menghapus akun <strong id="modalNama"></strong>.
                Tindakan ini <strong>permanen</strong> dan tidak dapat dibatalkan.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModal()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="konfirmasiHapus()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#dc2626;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<form id="formHapus" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
const baseUrl = "{{ url('admin/pengguna') }}";
const csrfToken = "{{ csrf_token() }}";

function bukaModa(id, nama) {
    document.getElementById('modalNama').textContent = nama;
    document.getElementById('formHapus').action = `${baseUrl}/${id}`;
    document.getElementById('modalHapus').style.display = 'flex';
}

function tutupModal() {
    document.getElementById('modalHapus').style.display = 'none';
}

function konfirmasiHapus() {
    document.getElementById('formHapus').submit();
}

document.getElementById('modalHapus').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput    = document.getElementById('searchInput');
    const tableBody      = document.getElementById('tableBody');
    const paginationWrap = document.getElementById('paginationWrap');
    const url            = "{{ route('admin.pengguna.index') }}";
    let timeout = null;

    const roleClass = {
        'admin'  : 'badge-red',
        'ketua'  : 'badge-blue',
        'pic'    : 'badge-purple',
        'pamdal' : 'badge-orange',
    };

    searchInput.addEventListener('keyup', function () {
        clearTimeout(timeout);
        const keyword = this.value;

        if (keyword === '') {
            paginationWrap.style.display = '';
            location.reload();
            return;
        }

        paginationWrap.style.display = 'none';

        timeout = setTimeout(() => {
            fetch(`${url}?search=${encodeURIComponent(keyword)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <p>Tidak ada pengguna ditemukan.</p>
                                </div>
                            </td>
                        </tr>`;
                    return;
                }

                data.forEach((u, i) => {
                    const badge   = roleClass[u.role] ?? 'badge-gray';
                    const initial = u.name.charAt(0).toUpperCase();
                    const namaEsc = u.name.replace(/'/g, "\\'");
                    tableBody.innerHTML += `
                        <tr>
                            <td style="color:var(--text-muted);font-size:13px;">${i + 1}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;
                                                display:flex;align-items:center;justify-content:center;
                                                font-size:13px;font-weight:600;color:#1d4ed8;flex-shrink:0;">
                                        ${initial}
                                    </div>
                                    <span style="font-weight:500;">${u.name}</span>
                                </div>
                            </td>
                            <td style="font-size:13px;font-family:monospace;">${u.nim}</td>
                            <td style="font-size:13px;">${u.email}</td>
                            <td style="font-size:13px;">${u.organisasi}</td>
                            <td><span class="badge ${badge}">${u.role.charAt(0).toUpperCase() + u.role.slice(1)}</span></td>
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <a href="${baseUrl}/${u.id}" class="btn btn-outline btn-sm">Detail</a>
                                    <a href="${baseUrl}/${u.id}/edit" class="btn btn-outline btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="bukaModa('${u.id}', '${namaEsc}')">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>`;
                });
            });
        }, 300);
    });
});
</script>

@endsection