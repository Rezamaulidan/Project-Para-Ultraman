<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    use HasFactory;
    protected $table = 'stafs';

    // Menentukan primary key (karena default Laravel adalah 'id')

    protected $primaryKey = 'id_staf';

    // Kolom yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'nama_staf',
        'jadwal',      // Pagi / Malam
        'email',
        'no_hp',
        'foto_staf',
        'status',   // Opsional: untuk fitur upload foto profil nanti
    ];

    // Method akun() DIHAPUS karena sudah tidak ada relasi ke tabel akun.

    // Relasi ini TETAP ADA (Satu staf bisa punya banyak laporan)
    public function LaporanKeamanans()
    {
        return $this->hasMany(LaporanKeamanan::class, 'id_staf', 'id_staf');
    }
}

