<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LokasiAset;
use App\Models\Aset;

class LokasiAsetController extends Controller
{
    public function index()
    {
        $list = LokasiAset::orderBy('nama_lokasi')->get();
        
        foreach ($list as $item) {
            $item->aset_count = Aset::where('lokasi', $item->nama_lokasi)->count();
        }
        
        return view('aset.lokasi', compact('list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasi_aset,nama_lokasi',
        ], [
            'nama_lokasi.required' => 'Nama lokasi wajib diisi.',
            'nama_lokasi.unique' => 'Nama lokasi sudah terdaftar.',
        ]);

        LokasiAset::create([
            'nama_lokasi' => $request->nama_lokasi,
        ]);

        return redirect()->route('aset.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $lokasi = LokasiAset::findOrFail($id);
        $list = LokasiAset::orderBy('nama_lokasi')->get();
        
        foreach ($list as $item) {
            $item->aset_count = Aset::where('lokasi', $item->nama_lokasi)->count();
        }
        
        return view('aset.lokasi', compact('list', 'lokasi'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasi_aset,nama_lokasi,' . $id,
        ], [
            'nama_lokasi.required' => 'Nama lokasi wajib diisi.',
            'nama_lokasi.unique' => 'Nama lokasi sudah terdaftar.',
        ]);

        $lokasi = LokasiAset::findOrFail($id);
        
        // Update references in the Aset table first to maintain consistency
        Aset::where('lokasi', $lokasi->nama_lokasi)->update([
            'lokasi' => $request->nama_lokasi
        ]);

        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
        ]);

        return redirect()->route('aset.lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $lokasi = LokasiAset::findOrFail($id);
        
        $asetCount = Aset::where('lokasi', $lokasi->nama_lokasi)->count();
        if ($asetCount > 0) {
            return redirect()->route('aset.lokasi.index')
                ->with('warning', "Lokasi tidak bisa dihapus karena masih digunakan oleh {$asetCount} aset toko.");
        }

        $lokasi->delete();
        return redirect()->route('aset.lokasi.index')
            ->with('danger', 'Lokasi berhasil dihapus.');
    }
}
