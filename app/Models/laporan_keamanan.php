<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeamanan extends Model
{
    use HasFactory;

    protected $table = 'laporan_keamanans';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_staf',
        'judul_laporan',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function staf()
    {
        return $this->belongsTo(Staf::class, 'id_staf', 'id_staf');
    }

}
