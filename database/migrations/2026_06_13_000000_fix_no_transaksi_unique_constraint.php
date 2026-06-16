<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah no_transaksi dari UNIQUE menjadi INDEX
     * agar satu transaksi bisa memiliki multiple items
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Hapus unique constraint
            $table->dropUnique(['no_transaksi']);
            // Tambahkan index (non-unique)
            $table->index('no_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Hapus index
            $table->dropIndex(['no_transaksi']);
            // Kembalikan unique constraint
            $table->unique('no_transaksi');
        });
    }
};
