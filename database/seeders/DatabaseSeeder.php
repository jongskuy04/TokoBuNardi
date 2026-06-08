<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori
        DB::table('kategori')->insert([
            ['nama_kategori' => 'Peralatan Dapur',  'deskripsi' => 'Alat masak dan memasak'],
            ['nama_kategori' => 'Peralatan Makan',  'deskripsi' => 'Sendok, garpu, piring, mangkok'],
            ['nama_kategori' => 'Prabotan Logam',   'deskripsi' => 'Wajan, panci, dandang berbahan logam'],
            ['nama_kategori' => 'Prabotan Plastik', 'deskripsi' => 'Ember, baskom, toples berbahan plastik'],
            ['nama_kategori' => 'Bumbu & Alat Masak','deskripsi'=> 'Cobek, ulekan, parutan'],
        ]);

        // Produk
        DB::table('produk')->insert([
            ['kode_produk'=>'PRD-001','nama_produk'=>'Cobek Batu Ukuran Besar','kategori_id'=>5,'satuan'=>'pcs','harga_beli'=>35000,'harga_jual'=>55000,'stok'=>25,'stok_minimum'=>5,'deskripsi'=>'Cobek batu alam ukuran 30cm','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-002','nama_produk'=>'Cobek Batu Ukuran Kecil','kategori_id'=>5,'satuan'=>'pcs','harga_beli'=>20000,'harga_jual'=>35000,'stok'=>30,'stok_minimum'=>5,'deskripsi'=>'Cobek batu alam ukuran 20cm','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-003','nama_produk'=>'Sutil Stainless','kategori_id'=>1,'satuan'=>'pcs','harga_beli'=>8000,'harga_jual'=>15000,'stok'=>50,'stok_minimum'=>10,'deskripsi'=>'Sutil / spatula berbahan stainless steel','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-004','nama_produk'=>'Wajan Anti Lengket 28cm','kategori_id'=>3,'satuan'=>'pcs','harga_beli'=>65000,'harga_jual'=>95000,'stok'=>15,'stok_minimum'=>3,'deskripsi'=>'Wajan teflon anti lengket diameter 28cm','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-005','nama_produk'=>'Wajan Anti Lengket 24cm','kategori_id'=>3,'satuan'=>'pcs','harga_beli'=>55000,'harga_jual'=>80000,'stok'=>18,'stok_minimum'=>3,'deskripsi'=>'Wajan teflon anti lengket diameter 24cm','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-006','nama_produk'=>'Sendok Makan Stainless (lusin)','kategori_id'=>2,'satuan'=>'lusin','harga_beli'=>25000,'harga_jual'=>40000,'stok'=>20,'stok_minimum'=>5,'deskripsi'=>'Sendok makan stainless 1 lusin','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-007','nama_produk'=>'Garpu Makan Stainless (lusin)','kategori_id'=>2,'satuan'=>'lusin','harga_beli'=>22000,'harga_jual'=>38000,'stok'=>18,'stok_minimum'=>5,'deskripsi'=>'Garpu makan stainless 1 lusin','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-008','nama_produk'=>'Ember Plastik 20L','kategori_id'=>4,'satuan'=>'pcs','harga_beli'=>18000,'harga_jual'=>28000,'stok'=>35,'stok_minimum'=>8,'deskripsi'=>'Ember plastik kapasitas 20 liter','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-009','nama_produk'=>'Baskom Plastik Besar','kategori_id'=>4,'satuan'=>'pcs','harga_beli'=>12000,'harga_jual'=>22000,'stok'=>40,'stok_minimum'=>8,'deskripsi'=>'Baskom plastik ukuran besar','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-010','nama_produk'=>'Parutan Keju Stainless','kategori_id'=>5,'satuan'=>'pcs','harga_beli'=>15000,'harga_jual'=>25000,'stok'=>22,'stok_minimum'=>5,'deskripsi'=>'Parutan keju / kelapa stainless','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-011','nama_produk'=>'Panci Aluminium 26cm','kategori_id'=>3,'satuan'=>'pcs','harga_beli'=>45000,'harga_jual'=>70000,'stok'=>3,'stok_minimum'=>5,'deskripsi'=>'Panci aluminium diameter 26cm - STOK MENIPIS','created_at'=>now(),'updated_at'=>now()],
            ['kode_produk'=>'PRD-012','nama_produk'=>'Toples Plastik Bulat','kategori_id'=>4,'satuan'=>'pcs','harga_beli'=>10000,'harga_jual'=>18000,'stok'=>45,'stok_minimum'=>10,'deskripsi'=>'Toples plastik bulat tutup rapat','created_at'=>now(),'updated_at'=>now()],
        ]);

        // Barang Masuk
        DB::table('barang_masuk')->insert([
            ['kode_transaksi'=>'BM-202505-001','produk_id'=>1,'jumlah'=>10,'harga_beli'=>35000,'supplier'=>'Supplier Batu Alam Jaya','tanggal'=>'2025-05-01','keterangan'=>'Restock cobek batu besar'],
            ['kode_transaksi'=>'BM-202505-002','produk_id'=>3,'jumlah'=>30,'harga_beli'=>8000, 'supplier'=>'UD. Sumber Logam',       'tanggal'=>'2025-05-02','keterangan'=>'Restock sutil stainless'],
            ['kode_transaksi'=>'BM-202505-003','produk_id'=>4,'jumlah'=>8, 'harga_beli'=>65000,'supplier'=>'Toko Peralatan Dapur Maju','tanggal'=>'2025-05-03','keterangan'=>'Restock wajan anti lengket 28cm'],
            ['kode_transaksi'=>'BM-202505-004','produk_id'=>8,'jumlah'=>20,'harga_beli'=>18000,'supplier'=>'CV. Plastindo',           'tanggal'=>'2025-05-04','keterangan'=>'Restock ember plastik'],
            ['kode_transaksi'=>'BM-202505-005','produk_id'=>6,'jumlah'=>10,'harga_beli'=>25000,'supplier'=>'UD. Sumber Logam',        'tanggal'=>'2025-05-05','keterangan'=>'Restock sendok makan'],
        ]);

        // Barang Keluar
        DB::table('barang_keluar')->insert([
            ['kode_transaksi'=>'BK-202505-001','produk_id'=>1,'jumlah'=>3,'harga_jual'=>55000,'pembeli'=>'Pelanggan Umum',        'tanggal'=>'2025-05-01','keterangan'=>'Penjualan langsung'],
            ['kode_transaksi'=>'BK-202505-002','produk_id'=>3,'jumlah'=>5,'harga_jual'=>15000,'pembeli'=>'Bu Sari',               'tanggal'=>'2025-05-02','keterangan'=>'Penjualan langsung'],
            ['kode_transaksi'=>'BK-202505-003','produk_id'=>6,'jumlah'=>2,'harga_jual'=>40000,'pembeli'=>'Pelanggan Online Shopee','tanggal'=>'2025-05-03','keterangan'=>'Order Shopee #12345'],
            ['kode_transaksi'=>'BK-202505-004','produk_id'=>8,'jumlah'=>4,'harga_jual'=>28000,'pembeli'=>'Pelanggan Umum',        'tanggal'=>'2025-05-04','keterangan'=>'Penjualan langsung'],
            ['kode_transaksi'=>'BK-202505-005','produk_id'=>4,'jumlah'=>1,'harga_jual'=>95000,'pembeli'=>'Pak Budi',              'tanggal'=>'2025-05-05','keterangan'=>'Penjualan TikTok Shop'],
        ]);
    }
}
