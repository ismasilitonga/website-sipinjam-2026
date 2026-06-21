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
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background: #f8f7ff; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Barang</h2>
    <p class="sub">Dicetak pada {{ now()->format('d M Y, H:i') }} · SiPinjam</p>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Peminjam</th><th>Barang</th>
                <th>Jumlah</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman_barangs as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->user->nama   ?? '-' }}</td>
                <td>{{ $item->barang->nama ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali_rencana)->format('d M Y') }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>