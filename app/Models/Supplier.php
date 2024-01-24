<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Supplier extends Model
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $table = 'tbl_supplier';

    protected $guarded=['id'];

    public function transaksi(){
        return $this->hasMany(Transaksi::class);
    }
}
