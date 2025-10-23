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
        'username',
        'nama_staf',
        'foto_staf',
        'jadwal',
        'email',
        'no_hp',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'username', 'username');
    }

    public function LaporanKeamanans()
    {
        return $this->hasMany(LaporanKeamanan::class, 'id_staf', 'id_staf');
    }
}
