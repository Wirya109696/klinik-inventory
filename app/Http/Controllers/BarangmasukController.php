<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Detailtransaksi;
use App\Models\Gudang;
use App\Models\Penyimpanan;
use App\Models\Supplier;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class BarangmasukController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:masuk-index', ['only' => ['index']]);
        $this->middleware('permission:masuk-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:masuk-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:masuk-erase', ['only' => ['destroy']]);
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
            '/js/barangmasukjs/barangmasuk.js'
        );

        return view('barangmasuk.index',[
            'title'  => 'Transaksi Masuk',
            'header' => '<i class="bi bi-arrow-bar-right"></i>&nbsp;<b>Barang Masuk</b>',
            'data'   => $data,
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Transaksi::where('type', 'Masuk')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                 ->addColumn('action', function($row){
                    $actionBtn = "
                    <div class='dropdown'>
                        <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                        <i class='bx bx-dots-vertical-rounded'></i>
                        </button>
                        <div class='dropdown-menu'>
                        <a id='detailmasuk_btn' type='button' class='dropdown-item' data-id='".$row->id."'><i class='bi bi bi-file-lock2'></i> Detail</a>
                        <a id='edit_btn' type='button' class='dropdown-item' data-id='".$row->id."'><i class='bi bi-pencil-square'></i> Edit</a>
                        <a id='cancel_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-trx_code='".$row->trx_code."'><i class='bi bi-backspace-reverse'></i> Cancel</a>

                        </div>
                    </div>";
                    return $actionBtn;
                })
                ->addColumn('sup_name', function($row){
                    return $row->supplier->sup_name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getNoDocument($inputtype)
    {
       $kode = 1;

    // Get the latest ID from the Transaksi table based on the input type
    $latestTransaksi = Transaksi::where('type', $inputtype)->latest()->first();

    // Handle the case when there are no records in the Transaksi table yet
    if (!$latestTransaksi) {
        return 'KD/IN/'. str_pad($kode, 4, "0", STR_PAD_LEFT);
    }

    // Increment the document number only if the input type is not "keluar"
    if ($inputtype == 'Masuk') {
        $kode = intval(substr($latestTransaksi->trx_code, -4)) + 1;
    }

    return 'KD/IN/' . str_pad($kode, 4, "0", STR_PAD_LEFT);
    }

    public function autocomplete(Request $request)
    {
        $term = $request->input('term');

        $results = Barang::select('brg_nama','brg_kode','id')->where('brg_kode', 'LIKE', '%' . $term . '%')
                        ->orWhere('brg_nama', 'LIKE', '%' . $term . '%')->first();
        // var_dump($results);

        return response()->json([
            'barang_id' => $results->id,
            'brg_kode' => $results->brg_kode,
            'brg_nama' => $results->brg_nama,
        ], 200);
    }

   public function getPenyimpananByGudang(Request $request, $penyimpananId)
    {
        // Assuming you have a model named Penyimpanan
        // $penyimpanan = Penyimpanan::where('gudang_id', $gudangId)->get();
        $gudang = Gudang::where('penyimpanan_id', $penyimpananId)->get();

        return response()->json($gudang);
    }
    // public function autocomplete2(Request $request)
    // {
    //     $term = $request->input('term');

    //     // $results = Barang::select('brg_nama','id')->where('brg_kode', 'LIKE', '%' . $term . '%')
    //     //                 ->orWhere('brg_nama', 'LIKE', '%' . $term . '%')->all();
    //     $results =  Barang::all();
    //     $barang = array();
    //     foreach ($results as $key => $value) {
    //         $barang[] = $value->brg_nama;
    //     }
    //     // var_dump($results);

    //     return response()->json([
    //         'results' => $results,
    //         'barang' => $barang,
    //         // 'brg_nama' => $results->brg_nama,
    //         // 'brg_nama' => $results->brg_nama,
    //     ], 200);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    $transaksimasuk = new Transaksi(['type' => 'Masuk']);
    // $barangList = Barang::pluck('brg_nama', 'id')->toArray();
    $noDocument = $this->getNoDocument('Masuk');
    $results =  Barang::all();
    $barang = '';
    foreach ($results as $key => $value) {
        $barang .= $value->brg_kode . ' (' . $value->brg_nama . '),';
    }

    return view('barangmasuk.add', [
        'trx_code' => $noDocument,
        'transaksimasuk' => $transaksimasuk,
        'supplier' => Supplier::all(),
        // 'barangList' => $barangList,
        'barang' => $barang,
        'gudang' => Gudang::all(),
        'penyimpanan' => Penyimpanan::all(),
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

    $noDocument = $this->getNoDocument('Masuk');
    // dd($request);
    $request->validate([
        'trx_code' => 'required',
        'supplier_id' => 'required',
        'tanggal' => 'required|date',
        'total_barang' => 'required|integer',
        'trx_ket'      => 'required|string',
        'items.*.brg_kode' => 'required',
        'items.*.brg_nama' => 'required',
        'items.*.gudang_id' => 'required',
        'items.*.dtl_jumlah' => 'required|integer',
    ]);

    try {
        DB::beginTransaction();

        $transaksi = new Transaksi([
            'type' => 'Masuk',
            'created_by' => auth()->user()->id,
            'supplier_id' => $request->input('supplier_id'),
            'trx_code' => $noDocument,
            'tanggal' => $request->input('tanggal'),
            'total_barang' => $request->input('total_barang'),
            'status' => 'Ready', // Assuming 'Ready' as default status
            'trx_ket'      => $request->input('trx_ket'),
        ]);

        $transaksi->save();

        foreach ($request->input('items') as $item) {
            $detailTransaksi = new Detailtransaksi([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $item['barang_id'],
                'brg_nama' => $item['brg_nama'],
                'gudang_id'=> $item['gudang_id'],
                'dtl_jumlah' => $item['dtl_jumlah'],
            ]);

            $detailTransaksi->save();
        }


        DB::commit();

        return response()->json(['success' => true, 'message' => 'Data pengajuan barang masuk berhasil disimpan.']);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.', 'pesan' => $e->getMessage()]);
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

        return view('barangmasuk.detail', [
            'transaksi' => $data_transaksi,
            'detail' => $detailTransaksi,
            'supplier' => Supplier::all(),
            'penyimpanan' => Penyimpanan::all(),
            'gudang' => Gudang::all(),
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
        $dat_transaksi = Transaksi::where('id', $id)->first();
        $det_transaksi = Detailtransaksi::where('transaksi_id', $id)->get();

        $results =  Barang::all();
        $barang = '';
        foreach ($results as $key => $value) {
            $barang .= $value->brg_kode . ' (' . $value->brg_nama . '),';
        }

        return view('barangmasuk.edit', [
            'dataTransaksi' => $dat_transaksi,
            'detailTransaksi' => $det_transaksi,
            'supplier' => Supplier::all(),
            'penyimpanan' => Penyimpanan::all(),
            'gudang' => Gudang::all(),
            'barang' => $barang
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
            'supplier_id'    => 'required|exists:tbl_supplier,id', // Assuming you have a suppliers table
            'tanggal'        => 'required|date',
            'total_barang'   => 'required|numeric',
            'trx_ket'        => 'required|string',
            // 'items'          => 'required|array', // Assuming items is an array
        ]);

        // Fetch the existing Transaksi record
        $transaksi = Transaksi::findOrFail($id);

       // Update the Transaksi record
        $transaksi->update([
            'update_by' => auth()->user()->id,
            'trx_code' => $request->input('trx_code'),
            'supplier_id'    => $request->input('supplier_id'),
            'tanggal'        => $request->input('tanggal'),
            'total_barang'   => $request->input('total_barang'),
            'trx_ket'   => $request->input('trx_ket'),
        ]);


        // Update or create Detailtransaksi records


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
            $detTrans->dtl_jumlah = $jumlah[$key];
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

    public function cancel($id)
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

       public function deleteItem($id)
    {
        try {
            // Find the item by ID
            $item = Detailtransaksi::findOrFail($id);

            // Delete the item
            $item->delete();

            return response()->json(['success' => true, 'message' => 'Barang Berhasil Dihapus !']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting item']);
        }
    }
}
