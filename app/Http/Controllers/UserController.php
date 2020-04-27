<?php

namespace App\Http\Controllers;

use App\Http\Requests\Userrequest;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        return view('admin.user.index');
    }
    
    public function create()
    {
        $role = Role::get();
        return view('admin.user.create',compact('role'));
    }
    
    public function store(Userrequest $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
        
            try{
                // Step 1 : Create Role
                $input = $request->all();
                $input['password'] = Hash::make($input['password']);
            
            
                $user = User::create($input);
                $user->assignRole($request->role);
            
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'User Added Successfully'
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
        $users = DB::table('users')
                    ->select('users.id',
                        'users.name as name',
                        'users.email as email',
                        'roles.name as role_name'
                    )
            ->leftJoin('model_has_roles','users.id','=','model_has_roles.model_id')
            ->leftJoin('roles','model_has_roles.role_id','=','roles.id')
            ->get();
        
        //dd($users);
        
        return DataTables::of($users)
                ->addIndexColumn()
                ->editColumn('action', function ($users){
                    $return = "<div class='btn-group'>";
                    if(!empty($users->name))
                    {
                        $return .= "
                            <a href=\"/user/edit/$users->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                              <a rel=\"$users->id\" rel1=\"delete-user\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
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
        $user = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'model_has_roles.role_id'
            )
            ->leftJoin('model_has_roles','users.id','=','model_has_roles.model_id')
            ->leftJoin('roles','model_has_roles.role_id','=','roles.id')
            ->where('users.id',$id)
            ->first();
        $role = Role::get();
        return view('admin.user.edit',compact('role','user'));
    }
    
    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
        
            try{
                // Step 1 : Update Users
                $input = $request->all();
                
                $user = User::find($id);
                $user->update($input);
                DB::table('model_has_roles')->where('model_id',$id)->delete();
            
            
                $user->assignRole($request->role);
            
            
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'User updated Successfully'
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
        $user = User::findOrFail($id);
        
        DB::table('model_has_roles')->where('model_has_roles.model_id',$id)->delete();
        
        $user->delete();
        
        return redirect()->back()->with('flash_message_success','User Deleted Successfully');
    }
}
