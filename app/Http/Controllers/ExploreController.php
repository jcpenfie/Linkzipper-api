<?php

namespace App\Http\Controllers;

use App\Models\User;

class ExploreController extends Controller
{
    public function explore()
    {
        return User::where('publicAccount', 1)->orderBy('totalLikes', 'DESC')->get();
    }
}
