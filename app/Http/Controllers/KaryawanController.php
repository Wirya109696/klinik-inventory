<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:karyawan-index', ['only' => ['index']]);
        $this->middleware('permission:karyawan-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:karyawan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:karyawan-erase', ['only' => ['destroy']]);
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
            '/js/karyawanjs/karyawan.js'
        );

        return view('karyawan.index',[
            'title'  => 'Karyawan',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Data Karyawan Pengambil Barang</b>',
            'data'   => $data
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Karyawan::all();
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
                        <a id='delete_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-kar_nama='".$row->kar_nama."'><i class='bi bi-trash'></i> Delete</a>
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
        return view('karyawan.add',[
            'karyawan' => karyawan::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreKaryawanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kar_idkar'      => 'required|string|min:8|max:255|unique:tbl_karyawan',
            'kar_nama'       => 'required|string|max:255|',
            'kar_jabatan'       => 'required|string|max:255|',
            // 'kar_location'   => 'required|string|max:255|',
        ],
          [
             'unique' => 'Nomor Id Card :input Telah Digunakan !',
             'kar_idkar.numeric' => 'Id Harus Berupa Angka',
             'kar_idkar.min' => 'Minimal Karakter ID Karyawan 8 !',
             'kar_idkar.required' => 'Nomor ID Karyawan harus di isi !',
             'kar_nama.required' => 'Nama Karyawan Harus di isi !',
             'kar_jabatan.required' => 'Jabatan Harus Di Isi !'
          ]);

        DB::beginTransaction();
        $user = Karyawan::create([
            'kar_idkar'     => $data['kar_idkar'],
            'kar_nama'      => $data['kar_nama'],
            'kar_jabatan'      => $data['kar_jabatan'],
            // 'kar_location'  => $data['kar_location'],
        ]);


        if($user){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('kar_nama').' save successfully !',
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
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function show(Karyawan $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit',[
            'data'  => $karyawan,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKaryawanRequest  $request
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $rules = [
            'kar_idkar'    => 'required|string|min:8|max:255',
            'kar_nama'     => 'required|string|max:255',
            'kar_jabatan'     => 'required|string|max:255',
            // 'kar_location' => 'required|string|max:255',
        ];

        $message = [
             'unique' => 'Nomor Id Card :input Telah Digunakan !',
             'kar_idkar.numeric' => 'Id Harus Berupa Angka',
             'kar_idkar.min' => 'Minimal Karakter ID Karyawan 8 !',
             'kar_idkar.required' => 'Nomor ID Karyawan harus di isi !',
             'kar_nama.required' => 'Nama Karyawan Harus di isi !',
             'kar_jabatan.required' => 'Jabatan Harus Di Isi !'
        ];


        if($request->kar_idkar != $request->kar_idkar_old){
            $rules['kar_idkar'] = 'required|unique:tbl_karyawan';
        }



        $validatedData = $request->validate($rules, $message);
        DB::beginTransaction();

        //update data user
        $data = Karyawan::where('id', $karyawan->id)
        ->update($validatedData);


        if($data){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('kar_nama').' update successfully !',
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
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function destroy($karyawan)
    {
        $data = Karyawan::find($karyawan);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => $data->kar_nama.' successfully deleted !',
        ], 200);
    }
}
