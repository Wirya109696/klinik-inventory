<?php

namespace App\Http\Controllers;

use App\Models\Penyimpanan;
use App\Http\Requests\StorePenyimpananRequest;
use App\Http\Requests\UpdatePenyimpananRequest;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\DB;

class PenyimpananController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:penyimpanan-index', ['only' => ['index']]);
        $this->middleware('permission:penyimpanan-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:penyimpanan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:penyimpanan-erase', ['only' => ['destroy']]);
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
            '/js/penyimpananjs/penyimpanan.js'
        );

        return view('penyimpanan.index',[
            'title'  => 'Penyimpanan',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Data Penyimpanan</b>',
            'data'   => $data
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Penyimpanan::all();
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
                        <a id='delete_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-pen_nama='".$row->pen_nama."'><i class='bi bi-trash'></i> Delete</a>
                        </div>
                    </div>";
                    return $actionBtn;
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
          return view('penyimpanan.add',[
            'penyimpanan' => Penyimpanan::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePenyimpananRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          $request->validate([
            'pen_nama'      => 'required|string|max:255|unique:tbl_penyimpanan',
            'pen_desc'      => 'required',
        ],
          [
             'unique'            => 'Tempat Penyimpanan :input Telah Ada !',
             'pen_nama.required' => 'Lokasi Penyimpanan Harus Di Isi !',
             'pen_desc.required' => 'keterangan harus di isi !'
          ]);

        DB::beginTransaction();
        $user = Penyimpanan::create([
            'pen_nama'      => $request->input('pen_nama'),
            'pen_desc'      => $request->input('pen_desc'),
        ]);

        //get role dan konek role to user
        // $roles = Role::where('id', '=', $request->role_id)->first();
        // $user->assignRole($roles->name);

        // $this->setPermission($request, $user);

        if($user){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('pen_nama').' save successfully !',
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
     * @param  \App\Models\Penyimpanan  $penyimpanan
     * @return \Illuminate\Http\Response
     */
    public function show(Penyimpanan $penyimpanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penyimpanan  $penyimpanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penyimpanan $penyimpanan)
    {
        return view('penyimpanan.edit',[
            'data'  => $penyimpanan,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePenyimpananRequest  $request
     * @param  \App\Models\Penyimpanan  $penyimpanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penyimpanan $penyimpanan)
    {
        $rules = [
            'pen_nama'      => 'required|string|max:255',
            'pen_desc'      => 'required',
        ];

        $messages = [
        'pen_nama.required' => 'Lokasi Penyimpanan harus di isi !',
        // 'pen_nama.string' => 'The storage name mu.',
        'pen_nama.max' => 'The storage name must not exceed :max characters.',
        'pen_nama.unique' => 'Nama Lokasi Penyimpanan Telah ada !.',
        'pen_desc.required' => 'Keterangan harus di isi.',
        // Add more messages as needed
        ];

        if($request->pen_nama != $request->pen_nama_old){
            $rules['pen_nama'] = 'required|unique:tbl_penyimpanan';
        }


        $validatedData = $request->validate($rules, $messages);
        DB::beginTransaction();

        //update data user
        $data = Penyimpanan::where('id', $penyimpanan->id)
        ->update($validatedData);


        if($data){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('pen_nama').' update successfully !',
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
     * @param  \App\Models\Penyimpanan  $penyimpanan
     * @return \Illuminate\Http\Response
     */
    public function destroy($penyimpanan)
    {
         $data = Penyimpanan::find($penyimpanan);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => $data->pen_nama.' successfully deleted !',
        ], 200);
    }
}
