<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BarangKeluar;
use App\Helpers\InvenHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bln') ?: now()->format('m');
        $tahun = $request->get('thn') ?: now()->year;

        $rows = BarangKeluar::with('produk')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal',  $tahun)
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->get();

        $totalUnit  = $rows->sum('jumlah');
        $totalNilai = $rows->sum(fn($r) => $r->jumlah * $r->harga_jual);

        return view('inventaris.barang_keluar', compact('rows', 'totalUnit', 'totalNilai', 'bulan', 'tahun'));
    }

    public function create()
    {
        $kode       = InvenHelper::generateKodeTransaksi('BK');
        $produkList = Produk::where('stok', '>', 0)->orderBy('nama_produk')->get();
        return view('inventaris.tambah_keluar', compact('kode', 'produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id'      => 'required|exists:produk,id',
            'jumlah'         => 'required|integer|min:1',
            'tanggal'        => 'required|date',
            'kode_transaksi' => 'required|string|unique:barang_keluar,kode_transaksi',
            'harga_jual'     => 'nullable|numeric|min:0',
        ], [
            'produk_id.required' => 'Pilih produk terlebih dahulu.',
            'jumlah.min'         => 'Jumlah harus lebih dari 0.',
        ]);

        // Cek stok mencukupi
        $produk = Produk::findOrFail($request->produk_id);
        if ($produk->stok < $request->jumlah) {
            return back()->withInput()
                ->withErrors(['jumlah' => "Stok tidak cukup! Stok tersedia: {$produk->stok} {$produk->satuan} untuk produk \"{$produk->nama_produk}\"."]);
        }

        DB::transaction(function () use ($request, $produk) {
            BarangKeluar::create([
                'kode_transaksi' => $request->kode_transaksi,
                'produk_id'      => $request->produk_id,
                'jumlah'         => $request->jumlah,
                'harga_jual'     => $request->harga_jual ?? $produk->harga_jual,
                'pembeli'        => $request->pembeli,
                'tanggal'        => $request->tanggal,
                'keterangan'     => $request->keterangan,
            ]);

            // Kurangi stok
            $produk->decrement('stok', $request->jumlah);
        });

        return redirect()->route('keluar.index')
            ->with('success', 'Barang keluar berhasil dicatat. Stok produk berkurang.');
    }

    public function destroy(int $id)
    {
        DB::transaction(function () use ($id) {
            $bk = BarangKeluar::findOrFail($id);
            // Kembalikan stok
            Produk::where('id', $bk->produk_id)
                ->update(['stok' => DB::raw("stok + {$bk->jumlah}")]);
            $bk->delete();
        });

        return redirect()->route('keluar.index')
            ->with('danger', 'Catatan barang keluar dihapus. Stok dikembalikan.');
    }
}
