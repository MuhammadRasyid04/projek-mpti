<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';

    protected $fillable = [
        'id_sewa',
        'tgl_tagihan',
        'air',
        'wifi',
        'tgl_jatuhtempo',
        'status',
    ];

    public function sewa()
    {
        return $this->belongsTo(Sewa::class, 'id_sewa', 'id_sewa');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_tagihan', 'id_tagihan');
    }
}
