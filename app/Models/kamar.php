<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamars';
    protected $primaryKey = 'no_kamar';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'no_kamar',
        'foto_kamar',
        'status',
        'ukuran',
        'harga',
        'tipe_kamar',
        'fasilitas',
        'lantai',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'lantai' => 'integer',
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class, 'no_kamar', 'no_kamar');
    }

    public function isAvailable()
    {
        return $this->status === 'tersedia';
    }

    public function pemilikKos()
    {
        // Langsung query ke model PemilikKos dan ambil baris pertama (satu-satunya).
        return PemilikKos::first();
    }
}
