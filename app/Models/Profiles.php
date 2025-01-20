<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use HasFactory, HasUuids;
    protected $table = "profiles";
    protected $fillable = [
        'bio',
        'age',
        'image',
        'user_id'
    ];

    public function user(){

        return $this->belongsTo(User::class, 'user_id');
    
      }
}
