@extends('layouts.app')
@section('title', 'Inventaris Aset Toko')

@section('content')
@php use App\Helpers\InvenHelper; @endphp

<div class="page-header">
    <div>
        <h1><i class="fas fa-couch" style="color:var(--primary)"></i> Inventaris Aset Toko</h1>
        <div class="breadcrumb">Aset Toko › Daftar Aset</div>
    </div>
    <a href="{{ route('aset.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Aset
    </a>
</div>

{{-- STATS --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(170px,1fr));margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#1e40af"><i class="fas fa-boxes-stacked"></i></div>
        <div>
            <div class="label">Total Item</div>
            <div class="value">{{ number_format($totalItem) }}</div>
            <div class="sub">unit tercatat</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="label">Kondisi Baik</div>
            <div class="value" style="color:#166534">{{ $jumlahBaik }}</div>
            <div class="sub">jenis aset</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
            <div class="label">Perlu Perbaikan</div>
            <div class="value" style="color:#854d0e">{{ $jumlahPerlu }}</div>
            <div class="sub">jenis aset</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#991b1b"><i class="fas fa-circle-xmark"></i></div>
        <div>
            <div class="label">Rusak Berat</div>
            <div class="value" style="color:#991b1b">{{ $jumlahRusak }}</div>
            <div class="sub">jenis aset</div>
        </div>
    </div>
    <div class="stat-card" style="border:2px solid var(--primary)">
        <div class="stat-icon" style="background:#dbeafe;color:#1e40af"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="label" style="color:var(--primary);font-weight:800">Total Nilai Aset</div>
            <div class="value" style="color:var(--primary);font-size:15px">{{ InvenHelper::rupiah($totalNilai) }}</div>
            <div class="sub">estimasi harga perolehan</div>
        </div>
    </div>
</div>

{{-- FLASH --}}
@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('danger'))
    <div class="alert alert-danger mb-4"><i class="fas fa-exclamation-circle"></i> {{ session('danger') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Aset</h3>
        {{-- Filter --}}
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <input type="text" name="q" value="{{ $q }}" class="form-control" style="width:180px" placeholder="Cari nama / kode...">
            <select name="kondisi" class="form-control" style="width:auto">
                <option value="">Semua Kondisi</option>
                <option value="baik" {{ $kondisi==='baik' ? 'selected' : '' }}>Baik</option>
                <option value="perlu_perbaikan" {{ $kondisi==='perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                <option value="rusak_berat" {{ $kondisi==='rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
            <select name="kategori" class="form-control" style="width:auto">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" {{ $kategori===$kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
            @if($q || $kondisi || $kategori)
                <a href="{{ route('aset.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th style="text-align:center">Jumlah</th>
                        <th>Kondisi</th>
                        <th>Lokasi</th>
                        <th>Tgl Perolehan</th>
                        <th>Harga/unit</th>
                        <th>Total Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aset as $i => $a)
                    <tr>
                        <td style="color:var(--gray-500)">{{ $i + 1 }}</td>
                        <td><code style="font-size:12px;background:#f1f5f9;padding:2px 6px;border-radius:4px">{{ $a->kode_aset }}</code></td>
                        <td>
                            <div style="font-weight:700">{{ $a->nama_aset }}</div>
                            @if($a->keterangan)
                                <div style="font-size:11px;color:var(--gray-500)">{{ Str::limit($a->keterangan, 40) }}</div>
                            @endif
                        </td>
                        <td>{{ $a->kategori_aset ?: '—' }}</td>
                        <td style="text-align:center"><strong>{{ $a->jumlah }}</strong> {{ $a->satuan }}</td>
                        <td>
                            <span class="badge {{ $a->badgeKondisi() }}">
                                <i class="fas {{ $a->kondisi === 'baik' ? 'fa-circle-check' : ($a->kondisi === 'perlu_perbaikan' ? 'fa-triangle-exclamation' : 'fa-circle-xmark') }}"></i>
                                {{ $a->labelKondisi() }}
                            </span>
                        </td>
                        <td>{{ $a->lokasi ?: '—' }}</td>
                        <td style="white-space:nowrap">
                            {{ $a->tanggal_perolehan ? $a->tanggal_perolehan->format('d/m/Y') : '—' }}
                        </td>
                        <td style="white-space:nowrap">
                            {{ $a->harga_perolehan > 0 ? $a->harga_format : '—' }}
                        </td>
                        <td style="white-space:nowrap;font-weight:700;color:var(--primary)">
                            {{ $a->harga_perolehan > 0 ? $a->nilai_total_format : '—' }}
                        </td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('aset.edit', $a->id) }}" class="btn btn-outline btn-sm" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('aset.destroy', $a->id) }}"
                                      onsubmit="return confirm('Hapus aset {{ addslashes($a->nama_aset) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" style="text-align:center;padding:40px;color:var(--gray-500)">
                            <i class="fas fa-box-open" style="font-size:32px;display:block;margin-bottom:10px"></i>
                            Belum ada aset yang dicatat.
                            <br><a href="{{ route('aset.create') }}" style="color:var(--primary)">Tambah aset sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($aset->isNotEmpty())
                <tfoot>
                    <tr style="background:var(--gray-100);font-weight:700">
                        <td colspan="4" style="padding:11px 14px">TOTAL</td>
                        <td style="text-align:center">{{ number_format($totalItem) }}</td>
                        <td colspan="4"></td>
                        <td style="color:var(--primary)">{{ InvenHelper::rupiah($totalNilai) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<style>
.form-control { padding:7px 10px; border:1.5px solid var(--gray-200); border-radius:7px; font-size:13px; outline:none; background:#fff; }
.form-control:focus { border-color:var(--primary); }
.alert { padding:12px 16px; border-radius:8px; font-size:13px; display:flex; align-items:center; gap:8px; }
.alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.alert-danger  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.mb-4 { margin-bottom:16px; }
</style>
@endsection
