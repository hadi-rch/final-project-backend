<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use App\Models\Roles;
use App\Models\Profiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisterMail;
use App\Mail\GenerateEmailMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,id',
            'password' => 'required|min:8|confirmed',
        ],[
            'required' => 'inputaan :attribute harus diisi',
            'min' => 'inputaan :attribute minimal :min karakter',
            'max' => 'inputaan :attribute maksimal :max karakter',
            'email' => 'inputaan harus berformat email',
            'unique' => 'inputaan email sudah terdaftar',
            'confirmed' => 'inputaan password beda dengan konfirmasi',

        ]);

        $user = new User;

        $roleUser = Roles::where('name','user')->first();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;

        $user->save();

        Mail::to($user->email)->send(new UserRegisterMail($user));

        $filtered_user = [ 
            'name' => $user['name'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'id' => $user['id'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'] 
        ];

        return response([
            "message" => "Register Berhasil, siahkan cek email",
            "user" =>  $filtered_user
        ], 201);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ],[
            'required' => 'inputaan :attribute harus diisi'
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized, Invalid User'], 401);
        }


        $user = User::with(['profile','role'])->where('email', $request->input('email'))->first();
        return response([
            "message" => "user berhasil Login",
            "user" => $user,
            "token" => $token,
        ], 200);

    }

    public function currentuser()
    {
        $user = auth()->user();

        $userData = User::with('ordersProduct')->find($user->id);
        return response()->json([
            'message' => "berhasil get user",
            'user' => $userData
        ], 200);
    }
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Logout Berhasil']);
    }

    public function generateOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ],[
            'required' => 'inputaan :attribute harus diisi',
            'email' => 'inputaan harus email'
        ]);
        $user = User::where('email', $request->input('email'))->first();

        // jika user yang telah terverifikasi namun masih bisa generate code
        //maka dibuatlah kondisi agar user yang sudah terverfikasi tidak bisa mengakses generate code
        if($user->email_verified_at != null){
            return response()->json([
                "message" => "Anda sudah Verifikasi, ngapain generate code"
            ]);
        }

        $user->generate_otp();

        Mail::to($user->email)->send(new GenerateEmailMail($user));

        //karena return $user ada otp_code nya dan pada Example Response dokumentasi api otp_codenya 
        // tidak ada maka dibuatkanlah $filtered_user agar yang tampil sesuai dengan task yang diberikan 
        // Example Response dokumentasi api

        // Mengambil data yang diperlukan saja 
        $filtered_user = [ 
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'email_verified_at' => $user['email_verified_at'],
            'role_id' => $user['role_id'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'] 
        ];
        return response()->json([
            "message" => "Berhasil generate ulang otp code",
            "data" => $filtered_user
        ], 200);

    }

    public function verifikasi(Request $request )
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ],[
            'required' => 'inputaan :attribute harus diisi',
            'digits' => 'inputaan harus 6 angka'
        ]);        

        $user = auth()->user();

        //jika OTP code tidak ditemukan
        $otp_code = OtpCode::where('otp', $request->input('otp'))->where('user_id', $user->id)->first();

        if (!$otp_code){
            return response()->json([
                "message" => "Otp tidak ditemukan"
            ], 400);
        }

        // jika valid_until melebihi waktu sekarang
        $now = Carbon::now();
        if($now > $otp_code->valid_until){
            return response()->json([
                "message" => "Otp Sudah Kadaluarsa silahkan generate ulang otp code"
            ], 400);
        }

        //update user
        $user = User::find($otp_code->user_id);

        $user->email_verified_at = $now;

        $user->save();

        //setelah menyimpan email verified at (terverifikasi), maka kita harus menghapus supaya tidak jadi sampah didalam databasenya
        $otp_code->delete();

        return response()->json([
            "message" => "Berhasil Verifikasi Akun"
        ], 200);

    }

}
