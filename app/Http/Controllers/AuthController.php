<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //register user
    public function register(Request $req){
        // validate fields 
        $attrs=$req->validate([
            'username'=>'required|String',
            'phone_number'=>'required|numeric|unique:users,phone_number|min:9',
            'password'=>'required|min:6|confirmed'
        ]);

        // create user
        $user=User::create([
            'username'=>$attrs['username'],
            'phone_number'=>$attrs['phone_number'],
            'password'=>bcrypt($attrs['password'])
        ]);

        // return user $token in response
        return response([
            'user'=>$user,
            'token'=>$user->createToken('secret')->plainTextToken
        ]);
    }

    // login user 
    public function login(Request $req){
        // validate fields 
        $attrs=$req->validate([
            'phone_number'=>'required|numeric|min:9',
            'password'=>'required|min:6'
        ]);

        // attemp login 
        if(!Auth::attempt($attrs)){
            return response([
                'message'=>'invalid credentials.'
            ],400);
        }
        //get the authenticated user 
        $user = Auth::user();

        // return user $token in response
        return response([
            'user'=>$user,
            'token'=>$user->createToken('secret')->plainTextToken
        ],200);
    }

    // logout user
    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message'=>'logout successfully.'
        ],200);
    }

    // user
    public function user(){
        return response([
            'auth'=>auth()->user()
        ],200);
    }

    //update user
     public function update(Request $req){
        $attrs=$req->validate([
            'name'=>'required|string'
        ]);

        $image =$this->saveImage($req->$image,'profiles');
        auth()->user()->update([
            'name'=>$attrs['name'],
            'image'=>$image
        ]);

        return response([
            'message'=>'user update.',   
            'user' =>auth()->user()
        ],200);
     }
}
