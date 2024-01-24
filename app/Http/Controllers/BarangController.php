<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:barang-index', ['only' => ['index']]);
        $this->middleware('permission:barang-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:barang-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:barang-erase', ['only' => ['destroy']]);
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

        return view('barang.index',[
            'title'  => 'Barang',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Data Barang</b>',
            'data'   => $data
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Barang::all();
            return Datatables::of($data)
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
                  ->addColumn('kat_nama', function($row){
                    return $row->kategori->kat_nama;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('barang.add',[
            'barang' => Barang::all(),
            'kategori' => Kategori::all()
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
        $request->validate([
            'brg_kode'      => 'required|string|max:255|unique:tbl_barang',
            'brg_nama'      => 'required|string|max:255|unique:tbl_barang',
            'kategori_id'   => 'required',
            'brg_satuan'    => 'required|string',
            'brg_min'       => 'required|numeric',
            'brg_desc'      => 'required',
        ],
          [
             'unique' => 'Barang :input Telah Ada !',
          ]);

        DB::beginTransaction();
        $user = Barang::create([
            'brg_kode'      => $request->input('brg_kode'),
            'brg_nama'      => $request->input('brg_nama'),
            'kategori_id'   => $request->input('kategori_id'),
            'brg_satuan'    => $request->input('brg_satuan'),
            'brg_min'       => $request->input('brg_min'),
            'brg_desc'      => $request->input('brg_desc'),
        ]);

        //get role dan konek role to user
        // $roles = Role::where('id', '=', $request->role_id)->first();
        // $user->assignRole($roles->name);

        // $this->setPermission($request, $user);

        if($user){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('brg_nama').' save successfully !',
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
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit',[
            'data'  => $barang,
            'kategori' => Kategori::all()
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBarangRequest  $request
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $barang)
    {
        $rules = [
            'brg_kode'      => 'required|string|max:255',
            'brg_nama'      => 'required|string|max:255',
            'kategori_id'   => 'required',
            'brg_satuan'    => 'required|string',
            'brg_min'       => 'required|numeric',
            'brg_desc'      => 'required',
        ];

        if($request->brg_kode != $request->brg_kode_old){
            $rules['brg_kode'] = 'required|unique:tbl_barang';
        }


        $validatedData = $request->validate($rules);
        DB::beginTransaction();

        //update data user
        $data = Barang::where('id', $barang->id)
        ->update($validatedData);


        if($data){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('brg_nama').' update successfully !',
            ], 200);
        }else{
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Failed to update !"
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy($barang)
    {
        $data = Barang::find($barang);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => $data->brg_nama.' successfully deleted !',
        ], 200);
    }
}
