<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isadmin'])->except(['index','show']);
    }
    public function index()
    {
        $category = Categories::get();

        return response()->json([
            "message" => "Berhasil Tampil Kategori",
            "data" => $category
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter'
        ]);

        Categories::create([
            'name' => $request->input('name')
        ]);

        return response([
            "message" => "Berhasil Tambah Kategori"
        ], 201);
    }

    public function show(string $id)
    {
        $category = Categories::with('listProduct')->find($id);

        if(!$category){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        return response([
            "message" => "Berhasil Detail data dengan id $id",
            "data" => $category
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter'
        ]);
        $category = Categories::find($id);

        if(!$category){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $category->name = $request->input('name');

        $category->save();

        return response([
            "message" => "Berhasil melakukan update Kategori id $id",
        ],201);
    }

    public function destroy(string $id)
    {
        $category = Categories::find($id);

        if(!$category){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $category->delete($id);
        return response([
            "message" => "data dengan id : $id berhasil terhapus"
        ],200);
    }
}
