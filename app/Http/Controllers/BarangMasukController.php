<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BarangMasuk;
use App\Helpers\InvenHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bln') ?: now()->format('m');
        $tahun = $request->get('thn') ?: now()->year;

        $rows = BarangMasuk::with('produk')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal',  $tahun)
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->get();

        $totalUnit  = $rows->sum('jumlah');
        $totalNilai = $rows->sum(fn($r) => $r->jumlah * $r->harga_beli);

        return view('inventaris.barang_masuk', compact('rows', 'totalUnit', 'totalNilai', 'bulan', 'tahun'));
    }

    public function create()
    {
        $kode       = InvenHelper::generateKodeTransaksi('BM');
        $produkList = Produk::orderBy('nama_produk')->get();
        return view('inventaris.tambah_masuk', compact('kode', 'produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id'       => 'required|exists:produk,id',
            'jumlah'          => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'kode_transaksi'  => 'required|string|unique:barang_masuk,kode_transaksi',
            'harga_beli'      => 'nullable|numeric|min:0',
        ], [
            'produk_id.required' => 'Pilih produk terlebih dahulu.',
            'jumlah.min'         => 'Jumlah harus lebih dari 0.',
        ]);

        DB::transaction(function () use ($request) {
            BarangMasuk::create([
                'kode_transaksi' => $request->kode_transaksi,
                'produk_id'      => $request->produk_id,
                'jumlah'         => $request->jumlah,
                'harga_beli'     => $request->harga_beli ?? 0,
                'supplier'       => $request->supplier,
                'tanggal'        => $request->tanggal,
                'keterangan'     => $request->keterangan,
            ]);

            // Update stok + harga beli produk
            Produk::where('id', $request->produk_id)->update([
                'stok'       => DB::raw("stok + {$request->jumlah}"),
                'harga_beli' => $request->harga_beli ?? 0,
            ]);
        });

        return redirect()->route('masuk.index')
            ->with('success', 'Barang masuk berhasil dicatat. Stok produk otomatis bertambah.');
    }

    public function destroy(int $id)
    {
        DB::transaction(function () use ($id) {
            $bm = BarangMasuk::findOrFail($id);
            // Kembalikan stok
            Produk::where('id', $bm->produk_id)
                ->update(['stok' => DB::raw("stok - {$bm->jumlah}")]);
            $bm->delete();
        });

        return redirect()->route('masuk.index')
            ->with('danger', 'Catatan barang masuk dihapus. Stok dikembalikan.');
    }
}
