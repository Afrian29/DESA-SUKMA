<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuKeluarga extends Model
{
    protected $fillable = [
        'no_kk',
        'kepala_keluarga', // Legacy column, keep for now or deprecate
        'kepala_keluarga_nik',
        'dusun',
        'jenis_bangunan',
        'pemakaian_air',
        'jenis_bantuan',
        'status_kesejahteraan',
    ];

    protected $appends = ['nama_kepala'];

    public function kepalaKeluarga()
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_nik', 'nik');
    }

    // Accessor: Prioritize Linked Name, fallback to Legacy Name
    public function getNamaKepalaAttribute()
    {
        if ($this->kepala_keluarga_nik && $this->kepalaKeluarga) {
            return $this->kepalaKeluarga->nama_lengkap;
        }
        return $this->kepala_keluarga ?? '-';
    }
}
