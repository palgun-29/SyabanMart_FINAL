@extends('layouts.app')
@section('page-title', 'Manajemen Stok')

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Manajemen Stok (Pembelian)</h2>
        <p>Daftar pembelian barang dari supplier</p>
    </div>
    <a href="{{ route('pembelian.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle"></i> Catat Pembelian
    </a>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>No. Transaksi</th>
                <th>Barang</th>
                <th>Supplier</th>
                <th>Jumlah</th>
                <th>Harga Beli</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $trx)
            <tr>
                <td class="text-muted">{{ $i + 1 }}</td>
                <td><span class="fw-semibold text-primary">{{ $trx->no_transaksi }}</span></td>
                <td>{{ $trx->barang->nama ?? '-' }}</td>
                <td>{{ $trx->supplier->nama ?? '-' }}</td>
                <td>{{ number_format($trx->jumlah) }} pcs</td>
                <td>Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}</td>
                <td class="fw-bold">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                <td><span class="badge badge-success">{{ ucfirst($trx->status) }}</span></td>
                <td class="text-muted" style="font-size:12px;">
                    {{ $trx->tanggal_transaksi ? $trx->tanggal_transaksi->format('d/m/Y H:i') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                    Belum ada data pembelian.
                    <a href="{{ route('pembelian.create') }}">Catat pembelian sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
