<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Excel;
use Yajra\DataTables\Facades\DataTables;
use DB;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }
    
    public function create()
    {
        return view('admin.category.create');
    }
    
    public function get_category()
    {
        $category = Category::latest()->get();
    
        return DataTables::of($category)
            ->addIndexColumn()
            ->editColumn('action', function ($category) {
                $return = "<div class=\"btn-group\">";
                if (!empty($category->name))
                {
                    $return .= "
                            <a href=\"/category/edit/$category->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                              <a rel=\"$category->id\" rel1=\"delete-category\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
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
    
    public function store(CategoryRequest $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
        
            try{
                // Step 1 : Create Blood
                $category = new Category();
            
                $category->name = $request->name;
                $category->slug = $request->slug;
            
                $category->save();
            
                DB::commit();
            
                return response()->json([
                    'flash_message_success' => 'Category Added Successfully'
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
    
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        return view('admin.category.edit',compact('category'));
    }
    
    public function update(CategoryRequest $request, $id)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();
    
            try{
                // Step 1 : Create Blood
                $category = Category::findOrFail($id);
        
                $category->name = $request->name;
                $category->slug = $request->slug;
        
                $category->update();
        
                DB::commit();
        
                return response()->json([
                    'flash_message_success' => 'Category Updated Successfully'
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
        $vategory = Category::findOrFail($id);
        $vategory->delete();
    
        return redirect()->back()->with('flash_message_success','Blood Delete Successfully');
    }
    
    public function importData(Request $request)
    {
        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path)->get();
        
        //dd($data);
        if($data->count())
        {
            foreach ($data as $key => $value)
            {
                $arr[] = [
                    'name' => $value->name,
                    'slug' => $value->slug
                ];
            }
            
            if(!empty($arr))
            {
                Category::insert($arr);
            }
        }
    
        return redirect()->back()->with('flash_message_success', 'Category Imported Successfully');
    }
    
    public function downloadData($type)
    {
        $data = Category::get()->toArray();
        
        return Excel::create('excel_data', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
}
