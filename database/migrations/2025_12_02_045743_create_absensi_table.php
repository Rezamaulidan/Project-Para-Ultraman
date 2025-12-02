<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (membuat tabel absensi)
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->unsignedBigInteger('id_staf');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('shift')->nullable();
            $table->timestamps();

            // Foreign key ke tabel staf
            $table->foreign('id_staf')
                  ->references('id_staf')
                  ->on('stafs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Rollback migration (hapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};

