@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php use App\Helpers\InvenHelper; @endphp

<div class="page-header">
    <div>
        <h1><i class="fas fa-chart-pie" style="color:#1e40af;margin-right:8px"></i>Dashboard</h1>
        <div class="breadcrumb">Selamat datang di Sistem Manajemen Inventaris {{ config('app.name') }}</div>
    </div>
    <div style="font-size:13px;color:#64748b;background:#fff;padding:8px 14px;border-radius:8px;border:1px solid #e2e8f0;">
        <i class="fas fa-calendar"></i> {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#1e40af"><i class="fas fa-box-open"></i></div>
        <div>
            <div class="label">Total Produk</div>
            <div class="value" style="color:#1e40af">{{ $totalProduk }}</div>
            <div class="sub">{{ $totalKategori }} kategori</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-arrow-down"></i></div>
        <div>
            <div class="label">Barang Masuk (Bulan Ini)</div>
            <div class="value" style="color:#166534">{{ number_format($totalMasuk) }}</div>
            <div class="sub">unit diterima</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="label">Barang Keluar (Bulan Ini)</div>
            <div class="value" style="color:#854d0e">{{ number_format($totalKeluar) }}</div>
            <div class="sub">unit terjual</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#dc2626"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
            <div class="label">Barang Rusak (Bulan Ini)</div>
            <div class="value" style="color:#dc2626">{{ number_format($totalRusak) }}</div>
            <div class="sub">unit rusak</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;color:#be185d"><i class="fas fa-rotate-left"></i></div>
        <div>
            <div class="label">Retur (Bulan Ini)</div>
            <div class="value" style="color:#be185d">{{ number_format($totalReturn) }}</div>
            <div class="sub">unit dikembalikan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#991b1b"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
            <div class="label">Stok Kritis</div>
            <div class="value" style="color:#991b1b">{{ $stokKritis }}</div>
            <div class="sub">produk perlu restock</div>
        </div>
    </div>
    <div class="stat-card" style="border:2px solid #1e40af">
        <div class="stat-icon" style="background:#dbeafe;color:#1e40af"><i class="fas fa-sack-dollar"></i></div>
        <div>
            <div class="label" style="color:#1e40af;font-weight:800">Omset Bersih (Bulan Ini)</div>
            <div class="value" style="color:#1e40af;font-size:15px">{{ InvenHelper::rupiah($omsetBersih) }}</div>
            <div class="sub">setelah dikurangi retur</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#1e40af"><i class="fas fa-couch"></i></div>
        <div>
            <div class="label">Aset Toko</div>
            <div class="value">{{ number_format($totalAset) }}</div>
            <div class="sub">
                @if($asetPerluPerhatian > 0)
                    <span style="color:#dc2626">{{ $asetPerluPerhatian }} perlu perhatian</span>
                @else
                    semua kondisi baik
                @endif
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#ede9fe;color:#6d28d9"><i class="fas fa-warehouse"></i></div>
        <div>
            <div class="label">Nilai Inventaris</div>
            <div class="value" style="color:#6d28d9;font-size:15px">{{ InvenHelper::rupiah($nilaiInventaris) }}</div>
            <div class="sub">harga jual × stok saat ini</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
    {{-- Stok Kritis --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-triangle-exclamation" style="color:#ef4444"></i> Stok Kritis / Menipis</h3>
            <a href="{{ route('produk.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrapper">
            @if($produkKritis->isNotEmpty())
            <table>
                <thead>
                    <tr><th>Produk</th><th>Stok</th><th>Min.</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($produkKritis as $p)
                    <tr>
                        <td>
                            <div style="font-weight:600">{{ $p->nama_produk }}</div>
                            <div style="font-size:11px;color:#64748b">{{ $p->kategori->nama_kategori ?? '-' }}</div>
                        </td>
                        <td class="stok-kritis">{{ $p->stok }}</td>
                        <td style="color:#64748b">{{ $p->stok_minimum }}</td>
                        <td>
                            @if($p->stok == 0)
                                <span class="badge badge-danger">Habis</span>
                            @else
                                <span class="badge badge-warning">Kritis</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <i class="fas fa-check-circle" style="color:#10b981"></i>
                <p>Semua stok dalam kondisi aman</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clock-rotate-left" style="color:#3b82f6"></i> Aktivitas Terbaru</h3>
        </div>
        <div style="padding:8px 0">
            @foreach($aktivitas as $a)
            @php
                $isMasuk  = $a['tipe'] === 'masuk';
                $isRusak  = $a['tipe'] === 'rusak';
                $isReturn = $a['tipe'] === 'return';
            @endphp
            <div style="display:flex;align-items:flex-start;gap:12px;padding:10px 16px;border-bottom:1px solid #f1f5f9">
                @php
                    $bg    = $isMasuk ? '#dcfce7' : ($isRusak ? '#fee2e2' : ($isReturn ? '#fef9c3' : '#fef9c3'));
                    $color = $isMasuk ? '#166534' : ($isRusak ? '#991b1b' : ($isReturn ? '#854d0e' : '#854d0e'));
                    $icon  = $isMasuk ? 'arrow-down' : ($isRusak ? 'triangle-exclamation' : ($isReturn ? 'rotate-left' : 'arrow-up'));
                    $label = $isMasuk ? 'Masuk' : ($isRusak ? 'Rusak' : ($isReturn ? 'Retur' : 'Keluar'));
                @endphp
                <div style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;
                     background:{{ $bg }};color:{{ $color }};font-size:14px;flex-shrink:0">
                    <i class="fas fa-{{ $icon }}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:600;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $a['nama_produk'] }}
                    </div>
                    <div style="font-size:11.5px;color:#64748b">
                        {{ $label }} {{ $a['jumlah'] }} pcs &bull;
                        {{ \Carbon\Carbon::parse($a['tanggal'])->format('d/m/Y') }}
                    </div>
                    <div style="font-size:11px;color:#94a3b8">{{ $a['kode_transaksi'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Quick Links --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-bolt" style="color:#f59e0b"></i> Aksi Cepat</h3>
    </div>
    <div class="card-body">
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <a href="{{ route('produk.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</a>
            <a href="{{ route('masuk.create') }}"  class="btn btn-success"><i class="fas fa-arrow-down"></i> Catat Barang Masuk</a>
            <a href="{{ route('keluar.create') }}" class="btn btn-warning"><i class="fas fa-arrow-up"></i> Catat Barang Keluar</a>
            <a href="{{ route('rusak.create.rusak') }}" class="btn btn-danger"><i class="fas fa-triangle-exclamation"></i> Catat Rusak</a>
            <a href="{{ route('rusak.create.return') }}" class="btn btn-warning" style="background:#d97706;color:#fff"><i class="fas fa-rotate-left"></i> Catat Retur</a>
            <a href="{{ route('aset.index') }}" class="btn btn-outline" style="border-color:#1e40af;color:#1e40af"><i class="fas fa-couch"></i> Inventaris Aset</a>
            <a href="{{ route('laporan.index') }}" class="btn btn-outline"><i class="fas fa-file-chart-column"></i> Lihat Laporan</a>
        </div>
    </div>
</div>
@endsection
