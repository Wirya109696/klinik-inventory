<?php

namespace App\Http\Controllers;

use App\Models\Supplier;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:supplier-index', ['only' => ['index']]);
        $this->middleware('permission:supplier-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:supplier-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:supplier-erase', ['only' => ['destroy']]);
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
            '/js/supplierjs/supplier.js'
        );

        return view('supplier.index',[
            'title'  => 'Supplier',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Data Supplier</b>',
            'data'   => $data
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Supplier::all();
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
                        <a id='delete_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-sup_name='".$row->sup_name."'><i class='bi bi-trash'></i> Delete</a>
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
        return view('supplier.add',[
            'supplier' => Supplier::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSupplierRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sup_name'      => 'required|string|max:255|unique:tbl_supplier',
            'sup_phone'            => 'required|string|unique:tbl_supplier',
            'sup_addres'           => 'required|string',
        ],
          [
             'unique' => 'Data Supplier :input Telah Digunakan !',
          ]);

        DB::beginTransaction();
        $user = Supplier::create([
            'sup_name'    => $data['sup_name'],
            'sup_phone'            => $data['sup_phone'],
            'sup_addres'           => $data['sup_addres'],
        ]);


        if($user){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('sup_name').' save successfully !',
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
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit',[
            'data'  => $supplier,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupplierRequest  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $rules = [
            'sup_name'     => 'required|string|max:255',
            'sup_phone'             => 'required|string',
            'sup_addres'             => 'required|string',
        ];

        if($request->sup_name != $request->sup_name_old){
            $rules['sup_name'] = 'required|unique:tbl_supplier';
        }


        $validatedData = $request->validate($rules);
        DB::beginTransaction();

        //update data user
        $data = Supplier::where('id', $supplier->id)
        ->update($validatedData);


        if($data){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('sup_name').' update successfully !',
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
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($supplier)
    {
        $data = Supplier::find($supplier);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => $data->sup_name.' successfully deleted !',
        ], 200);
    }
}
