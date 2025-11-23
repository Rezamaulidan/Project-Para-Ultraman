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
        // 1. Update Tabel PENYEWAS
        Schema::table('penyewas', function (Blueprint $table) {
            // Menambahkan Foto KTP
            $table->string('foto_ktp')->nullable()->after('email');

            // Menambahkan Foto Profil
            $table->string('foto_profil')->nullable()->after('foto_ktp');
        });

        // 2. Update Tabel PEMILIK_KOS
        Schema::table('pemilik_kos', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('email');
        });

        // 3. Update Tabel BOOKINGS
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('durasi_sewa')->default(1)->after('no_kamar')->comment('Durasi dalam bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom jika migrasi di-rollback (urutan kebalikan)

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('durasi_sewa');
        });

        Schema::table('pemilik_kos', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });

        Schema::table('penyewas', function (Blueprint $table) {
            $table->dropColumn(['foto_ktp', 'foto_profil']);
        });
    }
};
