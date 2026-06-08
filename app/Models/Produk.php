<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\InvenHelper;

class Produk extends Model
{
    protected $table    = 'produk';
    protected $fillable = [
        'kode_produk', 'nama_produk', 'kategori_id',
        'satuan', 'harga_beli', 'harga_jual',
        'stok', 'stok_minimum', 'deskripsi',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class, 'produk_id');
    }

    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class, 'produk_id');
    }

    public function barangRusak(): HasMany
    {
        return $this->hasMany(BarangRusak::class, 'produk_id');
    }

    /** Status stok: 'habis' | 'kritis' | 'aman' */
    public function statusStok(): string
    {
        if ($this->stok == 0)                   return 'habis';
        if ($this->stok <= $this->stok_minimum) return 'kritis';
        return 'aman';
    }

    /** Format harga beli ke Rupiah */
    public function getHargaBeliFormatAttribute(): string
    {
        return InvenHelper::rupiah($this->harga_beli);
    }

    /** Format harga jual ke Rupiah */
    public function getHargaJualFormatAttribute(): string
    {
        return InvenHelper::rupiah($this->harga_jual);
    }
}
