<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hunian extends Model
{
    protected $table = 'hunian';
    protected $primaryKey = 'id_hunian';

    protected $fillable = [
        'nama_hunian',
        'gambar_hunian',
        'deskripsi_hunian',
        'status_harian',
    ];

    public function biaya()
    {
        return $this->hasOne(Biaya::class, 'id_hunian', 'id_hunian');
    }

    public function sewa()
    {
        return $this->hasMany(Sewa::class, 'id_hunian', 'id_hunian');
    }
}
