<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'content'=>'required|string',
            'post_id' => 'required|integer'
        ]);
        $post = Post::find($fields['post_id']);
        if(!$post){
            throw new CustomException('post not found');
        }

        $comment = Comment::create([
            'post_id'=>$fields['post_id'],
            'content'=>$fields['content'],
            'user_id'=> Auth::user()->id,
        ]);

        $response = [
            'status'=> true,
            'message'=> 'Comment created successfully!',
            'data' => [
                'comment'=> $comment,
            ]
        ];
        return response($response,201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if(!(($comment->user_id == Auth::user()->id) || ($comment->post->user_id ==  Auth::user()->id))){
            throw new CustomException('not your post');
        }

        $comment->delete();

        $response = [
            'status'=> true,
            'message'=> 'Comment deleted successfully!',
            'data' => [
            ]
        ];
        return response($response,201);
    }
}
