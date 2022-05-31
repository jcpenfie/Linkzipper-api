<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function create(Request $request){
        try {
            $validateData = $request->validate([
                'title' => 'required',
                'link' => 'required',
                'logo' => 'required',
                'idUser' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }

        Link::create([
            'title' => $validateData['title'],
            'link' => $validateData['link'],    
            'logo' => $validateData['logo'],
            'idUser' => $validateData['idUser'],
        ]);

        return response()->json([
            'message' => 'Good, link created',
        ], 200);
    }

    public function show(Request $request){
        try {
            $validateData = $request->validate([
                'idUser' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }

        $links = Link::where('idUser', $validateData['idUser'])->get();

        return response()->json([
            'message' => 'Good, link created',
            'links' => $links
        ], 200);
    }

    public function update(Request $request){
        try {
            $validateData = $request->validate([
                'title' => 'required',
                'link' => 'required',
                'logo' => 'required',
                'idUser' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }

        Link::where('idUser', $validateData['idUser'])->update(array(
            'title' => $validateData['title'],
            'link' => $validateData['link'],
            'logo' => $validateData['logo'],
            'idUser' => $validateData['idUser'],
        ));

        return response()->json([
            'message' => 'Good, link updated',
        ], 200);
    }

    public function delete(Request $request){
        try {
            $validateData = $request->validate([
                'idLink' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The validation fail',
            ]);
        }

        Link::where('id', $validateData['idLink'])->delete();

        return response()->json([
            'message' => 'Good, link removed',
        ], 200);
    }
}
