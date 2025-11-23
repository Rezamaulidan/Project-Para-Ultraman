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
        'nama_penyewa',
        'no_hp',
        'jenis_kelamin',
        'email',
        'foto_ktp',
        'foto_profil',
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class, 'username', 'username');
    }
}
