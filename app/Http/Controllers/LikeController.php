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

        DB::table('users')->where('id', $validateData['idUserLiked'])->increment('totalLikes');

        return response()->json([
            'message' => 'Like Added',
        ], 200);
    }

    public function dislike(Request $request)
    {
        try {
            $validateData = $request->validate([
                'idUser' => 'required',
                'idLike' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }

        Like::where('id', $validateData['idLike'])->delete();
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
        
        foreach ($likes as $like) {
            $likesArray []= User::where('id', "like" ,$like->idUserLiked)->get();
        }

        return response()->json([
            'message' => 'Likes showed',
            'likes' => $likesArray
        ], 200);
    }
}
