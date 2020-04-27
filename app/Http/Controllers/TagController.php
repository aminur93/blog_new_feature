<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Tag;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\Facades\DataTables;
Use Exception;

class TagController extends Controller
{
    public function index()
    {
        return view('admin.tag.index');
    }
    
    public function create()
    {
        return view('admin.tag.create');
    }
    
    public function store(TagRequest $request)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();
    
            try{
                // Step 1 : Create Permission
                $tag = new Tag();
                
                $tag->name = $request->name;
                
                $tag->save();
        
                DB::commit();
        
                return response()->json([
                    'flash_message_success' => 'Tag Added Successfully'
                ],200);
        
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
        
                $error = $e->getMessage();
                
                //dd($error);
        
                return response()->json([
                    'error' => $error
                ],200);
            }
        }
    }
    
    public function getData()
    {
        $tag = Tag::get();
        
        return DataTables::of($tag)
            ->addIndexColumn()
            ->editColumn('action',function ($tag){
                $return = "<div class='btn-group'>";
                if(!empty($tag->name))
                {
                    $return .= "
                            <a href=\"/tag/edit/$tag->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                            <a rel=\"$tag->id\" rel1=\"delete-tag\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                                  ";
                }
                
                $return .="</div>";
                
                return $return;
            })
            
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tag.edit',compact('tag'));
    }
    
    public function update(TagRequest $request, $id)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();
        
            try{
                // Step 1 : Create Permission
                $tag = Tag::findOrFail($id);
            
                $tag->name = $request->name;
            
                $tag->update();
            
                DB::commit();
            
                return response()->json([
                    'flash_message_success' => 'Tag Updated Successfully'
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
        $tag = Tag::findOrFail($id);
        $tag->delete();
        
        return redirect()->back()->with('flash_message_success','Tag Deleted Successfully');
    }
}
