@extends('layouts.app')
@section('page-title', 'Tambah Penjualan')

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Tambah Transaksi Penjualan</h2>
        <p>Tambahkan beberapa item, lalu bayar sekali untuk semua.</p>
    </div>
    <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header"><i class="bi bi-cart-plus me-2 text-primary"></i>Form Penjualan Multi-Item</div>
            <div class="card-body">
                <form action="{{ route('penjualan.store') }}" method="POST" id="formJual">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $oldBarangIds = old('barang_id');
                        $oldJumlah = old('jumlah');
                        if (!is_array($oldBarangIds) || count($oldBarangIds) === 0) {
                            $oldBarangIds = [null];
                            $oldJumlah = [1];
                        }
                    @endphp

                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddItem">
                            <i class="bi bi-plus-circle"></i> Tambah Item
                        </button>
                    </div>

                    <div class="alert alert-info d-none" id="duplicateWarning" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Perhatian:</strong> Barang yang sama dipilih lebih dari sekali. Kuantitas akan digabungkan saat disimpan.
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="cartTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:5%;">No</th>
                                    <th style="width:30%;">Barang</th>
                                    <th style="width:10%;">Kode</th>
                                    <th style="width:10%;">Stok</th>
                                    <th style="width:15%;">Harga Satuan</th>
                                    <th style="width:12%;">Jumlah</th>
                                    <th style="width:13%;">Subtotal</th>
                                    <th style="width:5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($oldBarangIds as $index => $oldBarangId)
                                    @php
                                        $selectedBarangId = old('barang_id.'.$index, $oldBarangId);
                                        $selectedBarang = $barangs->firstWhere('id', $selectedBarangId);
                                        $selectedJumlah = old('jumlah.'.$index, $oldJumlah[$index] ?? 1);
                                    @endphp
                                    <tr class="item-row">
                                        <td class="text-center text-muted item-number"><small>1</small></td>
                                        <td>
                                            <select name="barang_id[]" class="form-select barang-select" required>
                                                <option value="">— Pilih barang —</option>
                                                @foreach($barangs as $b)
                                                    <option value="{{ $b->id }}" data-stok="{{ $b->stok }}" data-harga="{{ $b->harga_jual }}" data-kode="{{ $b->kode }}"
                                                        {{ (string) $selectedBarangId === (string) $b->id ? 'selected' : '' }}>
                                                        {{ $b->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control kode-display" readonly value="{{ $selectedBarang ? $selectedBarang->kode : '—' }}" style="font-size:0.9em;">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control stok-display" readonly value="{{ $selectedBarang ? $selectedBarang->stok.' pcs' : '—' }}">
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control harga-display" readonly value="{{ $selectedBarang ? number_format($selectedBarang->harga_jual, 0, ',', '.') : '—' }}" style="font-size:0.9em;">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1"
                                                value="{{ $selectedJumlah }}" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control subtotal-display" readonly value="{{ $selectedBarang ? 'Rp ' . number_format($selectedBarang->harga_jual * $selectedJumlah, 0, ',', '.') : 'Rp 0' }}" style="font-size:0.9em;">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item" title="Hapus item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-label fw-semibold">Total Keseluruhan</label>
                            <div class="fs-4 fw-bold text-success" id="orderTotal">Rp 0</div>
                        </div>
                        <div class="text-end text-muted">
                            <div>Item: <span id="orderItemCount">0</span></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Opsional…">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Pembayaran: hanya Tunai (sederhana) -->
                    <div class="mb-4">
                        <label class="form-label">Metode Pembayaran</label>
                        <div class="mb-2">
                            <button type="button" class="btn btn-outline-primary btn-payment-method active" data-method="tunai">Tunai</button>
                        </div>
                        <div>
                            <label class="form-label">Jumlah Dibayar</label>
                            <input type="number" step="100" min="0" class="form-control" id="jumlahDibayarInputCreate" placeholder="Masukkan nominal yang dibayarkan">
                        </div>
                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <div class="text-muted">Kembalian</div>
                            <div class="fw-bold text-success" id="kembalianDisplayCreate">Rp 0</div>
                        </div>
                    </div>

                    <input type="hidden" name="payment_method" value="tunai" id="paymentMethodInputCreate">
                    <input type="hidden" name="jumlah_dibayar" value="0" id="jumlahDibayarHiddenCreate">
                    <input type="hidden" name="kembalian" value="0" id="kembalianHiddenCreate">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4" id="btnSimpan">
                            <i class="bi bi-check-circle me-1"></i> Bayar dan Simpan
                        </button>
                        <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    $barangsData = $barangs->map(function ($b) {
        return [
            'id' => $b->id,
            'nama' => $b->nama,
            'kode' => $b->kode,
            'stok' => $b->stok,
            'harga' => $b->harga_jual,
        ];
    })->all();
@endphp

@push('scripts')
<script>
const barangs = @json($barangsData);

const cartBody = document.querySelector('#cartTable tbody');
const orderTotalEl = document.getElementById('orderTotal');
const orderItemCountEl = document.getElementById('orderItemCount');
const btnAddItem = document.getElementById('btnAddItem');
const duplicateWarning = document.getElementById('duplicateWarning');
const jumlahDibayarInputCreate = document.getElementById('jumlahDibayarInputCreate');
const jumlahDibayarHiddenCreate = document.getElementById('jumlahDibayarHiddenCreate');
const kembalianDisplayCreate = document.getElementById('kembalianDisplayCreate');
const kembalianHiddenCreate = document.getElementById('kembalianHiddenCreate');
window.currentPosTotalCreate = 0;

function formatRp(value) {
    return 'Rp ' + Number(value).toLocaleString('id-ID');
}

function checkDuplicateItems() {
    const rows = document.querySelectorAll('.item-row');
    const selectedIds = {};
    let hasDuplicate = false;

    rows.forEach(row => {
        const select = row.querySelector('.barang-select');
        if (select.value) {
            selectedIds[select.value] = (selectedIds[select.value] || 0) + 1;
            if (selectedIds[select.value] > 1) {
                hasDuplicate = true;
            }
        }
    });

    if (hasDuplicate) {
        duplicateWarning.classList.remove('d-none');
    } else {
        duplicateWarning.classList.add('d-none');
    }
}

function createRow(selectedId = '', quantity = 1) {
    const tr = document.createElement('tr');
    tr.className = 'item-row';

    const numberTd = document.createElement('td');
    numberTd.className = 'text-center text-muted item-number';
    numberTd.innerHTML = '<small>1</small>';

    const barangTd = document.createElement('td');
    const select = document.createElement('select');
    select.name = 'barang_id[]';
    select.className = 'form-select barang-select';
    select.required = true;

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = '— Pilih barang —';
    select.appendChild(defaultOption);

    barangs.forEach(barang => {
        const option = document.createElement('option');
        option.value = barang.id;
        option.dataset.stok = barang.stok;
        option.dataset.harga = barang.harga;
        option.dataset.kode = barang.kode;
        option.textContent = barang.nama;
        if (String(barang.id) === String(selectedId)) {
            option.selected = true;
        }
        select.appendChild(option);
    });
    barangTd.appendChild(select);

    const kodeTd = document.createElement('td');
    const kodeInput = document.createElement('input');
    kodeInput.type = 'text';
    kodeInput.className = 'form-control kode-display';
    kodeInput.readOnly = true;
    kodeInput.value = '—';
    kodeInput.style.fontSize = '0.9em';
    kodeTd.appendChild(kodeInput);

    const stokTd = document.createElement('td');
    const stokInput = document.createElement('input');
    stokInput.type = 'text';
    stokInput.className = 'form-control stok-display';
    stokInput.readOnly = true;
    stokInput.value = '—';
    stokTd.appendChild(stokInput);

    const hargaTd = document.createElement('td');
    const hargaWrapper = document.createElement('div');
    hargaWrapper.className = 'input-group input-group-sm';
    const hargaPrefix = document.createElement('span');
    hargaPrefix.className = 'input-group-text';
    hargaPrefix.textContent = 'Rp';
    const hargaInput = document.createElement('input');
    hargaInput.type = 'text';
    hargaInput.className = 'form-control harga-display';
    hargaInput.readOnly = true;
    hargaInput.value = '—';
    hargaInput.style.fontSize = '0.9em';
    hargaWrapper.appendChild(hargaPrefix);
    hargaWrapper.appendChild(hargaInput);
    hargaTd.appendChild(hargaWrapper);

    const jumlahTd = document.createElement('td');
    const jumlahInput = document.createElement('input');
    jumlahInput.type = 'number';
    jumlahInput.name = 'jumlah[]';
    jumlahInput.className = 'form-control jumlah-input';
    jumlahInput.min = '1';
    jumlahInput.required = true;
    jumlahInput.value = quantity;
    jumlahTd.appendChild(jumlahInput);

    const subtotalTd = document.createElement('td');
    const subtotalInput = document.createElement('input');
    subtotalInput.type = 'text';
    subtotalInput.className = 'form-control subtotal-display';
    subtotalInput.readOnly = true;
    subtotalInput.value = 'Rp 0';
    subtotalInput.style.fontSize = '0.9em';
    subtotalTd.appendChild(subtotalInput);

    const actionTd = document.createElement('td');
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-sm btn-outline-danger btn-remove-item';
    removeButton.title = 'Hapus item';
    removeButton.innerHTML = '<i class="bi bi-trash"></i>';
    actionTd.appendChild(removeButton);

    tr.appendChild(numberTd);
    tr.appendChild(barangTd);
    tr.appendChild(kodeTd);
    tr.appendChild(stokTd);
    tr.appendChild(hargaTd);
    tr.appendChild(jumlahTd);
    tr.appendChild(subtotalTd);
    tr.appendChild(actionTd);

    attachRowListeners(tr);

    cartBody.appendChild(tr);
    updateRow(tr);
}

function attachRowListeners(row) {
    const select = row.querySelector('.barang-select');
    const jumlahInput = row.querySelector('.jumlah-input');
    const removeButton = row.querySelector('.btn-remove-item');

    if (select) {
        select.addEventListener('change', () => updateRow(row));
    }

    if (jumlahInput) {
        jumlahInput.addEventListener('input', () => updateRow(row));
    }

    if (removeButton) {
        removeButton.addEventListener('click', () => {
            row.remove();
            updateRowNumbers();
            refreshCart();
        });
    }
}

function updateRow(row) {
    const select = row.querySelector('.barang-select');
    const kodeDisplay = row.querySelector('.kode-display');
    const stokDisplay = row.querySelector('.stok-display');
    const hargaDisplay = row.querySelector('.harga-display');
    const jumlahInput = row.querySelector('.jumlah-input');
    const subtotalDisplay = row.querySelector('.subtotal-display');

    const selectedOption = select.selectedOptions[0];
    const stok = Number(selectedOption?.dataset?.stok || 0);
    const harga = Number(selectedOption?.dataset?.harga || 0);
    const kode = selectedOption?.dataset?.kode || '';
    const jumlah = Number(jumlahInput.value || 0);

    kodeDisplay.value = kode || '—';
    stokDisplay.value = stok > 0 ? `${stok} pcs` : 'Habis';
    hargaDisplay.value = harga > 0 ? Number(harga).toLocaleString('id-ID') : '—';
    subtotalDisplay.value = formatRp(harga * jumlah);

    // Validasi: jumlah tidak boleh melebihi stok
    if (jumlah < 1 || jumlah > stok || !selectedOption?.value) {
        jumlahInput.classList.add('is-invalid');
    } else {
        jumlahInput.classList.remove('is-invalid');
    }

    checkDuplicateItems();
    refreshCart();
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('.item-number');
        numberCell.innerHTML = `<small>${index + 1}</small>`;
    });
}

