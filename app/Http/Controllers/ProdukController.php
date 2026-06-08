<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Helpers\InvenHelper;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori')->orderBy('nama_produk');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('nama_produk', 'like', "%$q%")
                   ->orWhere('kode_produk', 'like', "%$q%");
            });
        }

        if ($request->filled('kat')) {
            $query->where('kategori_id', $request->kat);
        }

        $produk      = $query->get();
        $kategoriList = Kategori::orderBy('nama_kategori')->get();

        return view('produk.index', compact('produk', 'kategoriList'));
    }

    public function create()
    {
        $kode         = InvenHelper::generateKodeProduk();
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        return view('produk.create', compact('kode', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk'  => 'required|string|max:20|unique:produk,kode_produk',
            'nama_produk'  => 'required|string|max:150',
            'harga_jual'   => 'required|numeric|min:1',
            'harga_beli'   => 'nullable|numeric|min:0',
            'stok'         => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
        ], [
            'kode_produk.unique' => 'Kode produk sudah digunakan.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_jual.min'     => 'Harga jual harus lebih dari 0.',
        ]);

        Produk::create([
            'kode_produk'  => $request->kode_produk,
            'nama_produk'  => $request->nama_produk,
            'kategori_id'  => $request->kategori_id ?: null,
            'satuan'       => $request->satuan ?? 'pcs',
            'harga_beli'   => $request->harga_beli ?? 0,
            'harga_jual'   => $request->harga_jual,
            'stok'         => $request->stok ?? 0,
            'stok_minimum' => $request->stok_minimum ?? 5,
            'deskripsi'    => $request->deskripsi,
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $produk       = Produk::findOrFail($id);
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        return view('produk.edit', compact('produk', 'kategoriList'));
    }

    public function update(Request $request, int $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'harga_jual'  => 'required|numeric|min:1',
            'harga_beli'  => 'nullable|numeric|min:0',
            'stok'        => 'nullable|integer|min:0',
            'stok_minimum'=> 'nullable|integer|min:0',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_jual.min'       => 'Harga jual harus lebih dari 0.',
        ]);

        $produk->update([
            'nama_produk'  => $request->nama_produk,
            'kategori_id'  => $request->kategori_id ?: null,
            'satuan'       => $request->satuan ?? 'pcs',
            'harga_beli'   => $request->harga_beli ?? 0,
            'harga_jual'   => $request->harga_jual,
            'stok'         => $request->stok ?? $produk->stok,
            'stok_minimum' => $request->stok_minimum ?? 5,
            'deskripsi'    => $request->deskripsi,
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Produk::findOrFail($id)->delete();
        return redirect()->route('produk.index')
            ->with('danger', 'Produk berhasil dihapus.');
    }
}
