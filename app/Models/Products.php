<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable=[
        'categories_id','name','price','count','low_stock','image','shop_id'
    ];


    public function categories()
    {

      return $this->belongsTo(Categories::class);
    }

    public function shop()
    {
       return $this->belongsTo(Shop::class);
    }


}
