@extends('layouts.app')
@section('page-title', 'Laporan Stok')

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Laporan Stok Barang</h2>
        <p>Status persediaan barang terkini</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Filter Status Barang</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $statusFilter=='aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak aktif" {{ $statusFilter=='tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('laporan.stok') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
            <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#395e51;"><i class="bi bi-box-seam"></i></div>
            <div class="stat-info"><div class="label">Total Jenis Barang</div><div class="value">{{ $totalBarang }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-x-circle"></i></div>
            <div class="stat-info"><div class="label">Stok Habis</div><div class="value">{{ $stokHabis }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3;color:#d97706;"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-info"><div class="label">Stok Minimal</div><div class="value">{{ $stokMinimal }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-info">
                <div class="label">Nilai Inventori</div>
                <div class="value" style="font-size:17px;">Rp {{ number_format($nilaiInventori, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Nilai Stok</th>
                <th>Status Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $i => $b)
            <tr>
                <td class="text-muted">{{ $i + 1 }}</td>
                <td><code>{{ $b->kode }}</code></td>
                <td class="fw-semibold">{{ $b->nama }}</td>
                <td class="text-muted">{{ $b->kategori ?: '—' }}</td>
                <td class="text-muted">{{ $b->supplier->nama ?? '—' }}</td>
                <td class="fw-semibold">{{ number_format($b->stok) }}</td>
                <td>Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                <td class="fw-bold">Rp {{ number_format($b->stok * $b->harga_beli, 0, ',', '.') }}</td>
                <td>
                    @if($b->stok == 0)
                        <span class="badge badge-danger">Habis</span>
                    @elseif($b->stok <= 10)
                        <span class="badge badge-warning">Menipis</span>
                    @else
                        <span class="badge badge-success">Tersedia</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                    Tidak ada data barang.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
