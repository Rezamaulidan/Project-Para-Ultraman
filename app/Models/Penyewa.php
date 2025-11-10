<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penyewa extends Authenticatable
{
    use HasFactory;

    protected $table = 'penyewas';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'no_hp',
        'nama_penyewa',
        'jenis_kelamin',
        'email',
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class, 'username', 'username');
    }
}
