<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'stock_notifikasis';

    protected $fillable = [
        'barang_id',
        'tipe_notifikasi',
        'pesan',
        'dibaca',
        'user_id',
    ];

    protected $casts = [
        'dibaca'     => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('dibaca', false);
    }
}
