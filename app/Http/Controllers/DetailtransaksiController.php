<?php

namespace App\Http\Controllers;

use App\Models\Detailtransaksi;
use App\Http\Requests\StoreDetailtransaksiRequest;
use App\Http\Requests\UpdateDetailtransaksiRequest;
use App\Models\Barang;
use App\Models\Transaksi;

class DetailtransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users-index', ['only' => ['index']]);
        $this->middleware('permission:users-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users-erase', ['only' => ['destroy']]);
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
            '/js/barangjs/barang.js'
        );

        return view('detransaksi.index',[
            'title'  => 'Detail Transaksi',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Detail Transaksi</b>',
            'data'   => $data
        ]);
    }

    public function TabelDetailFix()
    {
        $get_all = Detailtransaksi::all();

        $data = array();
        $i = 1;
        foreach ($get_all as $row) {

            $data[] = array(
                $row->dtl_jumlah,
            );
        }
        echo json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDetailtransaksiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDetailtransaksiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detailtransaksi  $detailtransaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Detailtransaksi $detailtransaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Detailtransaksi  $detailtransaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Detailtransaksi $detailtransaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDetailtransaksiRequest  $request
     * @param  \App\Models\Detailtransaksi  $detailtransaksi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDetailtransaksiRequest $request, Detailtransaksi $detailtransaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detailtransaksi  $detailtransaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Detailtransaksi $detailtransaksi)
    {
        //
    }
}
