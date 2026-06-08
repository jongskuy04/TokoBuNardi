@extends('layouts.app')
@section('title', 'Catat Barang Masuk')

@section('content')

<div class="page-header">
    <div>
        <h1><i class="fas fa-arrow-down" style="color:#10b981;margin-right:8px"></i>Catat Barang Masuk</h1>
        <div class="breadcrumb">Inventaris › Barang Masuk › Tambah</div>
    </div>
    <a href="{{ route('masuk.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-arrow-down"></i> Form Barang Masuk</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('masuk.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Kode Transaksi</label>
                        <input type="text" name="kode_transaksi" value="{{ old('kode_transaksi', $kode) }}" required>
                        @error('kode_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group full">
                        <label>Produk <span style="color:#ef4444">*</span></label>
                        <select name="produk_id" required onchange="updateStokInfo(this)"
                                class="{{ $errors->has('produk_id') ? 'is-invalid' : '' }}">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produkList as $p)
                            <option value="{{ $p->id }}"
                                    data-stok="{{ $p->stok }}"
                                    data-satuan="{{ $p->satuan }}"
                                    {{ old('produk_id') == $p->id ? 'selected' : '' }}>
                                [{{ $p->kode_produk }}] {{ $p->nama_produk }} (Stok: {{ $p->stok }} {{ $p->satuan }})
                            </option>
                            @endforeach
                        </select>
                        @error('produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="stokInfo" style="font-size:12px;color:#10b981;margin-top:4px"></div>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Masuk <span style="color:#ef4444">*</span></label>
                        <input type="number" name="jumlah" min="1" value="{{ old('jumlah') }}" placeholder="0" required
                               class="{{ $errors->has('jumlah') ? 'is-invalid' : '' }}">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Harga Beli per Unit (Rp)</label>
                        <input type="number" name="harga_beli" min="0" step="100" value="{{ old('harga_beli', 0) }}">
                    </div>
                    <div class="form-group full">
                        <label>Supplier / Pemasok</label>
                        <input type="text" name="supplier" placeholder="Nama supplier" value="{{ old('supplier') }}">
                    </div>
                    <div class="form-group full">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="2" placeholder="Opsional">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div style="margin-top:20px;display:flex;gap:10px">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Barang Masuk</button>
                    <a href="{{ route('masuk.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3><i class="fas fa-circle-info"></i> Panduan</h3></div>
        <div class="card-body">
            <div style="font-size:13px;color:#475569;line-height:1.8">
                <p><i class="fas fa-circle-check" style="color:#10b981"></i> Pilih produk dari supplier</p>
                <p><i class="fas fa-circle-check" style="color:#10b981"></i> Isi jumlah unit yang diterima</p>
                <p><i class="fas fa-circle-check" style="color:#10b981"></i> Stok <strong>bertambah otomatis</strong></p>
                <p><i class="fas fa-circle-check" style="color:#10b981"></i> Harga beli diperbarui ke terbaru</p>
            </div>
            <div id="produkDetail" style="margin-top:16px;padding:12px;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;font-size:13px;color:#166534">
                Pilih produk untuk melihat stok saat ini
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStokInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.stok !== undefined) {
        document.getElementById('produkDetail').innerHTML =
            '<strong>Stok Sekarang:</strong> ' + opt.dataset.stok + ' ' + opt.dataset.satuan;
    }
}
</script>
@endpush
@endsection
