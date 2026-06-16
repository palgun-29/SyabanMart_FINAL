<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\StockNotifikasi;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['barang', 'supplier'])
            ->where('tipe', 'pembelian')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('transaksi.pembelian.index', ['transaksis' => $transaksis]);
    }

    public function create()
    {
        $barangs  = Barang::where('status', 'aktif')->get();
        $suppliers = Supplier::where('status', 'aktif')->get();
        return view('transaksi.pembelian.create', [
            'barangs'   => $barangs,
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id'    => 'required|exists:barangs,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'catatan'      => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($validated['barang_id']);

        $noTransaksi = 'PBL-' . date('Ymd') . '-' . str_pad(Transaksi::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        Transaksi::create([
            'no_transaksi'      => $noTransaksi,
            'barang_id'         => $validated['barang_id'],
            'supplier_id'       => $validated['supplier_id'],
            'tipe'              => 'pembelian',
            'jumlah'            => $validated['jumlah'],
            'harga_satuan'      => $validated['harga_satuan'],
            'total_harga'       => $validated['harga_satuan'] * $validated['jumlah'],
            'catatan'           => $validated['catatan'] ?? null,
            'status'            => 'selesai',
            'tanggal_transaksi' => now(),
        ]);

        // Tambah stok
        $barang->increment('stok', $validated['jumlah']);

        // Update harga beli
        $barang->update(['harga_beli' => $validated['harga_satuan']]);

        // Buat notifikasi stok tambah
        StockNotifikasi::create([
            'barang_id'       => $barang->id,
            'tipe_notifikasi' => 'stok_tambah',
            'pesan'           => "Stok barang '{$barang->nama}' ditambah {$validated['jumlah']} unit. Total stok: " . ($barang->stok + $validated['jumlah']) . " unit.",
            'dibaca'          => false,
        ]);

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil dicatat, stok barang bertambah!');
    }
}
