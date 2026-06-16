@extends('layouts.app')
@section('page-title', 'Transaksi Penjualan')

@section('content')
<div class="page-header d-none">
    <div class="page-header-title">
        <h2>Transaksi Penjualan</h2>
        <p>Daftar seluruh transaksi penjualan</p>
    </div>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle"></i> Tambah Penjualan
    </a>
</div>

{{-- Filter Bar --}}
<div class="card mb-3 d-none">
    <div class="card-body py-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Cari no. transaksi / barang…">
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="selesai">Selesai</option>
                    <option value="pending">Pending</option>
                    <option value="batal">Batal</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3" id="productSection">
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" id="productSearch" class="form-control" placeholder="🔍 Cari produk...">
                </div>

                <div class="row" id="productList">
                    @foreach($barangs as $b)
                        <div class="col-md-6 col-lg-4 mb-3 product-item" data-name="{{ strtolower($b->nama) }}" data-kode="{{ strtolower($b->kode) }}">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2"><strong>{{ $b->nama }}</strong></div>
                                    <div class="text-muted small">Kode: {{ $b->kode }} • Stok: {{ $b->stok }} pcs</div>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <div class="fw-bold text-success">Rp {{ number_format($b->harga_jual,0,',','.') }}</div>
                                        <button class="btn btn-sm btn-primary btn-add" data-id="{{ $b->id }}">Tambah</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Keranjang & Pembayaran</div>
            <div class="card-body">
                <form action="{{ route('penjualan.store') }}" method="POST" id="posForm">
                    @csrf
                    <div class="table-responsive mb-3">
                        <table class="table table-sm" id="cartTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th style="width:80px;">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="mb-3 clickable-summary border rounded p-2" data-target="#productSection" style="cursor:pointer;" title="Klik untuk menuju daftar produk">
                        <div class="d-flex justify-content-between">
                            <div>Total:</div>
                            <div class="fw-bold text-success" id="orderTotal">Rp 0</div>
                        </div>
                        <div class="text-muted small">Item: <span id="orderItemCount">0</span></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"></textarea>
                    </div>

                    <!-- Pembayaran: hanya Tunai -->
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" class="btn btn-outline-primary btn-payment-method active" data-method="tunai">Tunai</button>
                        </div>

                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label">Jumlah Dibayar</label>
                                <input type="number" step="100" min="0" class="form-control" id="jumlahDibayarInput" placeholder="Masukkan nominal yang dibayarkan">
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">Kembalian</div>
                                    <div class="fw-bold text-success" id="kembalianDisplay">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="payment_method" value="tunai" id="paymentMethodInput">
                    <input type="hidden" name="jumlah_dibayar" value="0" id="jumlahDibayarHidden">
                    <input type="hidden" name="kembalian" value="0" id="kembalianHidden">

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Bayar & Simpan</button>
                        <button type="button" id="btnClearCart" class="btn btn-outline-secondary">Bersihkan Keranjang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const barangs = @json($barangsData);

const productSearch = document.getElementById('productSearch');
const productItems = document.querySelectorAll('.product-item');
const cartBody = document.querySelector('#cartTable tbody');
const orderTotalEl = document.getElementById('orderTotal');
const orderItemCountEl = document.getElementById('orderItemCount');
const btnClearCart = document.getElementById('btnClearCart');
const jumlahDibayarInput = document.getElementById('jumlahDibayarInput');
const jumlahDibayarHidden = document.getElementById('jumlahDibayarHidden');
const kembalianDisplay = document.getElementById('kembalianDisplay');
const kembalianHidden = document.getElementById('kembalianHidden');
const paymentMethodInput = document.getElementById('paymentMethodInput');

window.currentPosTotal = 0;

function formatRp(value){
    return 'Rp ' + Number(value).toLocaleString('id-ID');
}

function findBarangById(id){
    return barangs.find(b => +b.id === +id);
}

function updateTotals(){
    let total = 0;
    let itemCount = 0;
    cartBody.querySelectorAll('tr').forEach(row => {
        const sub = Number(row.dataset.subtotal || 0);
        const qty = Number(row.querySelector('.qty-input').value || 0);
        total += sub;
        itemCount += qty;
    });
    orderTotalEl.textContent = formatRp(total);
    orderItemCountEl.textContent = itemCount;
    window.currentPosTotal = total;
    // update kembalian display if cashier already entered an amount
    updateChange();
}

