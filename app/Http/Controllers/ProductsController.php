<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isadmin'])->except(['index','show','getProduct']);
    }
    public function index(Request $request)
    {
        // $product = Products::get();


        $query = Products::query();
        if($request->has("search")){
            $searching = $request->input("search");
            $query->where('name', "LIKE", "%$searching");
        };

        $perpage = $request->input('per_page', 10);

        $product = $query->paginate($perpage);

        return response([
            "message" => "tampil data berhasil",
            "data" => $product
        ],200);
    }

    public function getProduct()
    {
        $product = Products::get();

        return response()->json([
            "message" => "Berhasil Tampil Product",
            "data" => $product
        ],200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|min:2,max:255',
            'price' => 'required|integer',
            'description' => 'required',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ],[
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'min' => 'inputan :attribute :min karakter',
            'max' => 'inputan :attribute :max karakter',
            'mimes' => 'inputan :attribute harus berformat jpeg,png,jpg,gif',
            'image' => 'inputan :attribute harus gambar',
            'exist' => 'inputan :attribute tidak ditemukan di table genres',
            'integer' => 'inputan harus berupa angka'
        ]);

        $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath(), [
            'folder' => 'final',
        ])->getSecurePath();

        $product = new Products;

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->image = $uploadedFileUrl;
        $product->stock = $request->input('stock');
        $product->category_id = $request->input('category_id');

        $product->save();

        return response()->json([
            "message" => "Data berhasil ditambahkan",
        ], 201);


    }

    public function show(string $id)
    {
        $product = Products::with(['category','listOrders'])->find($id);

        if(!$product){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        return response([
            "message" => "Data Detail ditampilkan",
            "data" => $product
        ],200);



    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|min:2,max:255',
            'price' => 'required|integer',
            'description' => 'required',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ],[
            'required' => ':attribute harus diisi tidak boleh kosong ',
            'min' => 'inputan :attribute :min karakter',
            'max' => 'inputan :attribute :max karakter',
            'mimes' => 'inputan :attribute harus berformat jpeg,png,jpg,gif',
            'image' => 'inputan :attribute harus gambar',
            'exist' => 'inputan :attribute tidak ditemukan di table genres',
            'integer' => 'inputan harus berupa angka'
        ]);

        $product = Products::find($id);

        if($request->hasFile('image')){
            $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath(), [
                'folder' => 'final',
            ])->getSecurePath();
            $product->image = $uploadedFileUrl;
        }

        if(!$product){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->stock = $request->input('stock');
        $product->category_id = $request->input('category_id');


        $product->save();

        return response([
            "message" => "Data berhasil diupdate",
        ],201);
    }

    public function destroy(string $id)
    {
        $product = Products::find($id);

        if(!$product){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $product->delete();
        return response([
            "message" => "berhasil Menghapus Products"
        ],200);
    }
}
