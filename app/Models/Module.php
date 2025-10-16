<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    //

     use HasFactory;


        protected $fillable = ['name', 'description'];


public function users()
{
    return $this->belongsToMany(User::class, 'user_modules')
        ->using(UserModule::class)
        ->withPivot('active')
        ->withTimestamps();
}   
}


