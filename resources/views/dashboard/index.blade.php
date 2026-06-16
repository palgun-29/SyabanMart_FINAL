@extends('layouts.app')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.stat-icon.blue   { background: #dcfce7; color: #395e51; }
.stat-icon.green  { background: #dcfce7; color: #16a34a; }
.stat-icon.amber  { background: #fef9c3; color: #d97706; }
.stat-icon.purple { background: #ede9fe; color: #7c3aed; }
.stat-icon.red    { background: #fee2e2; color: #dc2626; }
.stat-icon.orange { background: #ffedd5; color: #ea580c; }

.activity-item { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #f1f5f9; }
.activity-item:last-child { border-bottom:none; }
.activity-dot  { width:10px;height:10px;border-radius:50%;flex-shrink:0; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Dashboard</h2>
        <p>Selamat datang di SYA'BAN MART – {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
</div>

{{-- Stat Cards Row 1 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-building"></i></div>
            <div class="stat-info">
                <div class="label">Total Supplier</div>
                <div class="value">{{ $totalSuppliers }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-info">
                <div class="label">Supplier Aktif</div>
                <div class="value">{{ $activeSuppliers }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-box-seam"></i></div>
            <div class="stat-info">
                <div class="label">Total Barang</div>
                <div class="value">{{ $totalBarang }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-cart-check"></i></div>
            <div class="stat-info">
                <div class="label">Transaksi Bulan Ini</div>
                <div class="value">{{ $transaksibulanIni }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards Row 2 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-info">
                <div class="label">Omzet Bulan Ini</div>
                <div class="value" style="font-size:18px;">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-info">
                <div class="label">Stok Menipis</div>
                <div class="value">{{ $stokMinimal }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-info">
                <div class="label">Stok Habis</div>
                <div class="value">{{ $stokHabis }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Aktivitas & Quick Actions --}}
<div class="row g-3">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2 text-primary"></i>Aktivitas Transaksi Terbaru</span>
                <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($aktivitasTerbaru->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                        Belum ada transaksi
                    </div>
                @else
                    <div class="px-3 py-2">
                        @foreach($aktivitasTerbaru as $trx)
                            <div class="activity-item">
                                <div class="activity-dot" style="background:{{ $trx->tipe === 'penjualan' ? '#16a34a' : ($trx->tipe === 'pembelian' ? '#395e51' : '#f59e0b') }};"></div>
                                <div class="flex-1">
                                    <div style="font-size:13.5px;font-weight:600;">{{ $trx->no_transaksi }}</div>
                                    <div style="font-size:12px;color:#64748b;">{{ $trx->barang->nama ?? '-' }} &bull; {{ ucfirst($trx->tipe) }}</div>
                                </div>
                                <div class="text-end">
                                    <div style="font-size:13px;font-weight:600;">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
                                    <div style="font-size:11px;color:#94a3b8;">{{ $trx->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-lightning-fill me-2 text-warning"></i>Aksi Cepat</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('penjualan.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Tambah Penjualan
                </a>
                <a href="{{ route('pembelian.create') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <i class="bi bi-truck"></i> Catat Pembelian
                </a>
                <a href="{{ route('barangs.create') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam"></i> Tambah Barang
                </a>
                <a href="{{ route('suppliers.create') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="bi bi-building-add"></i> Tambah Supplier
                </a>
                <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan Penjualan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
