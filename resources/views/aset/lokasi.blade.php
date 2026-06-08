@extends('layouts.app')
@section('title', 'Kelola Lokasi Aset')

@section('content')

<div class="page-header">
    <div>
        <h1><i class="fas fa-location-dot" style="color:var(--primary);margin-right:8px"></i>Kelola Lokasi Aset</h1>
        <div class="breadcrumb">Aset Toko › Kelola Lokasi</div>
    </div>
    <a href="{{ route('aset.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali ke Aset
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start">
    {{-- Daftar --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-list"></i> Daftar Lokasi</h3></div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Lokasi</th>
                        <th style="text-align:center">Jumlah Aset Terkait</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $i => $l)
                    <tr>
                        <td style="color:#94a3b8">{{ $i + 1 }}</td>
                        <td style="font-weight:600">{{ $l->nama_lokasi }}</td>
                        <td style="text-align:center">
                            @if($l->aset_count > 0)
                                <span class="badge badge-primary">{{ $l->aset_count }} unit</span>
                            @else
                                <span class="badge badge-warning">0 unit</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('aset.lokasi.edit', $l->id) }}" class="btn btn-outline btn-sm" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @if($l->aset_count == 0)
                                    <form id="del-lok-{{ $l->id }}" action="{{ route('aset.lokasi.destroy', $l->id) }}" method="POST" style="display:none">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus"
                                        onclick="confirmDelete('del-lok-{{ $l->id }}', 'Hapus lokasi \'{{ addslashes($l->nama_lokasi) }}\'?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger btn-sm" style="opacity:0.4;cursor:not-allowed" 
                                            title="Tidak bisa dihapus karena sedang digunakan oleh aset" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:30px;color:var(--gray-500)">
                            Belum ada lokasi yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Form Tambah / Edit --}}
    <div class="card">
        <div class="card-header">
            <h3>
                <i class="fas fa-{{ isset($lokasi) ? 'pen' : 'plus' }}"></i>
                {{ isset($lokasi) ? 'Edit' : 'Tambah' }} Lokasi Aset
            </h3>
            @if(isset($lokasi))
            <a href="{{ route('aset.lokasi.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-times"></i></a>
            @endif
        </div>
        <div class="card-body">
            @if(isset($lokasi))
            <form method="POST" action="{{ route('aset.lokasi.update', $lokasi->id) }}">
                @csrf @method('PUT')
            @else
            <form method="POST" action="{{ route('aset.lokasi.store') }}">
                @csrf
            @endif
                <div class="form-group" style="margin-bottom:16px">
                    <label>Nama Lokasi <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_lokasi" required
                           value="{{ old('nama_lokasi', $lokasi->nama_lokasi ?? '') }}"
                           placeholder="Contoh: Gudang Belakang"
                           class="{{ $errors->has('nama_lokasi') ? 'is-invalid' : '' }}">
                    @error('nama_lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">
                    <i class="fas fa-save"></i> {{ isset($lokasi) ? 'Simpan Perubahan' : 'Tambah Lokasi' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