function refreshCart() {
    const rows = document.querySelectorAll('.item-row');
    let total = 0;
    let itemCount = 0;

    rows.forEach(row => {
        const subtotalText = row.querySelector('.subtotal-display').value.replace(/[^0-9]/g, '');
        const subtotal = Number(subtotalText) || 0;
        const jumlah = Number(row.querySelector('.jumlah-input').value || 0);
        const select = row.querySelector('.barang-select');

        if (select.value && jumlah >= 1) {
            total += subtotal;
            itemCount += 1;
        }
    });

    orderTotalEl.textContent = formatRp(total);
    orderItemCountEl.textContent = itemCount;
    window.currentPosTotalCreate = total;
    // update kembalian jika sudah diisi
    if (jumlahDibayarInputCreate) {
        const paid = Number(jumlahDibayarInputCreate.value) || 0;
        const change = Math.max(0, paid - total);
        kembalianDisplayCreate.textContent = formatRp(change);
        jumlahDibayarHiddenCreate.value = paid;
        kembalianHiddenCreate.value = change;
    }
}

btnAddItem.addEventListener('click', () => createRow());

window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.item-row').forEach(row => {
        attachRowListeners(row);
        updateRow(row);
    });
    updateRowNumbers();
    refreshCart();
    if (jumlahDibayarInputCreate) {
        jumlahDibayarInputCreate.addEventListener('input', function(){
            const paid = Number(this.value) || 0;
            const total = Number(window.currentPosTotalCreate) || 0;
            const change = Math.max(0, paid - total);
            kembalianDisplayCreate.textContent = formatRp(change);
            jumlahDibayarHiddenCreate.value = paid;
            kembalianHiddenCreate.value = change;
        });
    }
    // validate before submit
    const form = document.getElementById('formJual');
    if (form) {
        form.addEventListener('submit', function(e){
            const total = Number(window.currentPosTotalCreate) || 0;
            const paid = Number(jumlahDibayarInputCreate?.value) || 0;
            if (total > 0 && paid < total) {
                e.preventDefault();
                alert('Jumlah dibayar kurang dari total. Masukkan nominal tunai yang cukup.');
                jumlahDibayarInputCreate?.focus();
                return false;
            }
            jumlahDibayarHiddenCreate.value = paid;
            kembalianHiddenCreate.value = Math.max(0, paid - total);
        });
    }
});
</script>
@endpush
