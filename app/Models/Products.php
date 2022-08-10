<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable=[
        'c_id','name','price','count','low_stock','image','shop_id'
    ];


    public function categories()
    {
       $this->belongsTo(Categories::class);
    }


}
