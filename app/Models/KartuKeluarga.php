<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuKeluarga extends Model
{
    protected $fillable = [
        'no_kk',
        'kepala_keluarga',
        'dusun',
        'jenis_bangunan',
        'pemakaian_air',
        'jenis_bantuan',
        'status_kesejahteraan',
    ];
}
