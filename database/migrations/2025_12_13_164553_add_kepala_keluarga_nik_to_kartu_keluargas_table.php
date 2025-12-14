<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->char('kepala_keluarga_nik', 16)->nullable()->after('kepala_keluarga');
            $table->foreign('kepala_keluarga_nik')
                  ->references('nik')
                  ->on('penduduks')
                  ->onDelete('set null');
        });

        // Data Migration: Populate kepala_keluarga_nik from existing valid Residents
        $kks = DB::table('kartu_keluargas')->get();
        foreach ($kks as $kk) {
            // Find resident who is 'KEPALA KELUARGA' in this KK
            $head = DB::table('penduduks')
                ->where('no_kk', $kk->no_kk)
                ->where('status_hubungan_dalam_keluarga', 'KEPALA KELUARGA')
                ->first();

            if ($head) {
                DB::table('kartu_keluargas')
                    ->where('id', $kk->id)
                    ->update(['kepala_keluarga_nik' => $head->nik]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->dropForeign(['kepala_keluarga_nik']);
            $table->dropColumn('kepala_keluarga_nik');
        });
    }
};
