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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('id_booking');
            $table->string('username');
            $table->integer('no_kamar');
            $table->enum('jenis_transaksi', ['booking', 'pembayaran_sewa']);
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'lunas', 'terlambat'])->default('pending');
            $table->date('tanggal');
            $table->decimal('nominal', 10, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('username')
                  ->references('username')
                  ->on('penyewas')
                  ->onDelete('cascade');

            $table->foreign('no_kamar')
                  ->references('no_kamar')
                  ->on('kamars')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
