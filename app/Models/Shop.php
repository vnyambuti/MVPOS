<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;


    protected $fillable=[
      'name','address','phone','email','logo'
    ];


    public function products()
    {
        $this->hasMany(Products::class);
    }

    public function categories()
    {
      $this->hasMany(Categories::class);
    }

}
