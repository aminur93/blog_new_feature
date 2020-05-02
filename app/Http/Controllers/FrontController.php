<?php

namespace App\Http\Controllers;

use App\Category;
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
    
    public function singlePost($slug)
    {
        $posts = Post::with('category','tag')->where('slug',$slug)->first();
    
        $category = Category::latest()->get();
        $tags = Tag::latest()->get();
        $latest_post = Post::latest()->take(5)->get();
        Post::where('slug',$slug)->increment('view_count',1);
        return view('home',compact('posts','category','tags','latest_post'));
    }
    
    public function categoryPost($id)
    {
        $categories = DB::table('categories')
                        ->select(
                            'categories.id',
                            'categories.name as cat_name',
                            'categories.slug as cat_slug',
                            'tags.name as tag_name',
                            'posts.title as title',
                            'posts.image as image',
                            'posts.slug as slug',
                            'posts.body as body',
                            'posts.post_date as post_date'
                        )
                        ->join('posts','categories.id','=','posts.category_id')
                        ->join('tags','posts.tag_id','=','tags.id')
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
}
