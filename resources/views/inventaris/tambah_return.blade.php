@extends('layouts.app')

@section('title', 'Catat Return Barang')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-rotate-left" style="color:#854d0e"></i> Catat Return Barang</h1>
        <div class="breadcrumb">Inventaris › Rusak & Return › Catat Return</div>
    </div>
    <a href="{{ route('rusak.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="info-box mb-4">
    <i class="fas fa-circle-info"></i>
    <div>
        <strong>Return Barang</strong> — Pilih transaksi penjualan yang ingin di-return.
        Stok akan <strong>bertambah</strong> kembali dan omset akan <strong>berkurang</strong> sesuai harga jual saat transaksi.
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">

    {{-- Kiri: Form --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-file-pen"></i> Form Return</h3>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('rusak.store') }}" id="formReturn">
                @csrf
                <input type="hidden" name="jenis" value="return">
                <input type="hidden" name="barang_keluar_id" id="input_bk_id" value="{{ old('barang_keluar_id') }}">

                <div class="form-group">
                    <label>Kode Transaksi Return</label>
                    <input type="text" name="kode_transaksi" class="form-control"
                           value="{{ old('kode_transaksi', $kode) }}" required>
                    @error('kode_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Info transaksi yang dipilih --}}
                <div class="form-group" id="selected-info" style="{{ old('barang_keluar_id') ? '' : 'display:none' }}">
                    <label>Transaksi yang Di-return</label>
                    <div class="selected-trx" id="selected-trx-display">
                        @if(old('barang_keluar_id'))
                            @php $bkOld = $barangKeluar->firstWhere('id', old('barang_keluar_id')); @endphp
                            @if($bkOld)
                                <div class="trx-name">{{ $bkOld->produk->nama_produk }}</div>
                                <div class="trx-meta">
                                    {{ $bkOld->kode_transaksi }} &bull;
                                    {{ \Carbon\Carbon::parse($bkOld->tanggal)->format('d/m/Y') }} &bull;
                                    Pembeli: {{ $bkOld->pembeli ?: '-' }}
                                </div>
                                <div class="trx-meta">
                                    Harga jual: <strong>{{ \App\Helpers\InvenHelper::rupiah($bkOld->harga_jual) }}</strong>/unit &bull;
                                    Sisa bisa return: <strong>{{ $bkOld->sisa_return }} unit</strong>
                                </div>
                            @endif
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline btn-sm mt-1" onclick="clearSelected()">
                        <i class="fas fa-times"></i> Ganti Transaksi
                    </button>
                </div>

                <div class="form-group" id="group-jumlah" style="{{ old('barang_keluar_id') ? '' : 'display:none' }}">
                    <label>Jumlah Return <span class="required">*</span></label>
                    <input type="number" name="jumlah" id="input_jumlah" class="form-control {{ $errors->has('jumlah') ? 'is-invalid' : '' }}"
                           value="{{ old('jumlah', 1) }}" min="1" required>
                    <div class="hint" id="hint-maks"></div>
                    @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" id="group-tanggal" style="{{ old('barang_keluar_id') ? '' : 'display:none' }}">
                    <label>Tanggal Return <span class="required">*</span></label>
                    <input type="date" name="tanggal" class="form-control {{ $errors->has('tanggal') ? 'is-invalid' : '' }}"
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" id="group-sumber" style="{{ old('barang_keluar_id') ? '' : 'display:none' }}">
                    <label>Nama Pembeli <small style="color:var(--gray-500)">(opsional, otomatis dari transaksi)</small></label>
                    <input type="text" name="sumber" class="form-control" id="input_sumber"
                           value="{{ old('sumber') }}" placeholder="Nama pembeli yang return">
                </div>

                <div class="form-group" id="group-ket" style="{{ old('barang_keluar_id') ? '' : 'display:none' }}">
                    <label>Alasan Return <small style="color:var(--gray-500)">(opsional)</small></label>
                    <textarea name="keterangan" class="form-control" rows="2"
                              placeholder="Contoh: Barang tidak sesuai pesanan">{{ old('keterangan') }}</textarea>
                </div>

                <div id="group-submit" style="{{ old('barang_keluar_id') ? '' : 'display:none' }}">
                    {{-- Preview pengurangan omset --}}
                    <div class="omset-preview" id="omset-preview"></div>

                    <div style="display:flex;gap:10px;margin-top:12px">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-rotate-left"></i> Proses Return
                        </button>
                        <a href="{{ route('rusak.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </div>

                <div id="placeholder-pilih" style="{{ old('barang_keluar_id') ? 'display:none' : '' }}">
                    <div style="text-align:center;padding:24px;color:var(--gray-500)">
                        <i class="fas fa-hand-pointer" style="font-size:28px;margin-bottom:8px;display:block"></i>
                        Pilih transaksi penjualan di sebelah kanan
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Kanan: Daftar transaksi penjualan --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Pilih Transaksi Penjualan</h3>
            <input type="text" id="search-trx" class="form-control" style="width:200px"
                   placeholder="Cari produk / kode..." oninput="filterTrx()">
        </div>
        <div style="max-height:520px;overflow-y:auto">
            @forelse($barangKeluar as $bk)
            <div class="trx-item {{ old('barang_keluar_id') == $bk->id ? 'trx-selected' : '' }}"
                 data-id="{{ $bk->id }}"
                 data-nama="{{ $bk->produk->nama_produk ?? '-' }}"
                 data-kode="{{ $bk->kode_transaksi }}"
                 data-tanggal="{{ \Carbon\Carbon::parse($bk->tanggal)->format('d/m/Y') }}"
                 data-pembeli="{{ $bk->pembeli ?: '-' }}"
                 data-harga="{{ $bk->harga_jual }}"
                 data-harga-format="{{ \App\Helpers\InvenHelper::rupiah($bk->harga_jual) }}"
                 data-sisa="{{ $bk->sisa_return }}"
                 onclick="selectTrx(this)">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px">
                    <div>
                        <div class="trx-name">{{ $bk->produk->nama_produk ?? '-' }}</div>
                        <div class="trx-meta">
                            <code>{{ $bk->kode_transaksi }}</code> &bull;
                            {{ \Carbon\Carbon::parse($bk->tanggal)->format('d/m/Y') }}
                        </div>
                        <div class="trx-meta">
                            Pembeli: {{ $bk->pembeli ?: '-' }} &bull;
                            {{ \App\Helpers\InvenHelper::rupiah($bk->harga_jual) }}/unit
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <div style="font-size:11px;color:var(--gray-500)">Terjual</div>
                        <div style="font-weight:800;font-size:16px">{{ $bk->jumlah }}</div>
                        <div style="font-size:10px;color:#166534;background:#dcfce7;padding:2px 6px;border-radius:4px;margin-top:2px">
                            Sisa return: {{ $bk->sisa_return }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:32px;text-align:center;color:var(--gray-500)">
                <i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:8px"></i>
                Tidak ada transaksi penjualan yang bisa di-return.
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.form-group { margin-bottom:16px; }
label { display:block; font-size:13px; font-weight:600; color:var(--gray-700); margin-bottom:5px; }
.form-control { width:100%; padding:9px 12px; border:1.5px solid var(--gray-200); border-radius:8px; font-size:13.5px; color:var(--gray-900); outline:none; font-family:inherit; transition:border-color .2s; }
.form-control:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(30,64,175,.1); }
.form-control.is-invalid { border-color:var(--danger); }
.invalid-feedback { color:var(--danger); font-size:12px; margin-top:4px; }
.required { color:var(--danger); }
.mb-4 { margin-bottom:16px; }
.mt-1 { margin-top:6px; }
.alert { padding:12px 16px; border-radius:8px; font-size:13px; display:flex; align-items:flex-start; gap:8px; }
.alert-danger { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.info-box { background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:14px 16px; font-size:13px; color:#854d0e; display:flex; align-items:flex-start; gap:10px; }

.trx-item {
    padding:14px 16px; border-bottom:1px solid var(--gray-200);
    cursor:pointer; transition:background .15s;
}
.trx-item:hover { background:#fffbeb; }
.trx-item.trx-selected { background:#fffbeb; border-left:3px solid #f59e0b; }
.trx-name { font-weight:700; font-size:14px; color:var(--gray-900); }
.trx-meta { font-size:12px; color:var(--gray-500); margin-top:2px; }

.selected-trx { background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:12px 14px; }
.hint { font-size:12px; color:var(--gray-500); margin-top:4px; }

.omset-preview {
    background:#fee2e2; border:1px solid #fecaca; border-radius:8px;
    padding:12px 14px; font-size:13px; color:#991b1b;
}
</style>

<script>
// Data semua transaksi keluar
const trxData = @json($barangKeluar->values());

function selectTrx(el) {
    // Highlight
    document.querySelectorAll('.trx-item').forEach(e => e.classList.remove('trx-selected'));
    el.classList.add('trx-selected');

    const id      = el.dataset.id;
    const nama    = el.dataset.nama;
    const kode    = el.dataset.kode;
    const tgl     = el.dataset.tanggal;
    const pembeli = el.dataset.pembeli;
    const harga   = parseFloat(el.dataset.harga);
    const hargaFmt = el.dataset.hargaFormat;
    const sisa    = parseInt(el.dataset.sisa);

    // Set hidden input
    document.getElementById('input_bk_id').value = id;

    // Tampilkan info
    document.getElementById('selected-trx-display').innerHTML = `
        <div class="trx-name">${nama}</div>
        <div class="trx-meta">${kode} &bull; ${tgl} &bull; Pembeli: ${pembeli}</div>
        <div class="trx-meta">Harga jual: <strong>${hargaFmt}</strong>/unit &bull; Sisa bisa return: <strong>${sisa} unit</strong></div>
    `;

    // Pre-fill pembeli
    document.getElementById('input_sumber').value = pembeli !== '-' ? pembeli : '';

    // Set max jumlah
    const inputJml = document.getElementById('input_jumlah');
    inputJml.max   = sisa;
    inputJml.value = 1;
    document.getElementById('hint-maks').textContent = `Maksimal ${sisa} unit`;

    // Update omset preview
    updateOmsetPreview(harga, 1, hargaFmt);
    inputJml.addEventListener('input', () => {
        updateOmsetPreview(harga, parseInt(inputJml.value) || 0, hargaFmt);
    });

    // Show/hide elemen
    ['selected-info','group-jumlah','group-tanggal','group-sumber','group-ket','group-submit']
        .forEach(id => document.getElementById(id).style.display = '');
    document.getElementById('placeholder-pilih').style.display = 'none';
}

function updateOmsetPreview(harga, jumlah, hargaFmt) {
    const total = harga * jumlah;
    const fmt   = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('omset-preview').innerHTML =
        `<i class="fas fa-chart-line-down"></i> Omset akan berkurang: <strong>${fmt}</strong> (${jumlah} unit × ${hargaFmt})`;
}

function clearSelected() {
    document.getElementById('input_bk_id').value = '';
    document.querySelectorAll('.trx-item').forEach(e => e.classList.remove('trx-selected'));
    ['selected-info','group-jumlah','group-tanggal','group-sumber','group-ket','group-submit']
        .forEach(id => document.getElementById(id).style.display = 'none');
    document.getElementById('placeholder-pilih').style.display = '';
}

function filterTrx() {
    const q = document.getElementById('search-trx').value.toLowerCase();
    document.querySelectorAll('.trx-item').forEach(el => {
        const text = (el.dataset.nama + el.dataset.kode + el.dataset.pembeli).toLowerCase();
        el.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>
@endsection
