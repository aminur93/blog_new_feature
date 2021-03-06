<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use DB;

class FrontController extends Controller
{
    public function index()
    {
        $posts = Post::with('category','tag')->latest()->paginate(3);
        
        $category = Category::latest()->get();
        $tags = Tag::latest()->get();
        $latest_post = Post::latest()->take(5)->get();
        return view('welcome',compact('posts','category','tags','latest_post'));
    }
    
    public function singlePost($id)
    {
        $posts = Post::with('category','tag')->where('id',$id)->first();
    
        $category = Category::latest()->get();
        $tags = Tag::latest()->get();
        $latest_post = Post::latest()->take(5)->get();
        Post::where('id',$id)->increment('view_count',1);
    
        $commentss = DB::table('comments')
            ->select(
                'comments.id as id',
                'comments.post_id as post_id',
                'comments.post_slug as post_slug',
                'comments.description as description',
                'comments.created_at as created_at'
            )
            ->leftJoin('posts','comments.post_id','=','posts.id')
            ->where('comments.post_id',$id)
            ->orderBy('comments.id','ASC')
            ->get();
        
        //dd($commentss);
        
        return view('home',compact('posts','category','tags','latest_post','commentss'));
    }
    
    public function categoryPost($id)
    {
        $categories = DB::table('categories')
                        ->select(
                            'categories.id',
                            'categories.name as cat_name',
                            'categories.slug as cat_slug',
                            'tags.name as tag_name',
                            'posts.id as post_id',
                            'posts.title as title',
                            'posts.image as image',
                            'posts.slug as slug',
                            'posts.body as body',
                            'posts.post_date as post_date'
                        )
                        ->leftJoin('posts','categories.id','=','posts.category_id')
                        ->leftJoin('tags','posts.tag_id','=','tags.id')
                        ->where('categories.id',$id)
                        ->get();
        //dd($categories);
        
        $latest_post = Post::latest()->take(5)->get();
        $tags = Tag::latest()->get();
        $category = Category::latest()->get();
        return view('front.category_post',compact('categories','latest_post','tags','category'));
    }
    
    public function tagPost($id)
    {
        $tag = DB::table('tags')
                ->select(
                    'tags.id',
                    'tags.name as tag_name',
                    'categories.name as cat_name',
                    'categories.slug as cat_slug',
                    'posts.title as title',
                    'posts.image as image',
                    'posts.slug as slug',
                    'posts.body as body',
                    'posts.post_date as post_date'
                )
                ->join('posts','tags.id','=','posts.tag_id')
                ->join('categories','posts.category_id','=','categories.id')
                ->where('tags.id',$id)
                ->get();
        
        $latest_post = Post::latest()->take(5)->get();
        $tags = Tag::latest()->get();
        $category = Category::latest()->get();
        return view('front.tag_post',compact('tag','latest_post','tags','category'));
    }
    
    public function fetch(Request $request)
    {
        if ($request->get('query'))
        {
            $query = $request->get('query');
            
            $data = Post::where('title','LIKE',"%{$query}%")->get();
    
            $output = '<ul class="dropdown-menu" style="display:inline-block; position:relative;">';
            foreach($data as $row)
            {
                $output .= '
                           <li><a href="#">'.$row->title.'</a></li>
                           ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }
    
    public function search(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $title = $request->get('title');
            
            $data = Post::with('category','tag')->where('title','LIKE',"%{$title}%")->get();
            
           
            $output = '';
            foreach ($data as $row)
            {
                $output .= '
                            <article class="blog-post">
                                <div class="post-heading">
                                    <h3><a href="/post/single/'.$row->slug.'"> '.$row->title.' </a></h3>
                                </div>
                                <div class="row">
                                    <div class="span8">
                                        <div class="post-image">
                                            <a href="#"><img src="/assets/uploads/original_image/'. $row->image.'" alt="" /></a>
                                        </div>
                                        <ul class="post-meta">
                                            <li class="first"><i class="icon-calendar"></i><span></span></li>
                                            <li><i class="icon-comments"></i><span><a href="#">4 comments</a></span></li>
                                            <li class="last"><i class="icon-tags"></i><span><a href="#">'.$row->tag->name.'</a></span></li>
                                            <li class="last"><i class="icon-reorder"></i><span><a href="/post/category_post/'.$row->category->id.'">'.$row->category->name.'</a></span></li>
                                        </ul>
                                        <div class="clearfix">
                                        </div>
                                        <p>
                                            '.substr(strip_tags($row->body),0,200).'
                                            
                                        </p>
                                        <a href="/post/single/'.$row->slug.'" class="btn btn-small btn-theme">Read more</a>
                                    </div>
                                </div>
                            </article>
                          
                            <div class="pagination text-center">
                            
                            </div>';
            }
            
            echo $output;
        }
    }
    
    public function allPost()
    {
        $posts = Post::latest()->paginate(12);
        return view('front.all_post',compact('posts'));
    }
    
    public function contact()
    {
        return view('front.contact');
    }
    
    public function store(Request $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
    
            try{
                // Step 1 : Create Contact
                $name = $request->name;
                $email = $request->email;
                $subject = $request->subject;
                $message = $request->message;
                
                DB::table('contacts')->insert(['name'=>$name,'email'=>$email,'subject'=>$subject,'message'=>$message]);
        
                DB::commit();
        
                return response()->json([
                    'flash_message_success' => 'Message Send Successfully'
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
    
    public function subscriber_email(Request $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
        
            try{
                // Step 1 : Create subscribers
                $email = $request->email;
            
                DB::table('subscribers')->insert(['email'=>$email]);
            
                DB::commit();
            
                return response()->json([
                    'flash_message_success' => 'Subscriber  Successfully'
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
}
