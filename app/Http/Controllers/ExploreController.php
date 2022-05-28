<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class ExploreController extends Controller
{
    public function explore()
    {
        return User::where('publicAccount', 1)->orderBy('totalLikes', 'DESC')->get();
    }

    public function searchName(Request $request)
    {
        return User::select('userName')->where('userName', 'regexp', '^' . $request['userName'])->take(5)->get();
    }
    public function search(Request $request)
    {
        return User::where('userName', $request['userName'])->get();
    }

    public function profile(Request $request)
    {
        return User::where('userName', $request['userName'])->get();
    }
}
