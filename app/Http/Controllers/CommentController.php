<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{   

    public function index($post_id){
        $comments = Comment::with('children')
                    ->where('post_id',$post_id)->get();
        
        
        return response()->json([
            "status"=>200,
            'comments'=>$comments
        ],200);

    }
    
    public function store(Request $request){
        if(!Auth::check()){
            return response()->json([
                "status"=>"error",
                "message"=>"Unauthorized"
            ],401);
        }
        
        $user=Auth::getUser();

        $validator = Validator::make($request->all(),[
            'post_id' => 'required|int',
            'content' => 'required|string|min:5|max:500',
            'parent_id'=>'int'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>500,
                'errors'=>$validator->messages(),
            ],500);
        }   

        if(!Post::find($request->post_id)){
            return response()->json([
                "status"=>"error",
                "message"=>"Post not found"
            ],404);
        }
        
        if(!Comment::find($request->parent_id) && $request->parent_id !== null){
            return response()->json([
                "status"=>"error",
                "message"=>"Parent comment not found"
            ],404);
        }

        $comment = Comment::create([
            'user_id'=>$user->id,
            'post_id'=>$request->post_id,
            'content'=>$request->content,
            'parent_id'=>$request->parent_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully',
            'Comment'=>$comment
        ],201);
    }

    public function update ($id,Request $request){

        if(!Auth::check()){
            return response()->json([
                "status"=>"error",
                "message"=>"Unauthorized"
            ],401);
        }

        $user=Auth::getUser();

        $comment = Comment::find($request->id);

        if(!$comment){
            return response()->json([
                "status"=>"error",
                "message"=>"Comment not found!"
            ],404);
        }

        if($comment->user_id !== $user->id){
            return response()->json([
                "status"=>"error",
                "message"=>"You don't have permission to update this comment"
            ],406);
        }

        $validator = Validator::make($request->all(),[
            'content' => 'required|string|max:500',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>500,
                'errors'=>$validator->messages(),
            ],500);
        }

       
        $comment->content = ($request->content)? $request->content : $comment->content;

        $comment->save();

        return  response()->json([
            "status"=>"success",
            "message"=>"Comment updated successfully",
            "comment"=>$comment
        ],200);
    }

    public function delete ($id){

        $user=Auth::getUser();
        $comment = Comment::find($id);

        if($user->id !== $comment->id && $user->role === 'user'){
            return response()->json([
                "status"=>"error",
                "message"=>"You don't have permission to delete this comment"
            ],401);
        }

        if(!$comment){
            return response()->json([
                "status"=>"error",
                "message"=>"Post not found!"
            ],404);
        }
        $comment->delete();

            return response()->json([
                "status"=>"success",
                "message"=>"Comment deleted successfully"
            ],200);
    }
}
