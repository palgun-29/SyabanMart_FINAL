@extends('layouts.app')
@section('page-title', 'Stock Opname')

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Stock Opname</h2>
        <p>Sesuaikan stok sistem dengan stok fisik di gudang</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" id="searchBarang" class="form-control" placeholder="🔍 Cari nama / kode barang…">
            </div>
        </div>
    </div>
</div>

<form action="{{ route('stock-opname.store') }}" method="POST">
    @csrf
    <div class="table-container mb-3">
        <table class="table" id="tabelOpname">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Supplier</th>
                    <th>Stok Sistem</th>
                    <th>Stok Fisik</th>
                    <th>Selisih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $i => $b)
                <tr class="opname-row" data-search="{{ strtolower($b->kode . ' ' . $b->nama) }}">
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td><code>{{ $b->kode }}</code></td>
                    <td class="fw-semibold">{{ $b->nama }}</td>
                    <td class="text-muted">{{ $b->supplier->nama ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $b->stok == 0 ? 'badge-danger' : ($b->stok <= 10 ? 'badge-warning' : 'badge-success') }}">
                            {{ $b->stok }} pcs
                        </span>
                    </td>
                    <td style="width:140px;">
                        <input type="hidden" name="barang_id[]" value="{{ $b->id }}">
                        <input type="number" name="stok_fisik[]"
                            class="form-control form-control-sm stok-fisik"
                            min="0" value="{{ $b->stok }}"
                            data-sistem="{{ $b->stok }}"
                            id="stok_fisik_{{ $b->id }}">
                    </td>
                    <td id="selisih_{{ $b->id }}" class="fw-semibold text-muted">0</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                        Belum ada barang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($barangs->count() > 0)
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4"
            onclick="return confirm('Simpan hasil stock opname? Stok sistem akan diperbarui sesuai stok fisik.')">
            <i class="bi bi-clipboard-check me-1"></i> Simpan Stock Opname
        </button>
    </div>
    @endif
</form>
@endsection

@push('scripts')
<script>
// Real-time selisih
document.querySelectorAll('.stok-fisik').forEach(input => {
    const sistemt = parseInt(input.dataset.sistem) || 0;
    const barangId = input.id.replace('stok_fisik_', '');
    const selisihEl = document.getElementById('selisih_' + barangId);

    input.addEventListener('input', () => {
        const fisik = parseInt(input.value) || 0;
        const diff = fisik - sistemt;
        selisihEl.textContent = (diff >= 0 ? '+' : '') + diff;
        selisihEl.className = 'fw-semibold ' + (diff > 0 ? 'text-success' : diff < 0 ? 'text-danger' : 'text-muted');
    });
});

// Search
document.getElementById('searchBarang').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.opname-row').forEach(r => {
        r.style.display = !q || r.dataset.search.includes(q) ? '' : 'none';
    });
});
</script>
@endpush
