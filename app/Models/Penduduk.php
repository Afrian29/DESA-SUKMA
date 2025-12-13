<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penduduk extends Model
{
    use HasFactory;

    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'no_kk',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan_terakhir',
        'pekerjaan',
        'status_hubungan_dalam_keluarga',
        'jenis_bantuan',
        'status_dasar',
        'kewarganegaraan',
    ];

    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class, 'no_kk', 'no_kk');
    }

    public function mutasis()
    {
        return $this->hasMany(Mutasi::class, 'nik', 'nik');
    }

    /**
     * Accessor: Format Status Hubungan Dalam Keluarga (SHDK)
     * - KEPALA KELUARGA: UPPERCASE
     * - Others: Title Case
     */
    public function getFormattedShdkAttribute()
    {
        $shdk = $this->status_hubungan_dalam_keluarga;
        
        if (empty($shdk)) {
            return '-';
        }

        // Normalize to lowercase first
        $normalized = strtolower(trim($shdk));

        // KEPALA KELUARGA stays UPPERCASE
        if ($normalized === 'kepala keluarga') {
            return 'KEPALA KELUARGA';
        }

        // Others become Title Case
        return ucwords($normalized);
    }
    //
}
