<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'no_transaksi',
        'barang_id',
        'supplier_id',
        'tipe',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'catatan',
        'status',
        'tanggal_transaksi',
        'user_id',
        'payment_method',
        'jumlah_dibayar',
        'kembalian',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'tanggal_transaksi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
