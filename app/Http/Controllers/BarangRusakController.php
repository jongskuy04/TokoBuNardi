<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BarangKeluar;
use App\Models\BarangRusak;
use App\Helpers\InvenHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangRusakController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bln', now()->format('m'));
        $tahun = $request->get('thn', now()->year);
        $jenis = $request->get('jenis', '');

        $query = BarangRusak::with(['produk', 'barangKeluar'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal',  $tahun);

        if ($jenis !== '') {
            $query->where('jenis', $jenis);
        }

        $rows = $query->orderByDesc('tanggal')
                      ->orderByDesc('created_at')
                      ->get();

        $totalUnit      = $rows->sum('jumlah');
        $totalRusak     = $rows->where('jenis', 'rusak')->sum('jumlah');
        $totalReturn    = $rows->where('jenis', 'return')->sum('jumlah');
        $totalNilaiReturn = $rows->where('jenis', 'return')->sum(fn($r) => $r->nilaiReturn());

        return view('inventaris.barang_rusak', compact(
            'rows', 'totalUnit', 'totalRusak', 'totalReturn', 'totalNilaiReturn',
            'bulan', 'tahun', 'jenis'
        ));
    }

    /** Form untuk RUSAK — pilih dari produk yang ada di stok */
    public function createRusak()
    {
        $kode       = InvenHelper::generateKodeTransaksi('BR');
        $produkList = Produk::where('stok', '>', 0)->orderBy('nama_produk')->get();
        return view('inventaris.tambah_rusak', compact('kode', 'produkList'));
    }

    /** Form untuk RETURN — pilih dari transaksi barang keluar */
    public function createReturn()
    {
        $kode          = InvenHelper::generateKodeTransaksi('RT');
        // Ambil data barang keluar yang masih bisa di-return
        // (jumlah yang sudah di-return tidak melebihi jumlah asli)
        $barangKeluar  = BarangKeluar::with('produk')
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($bk) {
                // Hitung total yang sudah di-return dari transaksi ini
                $sudahReturn = BarangRusak::where('barang_keluar_id', $bk->id)
                    ->where('jenis', 'return')
                    ->sum('jumlah');
                $bk->sisa_return = $bk->jumlah - $sudahReturn;
                return $bk;
            })
            ->filter(fn($bk) => $bk->sisa_return > 0); // hanya yang masih bisa di-return

        return view('inventaris.tambah_return', compact('kode', 'barangKeluar'));
    }

    /** Router create — arahkan ke form yang sesuai */
    public function create(Request $request)
    {
        $jenis = $request->get('jenis', 'rusak');
        if ($jenis === 'return') {
            return $this->createReturn();
        }
        return $this->createRusak();
    }

    /** Simpan BARANG RUSAK — kurangi stok */
    public function storeRusak(Request $request)
    {
        $request->validate([
            'produk_id'      => 'required|exists:produk,id',
            'jumlah'         => 'required|integer|min:1',
            'tanggal'        => 'required|date',
            'kode_transaksi' => 'required|string|unique:barang_rusak,kode_transaksi',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok < $request->jumlah) {
            return back()->withInput()
                ->withErrors(['jumlah' => "Stok tidak cukup! Tersedia: {$produk->stok} {$produk->satuan}."]);
        }

        DB::transaction(function () use ($request, $produk) {
            BarangRusak::create([
                'kode_transaksi'  => $request->kode_transaksi,
                'produk_id'       => $request->produk_id,
                'barang_keluar_id'=> null,
                'jumlah'          => $request->jumlah,
                'harga_jual'      => 0,
                'jenis'           => 'rusak',
                'sumber'          => $request->sumber,
                'tanggal'         => $request->tanggal,
                'keterangan'      => $request->keterangan,
            ]);
            // Kurangi stok
            $produk->decrement('stok', $request->jumlah);
        });

        return redirect()->route('rusak.index')
            ->with('success', 'Barang rusak berhasil dicatat. Stok berkurang.');
    }

    /** Simpan RETURN — stok bertambah, omset berkurang */
    public function storeReturn(Request $request)
    {
        $request->validate([
            'barang_keluar_id' => 'required|exists:barang_keluar,id',
            'jumlah'           => 'required|integer|min:1',
            'tanggal'          => 'required|date',
            'kode_transaksi'   => 'required|string|unique:barang_rusak,kode_transaksi',
        ]);

        $barangKeluar = BarangKeluar::with('produk')->findOrFail($request->barang_keluar_id);

        // Hitung sisa yang bisa di-return
        $sudahReturn = BarangRusak::where('barang_keluar_id', $barangKeluar->id)
            ->where('jenis', 'return')
            ->sum('jumlah');
        $sisaReturn = $barangKeluar->jumlah - $sudahReturn;

        if ($request->jumlah > $sisaReturn) {
            return back()->withInput()
                ->withErrors(['jumlah' => "Jumlah return melebihi sisa yang bisa di-return! Maksimal: {$sisaReturn} unit."]);
        }

        DB::transaction(function () use ($request, $barangKeluar) {
            BarangRusak::create([
                'kode_transaksi'   => $request->kode_transaksi,
                'produk_id'        => $barangKeluar->produk_id,
                'barang_keluar_id' => $barangKeluar->id,
                'jumlah'           => $request->jumlah,
                'harga_jual'       => $barangKeluar->harga_jual, // simpan harga saat dijual
                'jenis'            => 'return',
                'sumber'           => $request->sumber ?? $barangKeluar->pembeli,
                'tanggal'          => $request->tanggal,
                'keterangan'       => $request->keterangan,
            ]);

            // Tambah stok kembali
            Produk::where('id', $barangKeluar->produk_id)
                ->increment('stok', $request->jumlah);
        });

        $nilaiReturn = InvenHelper::rupiah($request->jumlah * $barangKeluar->harga_jual);
        return redirect()->route('rusak.index')
            ->with('success', "Return berhasil dicatat. Stok bertambah. Omset berkurang {$nilaiReturn}.");
    }

    /** Router store */
    public function store(Request $request)
    {
        if ($request->jenis === 'return') {
            return $this->storeReturn($request);
        }
        return $this->storeRusak($request);
    }

    /** Hapus catatan — kembalikan efek */
    public function destroy(int $id)
    {
        DB::transaction(function () use ($id) {
            $br = BarangRusak::findOrFail($id);

            if ($br->jenis === 'rusak') {
                // Kembalikan stok yang dikurangi
                Produk::where('id', $br->produk_id)
                    ->increment('stok', $br->jumlah);
            } else {
                // Return: kurangi stok yang sudah ditambahkan
                Produk::where('id', $br->produk_id)
                    ->decrement('stok', $br->jumlah);
            }

            $br->delete();
        });

        return redirect()->route('rusak.index')
            ->with('danger', 'Catatan dihapus. Stok dikembalikan ke kondisi semula.');
    }
}
