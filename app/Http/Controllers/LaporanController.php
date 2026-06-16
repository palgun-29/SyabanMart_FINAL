<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $query = Transaksi::with(['barang', 'supplier'])
            ->where('tipe', 'penjualan')
            ->where('status', 'selesai');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->sampai);
        }

        $transaksis  = $query->orderBy('tanggal_transaksi', 'desc')->get();
        $totalOmzet  = $transaksis->sum('total_harga');
        $totalItem   = $transaksis->sum('jumlah');

        return view('laporan.penjualan', [
            'transaksis'  => $transaksis,
            'totalOmzet'  => $totalOmzet,
            'totalItem'   => $totalItem,
            'dari'        => $request->dari,
            'sampai'      => $request->sampai,
        ]);
    }

    public function stok(Request $request)
    {
        $barangs = Barang::with('supplier')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderBy('nama')
            ->get();

        $totalBarang    = $barangs->count();
        $stokHabis      = $barangs->where('stok', 0)->count();
        $stokMinimal    = $barangs->where('stok', '>', 0)->where('stok', '<=', 10)->count();
        $nilaiInventori = $barangs->sum(fn($b) => $b->stok * $b->harga_beli);

        return view('laporan.stok', [
            'barangs'       => $barangs,
            'totalBarang'   => $totalBarang,
            'stokHabis'     => $stokHabis,
            'stokMinimal'   => $stokMinimal,
            'nilaiInventori'=> $nilaiInventori,
            'statusFilter'  => $request->status,
        ]);
    }

    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with(['barang', 'supplier'])->findOrFail($id);
        $transaksis = Transaksi::with(['barang', 'supplier'])
            ->where('no_transaksi', $transaksi->no_transaksi)
            ->orderBy('created_at')
            ->get();

        return view('laporan.cetak-struk', [
            'transaksis'   => $transaksis,
            'no_transaksi' => $transaksi->no_transaksi,
        ]);
    }

    public function cetakLaporan(Request $request)
    {
        $query = Transaksi::with(['barang', 'supplier'])
            ->where('tipe', 'penjualan')
            ->where('status', 'selesai');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->sampai);
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();
        $totalOmzet = $transaksis->sum('total_harga');

        return view('laporan.cetak-laporan', [
            'transaksis' => $transaksis,
            'totalOmzet' => $totalOmzet,
            'dari'       => $request->dari,
            'sampai'     => $request->sampai,
        ]);
    }
}
