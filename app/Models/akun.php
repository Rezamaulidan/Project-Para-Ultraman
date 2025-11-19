<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Akun extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'akuns';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'jenis_akun',
        'password',
    ];

    public function pemilikKos()
    {
        return $this->hasOne(PemilikKos::class, 'username', 'username');
    }

    public function stafs()
    {
        return $this->hasMany(Staf::class, 'username', 'username');
    }
}
