<?php

namespace App\Http\Controllers;

use App\Category;
use App\Mail\SendMail;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Mail;
use Image;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.post.index');
    }
    
    public function create()
    {
        $category = Category::get();
        $tag = Tag::get();
        return view('admin.post.create',compact('category','tag'));
    }
    
    public function store(Request $request)
    {
        if ($request->isMethod('post'))
        {
            
//            DB::beginTransaction();
//
//            try{
    
                $user_id = Auth::user()->id;
                
                $post = new Post();
                
                $post->category_id = $request->category_id;
                $post->tag_id = $request->tag_id;
                $post->user_id = $user_id;
                $post->title = $request->title;
                $post->slug = $request->slug;
                $post->body = $request->body;
                
                if($request->hasFile('image')){
            
                    $image_tmp = $request->file('image');
                    if($image_tmp->isValid()){
                        $extenson = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extenson;
                        $large_image_path = public_path().'/assets/uploads/original_image/'.$filename;
                        $medium_image_path = public_path().'/assets/uploads/thumbnail/'.$filename;
                
                        //Resize Image
                        Image::make($image_tmp)->save($large_image_path);
                        Image::make($image_tmp)->resize(150,150)->save($medium_image_path);
                
                        //store product image in data table
    
                        $post->image = $filename;
                    }
                }
                
                $post->post_date = $request->post_date;
                $post->view_count = 0;
                
                if(!empty($request->status))
                {
                    $post->status = 1;
                }else{
                    $post->status = 0;
                }
                
                if(!empty($request->is_approved))
                {
                    $post->is_approved = 1;
                }else{
                    $post->is_approved = 0;
                }
                
                
                $post->save();
    
                return response()->json([
                    'flash_message_success' => 'Post Added Successfully'
                ],200);
                
                //return redirect()->back();
                
                
//            }catch(\Illuminate\Database\QueryException $e){
//                DB::rollback();
//
//                $error = $e->getMessage();
//
//                return response()->json([
//                    'error' => $error
//                ],200);
//            }
        }
    }
    
    public function getData()
    {
        $post = DB::table('posts')
                ->select(
                    'posts.id',
                    'posts.image',
                    'posts.title',
                    'posts.slug',
                    'categories.name as category_name',
                    'tags.name as tag_name',
                    'roles.name as role_name',
                    'posts.post_date',
                    'posts.view_count',
                    'posts.status',
                    'posts.is_approved'
                    
                )
                ->leftJoin('categories','posts.category_id','=','categories.id')
                ->leftJoin('tags','posts.tag_id','=','tags.id')
                ->leftJoin('users','posts.user_id','=','users.id')
                ->leftJoin('model_has_roles','users.id','=','model_has_roles.model_id')
                ->leftJoin('roles','model_has_roles.role_id','=','roles.id')
                ->get();
    
        return DataTables::of($post)
            ->addIndexColumn()
            ->addColumn('image',function ($post){
                $url=asset("assets/uploads/thumbnail/$post->image");
                return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
            })
            ->addColumn('status',function ($post){
                if($post->status == 0)
                {
                    return '<span class="badge badge-primary">Pending</span>';
                }else{
                    return '<span class="badge badge-danger">Publish</span>';
                }
            })
            ->addColumn('is_approved',function ($post){
                if($post->is_approved == 0)
                {
                    return '<span class="badge badge-primary">Not Approve</span>';
                }else{
                    return '<span class="badge badge-danger">Approve</span>';
                }
            })
            ->editColumn('action', function ($post){
                $return = "<div class='btn-group'>";
                if(!empty($post->title))
                {
                    $return .= "
                            <a href=\"/post/edit/$post->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                              <a rel=\"$post->id\" rel1=\"delete-post\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                              ||
                              <a rel=\"$post->id\" rel1=\"publish-post\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-dark post_publish \"><i class='fas fa-newspaper'></i></a>
                                ||
                              <a rel=\"$post->id\" rel1=\"approve-post\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-success check_approve \"><i class='fa fa-check'></i></a>
                              ||
                              <a  href=\"/post/dropzone/image/upload/$post->id\" style='margin-right: 5px' class=\"btn btn-sm btn-primary \"><i class='fa fa-file-image'></i></a>
                             
                              
                              ";
                }
            
                $return .= "</div>";
            
                return $return;
            })
        
            ->rawColumns(['image','status','is_approved','action'])
            ->make(true);
    }
    
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $category = Category::get();
        $tag = Tag::get();
        return view('admin.post.edit',compact('category','tag','post'));
    }
    
    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
    
            if($request->hasFile('image')){
        
                $image_tmp = $request->file('image');
                if($image_tmp->isValid()){
                    $extenson = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extenson;
                    $large_image_path = public_path().'/assets/uploads/original_image/'.$filename;
                    $medium_image_path = public_path().'/assets/uploads/thumbnail/'.$filename;
            
                    //Resize Image
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(150,150)->save($medium_image_path);
            
                }
            }else {
                $filename = $request->current_image;
            }
        
           
        
            $user_id = Auth::user()->id;
        
            $post = Post::findOrFail($id);
        
            $post->category_id = $request->category_id;
            $post->tag_id = $request->tag_id;
            $post->user_id = $user_id;
            $post->title = $request->title;
            $post->slug = $request->slug;
            $post->body = $request->body;
            
            $post->image = $filename;
           
            $post->post_date = $request->post_date;
            $post->view_count = 0;
            if(!empty($request->status))
            {
                $post->status = 1;
            }else{
                $post->status = 0;
            }
    
            if(!empty($request->is_approved))
            {
                $post->is_approved = 1;
            }else{
                $post->is_approved = 0;
            }
        
            $post->update();
        
            return response()->json([
                'flash_message_success' => 'Post Updated Successfully'
            ],200);
        }
    }
    
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
    
        $image_path = public_path().'/assets/uploads/original_image/'.$post->image;
        $image_second = public_path().'/assets/uploads/thumbnail/'.$post->image;
        
        unlink($image_path);
        unlink($image_second);
        
        $post->delete();
        
        return redirect()->back()->with('flash_message_success', 'Post Deleted Successfully');
    }
    
    public function approve($id)
    {
        
        Post::where('id',$id)->update(array('is_approved'=>1));
        
        return redirect()->back()->with('flash_message_success','Post Is Approved');
    }
    
    public function publish($id)
    {
        Post::where('id',$id)->update(array('status'=>1));
    
        return redirect()->back()->with('flash_message_success','Post Is Publish');
    }
    
    public function dropzone($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.post.dropzone_image',compact('post'));
    }
    
    public function image_create($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.post.create_image',compact('post'));
    }
    
    public function image_upload(Request $request,$id)
    {
        $post = Post::findOrFail($id);
        
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
        $image->move(public_path('assets/uploads/posts_image'),$imageName);
    
        DB::table('post_image')->insert([
            'post_id' => $post->id,
            'image' => $imageName
        ]);
        
        return response()->json(['success'=>$imageName]);
    }
    
    public function image_delete(Request $request)
    {
        $filename =  $request->get('filename');
        
        DB::table('post_image')->where('image',$filename)->delete();
        
        $path=public_path().'/assets/uploads/posts_image/'.$filename;
        if (file_exists($path)) {
            unlink($path);
        }
        return $filename;
    }
    
    public function dropzone_getData()
    {
        $post_image = DB::table('post_image')->select('id','post_id','image')->get();
    
        return DataTables::of($post_image)
            ->addIndexColumn()
            ->addColumn('image',function ($post_image){
                $url=asset("assets/uploads/posts_image/$post_image->image");
                return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
            })
            ->editColumn('action', function ($post_image){
                $return = "<div class='btn-group'>";
                if(!empty($post_image->post_id))
                {
                    $return .= "
                          
                            <a rel=\"$post_image->id\" rel1=\"delete-post_image\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                            ";
                }
            
                $return .= "</div>";
            
                return $return;
            })
        
            ->rawColumns(['image','action'])
            ->make(true);
    }
    
    public function Dropdestroy($id)
    {
        $post_image = DB::table('post_image')->where('id',$id);
        
        $image = $post_image->first();
        
        $image_path = public_path().'/assets/uploads/posts_image/'.$image->image;
        
        unlink($image_path);
    
        $post_image->delete();
        
        return redirect()->back()->with('flash_message_success','Post Image Deleted Successfully');
    }
    
    public function userIndex()
    {
        return view('admin.user_message.index');
    }
    
    public function messageData()
    {
        $message = DB::table('contacts')->get();
    
        return DataTables::of($message)
            ->addIndexColumn()
            ->editColumn('action', function ($message){
            
                $return = "<div class='btn-group'>";
                if(!empty($message->name))
                {
                    $return .= "
                            <a href=\"/message/view/$message->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-eye'></i></a>
                            ||
                            <a  href=\"/message/reply/$message->id\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-reply'></i></a>
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
    
    public function view($id)
    {
        $message = DB::table('contacts')->where('id',$id)->first();
        return view('admin.user_message.view_message',compact('message'));
    }
    
    public function reply($id)
    {
        $message = DB::table('contacts')->where('id',$id)->first();
        return view('admin.user_message.reply_message',compact('message'));
    }
    
    public function send(Request $request)
    {
        $data = array(
            'name' => $request->name,
            'from_email' => $request->from_email,
            'subject' => $request->subject,
            'message' => $request->message
        );
        
        
        Mail::to($request->to_email)->queue( new SendMail($data));
    
    
        return redirect()->back()->with('flash_message_success','Email Has Been sent');
    }
}
