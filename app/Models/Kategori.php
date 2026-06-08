<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table      = 'kategori';
    protected $fillable   = ['nama_kategori', 'deskripsi'];
    public    $timestamps = true;
    const     UPDATED_AT  = null; // tabel hanya punya created_at

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
