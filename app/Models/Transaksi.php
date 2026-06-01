<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'invoice',
        'id_tagihan',
        'biaya_sampah',
        'biaya_wifi',
        'biaya_air',
        'biaya_kost',
        'total_bayar',
        'tgl_request',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan', 'id_tagihan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'invoice', 'invoice');
    }
}
