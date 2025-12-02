<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';
    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'tanggal',
        'jumlah',
        'nominal', // Kita pakai nominal (sesuai migrasi terakhir)
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'integer',
        'nominal' => 'decimal:2',
    ];

    public function pemilikKos()
    {
        return PemilikKos::first();
    }
}
