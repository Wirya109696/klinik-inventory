<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Transaksi extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'tbl_transaksi';

    protected $guarded = ['id'];

     protected $appends = ['total_stock'];

    public function getTotalStockAttribute()
    {

       $data = Detailtransaksi::all();

            $total_stock = [];

        foreach ($data as $row) {
            $key = $row->barang_id . '-' . $row->gudang_id; // Adjust based on your column names
            if (!isset($total_stock[$key])) {
                $total_stock[$key] = $row;
            } else {
                // Sum the total stock for items with the same location
                $total_stock[$key]->dtl_jumlah += $row->dtl_jumlah;
            }
        }


    }

    public function detailTransaksi()
    {
        return $this->hasMany(Detailtransaksi::class, 'transaksi_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

      public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

        public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
