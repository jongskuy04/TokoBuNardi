@extends('layouts.app')
@section('title', 'Barang Masuk')

@section('content')
@php use App\Helpers\InvenHelper; @endphp

<div class="page-header">
    <div>
        <h1><i class="fas fa-arrow-down" style="color:#10b981;margin-right:8px"></i>Barang Masuk</h1>
        <div class="breadcrumb">Inventaris › Barang Masuk</div>
    </div>
    <a href="{{ route('masuk.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Catat Barang Masuk
    </a>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <label style="font-size:13px;font-weight:600;margin:0">Filter Periode:</label>
            <select name="bln" style="width:auto">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ str_pad($m,2,'0',STR_PAD_LEFT) }}" {{ $bulan == str_pad($m,2,'0',STR_PAD_LEFT) ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                </option>
                @endfor
            </select>
            <input type="number" name="thn" value="{{ $tahun }}" style="width:90px">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
</div>

{{-- Summary --}}
<div class="stats-grid" style="margin-bottom:16px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-boxes-stacked"></i></div>
        <div><div class="label">Total Transaksi</div><div class="value" style="color:#166534">{{ $rows->count() }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-arrow-down"></i></div>
        <div><div class="label">Total Unit Masuk</div><div class="value" style="color:#166534">{{ number_format($totalUnit) }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#ede9fe;color:#6d28d9"><i class="fas fa-wallet"></i></div>
        <div><div class="label">Total Nilai Pembelian</div><div class="value" style="color:#6d28d9;font-size:16px">{{ InvenHelper::rupiah($totalNilai) }}</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Barang Masuk — {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</h3>
    </div>
    <div class="table-wrapper">
        @if($rows->isNotEmpty())
        <table>
            <thead>
                <tr><th>#</th><th>Kode</th><th>Tanggal</th><th>Produk</th><th style="text-align:center">Jumlah</th><th>Harga Beli</th><th>Total</th><th>Supplier</th><th>Keterangan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $r)
                <tr>
                    <td style="color:#94a3b8">{{ $i + 1 }}</td>
                    <td><span class="badge badge-success">{{ $r->kode_transaksi }}</span></td>
                    <td>{{ $r->tanggal->format('d/m/Y') }}</td>
                    <td>
                        <div style="font-weight:600">{{ $r->produk->nama_produk ?? '-' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $r->produk->kode_produk ?? '' }}</div>
                    </td>
                    <td style="text-align:center;font-weight:700;color:#166534">+{{ $r->jumlah }}</td>
                    <td>{{ InvenHelper::rupiah($r->harga_beli) }}</td>
                    <td style="font-weight:600">{{ InvenHelper::rupiah($r->jumlah * $r->harga_beli) }}</td>
                    <td>{{ $r->supplier ?: '-' }}</td>
                    <td style="color:#64748b">{{ $r->keterangan ?: '-' }}</td>
                    <td>
                        <form id="del-bm-{{ $r->id }}" action="{{ route('masuk.destroy', $r->id) }}" method="POST" style="display:none">
                            @csrf @method('DELETE')
                        </form>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="confirmDelete('del-bm-{{ $r->id }}', 'Hapus catatan ini? Stok akan dikembalikan.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f8fafc;font-weight:700">
                    <td colspan="4" style="text-align:right;padding:12px 14px">Total:</td>
                    <td style="text-align:center;color:#166534">+{{ number_format($totalUnit) }}</td>
                    <td></td>
                    <td>{{ InvenHelper::rupiah($totalNilai) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>Belum ada catatan barang masuk pada periode ini.</p>
            <a href="{{ route('masuk.create') }}" class="btn btn-success" style="margin-top:14px">
                <i class="fas fa-plus"></i> Catat Sekarang
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
