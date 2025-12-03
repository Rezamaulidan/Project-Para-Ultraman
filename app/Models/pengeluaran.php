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
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'integer', // Tipe data Integer untuk kuantitas
        'nominal' => 'decimal:2', // Nominal/Harga total dalam format desimal (Rp xxx.xx)
    ];

    public function pemilikKos()
    {
        return PemilikKos::first();
    }
}
