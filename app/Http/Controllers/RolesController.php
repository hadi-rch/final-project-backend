<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $roleWithUsers=Roles::with('roleToUsers')->get();
        $role=Roles::get();

        return response()->json([
            "message" => "Data berhasil ditampilkan",
            "data" => $role
        ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2,max:255',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter' 
        ]);

        Roles::create([
            'name' => $request->input('name')
        ]);

        return response([
            "message" => "Data berhasil ditambahkan"
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|min:2,max:255',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter' 
        ]);
        $roles = Roles::find($id);
        
        if(!$roles){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $roles->name = $request->input('name');
        
        $roles->save();

        return response([
            "message" => "Data berhasil Diupdate",
        ],201);
    }

    public function destroy(string $id)
    {
        $roles = Roles::find($id);

        if(!$roles){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $roles->delete($id);
        return response([
            "message" => "berhasil Menghapus Role"
        ],200);
    }
}

