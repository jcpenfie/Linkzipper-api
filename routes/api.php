<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\LikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// login,register,useToken
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::get('/getUserLogin',[AuthController::class, 'getUserLogin'])->middleware('auth:sanctum'); //protegido para que no entren usuarios no autenticados

// explore, search, profile
Route::get('/explore',[ExploreController::class, 'explore']); 
Route::get('/search',[ExploreController::class, 'search']); 
Route::get('/profile',[ExploreController::class, 'profile']); 

//dar/quitar megusta
Route::post('/like',[LikeController::class, 'like']); 
Route::post('/dislike',[LikeController::class, 'dislike']); 