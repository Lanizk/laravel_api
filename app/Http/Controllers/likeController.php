<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\post;
use App\Models\like;

class likeController extends Controller
{
    //like or unlike
    public function likeOrUnlike($id)
    {
        $post = post::find($id);
        if (!$post) {
            return response([
                'message' => 'not found'
            ], 403);
        }

        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        //if not liked then like
        if (!$like) {
            like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id,
            ]);
            return response([
                'message' => 'post liked'
            ], 200);

        }
        //else dislike
        $like->delete();

        return response([
            'message' => 'disliked'
        ], 200);

    }
}
