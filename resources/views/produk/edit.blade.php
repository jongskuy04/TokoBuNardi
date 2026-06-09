@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')

<div class="page-header">
    <div>
        <h1><i class="fas fa-pen" style="color:#1e40af;margin-right:8px"></i>Edit Produk</h1>
        <div class="breadcrumb">Produk › Edit Produk</div>
    </div>
    <a href="{{ route('produk.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-box"></i> Form Edit Produk</h3>
        <span class="badge badge-primary">{{ $produk->kode_produk }}</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('produk.update', $produk->id) }}">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Produk</label>
                    <input type="text" value="{{ $produk->kode_produk }}" class="input-readonly" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Produk <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}"
                           class="{{ $errors->has('nama_produk') ? 'is-invalid' : '' }}" required>
                    @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                        <label style="margin:0">Kategori</label>
                        <a href="{{ route('kategori.index') }}" class="btn btn-outline btn-sm" style="padding:4px 8px;font-size:11.5px">
                            <i class="fas fa-tags"></i> Kelola Kategori
                        </a>
                    </div>
                    <select name="kategori_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id', $produk->kategori_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <select name="satuan">
                        @foreach(['pcs','lusin','kg','gram','liter','box','pack','set','roll'] as $s)
                        <option value="{{ $s }}" {{ old('satuan', $produk->satuan) === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga Beli (Rp)</label>
                    <input type="number" name="harga_beli" min="0" step="100" value="{{ old('harga_beli', $produk->harga_beli) }}">
                </div>
                <div class="form-group">
                    <label>Harga Jual (Rp) <span style="color:#ef4444">*</span></label>
                    <input type="number" name="harga_jual" min="0" step="100" value="{{ old('harga_jual', $produk->harga_jual) }}"
                           class="{{ $errors->has('harga_jual') ? 'is-invalid' : '' }}" required>
                    @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Stok Saat Ini</label>
                    <input type="number" name="stok" min="0" value="{{ old('stok', $produk->stok) }}">
                    <small style="color:#64748b;font-size:11px">⚠️ Sebaiknya ubah stok via Barang Masuk/Keluar untuk akurasi laporan</small>
                </div>
                <div class="form-group">
                    <label>Stok Minimum</label>
                    <input type="number" name="stok_minimum" min="0" value="{{ old('stok_minimum', $produk->stok_minimum) }}">
                </div>
                <div class="form-group full">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>
            </div>
            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('produk.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
