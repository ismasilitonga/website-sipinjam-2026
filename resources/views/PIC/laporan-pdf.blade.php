<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1e293b; margin: 20px; }

        h2 { text-align: center; margin-bottom: 4px; font-size: 16px; }
        p.sub { text-align: center; color: #64748b; margin-bottom: 16px; font-size: 10px; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #1e1b4b; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10.5px; }
        tr:nth-child(even) td { background: #f8f7ff; }

        .footer { margin-top: 14px; font-size: 9px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Ruangan</h2>
    <p class="sub">Dicetak pada {{ now()->format('d M Y, H:i') }} · SiPinjam</p>

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
            @forelse($peminjaman_ruangans as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        {{ $item->user->name ?? '-' }}<br>
                        <span style="font-size: 9px; color: #6b7280;">{{ $item->user->nim ?? '' }}</span>
                    </td>
                    <td>{{ $item->nama_ormawa ?? '-' }}</td>
                    <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                    <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('H:i') }}
                    </td>
                    <td>{{ $item->keperluan ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9ca3af; padding: 20px;">
                        Tidak ada data.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Total: {{ $peminjaman_ruangans->count() }} data peminjaman</div>
</body>
</html>