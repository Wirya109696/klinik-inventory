<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'tbl_divisi';

    protected $guarded = ['id'];

        public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
