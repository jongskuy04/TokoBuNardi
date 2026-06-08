@extends('layouts.app')
@section('title', 'Barang Keluar')

@section('content')
@php use App\Helpers\InvenHelper; @endphp

<div class="page-header">
    <div>
        <h1><i class="fas fa-arrow-up" style="color:#f59e0b;margin-right:8px"></i>Barang Keluar</h1>
        <div class="breadcrumb">Inventaris › Barang Keluar</div>
    </div>
    <a href="{{ route('keluar.create') }}" class="btn btn-warning">
        <i class="fas fa-plus"></i> Catat Barang Keluar
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
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="label">Total Transaksi</div>
            <div class="value" style="color:#854d0e">{{ $rows->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="label">Total Unit Keluar</div>
            <div class="value" style="color:#854d0e">{{ number_format($totalUnit) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-sack-dollar"></i></div>
        <div>
            <div class="label">Total Pendapatan</div>
            <div class="value" style="color:#166534;font-size:16px">{{ InvenHelper::rupiah($totalNilai) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Barang Keluar — {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</h3>
    </div>
    <div class="table-wrapper">
        @if($rows->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Kode</th><th>Tanggal</th><th>Produk</th>
                    <th style="text-align:center">Jumlah</th><th>Harga Jual</th><th>Total</th>
                    <th>Pembeli</th><th>Keterangan</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $r)
                <tr>
                    <td style="color:#94a3b8">{{ $i + 1 }}</td>
                    <td><span class="badge badge-warning">{{ $r->kode_transaksi }}</span></td>
                    <td>{{ $r->tanggal->format('d/m/Y') }}</td>
                    <td>
                        <div style="font-weight:600">{{ $r->produk->nama_produk ?? '-' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $r->produk->kode_produk ?? '' }}</div>
                    </td>
                    <td style="text-align:center;font-weight:700;color:#dc2626">-{{ $r->jumlah }}</td>
                    <td>{{ InvenHelper::rupiah($r->harga_jual) }}</td>
                    <td style="font-weight:600;color:#166534">{{ InvenHelper::rupiah($r->jumlah * $r->harga_jual) }}</td>
                    <td>{{ $r->pembeli ?: '-' }}</td>
                    <td style="color:#64748b">{{ $r->keterangan ?: '-' }}</td>
                    <td>
                        <form id="del-bk-{{ $r->id }}" action="{{ route('keluar.destroy', $r->id) }}" method="POST" style="display:none">
                            @csrf @method('DELETE')
                        </form>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="confirmDelete('del-bk-{{ $r->id }}', 'Hapus catatan ini? Stok akan dikembalikan.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f8fafc;font-weight:700">
                    <td colspan="4" style="text-align:right;padding:12px 14px">Total:</td>
                    <td style="text-align:center;color:#dc2626">-{{ number_format($totalUnit) }}</td>
                    <td></td>
                    <td style="color:#166534">{{ InvenHelper::rupiah($totalNilai) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-box"></i>
            <p>Belum ada catatan barang keluar pada periode ini.</p>
            <a href="{{ route('keluar.create') }}" class="btn btn-warning" style="margin-top:14px">
                <i class="fas fa-plus"></i> Catat Sekarang
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
