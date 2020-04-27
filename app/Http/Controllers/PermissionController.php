<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DB;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permission.index');
    }
    
    public function create()
    {
        return view('admin.permission.create');
    }
    
    public function store(PermissionRequest $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
    
            try{
                // Step 1 : Create Permission
                $permission = new Permission();
        
                $permission->name = $request->name;
                $permission->guard_name = 'web';
        
                $permission->save();
        
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'Permission Added Successfully'
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
    
    public function getData()
    {
        $permission = Permission::latest()->get();
        
        return DataTables::of($permission)
                ->addIndexColumn()
                ->editColumn('action', function ($permission){
                    
                    $return = "<div class='btn-group'>";
                    if(!empty($permission->name))
                    {
                        $return .= "
                            <a href=\"/permission/edit/$permission->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                            <a rel=\"$permission->id\" rel1=\"delete-permission\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                                  ";
                    }
                    $return .= "</div>";
                    
                    return $return;
                })
        
                ->rawColumns([
                    'action'
                ])
                ->make(true);
            
    }
    
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permission.edit',compact('permission'));
    }
    
    public function update(PermissionRequest $request, $id)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();
    
            try{
                // Step 1 : Create Permission
                $permission = Permission::findOrFail($id);
        
                $permission->name = $request->name;
                $permission->guard_name = 'web';
        
                $permission->update();
        
                DB::commit();
        
                return response()->json([
                    'flash_message_success' => 'Permission Updated Successfully'
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
        $permission = Permission::findOrFail($id);
        $permission->delete();
        
        return redirect()->back()->with('flash_message_success','Permission Delete Successfully');
    }
}
