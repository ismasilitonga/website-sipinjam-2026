<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1e293b; }
        h2 { text-align: center; margin-bottom: 4px; font-size: 16px; }
        p.sub { text-align: center; color: #64748b; margin-bottom: 16px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1e1b4b; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        tr:nth-child(even) td { background: #f8f7ff; }
    </style>
</head>
<body>
    <h2>Laporan Insiden</h2>
    <p class="sub">Dicetak pada {{ now()->format('d M Y, H:i') }} · SiPinjam</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelapor</th>
                <th>Judul</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tindak Lanjut</th>
                <th>Tanggal Lapor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($insidens as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->user->nama ?? '-' }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->lokasi }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>{{ $item->tindak_lanjut ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="text-align: right; font-size: 10px; color: #555; margin-top: 8px;">
        Total: {{ count($insidens) }} data insiden
    </div>
</body>
</html>