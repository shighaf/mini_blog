<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function PHPUnit\Framework\throwException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        $res = [];
        foreach ($posts as $key => $one){
            $res[$key] = $one;
            $res[$key]['comments'] = $one->comments;
        }

        $response = [
            'status'=> true,
            'message'=> 'success',
            'data' => [
                'posts'=> $res,
            ]
        ];
        return response($response,201);
    }

    public function my_posts()
    {
        $posts = Post::where(['user_id' => Auth::user()->id])->get();

        $res = [];
        foreach ($posts as $key => $one){
            $res[$key] = $one;
            $res[$key]['comments'] = $one->comments;
        }

        $response = [
            'status'=> true,
            'message'=> 'success',
            'data' => [
                'posts'=> $res,
            ]
        ];
        return response($response,201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' =>'required|string',
            'content'=>'required|string',
        ]);

        $post = Post::create([
            'title'=>$fields['title'],
            'content'=>$fields['content'],
            'user_id'=> Auth::user()->id,
        ]);

        $response = [
            'status'=> true,
            'message'=> 'Post created successfully!',
            'data' => [
                'post'=> $post,
            ]
        ];
        return response($response,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post['comments'] = $post->comments;
        $response = [
            'status'=> true,
            'message'=> 'success',
            'data' => [
                'post'=> $post,
            ]
        ];
        return response($response,201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $fields = $request->validate([
            'title' =>'required|string',
            'content'=>'required|string',
        ]);

        if($post->user_id != Auth::user()->id){
            throw new CustomException('not your post');
        }

        $post->update([
            'title'=> $fields['title'],
            'content'=> $fields['content'],
        ]);

        $response = [
            'status'=> true,
            'message'=> 'Post updated successfully!',
            'data' => [
                'post'=> $post,
            ]
        ];
        return response($response,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->user_id != Auth::user()->id){
            throw new CustomException('not your post');
        }

        $post->delete();

        $response = [
            'status'=> true,
            'message'=> 'Post deleted successfully!',
            'data' => [
            ]
        ];
        return response($response,201);
    }
}
