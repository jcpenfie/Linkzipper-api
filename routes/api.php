<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\UserController;
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
Route::post('/search',[ExploreController::class, 'search']); //devuelve los datos del usario que ha buscado
Route::post('/searchName',[ExploreController::class, 'searchName']); //solo devuelve los nombres 
Route::get('/profile',[ExploreController::class, 'profile']); 

//dar,quitar y listar megustas 
Route::post('/like',[LikeController::class, 'like']); 
Route::post('/dislike',[LikeController::class, 'dislike']);
Route::post('/likes',[LikeController::class, 'show']); 

//Formulario para modificar los datos del perfil
Route::put('/panel',[UserController::class, 'panel']);

//links (crud)
Route::put('/link/create',[LinkController::class, 'create']);
Route::post('/link/show',[LinkController::class, 'show']);
Route::put('/link/update',[LinkController::class, 'update']);
Route::delete('/link/{id}',[LinkController::class, 'delete']);
