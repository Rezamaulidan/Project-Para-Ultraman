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
        'jumlah' => 'decimal:2',
        'nominal' => 'decimal:2',
    ];

    public function pemilikKos()
    {
        // Langsung query ke model PemilikKos dan ambil baris pertama (satu-satunya).
        return PemilikKos::first();
    }

}