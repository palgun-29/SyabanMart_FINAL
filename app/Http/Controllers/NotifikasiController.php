<?php

namespace App\Http\Controllers;

use App\Models\StockNotifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = StockNotifikasi::with('barang')
            ->orderBy('created_at', 'desc')
            ->get();

        // Tandai semua sebagai dibaca saat halaman dibuka
        StockNotifikasi::where('dibaca', false)->update(['dibaca' => true]);

        return view('notifikasi.index', ['notifikasis' => $notifikasis]);
    }

    public function markRead($id)
    {
        $notifikasi = StockNotifikasi::findOrFail($id);
        $notifikasi->update(['dibaca' => true]);
        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function destroy($id)
    {
        StockNotifikasi::findOrFail($id)->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
