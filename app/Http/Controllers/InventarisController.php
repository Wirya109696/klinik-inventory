<?php

namespace App\Http\Controllers;

use App\Models\Detailtransaksi;
use App\Models\Gudang;
use App\Models\Kategori;
use App\Models\Penyimpanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller
{
      public function __construct()
    {
        $this->middleware('permission:inventaris-index', ['only' => ['index']]);
        $this->middleware('permission:inventaris-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:inventaris-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:inventaris-erase', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['css'] = array(
            '/lib/datatables/dataTables.bootstrap.min.css',
			'/lib/select/component-chosen.min.css',
            '/lib/select/bootstrap-chosen.css',
        );

        $data['js'] = array(
            '/lib/datatables/datatables.min.js',
            '/lib/datatables/dataTables.bootstrap5.min.js',
            '/lib/select/chosen.jquery.min.js',
            '/js/inventarisjs/inventaris.js'
        );


        return view('inventaris.index',[
            'title'  => 'Laporan',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Filter Data Barang</b>',
            'data'   => $data,
            'kategori' => Kategori::all(),
            'penyimpanan' => Penyimpanan::all(),
            'gudang' => Gudang::all()
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
        $data = Detailtransaksi::select('tbl_transaksi_dtl.*')->with('gudang.penyimpanan')
        ->join('tbl_transaksi', 'tbl_transaksi_dtl.transaksi_id', '=', 'tbl_transaksi.id')
        ->where('tbl_transaksi.status', 'Ready')
        ->get();

        $groupedData = [];

        foreach ($data as $row) {
            $key = $row->barang_id . '-' . $row->gudang_id; // Adjust based on your column names
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = $row;
            } else {
                // Sum the total stock for items with the same location
                $groupedData[$key]->dtl_jumlah += $row->dtl_jumlah;
            }
        }

            return Datatables::of(array_values($groupedData))
                ->addIndexColumn()
                  ->addColumn('brg_kode', function($row){
                    return $row->barang->brg_kode;
                })
                ->addColumn('brg_nama', function($row){
                    return $row->barang->brg_nama;
                })
                ->addColumn('brg_min', function($row){
                    return $row->barang->brg_min;
                })
                ->addColumn('kat_nama', function($row){
                    return $row->barang->kategori->kat_nama;
                })
                 ->addColumn('pen_nama', function($row){
                    return $row->gudang->penyimpanan->pen_nama;
                })
                ->addColumn('gud_nama', function($row){
                    return $row->gudang->gud_nama;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
