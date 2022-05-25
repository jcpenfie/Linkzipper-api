<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validateData = $request->validate([
            'user' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|max:8'
        ]);

        $user = User::create([
            'userName' => $validateData['user'],
            'showName' => $validateData['user'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            'totalLikes' => 0,
            'publicAccount' => true,
            'theme' => 'White',
            'description' => '',
            'profileImg' => '/profile/default.png',
            'backgroundImg' => 'bg/default.png'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'=> 'Bearer'
        ],200);
    }

    public function login(Request $request){
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'message' =>'Invalid login details'
            ],401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'=> 'Bearer'
        ],200);
    }

    public function infouser(Request $request){
        return $request->user();
    }
}
