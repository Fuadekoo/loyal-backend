<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
class CommentController extends Controller
{
    //get all comments
    public function index($id)
    {
        $post=post::find($id);
        if(!$post)
        {
            return response([
                'message'=>'post not found.'
            ],400);

        }

        return response([
            'comments'=>$post->comments()->with('user:id,username,image')->get()
        ],200);
}

// create a comment 
public function store(Request $req, $id)
{
    $post=post::find($id);
    if(!$post)
    {
        return response([
            'message'=>'post not found.'
        ],400);

    }

                // validate fields
                $attrs=$req->validate([
                    'comment'=>'required|String'
                ]);

                Comment::create([
                    'comment'=>$attrs['comment'],
                    'post_id'=>$id,
                    "user_id"=>auth()->user()->id
                ]);

    return response([
        'message'=>'comment created.'
    ],200);
}

//update a comment
public function update(Request $req,$id){
    $comment = Comment::find($id);
    if(!$comment)
    {
        return response([
            'message'=>'post not found.'
        ],400);

    }

    if($comment->user_id != auth()->user()->id){
        return response([
            'message'=>'permission denied'
        ],400);
    }

                    // validate fields
                    $attrs=$req->validate([
                        'comment'=>'required|String'
                    ]);
                    $comment->update([
                        'comment'=>$attrs('comment')
                    ]);

                    return response([
                        'message'=>'comment update.'
                    ],200);
}
//delete comment
public function destroy($id){
    $comment = Comment::find($id);
    if(!$comment)
    {
        return response([
            'message'=>'post not found.'
        ],400);

    }

    if($comment->user_id != auth()->user()->id){
        return response([
            'message'=>'permission denied'
        ],400);
}
$comment->delete();
return response([
    'message'=>'comment deleted.'
],200);
}
}