<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PemilikKos extends Authenticatable
{
    use HasFactory;

    protected $table = 'pemilik_kos';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'nama_pemilik',
        'email',
        'no_hp',
        'foto_profil',
    ];

    public function akuns()
    {
        return $this->belongsTo(Akun::class, 'username', 'username');
    }

    public function akunStaf()
    {
        // Langsung query ke model Akun berdasarkan business logic
        return Akun::where('jenis_akun', 'staf')->get();
    }

    public function kamars()
    {
        return Kamar::all();
    }

    public function pengeluarans()
    {
        return Pengeluaran::all();
    }

}