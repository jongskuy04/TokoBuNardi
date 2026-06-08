<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Helpers\InvenHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tgl_mulai = $request->get('tgl_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tgl_selesai = $request->get('tgl_selesai', now()->endOfMonth()->format('Y-m-d'));

        // Validasi dan bersihkan format tanggal
        try {
            $tgl_mulai = \Carbon\Carbon::parse($tgl_mulai)->format('Y-m-d');
        } catch (\Exception $e) {
            $tgl_mulai = now()->startOfMonth()->format('Y-m-d');
        }

        try {
            $tgl_selesai = \Carbon\Carbon::parse($tgl_selesai)->format('Y-m-d');
        } catch (\Exception $e) {
            $tgl_selesai = now()->endOfMonth()->format('Y-m-d');
        }

        // Koreksi otomatis jika tanggal mulai melewati tanggal selesai
        if ($tgl_mulai > $tgl_selesai) {
            $temp = $tgl_mulai;
            $tgl_mulai = $tgl_selesai;
            $tgl_selesai = $temp;
        }

        $rows = Produk::select([
                'produk.id',
                'produk.kode_produk',
                'produk.nama_produk',
                'produk.satuan',
                'produk.stok as stok_sekarang',
                'produk.stok_minimum',
                'produk.harga_jual',
                'kategori.nama_kategori',
                DB::raw("COALESCE((SELECT SUM(jumlah) FROM barang_masuk WHERE produk_id = produk.id AND tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'), 0) as total_masuk"),
                DB::raw("COALESCE((SELECT SUM(jumlah) FROM barang_keluar WHERE produk_id = produk.id AND tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'), 0) as total_keluar"),
                DB::raw("COALESCE((SELECT SUM(jumlah) FROM barang_rusak WHERE produk_id = produk.id AND jenis = 'rusak' AND tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'), 0) as total_barang_rusak"),
                DB::raw("COALESCE((SELECT SUM(jumlah) FROM barang_rusak WHERE produk_id = produk.id AND jenis = 'return' AND tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'), 0) as total_return"),
                // Omset = penjualan - nilai return
                DB::raw("COALESCE((SELECT SUM(jumlah * harga_jual) FROM barang_keluar WHERE produk_id = produk.id AND tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'), 0)
                         - COALESCE((SELECT SUM(jumlah * harga_jual) FROM barang_rusak WHERE produk_id = produk.id AND jenis = 'return' AND tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'), 0)
                         as omset"),
                DB::raw("COALESCE((SELECT SUM(jumlah * harga_jual) FROM barang_keluar WHERE produk_id = produk.id AND tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'), 0) as omset_kotor"),
                DB::raw("COALESCE((SELECT SUM(jumlah * harga_jual) FROM barang_rusak WHERE produk_id = produk.id AND jenis = 'return' AND tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'), 0) as nilai_return"),
            ])
            ->leftJoin('kategori', 'produk.kategori_id', '=', 'kategori.id')
            ->orderByDesc('omset')
            ->orderBy('produk.nama_produk')
            ->get();

        $grandMasuk     = $rows->sum('total_masuk');
        $grandKeluar    = $rows->sum('total_keluar');
        $grandRusak     = $rows->sum('total_barang_rusak');
        $grandReturn    = $rows->sum('total_return');
        $grandOmsetKotor= $rows->sum('omset_kotor');
        $grandNilaiReturn = $rows->sum('nilai_return');
        $grandOmset     = $rows->sum('omset'); // sudah bersih (kotor - return)
        $grandNilaiStok = $rows->sum(fn($r) => $r->stok_sekarang * $r->harga_jual);

        return view('inventaris.laporan', compact(
            'rows', 'tgl_mulai', 'tgl_selesai',
            'grandMasuk', 'grandKeluar', 'grandRusak', 'grandReturn',
            'grandOmsetKotor', 'grandNilaiReturn', 'grandOmset', 'grandNilaiStok'
        ));
    }
}
