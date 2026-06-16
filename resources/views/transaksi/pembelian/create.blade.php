@extends('layouts.app')
@section('page-title', 'Catat Pembelian')

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Catat Pembelian Barang</h2>
        <p>Isi form pembelian dari supplier untuk menambah stok</p>
    </div>
    <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-truck me-2 text-primary"></i>Form Pembelian</div>
            <div class="card-body">
                <form action="{{ route('pembelian.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Barang <span class="text-danger">*</span></label>
                        <select name="barang_id" id="barangSelect" class="form-select @error('barang_id') is-invalid @enderror" required>
                            <option value="">— Pilih barang —</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}"
                                    data-harga="{{ $b->harga_beli }}"
                                    data-stok="{{ $b->stok }}"
                                    {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama }} (Stok saat ini: {{ $b->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Saat Ini</label>
                        <div class="form-control bg-light" id="stokDisplay">—</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                            <option value="">— Pilih supplier —</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Beli <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
                                min="1" value="{{ old('jumlah', 1) }}" required>
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Beli/pcs <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_satuan" id="hargaSatuan" class="form-control @error('harga_satuan') is-invalid @enderror"
                                    min="0" step="100" value="{{ old('harga_satuan') }}" required placeholder="0">
                                @error('harga_satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Total Pembelian</label>
                        <div class="p-3 rounded-3 text-center fw-bold fs-4" style="background:#ecf7ee;color:#395e51;border:1px solid #c7ead6;" id="totalHarga">
                            Rp 0
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Opsional…">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Simpan Pembelian
                        </button>
                        <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const barangSelect = document.getElementById('barangSelect');
const stokDisplay = document.getElementById('stokDisplay');
const jumlahInput  = document.getElementById('jumlah');
const hargaInput   = document.getElementById('hargaSatuan');
const totalEl      = document.getElementById('totalHarga');

function formatRp(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }
function updateTotal() {
    const j = parseInt(jumlahInput.value) || 0;
    const h = parseFloat(hargaInput.value) || 0;
    totalEl.textContent = formatRp(j * h);
}

function updateSelectedBarang() {
    const opt = barangSelect.selectedOptions[0];
    const barangId = opt?.value;
    if (!barangId) {
        stokDisplay.textContent = '—';
        return;
    }

    fetch(`/barangs/${barangId}`, { headers: { Accept: 'application/json' } })
        .then(response => response.ok ? response.json() : null)
        .then(data => {
            if (!data) {
                stokDisplay.textContent = '—';
                return;
            }
            stokDisplay.textContent = data.stok > 0 ? `${data.stok} pcs` : 'Habis';
            if (data.harga_beli !== undefined && data.harga_beli !== null) {
                hargaInput.value = data.harga_beli;
            }
            updateTotal();
        })
        .catch(() => {
            stokDisplay.textContent = '—';
        });
}

barangSelect.addEventListener('change', updateSelectedBarang);

jumlahInput.addEventListener('input', updateTotal);
hargaInput.addEventListener('input', updateTotal);

updateSelectedBarang();
</script>
@endpush
