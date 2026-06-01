<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sewa extends Model
{
    protected $table = 'sewa';
    protected $primaryKey = 'id_sewa';

    protected $fillable = [
        'id_users',
        'id_hunian',
        'tgl_jatuhtempo',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function hunian()
    {
        return $this->belongsTo(Hunian::class, 'id_hunian', 'id_hunian');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_sewa', 'id_sewa');
    }
}
