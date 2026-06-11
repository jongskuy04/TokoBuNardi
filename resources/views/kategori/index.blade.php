@extends('layouts.app')
@section('title', 'Kategori Produk')

@section('content')

<div class="page-header">
    <div>
        <h1><i class="fas fa-tags" style="color:#1e40af;margin-right:8px"></i>Kategori Produk</h1>
        <div class="breadcrumb">Produk › Kategori</div>
    </div>
    <div class="btn-group">
        @if(request()->query('back') === 'create')
            <a href="{{ route('produk.create') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali ke Tambah Produk
            </a>
        @elseif(request()->query('back') === 'edit' && request()->query('id'))
            <a href="{{ route('produk.edit', request()->query('id')) }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali ke Edit Produk
            </a>
        @else
            <a href="{{ route('produk.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali ke Produk
            </a>
        @endif
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start">
    {{-- Daftar --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-list"></i> Daftar Kategori</h3></div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th style="text-align:center">Jml Produk</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($list as $i => $k)
                    <tr>
                        <td style="color:#94a3b8">{{ $i + 1 }}</td>
                        <td style="font-weight:600">{{ $k->nama_kategori }}</td>
                        <td style="color:#64748b">{{ $k->deskripsi ?: '-' }}</td>
                        <td style="text-align:center"><span class="badge badge-primary">{{ $k->produk_count }}</span></td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('kategori.edit', [$k->id] + request()->only(['back', 'id'])) }}" class="btn btn-outline btn-sm">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form id="del-kat-{{ $k->id }}" action="{{ route('kategori.destroy', [$k->id] + request()->only(['back', 'id'])) }}" method="POST" style="display:none">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('del-kat-{{ $k->id }}', 'Hapus kategori \'{{ addslashes($k->nama_kategori) }}\'?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Form Tambah / Edit --}}
    <div class="card">
        <div class="card-header">
            <h3>
                <i class="fas fa-{{ isset($kategori) ? 'pen' : 'plus' }}"></i>
                {{ isset($kategori) ? 'Edit' : 'Tambah' }} Kategori
            </h3>
            @if(isset($kategori))
            <a href="{{ route('kategori.index', request()->only(['back', 'id'])) }}" class="btn btn-outline btn-sm"><i class="fas fa-times"></i></a>
            @endif
        </div>
        <div class="card-body">
            @if(isset($kategori))
            <form method="POST" action="{{ route('kategori.update', [$kategori->id] + request()->only(['back', 'id'])) }}">
                @csrf @method('PUT')
            @else
            <form method="POST" action="{{ route('kategori.store', request()->only(['back', 'id'])) }}">
                @csrf
            @endif
                <div class="form-group" style="margin-bottom:14px">
                    <label>Nama Kategori <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_kategori" required
                           value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}"
                           placeholder="Contoh: Peralatan Dapur"
                           class="{{ $errors->has('nama_kategori') ? 'is-invalid' : '' }}">
                    @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:16px">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Opsional">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">
                    <i class="fas fa-save"></i> {{ isset($kategori) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
