<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    protected $table = 'biaya';
    protected $primaryKey = 'id_biaya';

    protected $fillable = [
        'id_hunian',
        'wifi',
        'sampah',
        'kost',
    ];

    public function hunian()
    {
        return $this->belongsTo(Hunian::class, 'id_hunian', 'id_hunian');
    }
}
