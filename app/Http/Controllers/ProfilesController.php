<?php

namespace App\Http\Controllers;

use App\Models\Profiles;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function storeupdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'bio' => 'required',
            'age' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],[
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'integer' => 'inputan :attribute harus berupa angka',
            'max' => 'inputan :attribute :max karakter',
            'mimes' => 'inputan :attribute harus berformat jpeg,png,jpg,gif',
            'image' => 'inputan :attribute harus gambar',
        ]);

        $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath(), [
            'folder' => 'final',
        ])->getSecurePath();


        $profile = Profiles::updateOrCreate(
        ['user_id' => $user->id],
        [
            'bio' => $request->input('bio'),
            'age' => $request->input('age'),
            'image' => $uploadedFileUrl,

        ]);

        return response([
            "message" => "Profile berhasil diubah",
            "data" => $profile
        ],201);
    }
}
