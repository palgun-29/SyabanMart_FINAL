@extends('layouts.app')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Detail Transaksi</h2>
        <p>{{ $no_transaksi }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.cetak-struk', $transaksis->first()->id) }}" class="btn btn-outline-secondary" target="_blank">
            <i class="bi bi-printer me-1"></i> Cetak Struk
        </a>
        <a href="{{ route('penjualan.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

@php
    $totalHarga = $transaksis->sum('total_harga');
    $totalJumlah = $transaksis->sum('jumlah');
    $firstItem = $transaksis->first();
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Informasi Transaksi</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted fw-semibold" style="width:200px">No. Transaksi</td><td>{{ $no_transaksi }}</td></tr>
                    <tr><td class="text-muted fw-semibold">Jumlah Item</td><td>{{ $transaksis->count() }} barang</td></tr>
                    <tr><td class="text-muted fw-semibold">Total Jumlah</td><td>{{ number_format($totalJumlah) }} pcs</td></tr>
                    <tr><td class="text-muted fw-semibold">Total Harga</td><td class="fs-5 fw-bold text-success">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td></tr>
                    <tr><td class="text-muted fw-semibold">Supplier</td><td>{{ $firstItem->supplier->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted fw-semibold">Catatan</td><td>{{ $firstItem->catatan ?: '—' }}</td></tr>
                    <tr>
                        <td class="text-muted fw-semibold">Status</td>
                        <td>
                            @if($firstItem->status === 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($firstItem->status === 'batal')
                                <span class="badge badge-danger">Batal</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted fw-semibold">Tanggal</td><td>{{ $firstItem->tanggal_transaksi ? $firstItem->tanggal_transaksi->format('d F Y, H:i') : '-' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Daftar Item ({{ $transaksis->count() }} item)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 30%;">Nama Barang</th>
                                <th style="width: 15%;">Kode</th>
                                <th style="width: 15%;">Jumlah</th>
                                <th style="width: 20%;">Harga Satuan</th>
                                <th style="width: 15%;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $idx => $item)
                                <tr>
                                    <td class="text-muted text-center"><small>{{ $idx + 1 }}</small></td>
                                    <td><strong>{{ $item->barang->nama ?? '-' }}</strong></td>
                                    <td><code>{{ $item->barang->kode ?? '-' }}</code></td>
                                    <td class="text-center">{{ number_format($item->jumlah) }} pcs</td>
                                    <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light fw-bold">
                                <td colspan="3" class="text-end">TOTAL:</td>
                                <td class="text-center">{{ number_format($totalJumlah) }} pcs</td>
                                <td colspan="1"></td>
                                <td class="text-end text-success fs-5">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Aksi</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('laporan.cetak-struk', $transaksis->first()->id) }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-printer me-1"></i> Cetak Struk
                </a>
                @if($firstItem->status !== 'batal')
                <form action="{{ route('penjualan.destroy', $firstItem->id) }}" method="POST"
                    onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Transaksi
                    </button>
                </form>
                @endif
                <a href="{{ route('penjualan.create') }}" class="btn btn-outline-success">
                    <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
