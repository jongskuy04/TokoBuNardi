<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_rusak', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();

            // Untuk jenis RETURN: referensi ke transaksi barang keluar yang di-return
            // Untuk jenis RUSAK: null
            $table->foreignId('barang_keluar_id')->nullable()->constrained('barang_keluar')->nullOnDelete();

            $table->integer('jumlah');
            $table->decimal('harga_jual', 15, 2)->default(0); // harga saat dijual (untuk hitung pengurangan omset)
            $table->enum('jenis', ['rusak', 'return'])->default('rusak');
            $table->string('sumber', 150)->nullable(); // nama pembeli yang return / penyebab rusak
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_rusak');
    }
};
