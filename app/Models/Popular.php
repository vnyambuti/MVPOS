<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popular extends Model
{
    use HasFactory;


    protected $fillable=[
      'shop_id','product_id','count'
    ];

    public function shop()
    {
       return $this->belongsTo(Shop::class);
    }

    public function product()
    {
       return $this->belongsTo(Products::class);
    }
}
