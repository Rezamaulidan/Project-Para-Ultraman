<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    use HasFactory;

    // --- PENTING: Mencegah error "Table 'staf' doesn't exist" ---
    protected $table = 'stafs';

    // Menentukan primary key (karena default Laravel adalah 'id')
    protected $primaryKey = 'id_staf';

    // Kolom yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'nama_staf',
        'jadwal',      // Pagi / Malam
        'email',
        'no_hp',
        'foto_staf',   // Opsional: untuk fitur upload foto profil nanti
    ];
}
