<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateData = $request->validate([
                'userName' => 'required|string|unique:users|max:255',
                'email' => 'required|string|max:255|unique:users',
                'password' => 'required|string|max:8'
            ], [
                'email.unique' => 'Email already registered'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The user name or email has already been taken.',
                'error_type' => '422'
            ]);
        }
        $user = User::create([
            'userName' => strtolower($validateData['userName']),
            'showName' => $validateData['userName'],
            'email' => strtolower($validateData['email']),
            'password' => Hash::make($validateData['password']),
            'totalLikes' => 0,
            'publicAccount' => 1,
            'theme' => 'White',
            'description' => '',
            'profileImg' => '/profile/default.png',
            'backgroundImg' => 'bg/default.png'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Good',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function login(Request $request)
    {
        // if (!Auth::attempt($request->only('email', 'password'))) {
        //     return response()->json([
        //         'message' => 'Invalid login details'
        //     ], 401);
        // }

        try {
            $user = User::where('email', $request['email'])->firstOrFail();

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The user email or password are not registered in the database',
                'error_type' => '422'
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Good',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function getUserLogin(Request $request)
    {
        return $request->user();
    }
}
