<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles-index', ['only' => ['index']]);
        $this->middleware('permission:roles-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles-erase', ['only' => ['destroy']]);
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
            '/lib/datatables/dataTables.bootstrap5.min.css',
			'/lib/select/component-chosen.min.css',
            '/lib/select/bootstrap-chosen.css',  
        );
        
        $data['js'] = array(
            '/lib/datatables/datatables.min.js',
            '/lib/datatables/dataTables.bootstrap5.min.js',
            '/lib/select/chosen.jquery.min.js', 
            '/js/master/role.js'
        );
        return view('role.index',[
            'title'  => 'Roles',
            'header' => '<i class="bi bi-sliders2-vertical"></i>&nbsp;<b>Data Roles</b>',
            'data'   => $data,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTable(Request $request){

        if ($request->ajax()) {
            $data = Role::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = "<a id='edit_btn' type='button' class='text-primary' data-id='".$row->id."' id='order_btn'><i class='bi bi-pencil-square'></i></a>
                    <a id='delete_btn' type='button' class='text-danger' data-id='".$row->id."' data-name='".$row->name."' id='order_btn'><i class='bi bi-trash3'></i></a>";
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
        return view('role.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|unique:roles',
            'guard_name'  => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $user = Role::create([
            'name'        => $data['name'],
            'guard_name'  => $data['guard_name'],
            'description' => $data['description'],
        ]);

        if($user){
            return response()->json([
                'success' => true,
                'message' => $request->input('username').' save successfully !',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Failed to save !"
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('role.edit',[
            'data'  => $role,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $rules = [
            'name'        => 'required|string',
            'guard_name'  => 'required|string|max:255',
            'description' => 'required|string',
        ];
       
        if($request->name != $request->name_old){
            $rules['name'] = 'required|unique:roles';
        }

        $validatedData =  $request->validate($rules);

        $data = Role::where('id', $role->id)
        ->update($validatedData);
       
        if($data){
            return response()->json([
                'success' => true,
                'message' => $request->input('name').' update successfully !',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Failed to update !"
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role, Request $request)
    {
        $data = Role::find($role);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => $request->input('name').' successfully deleted !',
        ], 200);
    }
}
