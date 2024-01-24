<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\ListMenuPermission;
use Illuminate\Support\Facades\DB;
use DataTables;
class PermissionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:permissions-index', ['only' => ['index']]);
        // $this->middleware('permission:permissions-store', ['only' => ['create', 'store']]);
        // $this->middleware('permission:permissions-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:permissions-erase', ['only' => ['destroy']]);
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
            '/lib/multiselect/css/bootstrap-select.min.css',
            '/lib/iconpicker/css/bootstrapicons-iconpicker.css'
        );

        $data['js'] = array(
            '/lib/datatables/datatables.min.js',
            '/lib/datatables/dataTables.bootstrap5.min.js',
            '/lib/select/chosen.jquery.min.js',
            '/lib/iconpicker/js/bootstrapicon-iconpicker.js',
            '/lib/multiselect/js/bootstrap-select.min.js',
            '/js/master/permission.js'
        );

       return view('permission.index',[
            'title'  => 'Permissions',
            'header' => '<i class="bi bi-sliders2-vertical"></i>&nbsp;<b>Data Permissions</b>',
            'data'   => $data,
            'roles'  => Role::all()
       ]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTable(Request $request){
        if ($request->ajax()) {
            $data = ListMenuPermission::select('*')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = "<a id='edit_btn' type='button' class='text-primary' data-name='".$row->menu_name."' data-id='".$row->id."' id='order_btn'><i class='bi bi-pencil-square'></i></a>
                    <a id='delete_btn' type='button' class='text-danger' data-id='".$row->id."' data-name=".$row->menu_name." id='order_btn'><i class='bi bi-trash3'></i></a>";
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
        $parents = Permission::where('has_child', 'Y')
        ->select('id','menu_name')
        ->get();

        return view('permission.add',[
            'roles'   => Role::all(),
            'parents' => $parents,
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
            'menu_name'  => 'required|string|max:255',
            'icon'       => 'required|string',
            'order_line' => 'required|string',
            'role'       => 'required',
        ]);

       DB::beginTransaction();
    //    try{
            $data = [
                'menu_name'  => $request->menu_name,
                'route_name' => $request->route_name,
                'icon'       => $request->icon,
                'order_line' => $request->order_line,
                'parent_id'  => ($request->parent_id) ? $request->parent_id : 0,
                'has_route'  => ($request->is_route == "true") ? 'Y' : 'N',
                'has_child'  => ($request->has_child == "true") ? 'Y' : 'N',
            ];

            $index  = $request->index;
            $create = $request->create;
            $edit   = $request->edit;
            $erase  = $request->erase;
            $role   = $request->role;

            if($index == "true"){
                $this->generatePermission($data, '-index',$role);
            }
            if($create == "true"){
                $this->generatePermission($data, '-store',$role);
            }
            if($edit == "true"){
                $this->generatePermission($data, '-edit',$role);
            }
            if($erase == "true"){
                $this->generatePermission($data, '-erase',$role);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->username.' save successfully !',
            ], 200);

    //    }catch (\Exception $exception) {
    //     DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => "Failed to save !"
    //         ], 401);
    //     }
    }

      /**
     * @param $data
     * @param $suffix
     * @param $role array
     */
    function generatePermission($data, $suffix = '-index', $role){

        $data['name']   = strtolower($data['menu_name'] . $suffix);
        $check          = Permission::where('name', '=', $data['menu_name'] . $suffix)->first();

        if ($check) {
            Permission::where('id', '=', $check->id)->update($data);
            $this->assignPermissionToRoleUpdate($check->id, $role);
        } else {
            $permission =  Permission::create($data);
            $this->assignPermissionToRoleInsert($permission, $role);
        }
    }


     /**
     * @param $permission
     * @param $role array
     */
    protected function assignPermissionToRoleInsert($permission, $role)
    {

        foreach($role as $rl){
            $roles       = Role::where('id', '=', $rl)->first();
            $permissions = Permission::where('id', '=', $permission->id)->first();

            $permissions->assignRole($roles->name);
            $this->assignPermissionToUser($permission, $roles);
        }

    }

     /**
     * @param $permission
     * @param $role array
     */
    protected function assignPermissionToRoleUpdate($permission, $role)
    {

        foreach($role as $rl){

            $roles       = Role::where('id', '=', $rl)->first();
            $permissions = Permission::where('id', '=', $permission)->first();
            $permissions->assignRole($roles->name);
            $this->assignPermissionToUser($permissions, $roles);
        }

    }

     /**
     * @param $permissions
     * @param $role
     */
    function assignPermissionToUser($permissions, $role){


        $users = User::leftJoin('model_has_roles', 'model_has_roles.model_id', 'users.id')
            ->select('users.*')
            ->where('model_has_roles.role_id', '=', $role->id)
            ->get();

        if ($users) {
            foreach ($users as $user) {
                $user->givePermissionTo($permissions->name);
            }
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
    public function edit($id, Request $request)
    {
        $parents = Permission::where('has_child', 'Y')
        ->select('id','menu_name')
        ->get();

        $data =  ListMenuPermission::where('menu_name', $request->menu_name)->first();

        return view('permission.edit',[
            'roles'   => Role::all(),
            'parents' => $parents,
            'data'    => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_name'  => 'required|string|max:255',
            'icon'       => 'required|string',
            'order_line' => 'required|string',
            'role'       => 'required',
        ]);

       DB::beginTransaction();
       try{
            $data = [
                'menu_name'  => $request->menu_name,
                'route_name' => $request->route_name,
                'icon'       => $request->icon,
                'order_line' => $request->order_line,
                'parent_id'  => ($request->parent_id) ? $request->parent_id : 0,
                'has_route'  => ($request->is_route == "true") ? 'Y' : 'N',
                'has_child'  => ($request->has_child == "true") ? 'Y' : 'N',
            ];

            $index  = $request->index;
            $create = $request->create;
            $edit   = $request->edit;
            $erase  = $request->erase;
            $role   = $request->role;

            if($index == "true"){
                $this->generatePermission($data, '-index',$role);
            }
            if($create == "true"){
                $this->generatePermission($data, '-store',$role);
            }
            if($edit == "true"){
                $this->generatePermission($data, '-edit',$role);
            }
            if($erase == "true"){
                $this->generatePermission($data, '-erase',$role);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->username.' save successfully !',
            ], 200);

       }catch (\Exception $exception) {
        DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Failed to save !"
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $details = Permission::where("menu_name", "=", $request->name)->first();
        // dd($details);
        if ($details) {
            $details->delete();
            return response()->json([
                'success' => true,
                'message' => $request->input('name').' successfully deleted !',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => "Failed to deleted !"
        ], 401);
    }
}
