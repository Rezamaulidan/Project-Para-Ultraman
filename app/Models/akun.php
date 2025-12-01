<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Akun extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'akun';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'jenis_akun',
        'password',
        'nama_lengkap',
        'no_hp',
        'email',
        'alamat',
    ];

    // Relasi ke Pemilik
    public function pemilikKos()
    {
        return $this->hasOne(PemilikKos::class, 'username', 'username');
    }

    // Relasi ke Staff
    public function stafs()
    {
        return $this->hasMany(Staf::class, 'username', 'username');
    }

    public function penyewa()
    {
        return $this->hasOne(Penyewa::class, 'username', 'username');
    }
}