function updateChange(){
    if (!jumlahDibayarInput) return;
    const paid = Number(jumlahDibayarInput.value) || 0;
    const total = Number(window.currentPosTotal) || 0;
    const rawChange = paid - total;
    const change = rawChange > 0 ? rawChange : 0;
    kembalianDisplay.textContent = formatRp(change);
    kembalianHidden.value = change;
    jumlahDibayarHidden.value = paid;
}

function setupClickableSummary(){
    document.querySelectorAll('.clickable-summary').forEach(el => {
        el.addEventListener('click', function(event){
            const tag = event.target.tagName;
            if (['INPUT','TEXTAREA','SELECT','BUTTON'].includes(tag)) {
                return;
            }
            const target = this.dataset.target;
            if (!target) return;
            const node = document.querySelector(target);
            if (node) {
                node.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

function addToCart(id){
    const barang = findBarangById(id);
    if(!barang) return;

    // if already in cart, increment
    let existing = cartBody.querySelector('tr[data-id="'+id+'"]');
    if(existing){
        const input = existing.querySelector('.qty-input');
        input.value = Number(input.value) + 1;
        existing.dataset.subtotal = Number(input.value) * barang.harga;
        existing.querySelector('.subtotal-cell').textContent = formatRp(existing.dataset.subtotal);
        updateTotals();
        return;
    }

    const tr = document.createElement('tr');
    tr.dataset.id = id;
    tr.dataset.subtotal = barang.harga;
    tr.innerHTML = `
        <td>
            <strong>${barang.nama}</strong><br><small class="text-muted">${barang.kode}</small>
            <input type="hidden" name="barang_id[]" value="${barang.id}">
        </td>
        <td>
            <input type="number" name="jumlah[]" class="form-control form-control-sm qty-input" value="1" min="1" style="width:70px;">
        </td>
        <td class="text-end subtotal-cell">${formatRp(barang.harga)}</td>
        <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger btn-remove">×</button></td>
    `;

    cartBody.appendChild(tr);

    // events
    tr.querySelector('.qty-input').addEventListener('change', function(){
        const q = Number(this.value) || 0;
        tr.dataset.subtotal = q * barang.harga;
        tr.querySelector('.subtotal-cell').textContent = formatRp(tr.dataset.subtotal);
        updateTotals();
    });

    tr.querySelector('.btn-remove').addEventListener('click', function(){
        tr.remove();
        updateTotals();
    });

    updateTotals();
}

document.querySelectorAll('.btn-add').forEach(btn => {
    btn.addEventListener('click', function(){
        addToCart(this.dataset.id);
    });
});

productSearch.addEventListener('input', function(){
    const q = this.value.trim().toLowerCase();
    productItems.forEach(it => {
        const name = it.dataset.name || '';
        const kode = it.dataset.kode || '';
        const show = !q || name.includes(q) || kode.includes(q);
        it.style.display = show ? '' : 'none';
    });
});

btnClearCart.addEventListener('click', function(){
    cartBody.innerHTML = '';
    updateTotals();
});

// ensure totals updated before submit
document.getElementById('posForm').addEventListener('submit', function(e){
    if(cartBody.querySelectorAll('tr').length === 0){
        e.preventDefault();
        alert('Keranjang kosong. Tambahkan minimal 1 barang.');
    }
    // validate pembayaran tunai: jumlah dibayar harus >= total
    const total = Number(window.currentPosTotal) || 0;
    const paid = Number(jumlahDibayarInput.value) || 0;
    if (total > 0 && paid < total) {
        e.preventDefault();
        alert('Jumlah dibayar kurang dari total. Masukkan nominal tunai yang cukup.');
        jumlahDibayarInput.focus();
        return false;
    }
    // set hidden fields (redundant but ensures correct values)
    jumlahDibayarHidden.value = paid;
    kembalianHidden.value = Math.max(0, paid - total);
});

if (jumlahDibayarInput) {
    jumlahDibayarInput.addEventListener('input', function(){
        updateChange();
    });
}

updateTotals();
setupClickableSummary();
</script>
@endpush
