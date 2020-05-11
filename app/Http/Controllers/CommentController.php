<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use DB;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
    
            try{
        
                $comment = new Comment();
                
                $comment->post_id = $request->post_id;
                $comment->user_id = 1;
                $comment->post_slug = $request->post_slug;
                $comment->description = $request->description;
                
                $comment->save();
    
                $comments = DB::table('comments')
                                ->select(
                                    'comments.id as id',
                                    'comments.post_id as post_id',
                                    'comments.post_slug as post_slug',
                                    'comments.description as description',
                                    'comments.created_at as created_at'
                                )
                                ->leftJoin('posts','comments.post_id','=','posts.id')
                                ->where('comments.post_id',$request->post_id)
                                ->orderBy('comments.id','ASC')
                                ->first();
    
                $get_comment[] = view('front.comment_data',compact('comments'))->render();
        
                DB::commit();
        
                return response()->json([
                    'flash_message_success' => 'Comment Added Successfully',
                    'comment'=> $get_comment
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
    
    public function Comment_replyss(Request $request, $comment_id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
        
                $comments_id = $comment_id;
                
                $reply_description = $request->reply_description;
                
                DB::table('replys')->insert(['comment_id'=>$comments_id,'reply_description'=>$reply_description]);
        
                DB::commit();
        
                return response()->json([
                    'flash_message_success' => 'reply Added Successfully',
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
    
    public function replies_from($id)
    {
        $comment = Comment::findOrFail($id);
    
        $response = [];
        $response['code'] = 1;
        $response['view'] = view('front.replies_from',compact('comment'))->render();
        
        echo json_encode($response);
    }
}
