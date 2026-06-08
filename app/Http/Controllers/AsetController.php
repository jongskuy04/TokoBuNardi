<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Helpers\InvenHelper;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $q        = $request->get('q', '');
        $kondisi  = $request->get('kondisi', '');

        $query = Aset::query();

        if ($q) {
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_aset', 'like', "%$q%")
                   ->orWhere('kode_aset', 'like', "%$q%")
                   ->orWhere('lokasi', 'like', "%$q%");
            });
        }
        if ($kondisi) $query->where('kondisi', $kondisi);

        $aset = $query->orderBy('nama_aset')->get();

        // Statistik
        $totalItem    = $aset->sum('jumlah');
        $totalNilai   = $aset->sum(fn($a) => $a->nilaiTotal());
        $jumlahBaik   = $aset->where('kondisi', 'baik')->count();
        $jumlahPerlu  = $aset->where('kondisi', 'perlu_perbaikan')->count();
        $jumlahRusak  = $aset->where('kondisi', 'rusak_berat')->count();

        return view('aset.index', compact(
            'aset', 'q', 'kondisi',
            'totalItem', 'totalNilai',
            'jumlahBaik', 'jumlahPerlu', 'jumlahRusak'
        ));
    }

    public function create()
    {
        $kode         = Aset::generateKode();
        $lokasiList   = Aset::select('lokasi')->distinct()->whereNotNull('lokasi')->pluck('lokasi');
        return view('aset.create', compact('kode', 'lokasiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_aset'  => 'required|string|unique:aset,kode_aset',
            'nama_aset'  => 'required|string|max:150',
            'jumlah'     => 'required|integer|min:1',
            'kondisi'    => 'required|in:baik,perlu_perbaikan,rusak_berat',
        ], [
            'kode_aset.unique' => 'Kode aset sudah digunakan.',
            'nama_aset.required' => 'Nama aset wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ]);

        Aset::create([
            'kode_aset'         => $request->kode_aset,
            'nama_aset'         => $request->nama_aset,
            'jumlah'            => $request->jumlah,
            'satuan'            => $request->satuan ?: 'unit',
            'harga_perolehan'   => $request->harga_perolehan ?: 0,
            'tanggal_perolehan' => $request->tanggal_perolehan ?: null,
            'kondisi'           => $request->kondisi,
            'lokasi'            => $request->lokasi ?: $request->lokasi_baru,
            'keterangan'        => $request->keterangan,
        ]);

        return redirect()->route('aset.index')
            ->with('success', "Aset \"{$request->nama_aset}\" berhasil ditambahkan.");
    }

    public function edit(int $id)
    {
        $aset         = Aset::findOrFail($id);
        $lokasiList   = Aset::select('lokasi')->distinct()->whereNotNull('lokasi')->pluck('lokasi');
        return view('aset.edit', compact('aset', 'lokasiList'));
    }

    public function update(Request $request, int $id)
    {
        $aset = Aset::findOrFail($id);

        $request->validate([
            'kode_aset'  => "required|string|unique:aset,kode_aset,{$id}",
            'nama_aset'  => 'required|string|max:150',
            'jumlah'     => 'required|integer|min:1',
            'kondisi'    => 'required|in:baik,perlu_perbaikan,rusak_berat',
        ]);

        $aset->update([
            'kode_aset'         => $request->kode_aset,
            'nama_aset'         => $request->nama_aset,
            'jumlah'            => $request->jumlah,
            'satuan'            => $request->satuan ?: 'unit',
            'harga_perolehan'   => $request->harga_perolehan ?: 0,
            'tanggal_perolehan' => $request->tanggal_perolehan ?: null,
            'kondisi'           => $request->kondisi,
            'lokasi'            => $request->lokasi ?: $request->lokasi_baru,
            'keterangan'        => $request->keterangan,
        ]);

        return redirect()->route('aset.index')
            ->with('success', "Aset \"{$aset->nama_aset}\" berhasil diperbarui.");
    }

    public function destroy(int $id)
    {
        $aset = Aset::findOrFail($id);
        $nama = $aset->nama_aset;
        $aset->delete();

        return redirect()->route('aset.index')
            ->with('danger', "Aset \"{$nama}\" berhasil dihapus.");
    }
}
