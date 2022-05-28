<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LikeController extends Controller
{
    public function like(Request $request)
    {
        DB::table('users')->where('id', $request['id'])->increment('totalLikes');
        return response()->json([
            'message' => 'Like Added',
        ], 200);
    }

    public function dislike(Request $request)
    {
        DB::table('users')->where('id', $request['id'])->decrement('totalLikes');
        return response()->json([
            'message' => 'Like Removed',
        ], 200);
    }
}
