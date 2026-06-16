<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\StockNotifikasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('status', 'aktif')->count();
        $totalBarang = Barang::count();
        $transaksibulanIni = Transaksi::whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->where('status', 'selesai')
            ->count();
        $totalOmzet = Transaksi::whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->where('tipe', 'penjualan')
            ->where('status', 'selesai')
            ->sum('total_harga');
        $stokMinimal = Barang::where('stok', '<=', 10)->where('stok', '>', 0)->count();
        $stokHabis = Barang::where('stok', 0)->count();
        $unreadNotif = StockNotifikasi::where('dibaca', false)->count();

        $aktivitasTerbaru = Transaksi::with(['barang', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'totalSuppliers'    => $totalSuppliers,
            'activeSuppliers'   => $activeSuppliers,
            'totalBarang'       => $totalBarang,
            'transaksibulanIni' => $transaksibulanIni,
            'totalOmzet'        => $totalOmzet,
            'stokMinimal'       => $stokMinimal,
            'stokHabis'         => $stokHabis,
            'unreadNotif'       => $unreadNotif,
            'aktivitasTerbaru'  => $aktivitasTerbaru,
        ]);
    }
}
