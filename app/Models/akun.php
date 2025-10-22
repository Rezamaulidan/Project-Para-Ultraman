<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Akun extends Authenticatable
{
    use HasFactory;

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
        return $this->belongsTo(PemilikKos::class, 'username', 'username');
    }

    public function stafs()
    {
        return $this->hasMany(Staf::class, 'username', 'username');
    }
}
