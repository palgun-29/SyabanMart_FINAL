@extends('layouts.app')

@section('page-title', 'Edit Barang')

@section('content')
<div class="content-header">
    <div class="content-header-title">
        <h2>Edit Barang</h2>
        <p>Perbarui informasi barang</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('barangs.update', $barang) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ $barang->kode }}" required>
                        @error('kode') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ $barang->nama }}" required>
                        @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $barang->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{ $barang->kategori }}">
                        @error('kategori') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Harga Beli</label>
                                <input type="number" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" value="{{ $barang->harga_beli }}" step="0.01" required>
                                @error('harga_beli') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Harga Jual</label>
                                <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ $barang->harga_jual }}" step="0.01" required>
                                @error('harga_jual') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ $barang->stok }}" required>
                        @error('stok') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ $barang->deskripsi }}</textarea>
                        @error('deskripsi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ $barang->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak aktif" {{ $barang->status == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i> Perbarui
                        </button>
                        <a href="{{ route('barangs.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
