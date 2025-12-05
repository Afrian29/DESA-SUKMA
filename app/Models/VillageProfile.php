<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'kades_name',
        'kades_photo',
        'sambutan_title',
        'sambutan_content',
        'video_url',
        'luas_wilayah',
        'umkm_count',
    ];
}
