<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel users sudah ada (sudah dibuat oleh migration default)
        // Insert default admin jika belum ada
        if (DB::table('users')->where('email', 'admin@tokobn.com')->doesntExist()) {
            DB::table('users')->insert([
                'name'       => 'Admin',
                'email'      => 'admin@tokobn.com',
                'password'   => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'admin@tokobn.com')->delete();
    }
};
