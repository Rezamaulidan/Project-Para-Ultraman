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
        Schema::create('stafs', function (Blueprint $table) {
            $table->id('id_staf');
            $table->string('username'); // FK ke akuns
            $table->string('nama_staf', 100);
            $table->string('foto_staf')->nullable();
            $table->enum('jadwal', ['Pagi', 'Malam']);
            $table->string('email')->unique();
            $table->string('no_hp', 20);
            $table->timestamps();

            $table->foreign('username')
                  ->references('username')
                  ->on('akuns')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafs');
    }
};
