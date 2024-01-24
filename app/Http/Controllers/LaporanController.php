<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Detailtransaksi;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan-index', ['only' => ['index']]);
        $this->middleware('permission:laporan-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:laporan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:laporan-erase', ['only' => ['destroy']]);
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
            '/js/laporanjs/laporan.js'
        );

        return view('laporan.index',[
            'title'  => 'Laporan',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Cetak Data Laporan</b>',
            'data'   => $data
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
        $data = Detailtransaksi::select('tbl_transaksi_dtl.*')
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

            return DataTables::of(array_values($groupedData))
                ->addIndexColumn()
                 ->addColumn('action', function($row){
                    $actionBtn = "
                    <div class='dropdown'>
                        <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                        <i class='bx bx-dots-vertical-rounded'></i>
                        </button>
                        <div class='dropdown-menu'>
                        <a id='edit_btn' type='button' class='dropdown-item' data-id='".$row->id."'><i class='bi bi-pencil-square'></i> Edit</a>
                        <a id='delete_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-brg_nama='".$row->brg_nama."'><i class='bi bi-trash'></i> Delete</a>
                        </div>
                    </div>";
                    return $actionBtn;
                })
                  ->addColumn('brg_kode', function($row){
                    return $row->barang->brg_kode;
                })
                ->addColumn('brg_nama', function($row){
                    return $row->barang->brg_nama;
                })
                ->addColumn('kat_nama', function($row){
                    return $row->barang->kategori->kat_nama;
                })
                ->addColumn('gud_nama', function($row){
                    return $row->gudang->gud_nama;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
