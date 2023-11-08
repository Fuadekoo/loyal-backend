<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    //get all post 
    public function index(){
        return response([
            'posts'=>Post::orderBy('created_at','desc')->with('user:id,username,image')->withCount('comments','likes')
            ->with('likes',function($likes){
                return $like->where('user_id',auth()->user()->id)->select('id','user_id','post_id');
            })
            ->get()
        ],200);
    }

    //get single post
    public function show($id){
        return response([
            'post'=>Post::where('id',$id)->withCount('comments','likes')->get()
        ],200);
    }

    //create a post
    public function store(Request $req){
        // validate fields
        $attrs=$req->validate([
            'body'=>'required|string'
        ]);

        $image = $this->saveImage($req->image,'posts');
        $post = Post::create([
            'body'=>$attrs['body'],
            'user_id'=>auth()->user()->id,
            'image' =>$image
        ]);

        //for now skip for post images
        return response([
            'message'=>'post created.',
            'post'=>$post
        ],200);

    }

        //update a post
        public function update(Request $req, $id){
            $post=Post::find($id);

            if(!$post)
            {
                return response([
                    'message'=>'post not found'
                ],400);
            }
            
            if($post->user_id != auth()->user()->id){
                return response([
                    'message'=>'permission denied'
                ],400);
            }
            // validate fields
            $attrs=$req->validate([
                'body'=>'required|String'
            ]);

            $post->update([
                'body'=>$attrs['body']
            ]);

    
            //for now skip for post images
            return response([
                'message'=>'post updated.',
                'post'=>$post
            ],200);
    
        }

        //post delete
        public function destroy($id){
            $post=post::find($id);
            if(!$post)
            {
                return response([
                    'message'=>'post not found.'
                ],400);
            }
            $post->comments()->delete();
            $post->likes()->delete();
            $post->delete();
            return response ([
                'message'=>'post deleted.'
            ],200);
        }
}
