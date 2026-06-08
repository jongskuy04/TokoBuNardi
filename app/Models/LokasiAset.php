<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiAset extends Model
{
    protected $table = 'lokasi_aset';

    protected $fillable = [
        'nama_lokasi',
    ];
}
