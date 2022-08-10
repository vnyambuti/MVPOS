<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable=[
        'name','image','shop_id'
    ];


    public function products()
    {
       $this->hasMany(Products::class);
    }


    public function shop()
    {

        $this->belongsTo(Shop::class);

    }

}
