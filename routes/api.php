<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('pay',[PaymentController::class, 'createTransaction'])->middleware(['auth:api','isverified']);
    Route::apiResource('order',OrdersController::class)->middleware(['auth:api','isadmin']);
    Route::apiResource('category',CategoriesController::class);
    Route::apiResource('product',ProductsController::class);
    Route::apiResource('role',RolesController::class)->middleware(['auth:api','isadmin']);

    //auth
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::get('me', [AuthController::class, 'currentuser'])->middleware('auth:api');
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('verifikasi-akun', [AuthController::class, 'verifikasi'])->middleware('auth:api');
        Route::post('generate-otp-code', [AuthController::class, 'generateOtp'])->middleware('auth:api');
    })->middleware('api');
    
    Route::post('profile', [ProfilesController::class, 'storeupdate'])->middleware(['auth:api','isverified']);
   });
