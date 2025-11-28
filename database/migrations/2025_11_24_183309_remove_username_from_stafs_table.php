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
        Schema::table('stafs', function (Blueprint $table) {
            // 1. Hapus Foreign Key dulu (Wajib dilakukan sebelum hapus kolom)
            // Format default Laravel: namaTabel_namaKolom_foreign
            $table->dropForeign(['username']);

            // 2. Hapus kolom username
            $table->dropColumn('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stafs', function (Blueprint $table) {
            // Kembalikan kolom jika migrasi dibatalkan (Rollback)
            $table->string('username')->nullable();

            // Kembalikan Foreign Key
            $table->foreign('username')
                  ->references('username')
                  ->on('akuns')
                  ->onDelete('cascade');
        });
    }
};
