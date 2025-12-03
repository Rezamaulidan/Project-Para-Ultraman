<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    use HasFactory;

    protected $table = 'stafs';
    protected $primaryKey = 'id_staf';

    // Kolom yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'username',      // Diperlukan untuk Shared Account
        'nama_staf',
        'jadwal',        // Pagi / Malam
        'email',
        'no_hp',
        'foto_staf',     // Opsional
    ];

    // Relasi ke Laporan Keamanan
    public function laporanKeamanan()
    {
        return $this->hasMany(LaporanKeamanan::class, 'id_staf', 'id_staf');
    }
}
