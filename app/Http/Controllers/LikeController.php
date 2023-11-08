<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
class LikeController extends Controller
{
    // like an ddislike
    public function likeORdislike($id){
        $post=Post::find($id);

        if(!$post)
        {
            return response([
                'message'=>'post not found.'
            ],400);

        }
        $like=$post->likes()->where('user_id',auth()->user()->id)->first();

        // if not like
        if(!$like)
        {

            Like::create([
                'post_id'=>$id,
                'user_id'=>auth()->user()->id
            ]);

            return response([
                'message'=>'liked'
            ],200);
        }
        //dislike it
        $like->delete();
        return response([
            'message'=>'disliked'
        ],200);

    }
}
