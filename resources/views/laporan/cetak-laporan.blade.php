<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan – Syaban Mart</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; background: #fff; padding: 30px; color: #1e293b; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #395e51; padding-bottom: 12px; }
        .header h1 { font-size: 22px; color: #395e51; }
        .header p  { font-size: 12px; color: #64748b; margin-top: 3px; }
        .meta { display: flex; gap: 30px; margin-bottom: 16px; font-size: 12px; }
        .meta span { color: #64748b; }
        .summary { display: flex; gap: 20px; margin-bottom: 20px; }
        .summary-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; flex: 1; }
        .summary-box .sum-label { font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: 700; }
        .summary-box .sum-value { font-size: 18px; font-weight: 700; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; font-size: 11.5px; }
        th { background: #395e51; color: #fff; padding: 9px 10px; text-align: left; }
        td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; }
        tr:nth-child(even) td { background: #f8fafc; }
        .total-row { border-top: 2px solid #395e51; }
        .total-row td { font-weight: 700; font-size: 13px; padding-top: 10px; }
        .footer { margin-top: 30px; text-align: center; font-size: 11px; color: #94a3b8; }
        .no-print { margin-top: 20px; text-align: center; }
        @media print {
            .no-print { display: none; }
            body { padding: 15px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SYA'BAN MART</h1>
        <p>Laporan Penjualan
            @if($dari || $sampai)
                ({{ $dari ? date('d/m/Y', strtotime($dari)) : '—' }} s/d {{ $sampai ? date('d/m/Y', strtotime($sampai)) : '—' }})
            @else
                (Semua Periode)
            @endif
        </p>
    </div>

    <div class="meta">
        <div><span>Dicetak pada: </span>{{ now()->format('d F Y, H:i') }}</div>
        <div><span>Oleh: </span>Admin</div>
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="sum-label">Total Transaksi</div>
            <div class="sum-value">{{ $transaksis->count() }}</div>
        </div>
        <div class="summary-box">
            <div class="sum-label">Total Omzet</div>
            <div class="sum-value">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Transaksi</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $trx)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $trx->no_transaksi }}</td>
                <td>{{ $trx->barang->nama ?? '-' }}</td>
                <td>{{ number_format($trx->jumlah) }}</td>
                <td>Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}</td>
                <td style="font-weight:600;">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                <td>{{ $trx->tanggal_transaksi ? $trx->tanggal_transaksi->format('d/m/Y') : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#94a3b8;padding:20px;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align:right;">TOTAL OMZET</td>
                <td>Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">Sayban Mart © {{ date('Y') }} – Sistem Manajemen Toko</div>

        <div class="no-print">
        <button onclick="window.print()" style="padding:9px 22px;background:#395e51;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">
            🖨 Cetak / Simpan PDF
        </button>
        <button onclick="closeReport()" style="padding:9px 22px;background:#f1f5f9;color:#334155;border:none;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px;">
            ✕ Tutup
        </button>
    </div>
    <script>
        function closeReport() {
            if (window.opener) {
                window.close();
                return;
            }
            if (window.history.length > 1) {
                window.history.back();
                return;
            }
            window.location.href = '{{ route('laporan.penjualan') }}';
        }
    </script>
</body>
</html>
