<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LikeController extends Controller
{
    
    /////////////////////LIKE/////////////////////////////////////
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


    /////////////////////DISLIKE/////////////////////////////////////
    public function dislike(Request $request)
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

        try {
            Like::where('idUserLiked', $validateData['idUserLiked'])->where('idUser', $validateData['idUser'])->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error to delete',
            ]);
        }


        try {
            DB::table('users')->where('id', $validateData['idUserLiked'])->decrement('totalLikes');
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error to decrement',
            ]);
        }

        return response()->json([
            'message' => 'Like Removed',
        ], 200);
    }



    /////////////////////SHOW/////////////////////////////////////
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

        try {
            $likesCount = Like::where('idUser', $validateData['idUser'])->count();

            if ($likesCount != 0) {
                $likes = Like::where('idUser', $validateData['idUser'])->get();
                foreach ($likes as $like) {
                    $likesArray[] = User::where('id', "like", $like->idUserLiked)->get();
                }
            } else {
                $likesArray = $likesCount;
            }

            return response()->json([
                'message' => 'Likes showed',
                'likes' => $likesArray
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error to get likes',
            ]);
        }
    }
}
