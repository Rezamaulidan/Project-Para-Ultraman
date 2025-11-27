<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    use HasFactory;

    protected $table = 'stafs';
    protected $primaryKey = 'id_staf';

    protected $fillable = [
        'nama_staf',
        'foto_staf', // Pastikan nama kolom di database 'foto_staf' atau 'foto_profil' (sesuaikan)
        'jadwal',
        'email',
        'no_hp',
    ];

    // Method akun() DIHAPUS karena sudah tidak ada relasi ke tabel akun.

    // Relasi ini TETAP ADA (Satu staf bisa punya banyak laporan)
    public function LaporanKeamanans()
    {
        return $this->hasMany(LaporanKeamanan::class, 'id_staf', 'id_staf');
    }
}