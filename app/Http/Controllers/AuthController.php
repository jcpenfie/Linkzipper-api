<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateData = $request->validate([
                'userName' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Validation Fail',
                'error_type' => '422',
            ]);
        }

        $userName = User::where('userName', $validateData['userName'])->get();
        $email = User::where('email', $validateData['email'])->get();

        if ($email != null || $userName != null) {

            return response()->json([
                'message' => 'The user name or email has already been taken.',
                'error_type' => '422',
                'result' => $userName,
                'result2' => $email,
            ]);
        } else {
            $user = User::create([
                'userName' => strtolower($validateData['userName']),
                'showName' => $validateData['userName'],
                'email' => strtolower($validateData['email']),
                'password' => Hash::make($validateData['password']),
                'totalLikes' => 0,
                'publicAccount' => 1,
                'theme' => 'white',
                'description' => ' ',
                'profileImg' => 'profileInput.png',
                'backgroundImg' => 'emptyBg.png'
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;



            //AutoLike al registrase un usuario
            $userL = User::where('userName', $user->userName)->get();

            Like::create([
                'idUser' => $userL[0]->id,
                'idUserLiked' => $userL[0]->id,
            ]);

            DB::table('users')->where('id', $userL[0]->id)->increment('totalLikes');

            return response()->json([
                'message' => 'Good',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        }
    }

    public function login(Request $request)
    {

        try {
            $validateData = $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Validation Fails',
                'error_type' => '422'
            ]);
        }

        try {
            $user = User::where('email', $validateData['email'])->firstOrFail();
            if (Hash::check($validateData['password'], $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Good',
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The user email or password are not registered in the database',
                'error_type' => '422'
            ]);
        }

        return response()->json([
            'message' => 'Incorrect password',
        ]);
    }

    public function getUserLogin(Request $request)
    {
        return $request->user();
    }
}
