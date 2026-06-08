<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $list = Kategori::withCount('produk')->orderBy('nama_kategori')->get();
        return view('kategori.index', compact('list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ], ['nama_kategori.required' => 'Nama kategori wajib diisi.']);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $kategori = Kategori::findOrFail($id);
        $list     = Kategori::withCount('produk')->orderBy('nama_kategori')->get();
        return view('kategori.index', compact('list', 'kategori'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        Kategori::findOrFail($id)->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $kategori = Kategori::withCount('produk')->findOrFail($id);

        if ($kategori->produk_count > 0) {
            return redirect()->route('kategori.index')
                ->with('warning', "Kategori tidak bisa dihapus karena masih digunakan oleh {$kategori->produk_count} produk.");
        }

        $kategori->delete();
        return redirect()->route('kategori.index')
            ->with('danger', 'Kategori berhasil dihapus.');
    }
}
