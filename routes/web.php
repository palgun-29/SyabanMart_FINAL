<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// ─── Auth (publik) ───────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Protected Routes (semua harus login) ───────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard – semua role
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Master Data (Manager & Admin) ─────────────────────────────────
    Route::middleware('role:manager,admin')->group(function () {
        Route::resource('suppliers', SupplierController::class);
    });

    // Barang: Manager & Admin bisa full CRUD; Kasir hanya bisa lihat (GET)
    Route::middleware('role:manager,admin')->group(function () {
        Route::resource('barangs', BarangController::class)->except(['index', 'show']);
    });
    Route::middleware('role:manager,admin,kasir')->group(function () {
        Route::get('barangs', [BarangController::class, 'index'])->name('barangs.index');
        Route::get('barangs/{barang}', [BarangController::class, 'show'])->name('barangs.show');
    });

    // ── Transaksi Penjualan (Manager & Kasir) ────────────────────────
    Route::middleware('role:manager,kasir')->group(function () {
        Route::get('penjualan', [TransaksiController::class, 'index'])->name('penjualan.index');
        Route::get('penjualan/create', [TransaksiController::class, 'create'])->name('penjualan.create');
        Route::post('penjualan', [TransaksiController::class, 'store'])->name('penjualan.store');
        Route::get('penjualan/{id}', [TransaksiController::class, 'show'])->name('penjualan.show');
        Route::delete('penjualan/{id}', [TransaksiController::class, 'destroy'])->name('penjualan.destroy');
    });

    // ── Manajemen Stok / Pembelian (Manager & Admin) ───────────────
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::get('pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    });

    // ── Stock Opname (Manager & Admin) ───────────────────────────────
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
        Route::post('stock-opname', [StockOpnameController::class, 'store'])->name('stock-opname.store');
    });

    // ── Notifikasi Stok (Manager & Admin) ────────────────────────────
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::post('notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
        Route::delete('notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    });

    // ── Laporan (Manager & Kasir: penjualan/cetak; Manager & Admin: stok) ──
    Route::middleware('role:manager,kasir')->group(function () {
        Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('laporan/cetak-struk/{id}', [LaporanController::class, 'cetakStruk'])->name('laporan.cetak-struk');
        Route::get('laporan/cetak-laporan', [LaporanController::class, 'cetakLaporan'])->name('laporan.cetak-laporan');
    });

    Route::middleware('role:manager,admin')->group(function () {
        Route::get('laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    });
});
