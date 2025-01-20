<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier(){
        return $this->getKey();
        /**
         * 
         * 
         * @return array
         */


    }
    public function getJWTCustomClaims(){
        return [];
    }

    public static function boot(){
        parent::boot();

        static::created(function($model){
            $model->generate_otp();
        });

    }

    public function generate_otp()
    {
        do {
            $randomNumber = mt_rand(100000,999999);
            $check = OtpCode::where('otp',$randomNumber)->first();
        } while ($check);

        $now = Carbon::now();

        $otp_code = OtpCode::updateOrCreate(
            ['user_id' => $this->id],
            [
                'otp' => $randomNumber,
                'valid_until' => $now->addMinutes(5)
            ]);
    }

    public function profile()
    {
        return $this->hasOne(Profiles::class, 'user_id');
    }
    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }
    public function ordersProduct()
    {
        return $this->belongsToMany(Products::class, 'orders', 'user_id', 'product_id');
    } 
    
    public function otpData()
    {
        return $this->hasOne(OtpCode::class, 'user_id');
    }
}
