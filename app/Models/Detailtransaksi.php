<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailtransaksi extends Model
{
    use HasFactory;

    // protected $table = 'detail_transaksis';

    protected $table = 'tbl_transaksi_dtl';
    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function barang(){
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function gudang(){
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
}
