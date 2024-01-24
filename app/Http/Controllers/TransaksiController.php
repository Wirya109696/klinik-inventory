<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransaksiController extends Controller
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
            '/js/transaksijs/transaksi.js'
        );

        return view('transaksi.index',[
            'title'  => 'Transaksi',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Data Transaksi</b>',
            'data'   => $data
        ]);
    }

    public function getTable(Request $request){

        // if ($request->ajax()) {
        //     $data = Transaksi::all();
        //     return Datatables::of($data)
        //         ->addIndexColumn()
        //          ->addColumn('action', function($row){
        //             $actionBtn = "
        //             <div class='dropdown'>
        //                 <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
        //                 <i class='bx bx-dots-vertical-rounded'></i>
        //                 </button>
        //                 <div class='dropdown-menu'>
        //                 <a id='detail_btn' type='button' class='dropdown-item' data-id='".$row->id."'><i class='bi bi bi-file-lock2'></i> Detail</a>
        //                 <a id='edit_btn' type='button' class='dropdown-item' data-id='".$row->id."'><i class='bi bi-pencil-square'></i> Edit</a>
        //                 <a id='delete_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-sup_name='".$row->sup_name."'><i class='bi bi-trash'></i> Delete</a>
        //                 </div>
        //             </div>";
        //             return $actionBtn;
        //         })
        //         ->rawColumns(['action'])
        //         ->make(true);
        // }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transaksi.add',[
            'transaksi' => Transaksi::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransaksiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'trx_code'     => 'required|string|max:255|unique:transaksis',
            'type'               => 'required|string|max:255|unique:transaksis',
            'tanggal'            => 'required|date',
            'total_barang'       => 'required|string',
            'status'             => 'required|string',
        ]);

        DB::beginTransaction();
        $user = Transaksi::create([
            'trx_code'      => $data['trx_code'],
            'type'                => $data['type'],
            'tanggal'             => $data['tanggal'],
            'total_barang'        => $data['total_status'],
            'status'              => $data['status'],
        ]);

        //get role dan konek role to user
        // $roles = Role::where('id', '=', $request->role_id)->first();
        // $user->assignRole($roles->name);

        // $this->setPermission($request, $user);

        if($user){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('trx_code').' save successfully !',
            ], 200);
        }else{
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Failed to save !"
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransaksiRequest  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
    {
        //
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
}
