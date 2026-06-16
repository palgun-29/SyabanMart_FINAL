@extends('layouts.app')

@section('page-title', 'Data Supplier')

@section('content')
<div class="content-header">
    <div class="content-header-title">
        <h2>Manajemen Data Supplier</h2>
        <p>Kelola data pemasok barang</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Tambah Supplier
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" placeholder="Cari nama supplier..." id="searchInput">
            </div>
            <div class="col-md-4">
                <select class="form-control">
                    <option>Semua Status</option>
                    <option>Aktif</option>
                    <option>Tidak Aktif</option>
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
                <th>NAMA SUPPLIER</th>
                <th>ALAMAT</th>
                <th>TELEPON</th>
                <th>JENIS BARANG</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="supplierTableBody">
            @forelse ($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->kode }}</td>
                    <td>
                        <strong>{{ $supplier->nama }}</strong><br>
                        <small class="text-muted">{{ $supplier->email }}</small>
                    </td>
                    <td>{{ $supplier->alamat ?? '-' }}</td>
                    <td>{{ $supplier->telepon ?? '-' }}</td>
                    <td>{{ $supplier->jenis_barang ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $supplier->status == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-inbox"></i> Belum ada data supplier
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    // Real-time search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#supplierTableBody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection
