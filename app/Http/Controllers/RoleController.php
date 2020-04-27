<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        return view('admin.role.index');
    }
    
    public function create()
    {
        $permission = Permission::get();
        return view('admin.role.create',compact('permission'));
    }
    
    public function store(RoleRequest $request)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();
            
            try{
                
               $role = Role::create(['name' => $request->name]);
               $role->syncPermissions($request->input('permission'));
                
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'Permission Added Successfully'
                ],200);
        
                
            }catch (\Illuminate\Database\QueryException $e)
            {
                DB::rollback();
    
                $error = $e->getMessage();
    
                return response()->json([
                    'error' => $error
                ],200);
            }
        }
    }
    
    public function getData()
    {
        $role = Role::get();
        
        return DataTables::of($role)
            ->addIndexColumn()
            ->editColumn('action', function ($role){
                $return = "<div class='btn-group'>";
                if(!empty($role->name))
                {
                    $return .= "
                            <a href=\"/role/edit/$role->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                            <a rel=\"$role->id\" rel1=\"delete-role\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                                  ";
                }
                $return .= "</div>";
                
                return $return;
            })
            
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        
        $permission = Permission::get();
        
        $role_permission = DB::table('role_has_permissions')
                                ->where('role_has_permissions.role_id',$id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();
        
        return view('admin.role.edit',compact('role', 'permission' ,'role_permission'));
    }
    
    public function update(RoleRequest $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
        
            try{
                // Step 1 : Update Role
                $role = Role::findOrFail($id);
                $role->name = $request->name;
                $role->update();
            
                $role->syncPermissions($request->input('permission'));
            
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'Role Updated Successfully'
                ],200);
            
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
    
                $error = $e->getMessage();
    
                return response()->json([
                    'error' => $error
                ],200);
            }
        }
    }
    
    public function destroy($id)
    {
        $role = Role::where('id',$id)->first();
        
        DB::table('role_has_permissions')->where('role_has_permissions.role_id',$id)->delete();
        
        $role->delete();
        
        return redirect()->back()->with('flash_message_success','Role Deleted Successfully');
    }
}
