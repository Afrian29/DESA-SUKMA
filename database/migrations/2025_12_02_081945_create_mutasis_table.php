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
        Schema::create('mutasis', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->foreign('nik')->references('nik')->on('penduduks')->onDelete('cascade');
            $table->enum('jenis_mutasi', ['LAHIR', 'MATI', 'DATANG', 'PINDAH']);
            $table->date('tanggal_mutasi');
            $table->string('keterangan')->nullable(); // Tempat Lahir / Asal Datang / Tujuan Pindah / Tempat Meninggal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};
