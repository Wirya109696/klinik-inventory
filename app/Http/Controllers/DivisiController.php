<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Http\Requests\StoreDivisiRequest;
use App\Http\Requests\UpdateDivisiRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\DB;

class DivisiController extends Controller
{
       public function __construct()
    {
        $this->middleware('permission:divisi-index', ['only' => ['index']]);
        $this->middleware('permission:divisi-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:divisi-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:divisi-erase', ['only' => ['destroy']]);
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
            '/js/divisijs/divisi.js'
        );

        return view('divisi.index',[
            'title'  => 'Divisi',
            'header' => '<i class="bi bi-diagram-3"></i>&nbsp;<b>Data Divisi</b>',
            'data'   => $data
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Divisi::all();
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
                        <a id='delete_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-div_nama='".$row->div_nama."'><i class='bi bi-trash'></i> Delete</a>
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
         return view('divisi.add',[
            'divisi' => Divisi::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDivisiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $data = $request->validate([
            'div_nama'   => 'required|string|max:255|unique:tbl_divisi',
            'div_deskripsi'        => 'required',
        ],
          [
             'unique' => 'divisi :input Telah Ada !',
          ]);

        DB::beginTransaction();
        $user = Divisi::create([
            'div_nama'  => $data['div_nama'],
            'div_deskripsi'       => $data['div_deskripsi'],
        ]);

        //get role dan konek role to user
        // $roles = Role::where('id', '=', $request->role_id)->first();
        // $user->assignRole($roles->name);

        // $this->setPermission($request, $user);

        if($user){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('div_nama').' save successfully !',
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
     * @param  \App\Models\Divisi  $divisi
     * @return \Illuminate\Http\Response
     */
    public function show(Divisi $divisi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Divisi  $divisi
     * @return \Illuminate\Http\Response
     */
    public function edit(Divisi $divisi)
    {
        return view('divisi.edit',[
            'data'  => $divisi,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDivisiRequest  $request
     * @param  \App\Models\Divisi  $divisi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Divisi $divisi)
    {
     $rules = [
            'div_nama'        => 'required|string|max:255',
            'div_deskripsi'   => 'required',
        ];

        if($request->div_nama != $request->div_nama_old){
            $rules['div_nama'] = 'required|unique:tbl_divisi';
        }


        $validatedData = $request->validate($rules);
        DB::beginTransaction();

        //update data user
        $data = Divisi::where('id', $divisi->id)
        ->update($validatedData);


        if($data){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('div_nama').' update successfully !',
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
     * @param  \App\Models\Divisi  $divisi
     * @return \Illuminate\Http\Response
     */
    public function destroy($divisi)
    {
        $data = Divisi::find($divisi);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => $data->div_nama.' successfully deleted !',
        ], 200);
    }
}
