<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\StockNotifikasi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['barang', 'supplier'])
            ->where('tipe', 'penjualan')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('no_transaksi')
            ->map(function ($group) {
                $first = $group->first();

                return (object) [
                    'id'                => $first->id,
                    'no_transaksi'      => $first->no_transaksi,
                    'item_label'        => $group->pluck('barang.nama')->filter()->join(', '),
                    'item_count'        => $group->count(),
                    'jumlah'            => $group->sum('jumlah'),
                    'total_harga'       => $group->sum('total_harga'),
                    'status'            => $first->status,
                    'tanggal_transaksi' => $first->tanggal_transaksi,
                    'transaksis'        => $group,
                ];
            })
            ->values();

        // Also pass active products so index can act as POS: product list (left) + cart (right)
        $barangs = Barang::where('status', 'aktif')
            ->where('stok', '>', 0)
            ->get();

        // Prepare a simple array for use in JS (avoid closures inside Blade)
        $barangsData = $barangs->map(function ($b) {
            return [
                'id' => $b->id,
                'nama' => $b->nama,
                'kode' => $b->kode,
                'stok' => $b->stok,
                'harga' => $b->harga_jual,
            ];
        })->all();

        return view('transaksi.penjualan.index', ['transaksis' => $transaksis, 'barangs' => $barangs, 'barangsData' => $barangsData]);
    }

    public function create()
    {
        $barangs = Barang::where('status', 'aktif')
            ->where('stok', '>', 0)
            ->get();

        return view('transaksi.penjualan.create', ['barangs' => $barangs]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id'    => 'required|array|min:1',
            'barang_id.*'  => 'required|exists:barangs,id',
            'jumlah'       => 'required|array|min:1',
            'jumlah.*'     => 'required|integer|min:1',
            'catatan'      => 'nullable|string',
            'payment_method' => 'nullable|string',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
            'kembalian'      => 'nullable|numeric|min:0',
        ], [
            'barang_id.required' => 'Pilih minimal 1 barang untuk transaksi.',
            'barang_id.*.exists' => 'Barang yang dipilih tidak ditemukan.',
            'jumlah.required' => 'Masukkan jumlah untuk setiap barang.',
            'jumlah.*.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.*.min' => 'Jumlah minimal 1 pcs per barang.',
        ]);

        // Hitung total jumlah per barang_id (untuk validasi stok gabungan)
        $quantities = [];
        $itemDetails = [];

        foreach ($validated['barang_id'] as $index => $barangId) {
            $jumlah = $validated['jumlah'][$index] ?? 0;
            $quantities[$barangId] = ($quantities[$barangId] ?? 0) + $jumlah;
            $itemDetails[] = [
                'barang_id' => $barangId,
                'jumlah' => $jumlah,
                'index' => $index,
            ];
        }

        // Validasi stok
        $barangs = Barang::whereIn('id', array_keys($quantities))->get()->keyBy('id');
        $stokErrors = [];

        foreach ($quantities as $barangId => $totalJumlah) {
            $barang = $barangs->get($barangId);

            if (!$barang) {
                return back()
                    ->withErrors(['barang_id' => 'Barang tidak ditemukan.'])
                    ->withInput();
            }

            if ($barang->stok < $totalJumlah) {
                $stokErrors[] = "Barang '{$barang->nama}': stok tidak cukup (stok: {$barang->stok} pcs, dibutuhkan: {$totalJumlah} pcs)";
            }
        }

        if (!empty($stokErrors)) {
            return back()
                ->withErrors(['stok' => implode(', ', $stokErrors)])
                ->withInput();
        }

        // Generate nomor transaksi
        $noTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad(
            Transaksi::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        $created = [];

        // Simpan setiap item dengan no_transaksi yang sama
        foreach ($itemDetails as $detail) {
            $barang = $barangs->get($detail['barang_id']);
            $jumlah = $detail['jumlah'];

            $transaksiData = [
                'no_transaksi'      => $noTransaksi,
                'barang_id'         => $barang->id,
                'supplier_id'       => $barang->supplier_id,
                'tipe'              => 'penjualan',
                'jumlah'            => $jumlah,
                'harga_satuan'      => $barang->harga_jual,
                'total_harga'       => $barang->harga_jual * $jumlah,
                'catatan'           => $validated['catatan'] ?? null,
                'status'            => 'selesai',
                'tanggal_transaksi' => now(),
                'payment_method'    => $request->input('payment_method', 'tunai'),
                'jumlah_dibayar'    => $request->input('jumlah_dibayar', 0),
                'kembalian'         => $request->input('kembalian', 0),
            ];

            $transaksi = Transaksi::create($transaksiData);

            $created[] = $transaksi;
        }

        // Kurangi stok - gunakan total jumlah per barang_id
        foreach ($quantities as $barangId => $totalJumlah) {
            $barang = $barangs->get($barangId);
            $barang->decrement('stok', $totalJumlah);
            $this->checkStokNotifikasi($barang->fresh());
        }

        return redirect()->route('penjualan.show', $created[0]->id)
            ->with('success', 'Transaksi penjualan berhasil disimpan dengan ' . count($created) . ' item!');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['barang', 'supplier'])->findOrFail($id);

        $transaksis = Transaksi::with(['barang', 'supplier'])
            ->where('no_transaksi', $transaksi->no_transaksi)
            ->orderBy('created_at')
            ->get();

        return view('transaksi.penjualan.show', [
            'transaksis'   => $transaksis,
            'no_transaksi' => $transaksi->no_transaksi,
        ]);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $group = Transaksi::where('no_transaksi', $transaksi->no_transaksi)->get();

        foreach ($group as $item) {
            if ($item->status === 'selesai') {
                $item->barang->increment('stok', $item->jumlah);
            }
        }

        $group->each->update(['status' => 'batal']);

        return redirect()->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dibatalkan.');
    }

    private function checkStokNotifikasi(Barang $barang): void
    {
        if ($barang->stok == 0) {
            StockNotifikasi::create([
                'barang_id'       => $barang->id,
                'tipe_notifikasi' => 'stok_habis',
                'pesan'           => "Stok barang '{$barang->nama}' telah habis!",
                'dibaca'          => false,
            ]);
        } elseif ($barang->stok <= 10) {
            StockNotifikasi::create([
                'barang_id'       => $barang->id,
                'tipe_notifikasi' => 'stok_minimal',
                'pesan'           => "Stok barang '{$barang->nama}' sudah menipis (tersisa {$barang->stok} unit).",
                'dibaca'          => false,
            ]);
        }
    }
}