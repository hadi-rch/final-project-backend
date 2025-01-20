<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function destroy(string $id)
    {
        $idOrders = Orders::find($id);
        if(!$idOrders){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $idOrders->delete();
        return response([
            "message" => "berhasil Menghapus Products"
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function update(string $id)
    {
        $idOrders = Orders::find($id);
        $idOrders->status = "SUCCESS";
        $idOrders->save();
        return response([
            "message" => "Berhasil Tambah order"
        ], 201);
    }
}
