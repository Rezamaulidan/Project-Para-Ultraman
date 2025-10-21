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
            $table->foreignId('id_staf')
                  ->constrained('stafs', 'id_staf') // constrained ke tabel 'stafs' kolom 'id_staf'
                  ->onDelete('cascade');
            $table->string('judul_laporan', 200);
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();
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
