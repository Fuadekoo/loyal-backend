<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
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
// 
// public route
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

// protected route
Route::group(['middleware'=>['auth:sanctum']],function(){
    // user
    route::get('/user',[AuthController::class,'user']);
    route::put('/user',[AuthController::class,'update']);
    route::post('/logout',[AuthController::class,'logout']);

    // post
    Route::get('/posts',[PostController::class,'index']); //all posts
    Route::post('/posts',[PostController::class,'store']); //create post
    Route::get('/posts/{id}',[PostController::class,'show']); //get single post
    Route::put('/posts/{id}',[PostController::class,'update']); //update post
    Route::delete('/posts/{id}',[PostController::class,'destroy']); //delete post

    //comments
    Route::get('/posts/{id}/comments',[CommentController::class,'index']); //all comment of posts
    Route::post('/posts/{id}/comments',[CommentController::class,'store']); //create comment of post
    Route::post('/comments/{id}',[CommentController::class,'update']); //update comment
    Route::delete('/comments/{id}',[CommentController::class,'destroy']); //delete comment

    //likes
    Route::post('/posts/{id}/likes',[LikeController::class,'likeORdislike']); //all posts

});