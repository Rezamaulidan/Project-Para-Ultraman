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
        'no_hp',
        'password',
    ];

    public function akuns()
    {
        return $this->hasOne(Akun::class, 'username', 'username');
    }

    public function akunStaf()
    {
        // Langsung query ke model Akun berdasarkan business logic
        return Akun::where('jenis_akun', 'staf')->get();
    }

    public function kamars()
    {
        // Langsung query ke model Kamar dan ambil semua data.
        return Kamar::all();
    }

    public function pengeluarans()
    {
        // Langsung query ke model Pengeluaran dan ambil semua datanya.
        return Pengeluaran::all();
    }

}
