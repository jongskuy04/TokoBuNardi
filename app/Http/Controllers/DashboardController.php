<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BarangRusak;
use App\Models\Aset;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk     = Produk::count();
        $totalKategori   = Kategori::count();
        $stokKritis      = Produk::whereRaw('stok <= stok_minimum')->count();
        $nilaiInventaris = Produk::sum(DB::raw('stok * harga_jual'));

        $bln = now()->month;
        $thn = now()->year;

        $totalMasuk = BarangMasuk::whereMonth('tanggal', $bln)->whereYear('tanggal', $thn)->sum('jumlah');
        $totalKeluar = BarangKeluar::whereMonth('tanggal', $bln)->whereYear('tanggal', $thn)->sum('jumlah');
        $totalRusak  = BarangRusak::where('jenis','rusak')->whereMonth('tanggal', $bln)->whereYear('tanggal', $thn)->sum('jumlah');
        $totalReturn = BarangRusak::where('jenis','return')->whereMonth('tanggal', $bln)->whereYear('tanggal', $thn)->sum('jumlah');

        // Omset kotor dari barang keluar bulan ini
        $omsetKotor = BarangKeluar::whereMonth('tanggal', $bln)->whereYear('tanggal', $thn)
            ->sum(DB::raw('jumlah * harga_jual'));

        // Nilai return bulan ini
        $nilaiReturn = BarangRusak::where('jenis','return')
            ->whereMonth('tanggal', $bln)->whereYear('tanggal', $thn)
            ->sum(DB::raw('jumlah * harga_jual'));

        // Omset bersih = kotor - return
        $omsetBersih = $omsetKotor - $nilaiReturn;

        // Produk stok kritis
        $produkKritis = Produk::with('kategori')
            ->whereRaw('stok <= stok_minimum')
            ->orderBy('stok')
            ->limit(8)
            ->get();

        // Aktivitas terbaru — gabung masuk + keluar + rusak
        $aktivitasMasuk = BarangMasuk::with('produk')
            ->orderByDesc('created_at')->limit(5)->get()
            ->map(fn($bm) => [
                'tipe'           => 'masuk',
                'tanggal'        => $bm->tanggal,
                'jumlah'         => $bm->jumlah,
                'nama_produk'    => $bm->produk->nama_produk ?? '-',
                'kode_transaksi' => $bm->kode_transaksi,
                'created_at'     => $bm->created_at,
            ]);

        $aktivitasKeluar = BarangKeluar::with('produk')
            ->orderByDesc('created_at')->limit(5)->get()
            ->map(fn($bk) => [
                'tipe'           => 'keluar',
                'tanggal'        => $bk->tanggal,
                'jumlah'         => $bk->jumlah,
                'nama_produk'    => $bk->produk->nama_produk ?? '-',
                'kode_transaksi' => $bk->kode_transaksi,
                'created_at'     => $bk->created_at,
            ]);

        $aktivitasRusak = BarangRusak::with('produk')
            ->orderByDesc('created_at')->limit(5)->get()
            ->map(fn($br) => [
                'tipe'           => $br->jenis,
                'tanggal'        => $br->tanggal,
                'jumlah'         => $br->jumlah,
                'nama_produk'    => $br->produk->nama_produk ?? '-',
                'kode_transaksi' => $br->kode_transaksi,
                'created_at'     => $br->created_at,
            ]);

        $aktivitas = $aktivitasMasuk->concat($aktivitasKeluar)->concat($aktivitasRusak)
            ->sortByDesc('created_at')->take(8)->values();

        $totalAset       = Aset::count();
        $asetPerluPerhatian = Aset::whereIn('kondisi', ['perlu_perbaikan','rusak_berat'])->count();
        $nilaiAset       = Aset::get()->sum(fn($a) => $a->nilaiTotal());

        return view('dashboard.index', compact(
            'totalProduk', 'totalKategori', 'stokKritis',
            'nilaiInventaris', 'totalMasuk', 'totalKeluar',
            'totalRusak', 'totalReturn',
            'omsetKotor', 'nilaiReturn', 'omsetBersih',
            'produkKritis', 'aktivitas',
            'totalAset', 'asetPerluPerhatian', 'nilaiAset'
        ));
    }
}
