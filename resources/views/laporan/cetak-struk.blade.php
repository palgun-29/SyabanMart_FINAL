<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk – {{ $no_transaksi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            background: #fff;
            padding: 20px;
            max-width: 320px;
            margin: 0 auto;
        }
        .store-name { font-size: 20px; font-weight: bold; text-align: center; }
        .store-sub  { text-align: center; font-size: 11px; color: #555; margin-bottom: 4px; }
        .divider    { border-top: 1px dashed #999; margin: 8px 0; }
        .row        { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .label      { color: #555; }
        .item-row   { margin-bottom: 4px; }
        .total-row  { font-size: 16px; font-weight: bold; border-top: 2px solid #000; padding-top: 6px; margin-top: 4px; }
        .footer     { text-align: center; font-size: 11px; color: #777; margin-top: 10px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="store-name">★ SYA'BAN MART ★</div>
    <div class="store-sub">Sistem Manajemen Toko</div>
    <div class="divider"></div>

    <div class="row"><span class="label">No. Struk</span><span>{{ $no_transaksi }}</span></div>
    <div class="row"><span class="label">Tanggal</span><span>{{ $transaksis->first()->tanggal_transaksi ? $transaksis->first()->tanggal_transaksi->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span class="label">Kasir</span><span>Admin</span></div>

    <div class="divider"></div>

    @foreach($transaksis as $item)
        <div class="item-row">
            <div>{{ $item->barang->nama ?? '-' }} ({{ $item->jumlah }}x)</div>
            <div>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
        </div>
    @endforeach

    <div class="divider"></div>

    <div class="row total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</span>
    </div>

    @php
        $first = $transaksis->first();
        $paid = $first->jumlah_dibayar ?? 0;
        $change = $first->kembalian ?? 0;
        $method = $first->payment_method ?? 'Tunai';
    @endphp

    <div style="margin-top:6px;">
        <div class="row"><span class="label">Metode</span><span>{{ ucfirst($method) }}</span></div>
        <div class="row"><span class="label">Tunai</span><span>Rp {{ number_format($paid,0,',','.') }}</span></div>
        <div class="row"><span class="label">Kembalian</span><span>Rp {{ number_format($change,0,',','.') }}</span></div>
    </div>

    @if($transaksis->first()->catatan)
    <div class="divider"></div>
    <div style="font-size:11px;color:#555;">Catatan: {{ $transaksis->first()->catatan }}</div>
    @endif

    <div class="divider"></div>
    <div class="footer">
        Terima kasih atas pembelian Anda!<br>
        Barang yang sudah dibeli tidak dapat dikembalikan.
    </div>

    <div class="no-print" style="margin-top:20px;text-align:center;">
        <button onclick="window.print()" style="padding:8px 20px;background:#395e51;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">
            🖨 Cetak Struk
        </button>
        <button onclick="closeReport()" style="padding:8px 20px;background:#f1f5f9;color:#334155;border:none;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px;">
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
            window.location.href = '{{ route('penjualan.index') }}';
        }
        // Auto print on load (opsional - dikomentari)
        // window.onload = () => window.print();
    </script>
</body>
</html>
