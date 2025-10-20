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
        'jumlah',
        'sub_total',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'sub_total' => 'decimal:2',
    ];

    public function pemilikKos()
    {
        // Langsung query ke model PemilikKos dan ambil baris pertama (satu-satunya).
        return PemilikKos::first();
    }

}
