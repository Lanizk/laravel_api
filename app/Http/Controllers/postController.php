<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\post;
use Illuminate\Support\Facades\Auth;

class postController extends Controller
{
    public function index()
    {
        return response([
            'posts' => post::orderBy('created_at', 'desc')
                ->with('user:id,image,name')
                ->withCount('comments', 'likes')->with('likes', function ($like) {
                    return $like->where('user_id', auth()->user()->id)->
                        select('id', 'post_id', 'user_id')->get();
                })->get()
        ], 200);
    }



    public function show($id)
    {
        return response(['post' => post::where('id', $id)->withCount('likes', 'comments')->get()], 200);
    }

    //create a post
    public function store(Request $request)
    {
        $attrs = $request->validate([
            'body' => 'required|string',
        ]);

        $image = $this->saveImage($request->image, 'posts');


        //if (auth()->check()) {
        //    $user_id = auth()->user()->id;
        // $post = post::create([
        //    'body' => $attrs['body'],
        //    'user_id' => $user_id,
        //     'image' => $image,
        // ]);



        $post = post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image,
        ]);


        return response([
            'message' => 'post created',
            'post' => $post
        ], 200);
    }
    //else {
    //   return response([
    //         'message' => 'Unauthorized. Please log in to create a post.'
    //   ], 401);
    // }


    //}


    public function update(Request $request, $id)
    {
        $post = post::find($id);
        if (!$post) {
            return response([
                'message' => 'not found'
            ], 403);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'permission denied'
            ], 403);
        }

        $attrs = $request->validate([
            'body' => 'required|string',
        ]);

        $post->update([
            'body' => $attrs['body']
        ]);


        return response([
            'message' => 'post updated',
            'post' => $post
        ], 200);
    }

    public function destroy($id)
    {
        $post = post::find($id);
        if (!$post) {
            return response([
                'message' => 'not found'
            ], 403);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'permission denied'
            ], 403);
        }

        $post->coments()->delete();
        $post->like()->delete();
        $post->delete();

        return response([
            'message' => 'post deleted',
        ], 200);

    }
}



