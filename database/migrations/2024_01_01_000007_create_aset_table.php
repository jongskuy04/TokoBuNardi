<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aset', 30)->unique();
            $table->string('nama_aset', 150);
            $table->string('kategori_aset', 80)->nullable(); // Perabot, Elektronik, Alat Ukur, dll
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 30)->default('unit');
            $table->decimal('harga_perolehan', 15, 2)->default(0);
            $table->date('tanggal_perolehan')->nullable();
            $table->enum('kondisi', ['baik', 'perlu_perbaikan', 'rusak_berat'])->default('baik');
            $table->string('lokasi', 100)->nullable(); // Kasir, Gudang, Area Jual, dll
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};
