<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'invoice',
        'nama_pengirim',
        'bukti_trf',
        'tgl_bayar',
        'tgl_jatuhtempo',
        'status',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'invoice', 'invoice');
    }
}
