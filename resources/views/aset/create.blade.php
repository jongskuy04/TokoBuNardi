@extends('layouts.app')
@section('title', 'Tambah Aset Toko')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-plus-circle" style="color:var(--primary)"></i> Tambah Aset Toko</h1>
        <div class="breadcrumb">Aset Toko › Tambah Aset</div>
    </div>
    <a href="{{ route('aset.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

<div class="card">
    <div class="card-header"><h3><i class="fas fa-file-pen"></i> Form Data Aset</h3></div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
        @endif

        <form method="POST" action="{{ route('aset.store') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label>Kode Aset <span class="req">*</span></label>
                    <input type="text" name="kode_aset" class="form-control {{ $errors->has('kode_aset') ? 'is-invalid' : '' }}"
                           value="{{ old('kode_aset', $kode) }}" required>
                    @error('kode_aset')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Nama Aset <span class="req">*</span></label>
                    <input type="text" name="nama_aset" class="form-control {{ $errors->has('nama_aset') ? 'is-invalid' : '' }}"
                           value="{{ old('nama_aset') }}" placeholder="Contoh: Etalase Kaca 1m" required>
                    @error('nama_aset')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr;gap:16px">
                <div class="form-group">
                    <label>Lokasi</label>
                    <select name="lokasi" class="form-control">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($lokasiList as $lok)
                            <option value="{{ $lok }}" {{ old('lokasi')===$lok ? 'selected' : '' }}>{{ $lok }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                <div class="form-group">
                    <label>Jumlah <span class="req">*</span></label>
                    <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" class="form-control" value="{{ old('satuan', 'unit') }}" placeholder="unit / buah / set">
                </div>
                <div class="form-group">
                    <label>Kondisi <span class="req">*</span></label>
                    <select name="kondisi" class="form-control {{ $errors->has('kondisi') ? 'is-invalid' : '' }}" required>
                        <option value="baik" {{ old('kondisi','baik')==='baik' ? 'selected' : '' }}>✅ Baik</option>
                        <option value="perlu_perbaikan" {{ old('kondisi')==='perlu_perbaikan' ? 'selected' : '' }}>⚠️ Perlu Perbaikan</option>
                        <option value="rusak_berat" {{ old('kondisi')==='rusak_berat' ? 'selected' : '' }}>❌ Rusak Berat</option>
                    </select>
                    @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label>Harga Perolehan / unit</label>
                    <div style="position:relative">
                        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--gray-500);font-size:13px">Rp</span>
                        <input type="number" name="harga_perolehan" class="form-control" style="padding-left:32px"
                               value="{{ old('harga_perolehan', 0) }}" min="0" placeholder="0">
                    </div>
                </div>
                <div class="form-group">
                    <label>Tanggal Perolehan</label>
                    <input type="date" name="tanggal_perolehan" class="form-control"
                           value="{{ old('tanggal_perolehan') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Keterangan <small style="color:var(--gray-500)">(opsional)</small></label>
                <textarea name="keterangan" class="form-control" rows="3"
                          placeholder="Misal: Dibeli dari Toko Meubel Jaya, kondisi second">{{ old('keterangan') }}</textarea>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Aset</button>
                <a href="{{ route('aset.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

<div>
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-circle-info" style="color:var(--primary)"></i> Info</h3></div>
        <div class="card-body" style="padding:14px 16px;font-size:12.5px;color:var(--gray-700);line-height:1.8">
            <div>• <strong>Harga Perolehan</strong> = harga beli per unit saat pertama kali diperoleh</div>
            <div>• <strong>Total Nilai</strong> dihitung otomatis: jumlah × harga/unit</div>
            <div>• Kondisi bisa diperbarui kapan saja lewat tombol <strong>Edit</strong></div>
        </div>
    </div>
</div>

</div><!-- end grid -->

<style>
.form-group { margin-bottom:16px; }
.mt-1 { margin-top:6px; }
label { display:block; font-size:13px; font-weight:600; color:var(--gray-700); margin-bottom:5px; }
.form-control { width:100%; padding:9px 12px; border:1.5px solid var(--gray-200); border-radius:8px; font-size:13.5px; color:var(--gray-900); outline:none; font-family:inherit; transition:border-color .2s; }
.form-control:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(30,64,175,.1); }
.form-control.is-invalid { border-color:var(--danger); }
.invalid-feedback { color:var(--danger); font-size:12px; margin-top:4px; }
.req { color:var(--danger); }
.alert { padding:12px 16px; border-radius:8px; font-size:13px; display:flex; align-items:flex-start; gap:8px; }
.alert-danger { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.mb-4 { margin-bottom:16px; }
</style>
@endsection
