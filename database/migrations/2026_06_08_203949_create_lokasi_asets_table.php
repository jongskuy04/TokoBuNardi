<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lokasi_aset', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi', 100)->unique();
            $table->timestamps();
        });

        // Insert default options
        \DB::table('lokasi_aset')->insert([
            ['nama_lokasi' => 'Gudang', 'created_at' => now(), 'updated_at' => now()],
            ['nama_lokasi' => 'Seluruh Toko', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_aset');
    }
};
