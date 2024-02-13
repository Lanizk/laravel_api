<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\post;
use App\Models\comment;

class commentController extends Controller
{
    //get all comments of a post
    public function index($id)
    {
        $post = post::find($id);
        if (!$post) {
            return response([
                'message' => 'not found'
            ], 403);
        }

        return response([
            'comments' => $post->comments()->with('user:id,image,name')->get()
        ], 200);


    }

    //create a comment
    public function store(Request $request, $id)
    {
        $post = post::find($id);
        if (!$post) {
            return response([
                'message' => 'not found'
            ], 403);
        }

        $attrs = $request->validate([
            'comment' => 'required|string',
        ]);

        comment::create(
            [
                'comment' => $attrs['comment'],
                'post_id' => $id,
                'user_id' => auth()->user()->id
            ]
        );

        return response([
            'message' => 'comments created',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        $comment = comment::find($id);
        if (!$comment) {
            return response([
                'message' => 'comment not found'
            ], 403);
        }

        if ($comment->user_id != auth()->user()->id) {
            return response([
                'message' => 'permission denied'
            ], 403);
        }

        $attrs = $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update([
            'comment' => $attrs['comment']
        ]);


        return response([
            'message' => 'post updated',
        ], 200);
    }

    //delete a comment
    public function destroy($id)
    {
        $comment = comment::find($id);
        if (!$comment) {
            return response([
                'message' => 'comment not found'
            ], 403);
        }

        if ($comment->user_id != auth()->user()->id) {
            return response([
                'message' => 'permission denied'
            ], 403);
        }

        $comment->delete();
        return response(['message' => 'comment deleted'], 200);

    }
}
