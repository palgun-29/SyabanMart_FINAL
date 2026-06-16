<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('supplier')->orderBy('nama')->get();
        return view('stock-opname.index', ['barangs' => $barangs]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'   => 'required|array',
            'stok_fisik'  => 'required|array',
            'stok_fisik.*' => 'required|integer|min:0',
        ]);

        foreach ($request->barang_id as $index => $id) {
            $barang = Barang::find($id);
            if ($barang) {
                $stokFisik = (int) $request->stok_fisik[$index];
                $barang->update(['stok' => $stokFisik]);
            }
        }

        return redirect()->route('stock-opname.index')
            ->with('success', 'Stock opname berhasil disimpan. Stok telah diperbarui.');
    }
}
