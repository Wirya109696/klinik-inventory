<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Routing\Controller;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customer-index', ['only' => ['index']]);
        $this->middleware('permission:customer-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer-erase', ['only' => ['destroy']]);
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
            '/js/customerjs/customer.js'
        );

        return view('customer.index',[
            'title'  => 'Customer',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Data Karyawan Pengambil Barang</b>',
            'data'   => $data
        ]);
    }

    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Customer::all();
            return DataTables::of($data)
                ->addIndexColumn()
                 ->addColumn('action', function($row){
                    $actionBtn = "
                    <div class='dropdown'>
                        <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                        <i class='bx bx-dots-vertical-rounded'></i>
                        </button>
                        <div class='dropdown-menu'>

                        <a id='edit_btn' type='button' class='dropdown-item' data-id='".$row->id."'><i class='bi bi-pencil-square'></i> Edit</a>
                        <a id='delete_btn' type='button' class='dropdown-item' data-id='".$row->id."' data-cus_nama='".$row->cus_nama."'><i class='bi bi-trash'></i> Delete</a>
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
        return view('customer.add',[
            'customer' => Customer::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cus_nama' => 'required|string|max:255|unique:customers',
            'cus_dept'    => 'required|string|max:255|',
            'cus_location'         => 'required|string|max:255|',
        ]);

        DB::beginTransaction();
        $user = Customer::create([
            'cus_nama' => $data['cus_nama'],
            'cus_dept'    => $data['cus_dept'],
            'cus_location'         => $data['cus_location'],
        ]);


        if($user){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('cus_nama').' save successfully !',
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return 'ID ADALAH ' . $customer->id;
        // $customer = Customer::all();
        return view('customer.edit',[
            'data'  => $customer,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $rules = [
            'cus_nama' => 'required|string|max:255',
            'cus_dept'    => 'required|string|max:255',
            'cus_location'         => 'required|string|max:255',
        ];

        if($request->cus_nama != $request->cus_nama_old){
            $rules['cus_nama'] = 'required|unique:customers';
        }


        $validatedData = $request->validate($rules);
        DB::beginTransaction();

        //update data user
        $data = Customer::where('id', $customer->id)
        ->update($validatedData);


        if($data){
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->input('cus_nama').' update successfully !',
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($customer)
    {
        $data = Customer::find($customer);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => $data->cus_nama.' successfully deleted !',
        ], 200);
    }
}
