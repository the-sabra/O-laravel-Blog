<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(){
        $posts = Post::all();

        return response()->json([
            "status"=>200,
            "posts"=>$posts
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
            'title' => 'required|string|min:5|max:255',
            'content' => 'required|string|min:15|max:500',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>500,
                'errors'=>$validator->messages(),
            ],500);
        }

        $post = Post::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'user_id' => $user->id ,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'post'=>$post
        ]);
    }

    public function update ($id,Request $request){

        if(!Auth::check()){
            return response()->json([
                "status"=>"error",
                "message"=>"Unauthorized"
            ],401);
        }

        $user=Auth::getUser();

        $post = Post::find($request->id);

        if(!$post){
            return response()->json([
                "status"=>"error",
                "message"=>"Post not found!"
            ],404);
        }
        if($post->user_id !== $user->id){
            return response()->json([
                "status"=>"error",
                "message"=>"You don't have permission to update this post"
            ],406);
        }

        $validator = Validator::make($request->all(),[
            'title' => 'string|min:5|max:255',
            'content' => 'string|max:500',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>500,
                'errors'=>$validator->messages(),
            ],500);
        }

        $post->title= ($request->title)? $request->title : $post->title;
        $post->content = ($request->content)? $request->content : $post->content;

        $post->save();

        return  response()->json([
            "status"=>"success",
            "message"=>"Post updated successfully",
            "post"=>$post
        ],200);
    }

    public function delete ($id){

        $user=Auth::getUser();
        $post = Post::find($id);

        if($user->id !== $post->id && $user->role === 'user'){
            return response()->json([
                "status"=>"error",
                "message"=>"You don't have permission to delete this post"
            ],401);
        }

        if(!$post){
            return response()->json([
                "status"=>"error",
                "message"=>"Post not found!"
            ],404);
        }
        $post->delete();

            return response()->json([
                "status"=>"success",
                "message"=>"Post deleted successfully"
            ],200);
    }
}
