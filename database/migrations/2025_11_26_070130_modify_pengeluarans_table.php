<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            // 1. Ganti nama 'sub_total' jadi 'nominal' (Total Harga)
            $table->renameColumn('sub_total', 'nominal');

            // 2. Tambah kolom tanggal (Default hari ini)
            $table->date('tanggal')->default(DB::raw('CURRENT_DATE'))->after('id_pengeluaran');
        });
    }
    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            // Kembalikan nama kolom
            $table->renameColumn('nominal', 'sub_total');

            // Hapus kolom tanggal
            $table->dropColumn('tanggal');
        });
    }
};
