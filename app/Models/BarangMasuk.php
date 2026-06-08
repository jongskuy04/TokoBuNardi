<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    protected $table      = 'barang_masuk';
    protected $fillable   = [
        'kode_transaksi', 'produk_id', 'jumlah',
        'harga_beli', 'supplier', 'tanggal', 'keterangan',
    ];
    protected $casts      = ['tanggal' => 'date'];
    public    $timestamps = true;
    const     UPDATED_AT  = null;

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
