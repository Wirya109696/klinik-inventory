<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable();
            $table->foreignId('karyawan_id')->nullable();
            $table->foreignId('divisi_id')->nullable();
            $table->foreignId('created_by');
            $table->foreignId('update_by')->nullable();
            $table->string('trx_code');
            $table->string('type');
            $table->date('tanggal')->format('d-m-Y');
            $table->bigInteger('total_barang');
            $table->string('status');
            $table->string('trx_ket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_transaksi');
    }
}
