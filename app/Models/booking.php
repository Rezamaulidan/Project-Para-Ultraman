<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'username',
        'no_kamar',
        'status_booking',
        'tanggal',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class, 'username', 'username');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'no_kamar', 'no_kamar');
    }
}
