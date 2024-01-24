<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Http\Requests\StoreGudangRequest;
use App\Http\Requests\UpdateGudangRequest;
use App\Models\Penyimpanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
      public function __construct()
    {
        $this->middleware('permission:gudang-index', ['only' => ['index']]);
        $this->middleware('permission:gudang-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:gudang-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:gudang-erase', ['only' => ['destroy']]);
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
            '/js/gudangjs/gudang.js'
        );

        return view('gudang.index',[
            'title'  => 'Gudang',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Data Gudang</b>',
            'data'   => $data
        ]);
    }

     public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Gudang::all();
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
                        <a id='delete_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-gud_nama='".$row->gud_nama."'><i class='bi bi-trash'></i> Delete</a>
                        </div>
                    </div>";
                    return $actionBtn;
                })
                ->addColumn('pen_nama', function($row){
                    return $row->penyimpanan->pen_nama;
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
           return view('gudang.add',[
            'gudang' => Gudang::all(),
            'penyimpanan' => Penyimpanan::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGudangRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'gud_nama'   => 'required|string|max:255|unique:tbl_gudang',
            'penyimpanan_id' => 'required',
            'gud_desc'        => 'required',
        ],
          [
             'unique' => 'Data Gudang :input Telah Ada !',
          ]);

        DB::beginTransaction();
        $user = Gudang::create([
            'gud_nama'  => $data['gud_nama'],
            'penyimpanan_id'  => $data['penyimpanan_id'],
            'gud_desc'       => $data['gud_desc'],
        ]);

        //get role dan konek role to user
        // $roles = Role::where('id', '=', $request->role_id)->first();
        // $user->assignRole($roles->name);

        // $this->setPermission($request, $user);

        if($user){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('gud_nama').' save successfully !',
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
     * @param  \App\Models\Gudang  $gudang
     * @return \Illuminate\Http\Response
     */
    public function show(Gudang $gudang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gudang  $gudang
     * @return \Illuminate\Http\Response
     */
    public function edit(Gudang $gudang)
    {
         return view('gudang.edit',[
            'data'  => $gudang,
            'penyimpanan' => Penyimpanan::all()
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGudangRequest  $request
     * @param  \App\Models\Gudang  $gudang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gudang $gudang)
    {
        $rules = [
            'gud_nama'        => 'required|string|max:255',
            'penyimpanan_id'  => 'required',
            'gud_desc'        => 'required',
        ];

        if($request->gud_nama != $request->gud_nama_old){
            $rules['gud_nama'] = 'required|unique:tbl_gudang';
        }


        $validatedData = $request->validate($rules);
        DB::beginTransaction();

        //update data user
        $data = Gudang::where('id', $gudang->id)
        ->update($validatedData);


        if($data){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('gud_nama').' update successfully !',
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
     * @param  \App\Models\Gudang  $gudang
     * @return \Illuminate\Http\Response
     */
    public function destroy($gudang)
    {
         $data = Gudang::find($gudang);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => $data->gud_nama.' successfully deleted !',
        ], 200);
    }
}
