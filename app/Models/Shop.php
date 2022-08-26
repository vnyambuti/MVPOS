<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;


    protected $fillable=[
      'name','address','phone','email','logo','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
      return  $this->hasMany(Products::class);
    }

    public function categories()
    {
      return $this->hasMany(Categories::class);
    }

    public function tellers()
    {
      return $this->hasMany(Teller::class);
    }

    public function orders()
    {
       return $this->hasMany(order::class);
    }

}
