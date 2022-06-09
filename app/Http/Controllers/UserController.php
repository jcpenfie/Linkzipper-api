<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }
        try {
            User::where('userName', $validateData['userName'])->update(array(
                'showName' => $validateData['showName'],
                'password' => Hash::make($validateData['password']),
                'publicAccount' => $validateData['publicAccount'],
                'theme' => $validateData['theme'],
                'description' => $validateData['description'],
            ));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The update fail',
            ]);
        }

        try {
            if ($request->hasFile('profileImg')) {
                $completeFileName = $request->file('profileImg')->getClientOriginalName(); //nombre completo de la imagen con extension
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME); // nombre de la imagen sin extension
                $extenshion = $request->file('profileImg')->getClientOriginalExtension(); //extension solo de la imagen
                //Nombre final de la imagen: quita todos los posibles espacios por guión bajos que tenga en el nombre, le añade un número random, la hora en milisegundos para que no se repita y la extensión de la imagen
                $compPic = str_replace(' ', '_', $fileNameOnly) . '-' . rand() . '_' . time() . '.' . $extenshion;

                $request->file('profileImg')->move('img/logo', $compPic);
                User::where('userName', $request->userName)->update(array(
                    'profileImg' => $compPic,
                ));
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Fail to update profileImg',
            ]);
        }
        try {
            if ($request->hasFile('backgroundImg')) {
                $completeFileName = $request->file('backgroundImg')->getClientOriginalName(); //nombre completo de la imagen con extension
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME); // nombre de la imagen sin extension
                $extenshion = $request->file('backgroundImg')->getClientOriginalExtension(); //extension solo de la imagen
                //Nombre final de la imagen: quita todos los posibles espacios por guión bajos que tenga en el nombre, le añade un número random, la hora en milisegundos para que no se repita y la extensión de la imagen
                $compPic = str_replace(' ', '_', $fileNameOnly) . '-' . rand() . '_' . time() . '.' . $extenshion;

                $request->file('backgroundImg')->move('img/bg', $compPic);
                User::where('userName', $request->userName)->update(array(
                    'backgroundImg' => $compPic,
                ));
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Fail to update backgroundImg',
            ]);
        }

        return response()->json([
            'message' => 'Good, user updated',
        ], 200);
    }


    public function imgProfile($imgName)
    {
        $path = public_path().'\img\logo\\'.$imgName;
        return Response::download($path);        
    }
    public function imgBg($imgName)
    {
        $path = public_path().'\img\bg\\'.$imgName;
        return Response::download($path);        
    }
}
