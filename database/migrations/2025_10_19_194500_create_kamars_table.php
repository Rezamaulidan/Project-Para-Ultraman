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
        Schema::create('kamars', function (Blueprint $table) {
            $table->integer('no_kamar')->primary();
            $table->string('foto_kamar')->nullable();
            $table->enum('status', ['tersedia', 'terisi'])->default('tersedia');
            $table->string('ukuran', 50);
            $table->enum('tipe_kamar', ['kosongan', 'basic', 'ekslusif'])->default('basic');
            $table->decimal('harga', 10, 2);
            $table->text('fasilitas')->nullable();
            $table->integer('lantai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
