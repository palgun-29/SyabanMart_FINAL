@extends('layouts.app')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Laporan Penjualan</h2>
        <p>Ringkasan seluruh transaksi penjualan</p>
    </div>
    <a href="{{ route('laporan.cetak-laporan', request()->query()) }}" class="btn btn-outline-secondary" target="_blank">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </a>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('laporan.penjualan') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="{{ $dari }}">
            </div>
            <div class="col-md-4">
                <label class="form-label mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#395e51;"><i class="bi bi-receipt"></i></div>
            <div class="stat-info">
                <div class="label">Total Transaksi</div>
                <div class="value">{{ $transaksis->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-box"></i></div>
            <div class="stat-info">
                <div class="label">Total Barang Terjual</div>
                <div class="value">{{ number_format($totalItem) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3;color:#d97706;"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-info">
                <div class="label">Total Omzet</div>
                <div class="value" style="font-size:20px;">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>No. Transaksi</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $trx)
            <tr>
                <td class="text-muted">{{ $i + 1 }}</td>
                <td class="fw-semibold text-primary">{{ $trx->no_transaksi }}</td>
                <td>{{ $trx->barang->nama ?? '-' }}</td>
                <td>{{ number_format($trx->jumlah) }}</td>
                <td>Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}</td>
                <td class="fw-bold">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                <td class="text-muted" style="font-size:12px;">
                    {{ $trx->tanggal_transaksi ? $trx->tanggal_transaksi->format('d/m/Y') : '-' }}
                </td>
                <td>
                    <a href="{{ route('laporan.cetak-struk', $trx->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank" title="Cetak Struk">
                        <i class="bi bi-printer"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                    Tidak ada data untuk filter ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
