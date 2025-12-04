<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Faker\Factory as Faker;

class PendudukSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); // Use Indonesian locale

        // Create 50 Families
        for ($i = 0; $i < 50; $i++) {
            $kkNumber = $faker->unique()->numerify('################'); // 16 digits
            $dusun = $faker->randomElement(['Dusun 1', 'Dusun 2', 'Dusun 3']);
            
            // Create KK
            $kk = KartuKeluarga::create([
                'no_kk' => $kkNumber,
                'kepala_keluarga' => 'TBD',
                'dusun' => $dusun,
                'status_kesejahteraan' => $faker->randomElement(['KS1', 'KS2', 'KS3']),
                'jenis_bangunan' => $faker->randomElement(['Permanen', 'Semi Permanen', 'Kayu']),
                'pemakaian_air' => $faker->randomElement(['PDAM', 'Sumur', 'Sungai']),
                'jenis_bantuan' => $faker->randomElement(['PKH', 'BPNT', 'BST', null]),
            ]);

            // Create Father (Head)
            $fatherName = $faker->name('male');
            $fatherNik = $faker->unique()->numerify('################');
            
            Penduduk::create([
                'nik' => $fatherNik,
                'no_kk' => $kkNumber,
                'nama_lengkap' => $fatherName,
                'jenis_kelamin' => 'L',
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '-30 years'),
                'pendidikan_terakhir' => $faker->randomElement(['SLTA', 'S1', 'S2']),
                'pekerjaan' => $faker->jobTitle,
                'status_hubungan_dalam_keluarga' => 'KEPALA KELUARGA',
                'status_dasar' => 'HIDUP',
            ]);

            $kk->update(['kepala_keluarga' => $fatherName]);

            // Create Mother (Wife)
            Penduduk::create([
                'nik' => $faker->unique()->numerify('################'),
                'no_kk' => $kkNumber,
                'nama_lengkap' => $faker->name('female'),
                'jenis_kelamin' => 'P',
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '-28 years'),
                'pendidikan_terakhir' => $faker->randomElement(['SLTA', 'S1', 'D3']),
                'pekerjaan' => $faker->randomElement(['Ibu Rumah Tangga', 'Guru', 'Pedagang']),
                'status_hubungan_dalam_keluarga' => 'ISTRI',
                'status_dasar' => 'HIDUP',
            ]);

            // Create Children (0-3 kids)
            $numChildren = $faker->numberBetween(0, 3);
            for ($j = 0; $j < $numChildren; $j++) {
                $gender = $faker->randomElement(['L', 'P']);
                Penduduk::create([
                    'nik' => $faker->unique()->numerify('################'),
                    'no_kk' => $kkNumber,
                    'nama_lengkap' => $faker->name($gender == 'L' ? 'male' : 'female'),
                    'jenis_kelamin' => $gender,
                    'tempat_lahir' => $faker->city,
                    'tanggal_lahir' => $faker->date('Y-m-d', '-17 years'),
                    'pendidikan_terakhir' => $faker->randomElement(['SD', 'SLTP', 'Belum Sekolah']),
                    'pekerjaan' => 'Belum Bekerja',
                    'status_hubungan_dalam_keluarga' => 'ANAK',
                    'status_dasar' => 'HIDUP',
                ]);
            }
        }
    }
}
