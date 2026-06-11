@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')

<div class="page-header">
    <div>
        <h1><i class="fas fa-plus-circle" style="color:#1e40af;margin-right:8px"></i>Tambah Produk</h1>
        <div class="breadcrumb">Produk › Tambah Produk</div>
    </div>
    <a href="{{ route('produk.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-box"></i> Form Tambah Produk</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('produk.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Produk <span style="color:#ef4444">*</span></label>
                    <input type="text" name="kode_produk" value="{{ old('kode_produk', $kode) }}"
                           class="{{ $errors->has('kode_produk') ? 'is-invalid' : '' }}" required>
                    @error('kode_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small style="color:#64748b;font-size:11px">Kode dibuat otomatis, bisa diubah</small>
                </div>
                <div class="form-group">
                    <label>Nama Produk <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_produk" placeholder="Contoh: Cobek Batu Ukuran Besar"
                           value="{{ old('nama_produk') }}"
                           class="{{ $errors->has('nama_produk') ? 'is-invalid' : '' }}" required>
                    @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="position: relative;">
                    <label>Kategori</label>
                    <a href="{{ route('kategori.index', ['back' => 'create']) }}" class="btn btn-outline btn-sm" style="position: absolute; right: 0; top: -6px; padding: 3px 8px; font-size: 11px; line-height: 1.2;">
                        <i class="fas fa-tags"></i> Kelola Kategori
                    </a>
                    <select name="kategori_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <select name="satuan">
                        @foreach(['pcs','lusin','kg','gram','liter','box','pack','set','roll'] as $s)
                        <option value="{{ $s }}" {{ old('satuan', 'pcs') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga Beli (Rp)</label>
                    <input type="number" name="harga_beli" min="0" step="100" value="{{ old('harga_beli', 0) }}">
                </div>
                <div class="form-group">
                    <label>Harga Jual (Rp) <span style="color:#ef4444">*</span></label>
                    <input type="number" name="harga_jual" min="0" step="100" value="{{ old('harga_jual', 0) }}"
                           class="{{ $errors->has('harga_jual') ? 'is-invalid' : '' }}" required>
                    @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" min="0" value="{{ old('stok', 0) }}">
                </div>
                <div class="form-group">
                    <label>Stok Minimum (Batas Kritis)</label>
                    <input type="number" name="stok_minimum" min="0" value="{{ old('stok_minimum', 5) }}">
                </div>
                <div class="form-group full">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi produk (opsional)">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Produk</button>
                <a href="{{ route('produk.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
