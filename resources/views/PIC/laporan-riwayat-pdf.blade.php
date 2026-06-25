<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1e293b; margin: 20px; }

        h2 { text-align: center; margin-bottom: 4px; font-size: 16px; }
        p.sub { text-align: center; color: #64748b; margin-bottom: 4px; font-size: 10px; }
        p.filter-info { text-align: center; color: #4338ca; font-size: 10px; margin-bottom: 16px; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #1e1b4b; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10.5px; }
        tr:nth-child(even) td { background: #f8f7ff; }

        .footer { margin-top: 14px; font-size: 10px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Riwayat Peminjaman Ruangan</h2>
    <p class="sub">Dicetak pada {{ now()->format('d M Y, H:i') }} · SiPinjam</p>
    <p class="filter-info">
        Periode: <strong>{{ $filterLabel }}</strong>
        &nbsp;·&nbsp;
        Ruangan: <strong>{{ $ruanganLabel }}</strong>
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 28px;">No</th>
                <th>Peminjam</th>
                <th>Ormawa</th>
                <th>Ruangan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Keperluan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        {{ $p->user->nama ?? '-' }}<br>
                        <span style="font-size: 9px; color: #6b7280;">{{ $p->user->nim ?? '' }}</span>
                    </td>
                    <td>{{ $p->nama_ormawa ?? '-' }}</td>
                    <td>{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
                    <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                    </td>
                    <td>{{ $p->keperluan ?? '-' }}</td>
                    <td>
                        @php
                            [$warna, $label] = match($p->status) {
                                'disetujui' => ['#065f46', 'Disetujui'],
                                'selesai'   => ['#374151', 'Selesai'],
                                default     => ['#374151', ucfirst($p->status)],
                            };
                        @endphp
                        <span style="font-size: 10px; font-weight: 700; color: {{ $warna }};">{{ $label }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9ca3af; padding: 20px;">
                        Tidak ada data untuk filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Total: {{ $data->count() }} data peminjaman</div>
</body>
</html>