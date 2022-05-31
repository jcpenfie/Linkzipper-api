<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function panel(Request $request)
    {
        try {
            $validateData = $request->validate([
                'userName' => 'required',
                'showName' => 'required',
                'password' => 'required',
                'theme' => 'required',
                'publicAccount' => 'required',
                'description' => 'required|string',
                'profileImg' => 'required|',
                'backgroundImg' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }
        User::where('userName', $validateData['userName'])->update(array(
            'showName' => $validateData['showName'],
            'password' => Hash::make($validateData['password']),
            'publicAccount' => $validateData['publicAccount'],
            'theme' => $validateData['theme'],
            'description' => $validateData['description'],
            'backgroundImg' => $validateData['backgroundImg']
        ));

        $user = User::where('userName', $validateData['userName']);
        $user->logo = $request['profileImg'];
        $user->save();

        return response()->json([
            'message' => 'Good, user updated',
        ], 200);
    }
}
