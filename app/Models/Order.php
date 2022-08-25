<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
       'products','total','status','mode','shop_id','teller_id'
    ];

    public function shop()
    {
      return $this->belongsTo(Shop::class);
    }

    public function teller()
    {
       return $this->belongsTo(Teller::class);
    }
}
