@extends('layouts.app')

@section('page-title', 'Data Barang')

@section('content')
<div class="content-header">
    <div class="content-header-title">
        <h2>Manajemen Data Barang</h2>
        <p>Kelola inventori produk Anda</p>
    </div>
    <a href="{{ route('barangs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Tambah Barang
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Cari nama barang..." id="searchInput">
            </div>
            <div class="col-md-3">
                <select class="form-control" id="supplierFilter">
                    <option value="">Semua Supplier</option>
                    @foreach (App\Models\Supplier::all() as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>KODE</th>
                <th>NAMA BARANG</th>
                <th>SUPPLIER</th>
                <th>KATEGORI</th>
                <th>HARGA BELI</th>
                <th>HARGA JUAL</th>
                <th>STOK</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="barangTableBody">
            @forelse ($barangs as $barang)
                <tr class="barang-row" data-supplier="{{ $barang->supplier_id }}" data-status="{{ $barang->status }}">
                    <td>{{ $barang->kode }}</td>
                    <td>
                        <strong>{{ $barang->nama }}</strong><br>
                        <small class="text-muted">{{ $barang->deskripsi ? substr($barang->deskripsi, 0, 30) . '...' : '-' }}</small>
                    </td>
                    <td>{{ $barang->supplier->nama ?? '-' }}</td>
                    <td>{{ $barang->kategori ?? '-' }}</td>
                    <td>Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $barang->stok > 10 ? 'badge-success' : ($barang->stok > 0 ? 'badge-warning' : 'badge-danger') }}">
                            {{ $barang->stok }} unit
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $barang->status == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($barang->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('barangs.edit', $barang) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('barangs.destroy', $barang) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="bi bi-inbox"></i> Belum ada data barang
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    // Real-time search and filter
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const supplierId = document.getElementById('supplierFilter').value;
        const status = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.barang-row');
        
        rows.forEach(row => {
            let show = true;
            const text = row.textContent.toLowerCase();
            const rowSupplier = row.getAttribute('data-supplier');
            const rowStatus = row.getAttribute('data-status');
            
            if (searchTerm && !text.includes(searchTerm)) show = false;
            if (supplierId && rowSupplier !== supplierId) show = false;
            if (status && rowStatus !== status) show = false;
            
            row.style.display = show ? '' : 'none';
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('supplierFilter').addEventListener('change', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
</script>
@endpush
@endsection
