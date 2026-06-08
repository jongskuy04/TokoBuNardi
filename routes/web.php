<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AsetController;

// AUTH
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // PRODUK
    Route::prefix('produk')->name('produk.')->group(function () {
        Route::get('/',           [ProdukController::class, 'index'])->name('index');
        Route::get('/tambah',     [ProdukController::class, 'create'])->name('create');
        Route::post('/tambah',    [ProdukController::class, 'store'])->name('store');
        Route::get('/{id}/edit',  [ProdukController::class, 'edit'])->name('edit');
        Route::put('/{id}',       [ProdukController::class, 'update'])->name('update');
        Route::delete('/{id}',    [ProdukController::class, 'destroy'])->name('destroy');
    });

    // KATEGORI
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/',          [KategoriController::class, 'index'])->name('index');
        Route::post('/',         [KategoriController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [KategoriController::class, 'update'])->name('update');
        Route::delete('/{id}',   [KategoriController::class, 'destroy'])->name('destroy');
    });

    // BARANG MASUK
    Route::prefix('inventaris/masuk')->name('masuk.')->group(function () {
        Route::get('/',        [BarangMasukController::class, 'index'])->name('index');
        Route::get('/tambah',  [BarangMasukController::class, 'create'])->name('create');
        Route::post('/tambah', [BarangMasukController::class, 'store'])->name('store');
        Route::delete('/{id}', [BarangMasukController::class, 'destroy'])->name('destroy');
    });

    // BARANG KELUAR
    Route::prefix('inventaris/keluar')->name('keluar.')->group(function () {
        Route::get('/',        [BarangKeluarController::class, 'index'])->name('index');
        Route::get('/tambah',  [BarangKeluarController::class, 'create'])->name('create');
        Route::post('/tambah', [BarangKeluarController::class, 'store'])->name('store');
        Route::delete('/{id}', [BarangKeluarController::class, 'destroy'])->name('destroy');
    });

    // BARANG RUSAK & RETURN
    Route::prefix('inventaris/rusak')->name('rusak.')->group(function () {
        Route::get('/',              [BarangRusakController::class, 'index'])->name('index');
        // create pakai query ?jenis=rusak atau ?jenis=return
        Route::get('/tambah',        [BarangRusakController::class, 'create'])->name('create');
        Route::get('/tambah-rusak',  [BarangRusakController::class, 'createRusak'])->name('create.rusak');
        Route::get('/tambah-return', [BarangRusakController::class, 'createReturn'])->name('create.return');
        Route::post('/tambah',       [BarangRusakController::class, 'store'])->name('store');
        Route::delete('/{id}',       [BarangRusakController::class, 'destroy'])->name('destroy');
    });

    // LAPORAN
    Route::get('/inventaris/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // ASET TOKO
    Route::prefix('aset')->name('aset.')->group(function () {
        Route::get('/',          [AsetController::class, 'index'])->name('index');
        Route::get('/tambah',    [AsetController::class, 'create'])->name('create');
        Route::post('/tambah',   [AsetController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AsetController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [AsetController::class, 'update'])->name('update');
        Route::delete('/{id}',   [AsetController::class, 'destroy'])->name('destroy');
    });
});
