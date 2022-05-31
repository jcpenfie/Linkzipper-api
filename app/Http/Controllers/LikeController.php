<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LikeController extends Controller
{
    public function like(Request $request)
    {
        try {
            $validateData = $request->validate([
                'idUser' => 'required',
                'idUserLiked' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }

        Like::create([
            'idUser' => $validateData['idUser'],
            'idUserLiked' => $validateData['idUserLiked'],
        ]);

        DB::table('users')->where('id', $validateData['idUser'])->increment('totalLikes');

        return response()->json([
            'message' => 'Like Added',
        ], 200);
    }

    public function dislike(Request $request)
    {
        try {
            $validateData = $request->validate([
                'idUser' => 'required',
                'idLink' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }

        Like::where('id', $validateData['idLink'])->delete();
        DB::table('users')->where('id', $validateData['idUser'])->decrement('totalLikes');

        return response()->json([
            'message' => 'Like Removed',
        ], 200);
    }
    public function show(Request $request)
    {
        try {
            $validateData = $request->validate([
                'idUser' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }
        
        $likes = Like::where('idUser', $validateData['idUser'])->get();
        DB::table('users')->where('id', $validateData['idUser'])->decrement('totalLikes');

        return response()->json([
            'message' => 'Likes showed',
            'likes' => $likes
        ], 200);
    }
}
