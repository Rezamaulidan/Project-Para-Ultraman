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
        Schema::create('laporan_keamanans', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->string('nama_staf'); // FK ke stafs.id_staf
            $table->string('judul_laporan', 200);
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('nama_staf')
                  ->references('id_staf')
                  ->on('stafs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_keamanans');
    }
};
