<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('master-data.suppliers.index', ['suppliers' => $suppliers]);
    }

    public function create()
    {
        return view('master-data.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:suppliers',
            'nama' => 'required',
            'email' => 'nullable|email',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'jenis_barang' => 'nullable',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        Supplier::create($validated);
        
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier)
    {
        return view('master-data.suppliers.edit', ['supplier' => $supplier]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:suppliers,kode,' . $supplier->id,
            'nama' => 'required',
            'email' => 'nullable|email',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'jenis_barang' => 'nullable',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        $supplier->update($validated);
        
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diubah');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus');
    }
}
