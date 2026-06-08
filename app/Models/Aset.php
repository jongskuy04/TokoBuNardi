<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InvenHelper;

class Aset extends Model
{
    protected $table = 'aset';

    protected $fillable = [
        'kode_aset', 'nama_aset',
        'jumlah', 'satuan', 'harga_perolehan',
        'tanggal_perolehan', 'kondisi', 'lokasi', 'keterangan',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'harga_perolehan'   => 'decimal:2',
    ];

    public function labelKondisi(): string
    {
        return match ($this->kondisi) {
            'baik'             => 'Baik',
            'perlu_perbaikan'  => 'Perlu Perbaikan',
            'rusak_berat'      => 'Rusak Berat',
            default            => ucfirst($this->kondisi),
        };
    }

    public function badgeKondisi(): string
    {
        return match ($this->kondisi) {
            'baik'             => 'badge-success',
            'perlu_perbaikan'  => 'badge-warning',
            'rusak_berat'      => 'badge-danger',
            default            => 'badge-info',
        };
    }

    public function nilaiTotal(): float
    {
        return (float) ($this->jumlah * $this->harga_perolehan);
    }

    public function getHargaFormatAttribute(): string
    {
        return InvenHelper::rupiah($this->harga_perolehan);
    }

    public function getNilaiTotalFormatAttribute(): string
    {
        return InvenHelper::rupiah($this->nilaiTotal());
    }

    /** Generate kode aset: AST-001 */
    public static function generateKode(): string
    {
        $total = static::count();
        return 'AST-' . str_pad($total + 1, 3, '0', STR_PAD_LEFT);
    }
}
