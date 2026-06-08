@extends('layouts.app')
@section('title', 'Catat Barang Keluar')

@section('content')

<div class="page-header">
    <div>
        <h1><i class="fas fa-arrow-up" style="color:#f59e0b;margin-right:8px"></i>Catat Barang Keluar</h1>
        <div class="breadcrumb">Inventaris › Barang Keluar › Tambah</div>
    </div>
    <a href="{{ route('keluar.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-arrow-up"></i> Form Barang Keluar</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('keluar.store') }}">
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
                        <select name="produk_id" required id="produkSelect" onchange="updateInfo(this)"
                                class="{{ $errors->has('produk_id') ? 'is-invalid' : '' }}">
                            <option value="">-- Pilih Produk (hanya yang ada stok) --</option>
                            @foreach($produkList as $p)
                            <option value="{{ $p->id }}"
                                    data-stok="{{ $p->stok }}"
                                    data-satuan="{{ $p->satuan }}"
                                    data-harga="{{ $p->harga_jual }}"
                                    {{ old('produk_id') == $p->id ? 'selected' : '' }}>
                                [{{ $p->kode_produk }}] {{ $p->nama_produk }} (Stok: {{ $p->stok }} {{ $p->satuan }})
                            </option>
                            @endforeach
                        </select>
                        @error('produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Jumlah Keluar <span style="color:#ef4444">*</span></label>
                        <input type="number" name="jumlah" id="jumlahInput" min="1"
                               value="{{ old('jumlah') }}" placeholder="0" required
                               class="{{ $errors->has('jumlah') ? 'is-invalid' : '' }}">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Harga Jual per Unit (Rp)</label>
                        <input type="number" name="harga_jual" id="hargaInput" min="0" step="100"
                               value="{{ old('harga_jual', 0) }}">
                    </div>
                    <div class="form-group full">
                        <label>Pembeli</label>
                        <input type="text" name="pembeli"
                               placeholder="Nama pembeli / platform (Shopee, TikTok Shop, dll)"
                               value="{{ old('pembeli') }}">
                    </div>
                    <div class="form-group full">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="2"
                                  placeholder="Nomor order, keterangan tambahan, dll">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div style="margin-top:20px;display:flex;gap:10px">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan Barang Keluar
                    </button>
                    <a href="{{ route('keluar.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-circle-info"></i> Info Produk</h3></div>
        <div class="card-body">
            <div id="infoPanel" style="padding:12px;background:#fef9c3;border-radius:8px;border:1px solid #fde68a;font-size:13px;color:#854d0e">
                Pilih produk untuk melihat informasi stok
            </div>
            <div style="margin-top:16px;font-size:13px;color:#475569;line-height:1.8">
                <p><i class="fas fa-circle-check" style="color:#f59e0b"></i> Stok <strong>berkurang otomatis</strong></p>
                <p><i class="fas fa-circle-check" style="color:#f59e0b"></i> Hanya produk berstok ditampilkan</p>
                <p><i class="fas fa-circle-check" style="color:#f59e0b"></i> Sistem cegah penjualan melebihi stok</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    const stok  = opt.dataset.stok;
    const satuan = opt.dataset.satuan;
    const harga  = opt.dataset.harga;
    if (stok !== undefined) {
        document.getElementById('infoPanel').innerHTML =
            '<strong>Stok Tersedia:</strong> <span style="font-size:18px;font-weight:800">'
            + stok + '</span> ' + satuan
            + '<br><strong>Harga Jual:</strong> Rp '
            + parseInt(harga).toLocaleString('id-ID');
        document.getElementById('hargaInput').value = harga;
        document.getElementById('jumlahInput').max  = stok;
    }
}
// Restore info jika ada old value (validasi gagal)
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('produkSelect');
    if (sel && sel.value) updateInfo(sel);
});
</script>
@endpush
@endsection
