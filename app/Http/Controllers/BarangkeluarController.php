<?php

namespace App\Http\Controllers;


use App\Models\Barang;
use App\Models\Karyawan;
use App\Models\Transaksi;
use App\Models\Detailtransaksi;
use App\Models\Divisi;
use App\Models\Gudang;
use App\Models\Penyimpanan;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;



class BarangkeluarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:keluar-index', ['only' => ['index']]);
        $this->middleware('permission:keluar-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:keluar-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:keluar-erase', ['only' => ['destroy']]);
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
            '/lib/jquery/jquery-ui.css',
        );

        $data['js'] = array(
            '/lib/datatables/datatables.min.js',
            '/lib/datatables/dataTables.bootstrap5.min.js',
            '/lib/select/chosen.jquery.min.js',
            '/lib/jquery/jquery-ui.js',
            '/lib/momentmin/moment.min.js',
            '/lib/momentmin/moment-with-locales.min.js',
            '/js/barangkeluarjs/barangkeluar.js'

        );

        return view('barangkeluar.index',[
            'title'  => 'Transaksi Keluar',
            'header' => '<i class="bi bi-arrow-bar-left"></i>&nbsp;<b>Barang Keluar</b>',
            'data'   => $data,
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Transaksi::where('type', 'Keluar')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                 ->addColumn('action', function($row){
                    $actionBtn = "
                    <div class='dropdown'>
                        <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                        <i class='bx bx-dots-vertical-rounded'></i>
                        </button>
                        <div class='dropdown-menu'>
                        <a id='detailkeluar_btn' type='button' class='dropdown-item' data-id='".$row->id."'><i class='bi bi bi-file-lock2'></i> Detail</a>
                        <a id='edit_btn' type='button' class='dropdown-item' data-id='".$row->id."'><i class='bi bi-pencil-square'></i> Edit</a>
                        <a id='cancel_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-trx_code='".$row->trx_code."'><i class='bi bi-backspace-reverse'></i> Cancel</a>

                        </div>
                    </div>";
                    return $actionBtn;
                })
                ->addColumn('kar_nama', function($row){
                    return $row->karyawan->kar_nama;
                })
                 ->addColumn('kar_location', function($row){
                    return $row->karyawan->kar_location;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getNoDocument($inputType)
{
    $kode = 1;


    // Get the latest ID from the Transaksi table based on the input type
    $latestTransaksi = Transaksi::where('type', $inputType)->latest()->first();

    // Handle the case when there are no records in the Transaksi table yet
    if (!$latestTransaksi) {
        return 'KD/OUT/'. str_pad($kode, 4, "0", STR_PAD_LEFT);
    }

    // Increment the document number only if the input type is not "keluar"
    if ($inputType == 'Keluar') {
        // $kode = $latestTransaksi->id + 1;
        $kode = intval(substr($latestTransaksi->trx_code, -4)) + 1;

    }

    return 'KD/OUT/' . str_pad($kode, 4, "0", STR_PAD_LEFT);
}





    public function autocomplete2(Request $request)
    {
      $term = $request->input('term');

        $results = Karyawan::select('kar_nama','kar_idkar','id')->where('kar_idkar', 'LIKE', '%' . $term . '%')->orWhere('kar_nama', 'LIKE', '%' . $term . '%')->first();
        // var_dump($results);

        if ($results) {
        return response()->json([
            'karyawan_id' => $results->id,
            'kar_idkar' => $results->kar_idkar,
            'kar_nama' => $results->kar_nama,
            // 'kar_location' => $results->kar_location,
        ], 200);
        } else {
            // Data not found, return an error message
            return response()->json(['error' => 'Data Tidak Ditemukan'], 404);
        }
    }

     public function autocomplete(Request $request)
    {

      $term = $request->input('term');
      $gudang = $request->input('gudang');
      $penyimpanan = $request->input('penyimpanan');
      $barang = $request->input('barang');
      $kategori = $request->input('kategori');

        $results = Barang::select('brg_min', 'brg_nama', 'brg_kode', 'id')
        ->whereHas('detailtransaksi', function($query) use ($gudang, $penyimpanan) {
            $query->whereHas('gudang', function($que) use ($gudang, $penyimpanan){
                    $que->where('gud_nama', $gudang)->whereHas('penyimpanan', function($query_pen) use ($penyimpanan){
                        $query_pen->where('penyimpanan_id', $penyimpanan);
                    });
            });
        })
        ->where('brg_kode', 'LIKE', '%' . $term . '%')
        ->orWhere('brg_nama', 'LIKE', '%' . $term . '%')
        ->first();

        // return $results;
        // return $term;

        if ($results) {
            // Use the new method to get total_stock
            $gud = Gudang::where('gud_nama', $gudang)->first();
            $totalStock = Barang::getTotalStockByBarangId($results->id, $gud->id);

            return response()->json([
                'barang_id' => $results->id,
                'brg_kode' => $results->brg_kode,
                'brg_nama' => $results->brg_nama,
                'brg_min' => $results->brg_min,
                'gudang_id' => $gud->id,
                'gud_nama' => $gud->gud_nama, // Update this line
                'total_stock' => $totalStock,
            ], 200);
        } else {
            return response()->json(['error' => 'No results found'], 404);
        }
    }

    public function getPenyimpananByGudang2(Request $request, $penyimpananId)
    {
    //     // Assuming you have a model named Penyimpanan

        // $gudang = Gudang::where('penyimpanan_id', $penyimpananId)->get();

        // return response()->json($gudang);

        $results = Detailtransaksi::select('tbl_transaksi_dtl.*')->with('gudang.penyimpanan')
        ->join('tbl_transaksi', 'tbl_transaksi_dtl.transaksi_id', '=', 'tbl_transaksi.id')
        ->where('tbl_transaksi.status', 'Ready')
        ->whereHas('gudang.penyimpanan', function ($query_pen) use ($penyimpananId) {
                $query_pen->where('penyimpanan_id', $penyimpananId);
            })
            ->get();
            // ->groupBy('detailtransaksi.gudang_id');

        $barang = '';
        $groupedData = [];
        foreach ($results as $key => $value) {
            $key = $value->barang_id . '-' . $value->gudang_id; // Adjust based on your column names
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = $value;

                $barang .= $value->barang->brg_kode . ' (' . $value->barang->brg_nama . ')(' . $value->gudang->gud_nama . '),';
            }
        }

        return response()->json($barang);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaksikeluar = new Transaksi(['type' => 'Keluar']);
        // $barangList = Barang::pluck('brg_nama', 'id')->toArray();
        $noDocument = $this->getNoDocument('Keluar');
        $pelanggan = Karyawan::all();
        $karyawan = '';
        foreach ($pelanggan as $key => $value) {
            $karyawan .= $value->kar_idkar . ' (' . $value->kar_nama . '),';
        }

        // $gudang = $request->input('gudang');
        // $penyimpanan = 1;
        // $gudang = 2;

        // $results = Detailtransaksi::select('tbl_transaksi_dtl.*')->with('gudang.penyimpanan')
        //     ->join('tbl_transaksi', 'tbl_transaksi_dtl.transaksi_id', '=', 'tbl_transaksi.id')
        //     ->where('tbl_transaksi.status', 'Ready')
        //     ->whereHas('gudang.penyimpanan', function ($query_pen) use ($penyimpanan) {
        //         $query_pen->where('penyimpanan_id', $penyimpanan);
        //         })
        //     ->get();
        // // ->groupBy('detailtransaksi.gudang_id');

        // $barang = '';
        // $groupedData = [];
        // foreach ($results as $key => $value) {
        //     $key = $value->barang_id . '-' . $value->gudang_id; // Adjust based on your column names
        //     if (!isset($groupedData[$key])) {
        //         $groupedData[$key] = $value;
        //         $barang .= $value->barang->brg_kode . ' (' . $value->barang->brg_nama . ')(' . $value->gudang->gud_nama . '),';
        //     }
        // }

    // return $results;
    // return $barang;

    return view('barangkeluar.add', [
        'trx_code' => $noDocument,
        'transaksikeluar' => $transaksikeluar,
        'karyawan' => $karyawan,
        // 'barangList' => $barangList,
        // 'barang' => $barang,
        'gudang' => Gudang::all(),
        'penyimpanan' => Penyimpanan::all(),
        'divisi' => Divisi::all(),
    ]);
}

/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request)
{
    $noDocument = $this->getNoDocument('Keluar');
    // dd($request);
    $request->validate([
        'trx_code' => 'required',
        'karyawan_id' => 'required',
        'divisi_id' => 'required',
        'tanggal' => 'required|date',
        'total_barang' => 'required|integer',
        'trx_ket' => 'required|string',
        'items.*.brg_kode' => 'required',
        'items.*.brg_nama' => 'required',
        'items.*.gudang_id' => 'required',
        'items.*.dtl_jumlah' => 'required|integer',
    ],[
        'karyawan_id.required' => 'Data Karyawan Wajib di isi.',
        'divisi_id.required' => 'Tujuan Barang Wajib Di Isi.',
        'tanggal.required' => 'Tanggal Wajib Di isi.',
        'tanggal.date' => 'Format tanggal harus sesuai.',
        'total_barang.required' => 'Total Barang Tidak Boleh Kosong !.',
        'total_barang.integer' => 'Total harus berupa angka.',
        'trx_ket.required' => 'Keterangan tidak boleh kosong',
        'items.*.brg_kode.required' => 'Kode Barang Harus Di Isi.',
        'items.*.brg_nama.required' => 'Nama Barang Harus Di Isi.',
        'items.*.gudang_id.required' => 'Gudang harus dipilih.',
        'items.*.dtl_jumlah.required' => 'Jumlah Tidak Boleh Kosong.',
        'items.*.dtl_jumlah.integer' => 'Jumlah Harus berupa angka.',
    ]);

    try {
        DB::beginTransaction();

        $transaksi = new Transaksi([
            'type' => 'Keluar',
            'created_by' => auth()->user()->id,
            'karyawan_id' => $request->input('karyawan_id'),
            'divisi_id' => $request->input('divisi_id'),
            'trx_code' => $noDocument,
            'tanggal' => $request->input('tanggal'),
            'total_barang' => $request->input('total_barang'),
            'status' => 'Ready', // Assuming 'Ready' as default status
            'trx_ket' => $request->input('trx_ket'),
        ]);

        $transaksi->save();

        foreach ($request->input('items') as $item) {
            $detailTransaksi = new Detailtransaksi([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $item['barang_id'],
                'brg_nama' => $item['brg_nama'],
                'gudang_id' => $item['gudang_id'],
                'dtl_jumlah' => -($item['dtl_jumlah']),
            ]);

            $detailTransaksi->save();
        }

        DB::commit();

        return response()->json(['success' => true, 'message' => 'Data pengajuan barang keluar berhasil disimpan.']);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json(['success' => false, 'message' => 'Lengkapi Data Barang.', 'pesan' => $e->getMessage()]);
    }
}
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi, $id)
    {

        $data_transaksi = Transaksi::where('id', $id)->first();
        $detailTransaksi = Detailtransaksi::where('transaksi_id', $id)->get();

        return view('barangkeluar.detail', [
            'transaksi' => $data_transaksi,
            'detail' => $detailTransaksi,
            'gudang' => Gudang::all(),
            'penyimpanan' => Penyimpanan::all(),
            'divisi' => Divisi::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi, $id)
    {
        $dat_transaksi = Transaksi::with('detailTransaksi', 'karyawan')->where('id', $id)->first();
        $det_transaksi = Detailtransaksi::where('transaksi_id', $id)->get();

        $pelanggan = Karyawan::all();
        $karyawan = '';
        foreach ($pelanggan as $key => $value) {
            $karyawan .= $value->kar_idkar . ', ' . $value->kar_nama . ', ' . $value->kar_jabatan . '.';
        }

        // $pelanggan = Karyawan::all();
        // $karyawan = array();
        // $karyawan = '';
        // $idKaryawan = '';
        // foreach ($pelanggan as $key => $value) {
        //       $karyawan .= '{"label":"' . $value->kar_nama . ' (' . $value->kar_location . ')", "value": "' . $value->id . '"},';
        // }

        return view('barangkeluar.edit', [
            'dataTransaksi' => $dat_transaksi,
            'detailTransaksi' => $det_transaksi,
            'karyawanss' => $karyawan,
            // 'idKar'=> $idKaryawan,
            'gudang' => Gudang::all(),
            'penyimpanan' => Penyimpanan::all(),
            'divisi' => Divisi::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Validate the request data


         $request->validate([
            'trx_code'       => 'required',
            'karyawan_id'    => 'required|exists:tbl_karyawan,id',
            'divisi_id'      => 'required|exists:tbl_divisi,id', // Assuming you have a karyawans table
            'tanggal'        => 'required|date',
            'total_barang'   => 'required|numeric',
            'trx_ket'        => 'required|string',
            // 'items'          => 'required|array',  Assuming items is an array
        ]);

        // Fetch the existing Transaksi record
        $transaksi = Transaksi::findOrFail($id);

        // Update the Transaksi record

        $transaksi->update([
            'update_by'    => auth()->user()->id,
            'trx_code'     => $request->input('trx_code'),
            'karyawan_id'  => $request->input('karyawan_id'),
            'divisi_id'    => $request->input('divisi_id'),
            'tanggal'      => $request->input('tanggal'),
            'total_barang' => $request->input('total_barang'),
            'trx_ket'      => $request->input('trx_ket'),
        ]);


       $jumlah = $request->jumlah;
        // Update or create Detailtransaksi records
        // dd($request->input('items'));
            foreach ($request->id as $key => $n) {
            // $detTrans = Detailtransaksi::find($item['id']);
            $detTrans = Detailtransaksi::firstOrNew([
                'id' => $n
            ]);

            // $detTrans->transaksi_id = $transaksi->id;
            // $detTrans->barang_id = $item['barang_id'];
            // $detTrans->penyimpanan_id = $item['penyimpanan_id'];
            $detTrans->dtl_jumlah = -($jumlah[$key]);
            $detTrans->save();
        }
        return response()->json(['success' => true, 'message' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }

    public function cancel2($id)
    {
        try {
            DB::beginTransaction();

            // Fetch the Transaksi record by ID
            $transaksi = Transaksi::findOrFail($id);

            // Check if the status is "Ready"
            if ($transaksi->status === 'Ready') {
                // Update the status to "Cancel"
                $transaksi->update([
                    'status' => 'Cancel',
                    'update_by' => auth()->user()->id
                ]);

                // Commit the transaction
                DB::commit();

                return response()->json(['success' => true, 'message' => 'Transaction cancelled successfully']);
            } else {
                // Status is not "Ready," so don't perform the cancellation
                return response()->json(['success' => false, 'message' => 'Transaction cannot be cancelled.']);
            }
        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            DB::rollback();

            return response()->json(['success' => false, 'message' => 'Error cancelling transaction']);
        }
    }
}
