<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->string('supplier', 150)->nullable();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};
