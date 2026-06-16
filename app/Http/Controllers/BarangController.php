<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('supplier')->get();
        return view('master-data.barangs.index', ['barangs' => $barangs]);
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('master-data.barangs.create', ['suppliers' => $suppliers]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:barangs',
            'nama' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
            'kategori' => 'nullable',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        Barang::create($validated);
        
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $suppliers = Supplier::all();
        return view('master-data.barangs.edit', ['barang' => $barang, 'suppliers' => $suppliers]);
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:barangs,kode,' . $barang->id,
            'nama' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
            'kategori' => 'nullable',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        $barang->update($validated);
        
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diubah');
    }

    public function show(Barang $barang)
    {
        return response()->json([
            'id' => $barang->id,
            'nama' => $barang->nama,
            'stok' => $barang->stok,
            'harga_beli' => $barang->harga_beli,
            'harga_jual' => $barang->harga_jual,
        ]);
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus');
    }
}
