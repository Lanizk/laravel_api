<?php

use App\Http\Controllers\commentController;
use App\Http\Controllers\likeController;
use App\Http\Controllers\postController;
use App\Http\Controllers\FlagController;
use App\Models\Flag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//  return $request->user();
//});


//public routes
Route::post('/register', [Authcontroller::class, 'register']);
Route::post('/login', [Authcontroller::class, 'Login']);



Route::group(['middleware' => 'auth:sanctum'], function () {

    //user
    Route::get('/user', [Authcontroller::class, 'user']);
    Route::put('/user', [Authcontroller::class, 'Update']);
    Route::post('/logOut', [Authcontroller::class, 'logOut']);

    //post
    Route::get('/posts', [postController::class, 'index']); //all posts
    Route::post('/posts', [postController::class, 'store']); //create posts
    Route::get('/posts/{id}', [postController::class, 'show']); //single posts
    Route::put('/posts/{id}', [postController::class, 'update']); //update
    Route::delete('/posts/{id}', [postController::class, 'destroy']); //destroy

    //comments
    Route::get('/posts/{id}/comments', [commentController::class, 'index']); //all posts
    Route::post('/posts/{id}/comments', [commentController::class, 'store']); //create posts
    Route::put('/comments/{id}', [commentController::class, 'update']); //update
    Route::delete('/comments/{id}', [commentController::class, 'destroy']); //destroy

    //likes
    Route::post('/posts/{id}/likes', [likeController::class, 'likeOrUnlike']); //like or dislike a post

    // Route::post('/posts/{id}/flagged', [FlagController::class, 'Flag']);
    // Route::get('/posts/{id}/flagged', [FlagController::class, 'getFlag']);




});


Route::post('/posts/{id}/flagged', [FlagController::class, 'Flag']);

// get single flagged post
Route::get('/posts/{id}/flagged', [FlagController::class, 'getFlag']);
// get all flaged posts
Route::get('/flagged', [FlagController::class, 'getAllFlaged']);
