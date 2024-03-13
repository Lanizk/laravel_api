<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\post;
use App\Models\Flag;


class FlagController extends Controller
{


    public function Flag(Request $request, $id)
    {

        $attrs = $request->validate([
            'reason' => 'required|string',
            'body' => 'required|string'
        ]);



        $image = $this->saveImage($request->image, 'posts');



        $post = post::find($id);

        if (!$post) {
            return response([
                'message' => 'not found'
            ], 403);
        }

        Flag::create([
            'post_id' => $id,
            //'user_id' => auth()->user()->id,

            'reason' => $attrs['reason'],
            'body' => $attrs['body'],
            'image' => $image,

        ]);
        return response([
            'message' => 'post flagged'
        ], 200);

    }

    public function getFlag(Request $request, $id)
    {
        $postId = $id;
        //$postId = $request->input('post_id');
        $userId = $request->input('user_id');
        $reason = $request->input('reason');

        // Query database for flagged post
        $post = Post::find($postId);

        // Return post details along with flagging reason
        return response()->json([
            'post' => $post,
            'reason' => $reason
        ]);
    }
    public function getAllFlaged()
    {

        return response([
            'posts' => Flag::orderBy('created_at', 'desc')->get()
        ], 200);
    }
}
