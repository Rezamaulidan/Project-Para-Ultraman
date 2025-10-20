<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pemilik_kos extends Authenticatable
{
    use HasFactory;

    protected $table = 'pemilik_kos';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'nama_pemilik',
        'no_hp',
        'password',
    ];
}
