<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Barang extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'tbl_barang';
    protected $guarded = ['id'];
    protected $appends = ['total_stock'];

    public function getTotalStockAttribute()
    {

        $total_stock1 = Detailtransaksi::where('barang_id', $this->id)->join('tbl_transaksi', 'tbl_transaksi_dtl.transaksi_id', '=', 'tbl_transaksi.id')
        ->where('tbl_transaksi.type', 'Masuk')
        ->where('tbl_transaksi.status', 'Ready')->sum('dtl_jumlah');

        $total_stock2 = Detailtransaksi::where('barang_id', $this->id)->join('tbl_transaksi', 'tbl_transaksi_dtl.transaksi_id', '=', 'tbl_transaksi.id')
        ->where('tbl_transaksi.type', 'Keluar')
        ->where('tbl_transaksi.status', 'Collected')->sum('dtl_jumlah');

        $total_stock = $total_stock1 + $total_stock2;
        return $total_stock;


    }

    //  public static function getTotalStockAttribute($barangId, $gudangId)
    // {

    //    $total_stock = Detailtransaksi::where('barang_id', $barangId)->where('tbl_transaksi_dtl.gudang_id', $gudangId)
    //         ->join('tbl_transaksi', 'tbl_transaksi_dtl.transaksi_id', '=', 'tbl_transaksi.id')
    //         ->where('tbl_transaksi.status', 'Ready')
    //         ->groupBy('gudang_id')
    //         ->sum('dtl_jumlah');

    //     return $total_stock;

    // }

     public static function getTotalStockByBarangId($barangId, $gudangId)
    {

       $total_stock = Detailtransaksi::where('barang_id', $barangId)->where('tbl_transaksi_dtl.gudang_id', $gudangId)
            ->join('tbl_transaksi', 'tbl_transaksi_dtl.transaksi_id', '=', 'tbl_transaksi.id')
            ->where('tbl_transaksi.status', 'Ready')
            ->groupBy('gudang_id')
            ->sum('dtl_jumlah');

        return $total_stock;

    }

      public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }


    public function detailtransaksi(){
        return $this->hasMany(Detailtransaksi::class, 'barang_id')->whereHas('transaksi', function($query){
            $query->where('type', 'Masuk');
        });
    }
}
