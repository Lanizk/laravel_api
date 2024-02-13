<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Authcontroller extends Controller
{
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create(
            [
                'name' => $attr['name'],
                'email' => $attr['email'],
                'password' => bcrypt($attr['password']),
            ]

        );

        //return responce
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken,

        ]);
    }

    //LOGIN USER
    public function Login(Request $request)
    {
        $attr = $request->validate([

            'email' => 'required|email',
            'password' => 'required|min:6',

        ]);


        if (!Auth::attempt($attr)) {
            return response(
                [
                    'message' => 'INVALID CREDENTIALS'
                ],
                403
            );
        }
        //return responce
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken,

        ], 200);
    }


    //logout user
    public function logOut(Request $request)
    {
        auth()->user()->token()->delete();
        return response(['message' => 'logout success']);
    }

    //get user details
    public function user()
    {
        return response(['user' => auth()->user()], 200);
    }

    public function Update(Request $request)
    {
        $attrs = $request->validate(['name' => 'required|String']);

        $image = $this->saveImage($request->image, 'profile');

        auth()->user()->update([

            'name' => $attrs['name'],
            'image' => $image
        ]);

        return response([
            'message' => 'user updated',
            'user' => auth()->user()
        ], 200);

    }

}