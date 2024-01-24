<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Penyimpanan extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'tbl_penyimpanan';

    protected $guarded = ['id'];

    public function gudang() {
        return $this->hasMany(Gudang::class);
    }
}
