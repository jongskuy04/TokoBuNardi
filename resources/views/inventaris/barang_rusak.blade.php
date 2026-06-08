@extends('layouts.app')

@section('title', 'Barang Rusak & Retur')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-triangle-exclamation" style="color:var(--danger)"></i> Barang Rusak & Retur</h1>
        <div class="breadcrumb">Inventaris › Barang Rusak & Retur</div>
    </div>
    <div class="btn-group">
        <a href="{{ route('rusak.create.rusak') }}" class="btn btn-danger">
            <i class="fas fa-triangle-exclamation"></i> Catat Rusak
        </a>
        <a href="{{ route('rusak.create.return') }}" class="btn btn-warning">
            <i class="fas fa-rotate-left"></i> Catat Retur
        </a>
    </div>
</div>

{{-- STATS --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr))">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:var(--danger)"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
            <div class="label">Barang Rusak</div>
            <div class="value" style="color:var(--danger)">{{ number_format($totalRusak) }}</div>
            <div class="sub">unit bulan ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-rotate-left"></i></div>
        <div>
            <div class="label">Retur</div>
            <div class="value" style="color:#854d0e">{{ number_format($totalReturn) }}</div>
            <div class="sub">unit bulan ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#991b1b"><i class="fas fa-chart-line"></i></div>
        <div>
            <div class="label">Omset Berkurang</div>
            <div class="value" style="color:#991b1b;font-size:15px">{{ \App\Helpers\InvenHelper::rupiah($totalNilaiReturn) }}</div>
            <div class="sub">dari retur bulan ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f3f4f6;color:#6b7280"><i class="fas fa-boxes-stacked"></i></div>
        <div>
            <div class="label">Total Dicatat</div>
            <div class="value">{{ number_format($totalUnit) }}</div>
            <div class="sub">unit (rusak + retur)</div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('danger'))
    <div class="alert alert-danger mb-4"><i class="fas fa-exclamation-circle"></i> {{ session('danger') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Catatan</h3>
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <select name="bln" class="form-control" style="width:auto">
                @foreach(range(1,12) as $m)
                    <option value="{{ str_pad($m,2,'0',STR_PAD_LEFT) }}" {{ $bulan == str_pad($m,2,'0',STR_PAD_LEFT) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                    </option>
                @endforeach
            </select>
            <input type="number" name="thn" value="{{ $tahun }}" class="form-control" style="width:85px" min="2020" max="2099">
            <select name="jenis" class="form-control" style="width:auto">
                <option value="" {{ $jenis === '' ? 'selected' : '' }}>Semua</option>
                <option value="rusak"  {{ $jenis === 'rusak'  ? 'selected' : '' }}>Rusak</option>
                <option value="return" {{ $jenis === 'return' ? 'selected' : '' }}>Retur</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Jenis</th>
                        <th style="text-align:center">Jumlah</th>
                        <th>Ref. Penjualan</th>
                        <th>Omset Berkurang</th>
                        <th>Pembeli / Ket.</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $r)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><code style="font-size:12px">{{ $r->kode_transaksi }}</code></td>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}</td>
                        <td>
                            <div style="font-weight:600">{{ $r->produk->nama_produk ?? '-' }}</div>
                            <div style="font-size:11px;color:var(--gray-500)">{{ $r->produk->kode_produk ?? '' }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $r->badgeJenis() }}">
                                <i class="fas {{ $r->jenis === 'rusak' ? 'fa-triangle-exclamation' : 'fa-rotate-left' }}"></i>
                                {{ $r->labelJenis() }}
                            </span>
                        </td>
                        <td style="text-align:center"><strong>{{ number_format($r->jumlah) }}</strong> <small>{{ $r->produk->satuan ?? '' }}</small></td>
                        <td>
                            @if($r->jenis === 'return' && $r->barangKeluar)
                                <div style="font-size:12px">
                                    <code>{{ $r->barangKeluar->kode_transaksi }}</code><br>
                                    <span style="color:var(--gray-500)">{{ \Carbon\Carbon::parse($r->barangKeluar->tanggal)->format('d/m/Y') }}</span>
                                </div>
                            @else
                                <span style="color:var(--gray-500)">—</span>
                            @endif
                        </td>
                        <td>
                            @if($r->jenis === 'return')
                                <span style="color:#991b1b;font-weight:700">
                                    −{{ \App\Helpers\InvenHelper::rupiah($r->nilaiReturn()) }}
                                </span>
                            @else
                                <span style="color:var(--gray-500)">—</span>
                            @endif
                        </td>
                        <td style="max-width:160px">
                            @if($r->sumber)<div style="font-size:13px">{{ $r->sumber }}</div>@endif
                            @if($r->keterangan)<div style="font-size:11px;color:var(--gray-500)">{{ Str::limit($r->keterangan,40) }}</div>@endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('rusak.destroy', $r->id) }}"
                                  onsubmit="return confirm('Hapus catatan ini?\n\nStok akan dikembalikan ke kondisi semula.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align:center;padding:32px;color:var(--gray-500)">
                            <i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:8px"></i>
                            Belum ada catatan untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($rows->isNotEmpty())
                <tfoot>
                    <tr style="background:var(--gray-100);font-weight:700">
                        <td colspan="5" style="padding:11px 14px">TOTAL</td>
                        <td style="text-align:center">{{ number_format($totalUnit) }}</td>
                        <td></td>
                        <td style="color:#991b1b">−{{ \App\Helpers\InvenHelper::rupiah($totalNilaiReturn) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<style>
.alert { padding:12px 16px; border-radius:8px; font-size:13px; display:flex; align-items:center; gap:8px; }
.alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.alert-danger  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.mb-4 { margin-bottom:16px; }
.form-control { padding:7px 10px; border:1.5px solid var(--gray-200); border-radius:7px; font-size:13px; outline:none; }
.form-control:focus { border-color:var(--primary); }
</style>
@endsection
