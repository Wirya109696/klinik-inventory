<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Gudang extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'tbl_gudang';

    protected $guarded = ['id'];

    public function penyimpanan() {
        return $this->belongsTo(Penyimpanan::class, 'penyimpanan_id');
    }

     public function detailtransaksi() {
        return $this->hasMany(Detailtransaksi::class);
    }



}
