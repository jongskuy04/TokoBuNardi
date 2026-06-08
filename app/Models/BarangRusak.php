<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangRusak extends Model
{
    protected $table    = 'barang_rusak';
    public    $timestamps = false;

    protected $fillable = [
        'kode_transaksi', 'produk_id', 'barang_keluar_id',
        'jumlah', 'harga_jual', 'jenis',
        'sumber', 'tanggal', 'keterangan',
    ];

    protected $casts = [
        'tanggal'    => 'date',
        'harga_jual' => 'decimal:2',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /** Relasi ke transaksi penjualan asal (hanya untuk jenis return) */
    public function barangKeluar(): BelongsTo
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }

    /** Hitung nilai omset yang berkurang akibat return ini */
    public function nilaiReturn(): float
    {
        return (float) ($this->jumlah * $this->harga_jual);
    }

    public function labelJenis(): string
    {
        return match ($this->jenis) {
            'rusak'  => 'Barang Rusak',
            'return' => 'Retur',
            default  => ucfirst($this->jenis),
        };
    }

    public function badgeJenis(): string
    {
        return match ($this->jenis) {
            'rusak'  => 'badge-danger',
            'return' => 'badge-warning',
            default  => 'badge-info',
        };
    }
}
