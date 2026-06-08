@extends('layouts.app')
@section('title', 'Data Produk')

@section('content')
@php use App\Helpers\InvenHelper; @endphp

<div class="page-header">
    <div>
        <h1><i class="fas fa-box-open" style="color:#1e40af;margin-right:8px"></i>Data Produk</h1>
        <div class="breadcrumb">Produk › Daftar Produk</div>
    </div>
    <a href="{{ route('produk.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Produk ({{ $produk->count() }} data)</h3>
        <form method="GET" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <select name="kat" onchange="this.form.submit()" style="width:auto;padding:7px 10px;font-size:13px">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $k)
                <option value="{{ $k->id }}" {{ request('kat') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}" style="width:220px">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
            @if(request('q') || request('kat'))
            <a href="{{ route('produk.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
    <div class="table-wrapper">
        @if($produk->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Kode</th><th>Nama Produk</th><th>Kategori</th>
                    <th>Satuan</th><th>Harga Beli</th><th>Harga Jual</th>
                    <th style="text-align:center">Stok</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produk as $i => $p)
                <tr>
                    <td style="color:#94a3b8">{{ $i + 1 }}</td>
                    <td><span class="badge badge-primary">{{ $p->kode_produk }}</span></td>
                    <td>
                        <div style="font-weight:600">{{ $p->nama_produk }}</div>
                        @if($p->deskripsi)
                        <div style="font-size:11px;color:#94a3b8">{{ Str::limit($p->deskripsi, 40) }}</div>
                        @endif
                    </td>
                    <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $p->satuan }}</td>
                    <td>{{ InvenHelper::rupiah($p->harga_beli) }}</td>
                    <td>{{ InvenHelper::rupiah($p->harga_jual) }}</td>
                    <td style="text-align:center">
                        <span class="{{ $p->stok <= $p->stok_minimum ? 'stok-kritis' : 'stok-aman' }}">{{ $p->stok }}</span>
                        <div style="font-size:10px;color:#94a3b8">min: {{ $p->stok_minimum }}</div>
                    </td>
                    <td>
                        @if($p->stok == 0)
                            <span class="badge badge-danger">Habis</span>
                        @elseif($p->stok <= $p->stok_minimum)
                            <span class="badge badge-warning">Kritis</span>
                        @else
                            <span class="badge badge-success">Aman</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('produk.edit', $p->id) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form id="del-produk-{{ $p->id }}" action="{{ route('produk.destroy', $p->id) }}" method="POST" style="display:none">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete('del-produk-{{ $p->id }}', 'Yakin hapus produk \'{{ addslashes($p->nama_produk) }}\'?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>Belum ada produk{{ request('q') ? ' untuk pencarian "'.request('q').'"' : '' }}.</p>
            <a href="{{ route('produk.create') }}" class="btn btn-primary" style="margin-top:14px">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
