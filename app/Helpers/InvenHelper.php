<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class InvenHelper
{
    /**
     * Format angka ke format Rupiah
     */
    public static function rupiah($angka): string
    {
        return 'Rp ' . number_format((float)$angka, 0, ',', '.');
    }

    /**
     * Generate kode produk otomatis: PRD-001, PRD-002, dst.
     */
    public static function generateKodeProduk(): string
    {
        $total = DB::table('produk')->count();
        return 'PRD-' . str_pad($total + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode transaksi: BM-202505-001 / BK-202505-001 / BR-202505-001
     */
    public static function generateKodeTransaksi(string $prefix): string
    {
        $tahun = date('Y');
        $bulan = date('m');

        $table = match ($prefix) {
            'BM'    => 'barang_masuk',
            'BK'    => 'barang_keluar',
            'BR'    => 'barang_rusak',
            'RT'    => 'barang_rusak',
            default => 'barang_masuk',
        };

        $total = DB::table($table)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->count();

        $no = str_pad($total + 1, 3, '0', STR_PAD_LEFT);
        return "{$prefix}-{$tahun}{$bulan}-{$no}";
    }
}
